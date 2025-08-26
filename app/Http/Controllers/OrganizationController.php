<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Sector;
use App\Models\Division;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\OrganizationHelper;




class OrganizationController extends Controller
{
public function index(Request $request)
{  $organizations = Organization::all();
    $query = Sector::with(['organization', 'divisions.departments', 'departments']);
    $searchTerm = $request->search ?? null; // Initialize searchTerm
    $labels = OrganizationHelper::labels();

    if (!empty($searchTerm)) {
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'LIKE', "%{$searchTerm}%")
              ->orWhereHas('organization', fn($orgQuery) => $orgQuery->where('name', 'LIKE', "%{$searchTerm}%"))
              ->orWhereHas('divisions', function($divisionQuery) use ($searchTerm) {
                  $divisionQuery->where('name', 'LIKE', "%{$searchTerm}%")
                               ->orWhereHas('departments', function($deptQuery) use ($searchTerm) {
                                   $deptQuery->where('name', 'LIKE', "%{$searchTerm}%");
                               });
              })
              ->orWhereHas('departments', function($deptQuery) use ($searchTerm) {
                  $deptQuery->where('name', 'LIKE', "%{$searchTerm}%");
              });
        });
    }

    $sectors = $query->get();

    // Calculate totals
    $totalDivisions = $sectors->sum(fn($sector) => $sector->divisions->count());
    $totalDepartments = $sectors->sum(fn($sector) => $sector->departments->count() + $sector->divisions->sum(fn($division) => $division->departments->count()));

    return view('organization.index', compact('sectors', 'totalDivisions', 'totalDepartments', 'searchTerm','labels','organizations'));
}


    public function create(Request $request)
    {
        $labels = OrganizationHelper::labels();
        $organizations = Organization::all();
        $sector = null;
        $division = null;
        
        if ($request->has('sector_id')) {
            $sector = Sector::find($request->sector_id);
        }
        
        if ($request->has('division_id')) {
            $division = Division::find($request->division_id);
        }
        
        return view('organization.create', compact('labels', 'sector', 'division','organizations'));
    }

public function store(Request $request)
    {
        // Validate first, before anything is saved
        $validated = $request->validate([
            'sector_name' => 'required_without:sector_id|string|max:255',
             'organization_id' => 'nullable|exists:organizations,id',
            'divisions' => 'nullable|array',
            'sector_id' => 'nullable|exists:sectors,id',
            'division_id' => 'nullable|exists:divisions,id',
            'divisions.*.name' => 'required_with:divisions|string|max:255',
            'departments' => 'nullable|array',
            'departments.*.division_index' => 'nullable|integer',
            'departments.*.name' => 'required_with:departments|string|max:255',
           
        ]);

        DB::beginTransaction();

        try {
            // Handle sector - either use existing or create new
            if ($request->has('sector_id')) {
                $sector = Sector::findOrFail($request->sector_id);
            } else {
                $sector = Sector::create(['name' => $validated['sector_name'],'organization_id' => $request->organization_id,]);
            }

            // Handle the case where we're adding to an existing division
            if ($request->has('division_id')) {
                $division = Division::findOrFail($request->division_id);
                
                // Create departments for the existing division
                if (!empty($validated['departments'])) {
                    foreach ($validated['departments'] as $deptData) {
                        $division->departments()->create([
                            'name' => $deptData['name'],
                        ]);
                    }
                }
                
                DB::commit();
                return redirect()->route('organization.index')->with('success', 'Departments added to division successfully!');
            }

            // Map to keep track of created divisions and their indexes
            $divisionMap = [];

            // Step 1: Create divisions and their departments
            if (!empty($validated['divisions'])) {
                foreach ($validated['divisions'] as $index => $divisionData) {
                    $division = $sector->divisions()->create(['name' => $divisionData['name']]);
                    $divisionMap[$index] = $division;

                    // Attach departments to this division if any
                    if (!empty($validated['departments'])) {
                        foreach ($validated['departments'] as $deptData) {
                            if (isset($deptData['division_index']) && $deptData['division_index'] == $index) {
                                $division->departments()->create([
                                    'name' => $deptData['name'],
                                ]);
                            }
                        }
                    }
                }
            }

            // Step 2: Create departments directly under the sector (no division)
            if (!empty($validated['departments'])) {
                foreach ($validated['departments'] as $deptData) {
                    // Skip if this department is already assigned to a division
                    if (array_key_exists('division_index', $deptData) && isset($divisionMap[$deptData['division_index']])) {
                        continue;
                    }

                    $divisionIndex = $deptData['division_index'] ?? null;
                    $division = $divisionIndex !== null ? ($divisionMap[$divisionIndex] ?? null) : null;

                    Department::create([
                        'name' => $deptData['name'],
                        'sector_id' => $sector->id,
                        'division_id' => $division?->id,
                    ]);
                }
            }

            DB::commit();

            // Redirect based on what was created
            if ($request->has('sector_id') && !empty($validated['divisions'])) {
                return redirect()->route('organization.index')->with('success', 'Divisions added to sector successfully!');
            } elseif ($request->has('sector_id') && !empty($validated['departments'])) {
                return redirect()->route('organization.index')->with('success', 'Departments added to sector successfully!');
            } else {
                return redirect()->route('organization.index')->with('success', 'Organization structure created successfully!');
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Organization store error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->back()
                ->withErrors('An error occurred while saving the data: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function getDivisionForm(Request $request)
    {
        $index = $request->index;
        $labels = OrganizationHelper::labels();
        
        return view('organization.partials.division-form', compact('index', 'labels'))->render();
    }

    public function getDepartmentForm(Request $request)
    {
        $divisionIndex = $request->division_index;
        $deptIndex = $request->dept_index;
        $labels = OrganizationHelper::labels();
        
        return view('organization.partials.department-form', compact('divisionIndex', 'deptIndex', 'labels'))->render();
    }
    // Edit sector form
public function editSector(Sector $sector)

{
       $organizations = Organization::all();
    return view('organization.edit-sector', compact('sector','organizations'));
}

// Update sector
public function updateSector(Request $request, Sector $sector)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'organization_id' => 'required|exists:organizations,id', // validate organization
    ]);

    $sector->update([
        'name' => $request->name,
        'organization_id' => $request->organization_id, // update organization
    ]);

    return redirect()->route('organization.index')->with('success', 'Sector updated successfully.');
}

// Delete sector
public function destroySector(Sector $sector)
{
    $sector->delete();
    return redirect()->route('organization.index')->with('success', 'Sector deleted successfully.');
}
// --- Division ---

public function editDivision(Division $division)
{
    return view('organization.edit-division', compact('division'));
}

public function updateDivision(Request $request, Division $division)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);
    $division->update(['name' => $request->name]);

    return redirect()->route('organization.index')->with('success', 'Division updated successfully.');
}

