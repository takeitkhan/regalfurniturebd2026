<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Depot;
use Illuminate\Support\Facades\DB;

class DepotController extends Controller
{
    //
    protected $model;

    public function __construct(Depot $model){
        $this->model = $model;
    }

    public function index(Request $request){
        $depot = $request->id ? $this->model->find($request->id) : null;
        return view('depot.index', compact('depot'));
    }


    public function get_district_by_division(Request $request)
    {
        //dd($request->division);
        $list = DB::table('districts')->groupBy('district')->where('division', $request->division)->get();
        $html = '';
        foreach ($list->toArray() as $dist) {
            $html .= '<option value="' . $dist->district . '">' . $dist->district . '</option>';
        }
        return response()->json($html);
    }

    public function store(Request $request){
        $attr = [
            'name' => $request->depot_name,
            'type' => $request->depot_type,
            'division' => $request->depot_location,
            'districts' => implode(',', $request->depot_district),
        ];
        $this->model->create($attr);

        return redirect()->back()->with(['success' => 'Saved Successfully']);
    }

    public function update(Request $request){
        $attr = [
            'name' => $request->depot_name,
            'type' => $request->depot_type,
            'division' => $request->depot_location,
            'districts' => implode(',', $request->depot_district),
        ];
        $this->model->where('id', $request->id)->update($attr);
        return redirect()->back()->with(['success' => 'Saved Successfully']);
    }

    public function destroy(Request $request){
        $data = $this->model::find($request->id);
        $data->delete();
        return redirect()->back()->with(['success' => 'Deleted Successfully']);
    }
}
