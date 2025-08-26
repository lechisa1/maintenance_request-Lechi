<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\OrganizationHelper;
use App\Models\OrganizationUnitLabel;
use Illuminate\Support\Facades\Cache;

class OrganizationLabelController extends Controller
{
    //
        public function index()
    {
        $labels = OrganizationUnitLabel::all();
        $label = OrganizationHelper::labels();
        return view('labels.index', compact('labels','label'));
    }

    public function update(Request $request)
    {
        foreach ($request->labels as $id => $label) {
            OrganizationUnitLabel::where('id', $id)->update(['label' => $label]);
        }
        Cache::forget('organization_labels');
        return back()->with('success', 'Labels updated successfully!');
    }
}