public function destroyDivision(Division $division)
{
    $division->delete();
    return redirect()->route('organization.index')->with('success', 'Division deleted successfully.');
}

// --- Department ---

public function editDepartment(Department $department)
{
    return view('organization.edit-department', compact('department'));
}

public function updateDepartment(Request $request, Department $department)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);
    $department->update(['name' => $request->name]);

    return redirect()->route('organization.index')->with('success', 'Department updated successfully.');
}

public function destroyDepartment(Department $department)
{
    $department->delete();
    return redirect()->route('organization.index')->with('success', 'Department deleted successfully.');
}
  public function addDivisionToSector(Sector $sector)
    {
        return view('organization.add_div_to_sector.add-division', compact('sector'));
    }
    
    public function storeDivisionToSector(Request $request, Sector $sector)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        Division::create([
            'name' => $request->name,
            'sector_id' => $sector->id,
        ]);
        
        return redirect()->route('organization.index')
            ->with('success', 'Division added successfully to ' . $sector->name);
    }

public function addDepartmentToDivision(Division $division)
    {
        return view('organization.add_dep_division.add-department', compact('division'));
    }
    
    public function storeDepartmentToDivision(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        Department::create([
            'name' => $request->name,
            'description' => $request->description,
            'sector_id' => $division->sector_id,
            'division_id' => $division->id,
        ]);
        
        return redirect()->route('organization.index')
            ->with('success', 'Department added successfully to ' . $division->name);
    }

    //here direct adding department to sector

    public function addDepartmentToSector(Sector $sector)
    {
        return view('organization.dep_to_sector.add_dep_to_sector', compact('sector'));
    }
    
    public function storeDepartmentToSector(Request $request, Sector $sector)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        Department::create([
            'name' => $request->name,
            'description' => $request->description,
           
            'sector_id' => $sector->id,
        ]);
        
        return redirect()->route('organization.index')
            ->with('success', 'Department added successfully to ' . $sector->name);
    }
    public function createOrganization(){
        return view('organization.organizationName.create_organization');

    }
    public function storeOrganization(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:organizations,name',
    ]);

    Organization::create([
        'name' => $request->name
    ]);

    return redirect()->route('organization.index')->with('success', 'Organization created successfully');
}

}
