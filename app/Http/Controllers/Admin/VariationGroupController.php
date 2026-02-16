<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VariationGroup;

class VariationGroupController extends Controller
{
    
    public function index() 
    {
        $variationGroup = VariationGroup::orderBy('id', 'DESC')->paginate(15);
        return view('admin.variationGroup.index', compact('variationGroup'));
    }

    public function create() {

        return view('admin.variationGroup.form');
    }


    public function store(Request $request)
    {
       VariationGroup::create($request->all());
        return back()->with('success', 'Data Uploaded');
    }

    public function edit($id)
    {
       $variationGroup = VariationGroup::find($id);
       return view('admin.variationGroup.form', compact('variationGroup'));
    }

    public function update(Request $request, $id)
    {
        $variation = [
            'title' => $request->title,
            'slug' => $request->slug,
            'active' => $request->active
        ];
       VariationGroup::where('id', $id)->update($variation);
        return back()->with('success', 'Data Updated');
    }

    public function delete($id)
    {
       VariationGroup::where('id', $id)->delete();
        return back()->with('success', 'Data Deleted!');
    }
}
