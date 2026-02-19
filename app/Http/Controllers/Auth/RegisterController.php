<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\UserRegisterLog;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if (is_blocked_ip(request()->ip())) {
                        $fail('Registration is blocked from this IP.');
                    }
                    if (is_disposable_email($value)) {
                        $fail('Disposable email addresses are not allowed.');
                    }
                }
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public function register(Request $request)
    {
        if (is_blocked_ip($request->ip())) {
            try {
                UserRegisterLog::create([
                    'user_id' => null,
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'source' => 'web_auth',
                    'status' => 'blocked',
                    'reason' => 'ip_blocked',
                    'payload' => $request->except(['password', 'password_confirmation'])
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            return redirect()->back()->withErrors(['email' => 'Registration is blocked from this IP.'])->withInput();
        }

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            try {
                UserRegisterLog::create([
                    'user_id' => null,
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'ip' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                    'source' => 'web_auth',
                    'status' => 'failed',
                    'reason' => $validator->errors()->first(),
                    'payload' => $request->except(['password', 'password_confirmation'])
                ]);
            } catch (\Exception $e) {
                // ignore logging failures
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        try {
            UserRegisterLog::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'source' => 'web_auth',
                'status' => 'success',
                'reason' => null,
                'payload' => $request->except(['password', 'password_confirmation'])
            ]);
        } catch (\Exception $e) {
            // ignore logging failures
        }

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
