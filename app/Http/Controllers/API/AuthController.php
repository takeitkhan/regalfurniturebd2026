<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OtpGenerate;
use App\Models\UserRegisterLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        if ($validate->fails()) {

            return response()->json([
                'type' => "missing",
                'messages' => $validate->errors()->first(),
                'token' => null,
                'success' => false
            ], 200);

        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response()->json([
                'type' => "incorrect",
                'messages' => 'The credentials was incorrect',
                'token' => null,
                'success' => false
            ],200);

        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'type' => 'success',
            'messages' => 'Login Successfully',
            'token' => $token,
            'success' => true
        ], 200);
    }


    //Nipun
    public function otpGenerate(Request $request){
        $user = User::where('phone', $request->phone)->latest()->first();
        if($user){ //check user
            $now = Carbon::now();
            $token = false;
            if($request->verification_code){ // If User input Verfication code
                $verification = OtpGenerate::where('user_id', $user->id)->latest()->first();
                if($verification && $verification->code != $request->verification_code){
                    $status = 0;
                    $msg = 'This verification code is incorrect.';
                }elseif($verification && $now->isBefore($verification->expired_at)){ //check given otp is expired or not
                    $token = $user->createToken('web_browser')->plainTextToken;
                    $status = 1;
                    $msg = 'Login Successfully.';
                    OtpGenerate::where('user_id', $user->id)->delete(); //Delete All row of this user
                }else {
                    $status = 0;
                    $msg = 'This verification code is expired please send again.';
                }
            }else {
                $otpGen = OtpGenerate::create([
                    'user_id' => $user->id,
                    'code' => rand(1234, 9999),
                    'expired_at' => Carbon::now()->addMinutes(5),
                ]);
                $status = 2; // 2 means otp sent
                sendSMS($request->phone ,'Dear Customer, Please use  '.$otpGen->code.' One Time Password for a successful Login at regalfurniturebd.com');
                $msg = 'OTP is sent to your mobile number. This OTP will expire in 5 minutes.';
            }
            //
        }else {
            $status = 0;
            $msg = 'The mobile phone number is not registered.';
            $token = false;
        }

        return response()->json([
            'status' => $status,
            'messages' => $msg,
            'data' => $user,
            'token' => $token,
        ]);
    }


    public function register(Request $request)
    {

        // return response()->json($request->all());

        if (is_blocked_ip($request->ip())) {
            try {
                UserRegisterLog::create([
                    'user_id' => null,
                    'name' => $request->name,
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'source' => 'api',
                    'status' => 'blocked',
                    'reason' => 'ip_blocked',
                    'payload' => $request->except(['password', 'password_confirmation'])
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            return response()->json([
                'type' => 'blocked',
                'messages' => 'Registration is blocked from this IP.',
                'result' => null
            ], 403);
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (is_disposable_email($value)) {
                        $fail('Disposable email addresses are not allowed.');
                    }
                }
            ],
            'telephone' => 'required',
            'emergency_contact_number' => 'required',
            'password' => 'required|string|min:6|max:32|confirmed',
            'agree' => 'required',
            'district' => 'required',
            'device_name' => 'required',
            'agree' => 'accepted',
            'birthday' => 'nullable|date|before:2010-01-01',
            'company' => ['nullable', function ($attribute, $value, $fail) {
                if ($value !== null && strtolower(trim($value)) === 'dhaka') {
                    $fail('The company is invalid.');
                }
            }]
        ]);

        if ($validate->fails()) {
            try {
                UserRegisterLog::create([
                    'user_id' => null,
                    'name' => $request->name,
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'source' => 'api',
                    'status' => 'failed',
                    'reason' => $validate->errors()->first(),
                    'payload' => $request->except(['password', 'password_confirmation'])
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            return response()->json([
                'type' => "missing",
                'messages' => $validate->errors(),
                'result' => null
            ]);
        }

        $userAttr = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->telephone,
            'emergency_phone' => $request->emergency_contact_number,
            'company' => $request->company,
            'address' => $request->address_1,
            'address_2' => $request->address_2,
            'district' => $request->district,
            'post_code' => $request->post_code,
            'password' => Hash::make($request->password),
            'birthday' => $request->filled('birthday') ? Carbon::parse($request->birthday)->toDateString() : null,
            'is_active' => true
        ];

        // return $userAttr;

        $user = User::create($userAttr);
        $token = $user->createToken($request->device_name)->plainTextToken;

        try {
            UserRegisterLog::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'source' => 'api',
                'status' => 'success',
                'reason' => null,
                'payload' => $request->except(['password', 'password_confirmation'])
            ]);
        } catch (\Exception $e) {
            // ignore logging failures
        }

        return response()->json([
            'type' => $user ? "success" : "fail",
            'messages' => $user ? 'Succesfully Created' : 'Fail to create',
            'result' => $user,
            'token' => $token
        ],200);
    }


    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validate = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ]);


        if ($validate->fails()) {

            return response()->json([
                'type' => 'missing',
                'messages' => $validate->errors()
            ],200);
        }

        $user = User::findOrFail($user->id);


        if (!$user || !Hash::check($request->oldPassword, $user->password)) {

            return response()->json([
                'type' => 'error',
                'messages' => ''
            ],200);
        }


        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'type' => 'success',
            'messages' => '',
            'results' => $user
        ],200);
    }




    public function logout(Request $request)
    {
        $revoke = $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => $revoke
        ],200);
    }


    public function user(Request $request)
    {
        return [
            'user' => $request->user()
        ];
    }


    public function hello(Request $request)
    {

        return response()->json([
            'message' => $request->message
        ],200);
    }

}
