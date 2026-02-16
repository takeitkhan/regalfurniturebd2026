<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{

    public function redirect($provider){

        return Socialite::driver($provider)->redirect();
    }


    public function callback($provider){

        $getInfo = Socialite::driver($provider)->user();

        $user = $this->addUser($provider,$getInfo);
        auth()->login($user);

        return redirect('/my_account');
    }

    public function addUser($provider,$getInfo){
        $user = User::where('email', $getInfo->email)->first();
        
        if(!$user){
            $user = User::create([
                'name' => $getInfo->name,
                'email' => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id
            ]);
        }
        return $user;
    }

}
