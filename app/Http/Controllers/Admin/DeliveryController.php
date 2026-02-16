<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class DeliveryController extends Controller
{

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function categories()
    {
        $terms = \App\Models\Term::where('id','!=',2)->where('parent','!=',2)->get();
        $timespans = \App\Models\DeliveryTimespan::where('is_active',true)->get();


        return response()->view('admin.delivery.category',compact('terms','timespans'));
    }


    public function termsTimeSpan()
    {
        $terms = \App\Models\Term::whereIn('id',$this->request->category);

        $terms->update(['timespan_id' => $this->request->timespan]);

        return redirect()->back()->with('success','Succfully Updated...');
    }


    public function timespan()
    {
        $timespans = \App\Models\DeliveryTimespan::orderBy('created_at','DESC')->paginate(30);

        $timespan = '';

        if($this->request->id){
            $timespan = \App\Models\DeliveryTimespan::findOrFail($this->request->id);
        }

        return response()->view('admin.delivery.time',compact('timespans','timespan'));
    }


    public function timespanStore()
    {

        $attr = [
            'timespan' => $this->request->timespan,
            'description' => $this->request->description,
            'is_active' => true
            ];

            \App\Models\DeliveryTimespan::create($attr);

        return redirect()->back()->with('success'.'Timespan added..');
    }


    public function timespanUpdate($id)
    {
        return redirect()->back()->with('success'.'Timespan updated..');
    }


    public function timespanStatus($id)
    {
        $timespan = \App\Models\DeliveryTimespan::findOrFail($this->request->id);
        $timespan->is_active = !$timespan->is_active;
        $timespan->save();

        return redirect()->back();
    }

    public function timespanDelete($id)
    {
        $timespan = \App\Models\DeliveryTimespan::findOrFail($this->request->id);
        $timespan->delete();

        return redirect()->back();
    }
}
