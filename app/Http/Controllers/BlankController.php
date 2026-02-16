<?php

namespace App\Http\Controllers;

class BlankController extends Controller
{

    public function index()
    {
        $blank = ['Samrat', 'Tritiyo Limited', 'Programmer', 'Managing Director'];
        return view('blank', compact('blank'));
    }
    
    public function testByAlimify(){
        
        return sendSMS('01767436576,01832292096','Hello This is abdul alim jewel');
    }


}
