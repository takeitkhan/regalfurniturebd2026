<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Repositories\District\DistrictInterface;
use Illuminate\Http\Request;
use Validator;

class DistrictController extends Controller
{

    protected $district;

    /**
     * DashboardController constructor.
     */
     
   public function __construct(DistrictInterface $district)
   {
        $this->district              = $district;
   }
    
    
    public function index(Request $request)
    {
        $q                           = $request->get('q');
        $col                         = $request->get('column');
        $query                       = $q && $col;
        $districts                   = $query ? $this->district->getByAny                                          ($col,$q) : $this->district->getAll();

        return response()->view('district.index',
        compact(
            'districts',
            'query'
            )
        );
    }
    
    
    
    public function store(Request $request)
    {
        
        
        $this->validate($request,[
           'division'            => 'required',
           'district'            => 'required',
           'thana'               => 'required',
        ]);
        
        $this->district->create([
           'division'            => $request->division,
           'district'            => $request->district,
           'thana'               => $request->thana,
           'postoffice'          => $request->postoffice,
           'postcode'            => $request->postcode,
           'is_active'           => 1
        ]);
        
        
        return redirect()->back()->with('success','Data Successfully Added!');
        
    }
    
    
    public function edit(Request $request,$id)
    {
        $q                           = $request->get('q');
        $col                         = $request->get('column');
        $query                       = $q && $col;
        $districts                   = $query ? $this->district->getByAny                                          ($col,$q) : $this->district->getAll();
        
        
        $district                    = $this->district->getById($id);
        
        return response()->view('district.index',
        compact(
            'districts',
            'district',
            'query'
            )
        );
    
    }
    
    
    
    public function update(Request $request,$id)
    {
        
        
        $this->validate($request,[
           'division'            => 'required',
           'district'            => 'required',
           'thana'               => 'required',
        ]);
        
        $this->district->update($id,[
           'division'            => $request->division,
           'district'            => $request->district,
           'thana'               => $request->thana,
           'postoffice'          => $request->postoffice,
           'postcode'            => $request->postcode,
           'is_active'           => 1
        ]);
        
        
        return redirect()->back()->with('success','Data Successfully Updated!');
        
    }
    
    
    
    
}