<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Division;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// class OrganizationController extends Controller
// {
//     public function create()
//     {
//         return view('organization.create');
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'sector_name' => 'required|string|max:255',
//             'divisions' => 'nullable|array',
//             'divisions.*.name' => 'required_with:divisions|string|max:255',
//             'departments' => 'nullable|array',
//             'departments.*.division_id' => 'required_with:departments|integer',
//             'departments.*.name' => 'required_with:departments|string|max:255',
//             'description'=>'string|required'
//         ]);

//         // Create sector
//         $sector = Sector::create(['name' => $validated['sector_name']]);

//         // Create divisions if they exist
//         if (!empty($validated['divisions'])) {
//             foreach ($validated['divisions'] as $divisionData) {
//                 $division = $sector->divisions()->create(['name' => $divisionData['name']]);

//                 // Check if this division has departments
//                 if (!empty($validated['departments'])) {
//                     foreach ($validated['departments'] as $deptData) {
//                         if ($deptData['division_id'] == $division->id) {
//                             $division->departments()->create(['name' => $deptData['name']]);
//                         }
//                     }
//                 }
//             }
//         }

//         // Handle departments without divisions (directly under sector)
//         if (!empty($validated['departments'])) {
//             foreach ($validated['departments'] as $deptData) {
//                 if (empty($deptData['division_id'])) {
//                     Department::create([
//                         'name' => $deptData['name'],
//                         'sector_id' => $sector->id,
//                         'division_id' => null
//                     ]);
//                 }
//             }
//         }

//         return redirect()->route('organization.create')->with('success', 'Organization structure created successfully!');
//     }

//     public function getDivisionForm(Request $request)
//     {
//         $index = $request->input('index', 0);
//         return view('organization.partials.division-form', ['index' => $index]);
//     }

//     public function getDepartmentForm(Request $request)
//     {
//         $divisionId = $request->input('division_id', null);
//         $deptIndex = $request->input('dept_index', 0);
//         $descIndex = $request->input('dept_index', "description");
//         return view('organization.partials.department-form', [
//             'division_id' => $divisionId,
//             'dept_index' => $deptIndex,
//             'description'=>$descIndex
//         ]);
//     }
// }


class OrganizationController extends Controller
{
public function index()
{
    $sectors = Sector::with(['divisions.departments', 'departments'])->get();
    
    $totalDivisions = $sectors->sum(function($sector) {
        return $sector->divisions->count();
    });
    
    $totalDepartments = $sectors->sum(function($sector) {
        return $sector->departments->count() + 
               $sector->divisions->sum(function($division) {
                   return $division->departments->count();
               });
    });
    
    return view('organization.index', compact('sectors', 'totalDivisions', 'totalDepartments'));
}

    public function create()
    {
        return view('organization.create');
    }

public function store(Request $request)
{
    // Validate first, before anything is saved
    $validated = $request->validate([
        'sector_name' => 'required|string|max:255',
        'divisions' => 'nullable|array',
        'divisions.*.name' => 'required_with:divisions|string|max:255',
        'departments' => 'nullable|array',
        'departments.*.division_index' => 'nullable|integer',
        'departments.*.name' => 'required_with:departments|string|max:255',
    ]);

    DB::beginTransaction();

    try {
        // Create the sector
        $sector = Sector::create(['name' => $validated['sector_name']]);

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

        return redirect()->route('organization.create')->with('success', 'Organization structure created successfully!');
    } catch (\Exception $e) {
        DB::rollBack();

        return redirect()->back()->withErrors('An error occurred while saving the data.')->withInput();
    }
}


    public function getDivisionForm(Request $request)
    {
        $index = $request->input('index', 0);
        return view('organization.partials.division-form', ['index' => $index]);
    }

    public function getDepartmentForm(Request $request)
    {
        $divisionIndex = $request->input('division_index', null);
        $deptIndex = $request->input('dept_index', 0);
        return view('organization.partials.department-form', [
            'division_index' => $divisionIndex,
            'dept_index' => $deptIndex
        ]);
    }
    // Edit sector form
public function editSector(Sector $sector)
{
    return view('organization.edit-sector', compact('sector'));
}

// Update sector
public function updateSector(Request $request, Sector $sector)
{
    $request->validate(['name' => 'required|string|max:255']);
    $sector->update(['name' => $request->name]);

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

}
