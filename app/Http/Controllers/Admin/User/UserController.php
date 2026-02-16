<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Repositories\Role\RoleInterface;
use App\Repositories\Role_user\Role_userInterface;
use App\Repositories\User\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    /**
     * @var UserInterface
     */
    private $user;
    /**
     * @var RoleInterface
     */
    private $role;
    /**
     * @var User
     */
    private $umodel;
    /**
     * @var Role_userInterface
     */
    private $role_user;

    /**
     * UserController constructor.
     * @param UserInterface $user
     * @param RoleInterface $role
     * @param Role_userInterface $role_user
     * @param User $umodel
     */
    public function __construct(UserInterface $user, RoleInterface $role, Role_userInterface $role_user, User $umodel)
    {
        $this->user = $user;
        $this->role = $role;

        // loading models
        $this->umodel = $umodel;
        $this->role_user = $role_user;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(Request $request)
    {
        if (!empty($request)) {
            $column = $request->get('column');
            $default = array(
                'column' => $column,
                'search_key' => $request->get('search_key')
            );
            $users = $this->user->getAll($default);
            return view('user.index')->with(['users' => $users]);
        } else {
            $users = $this->user->getAll();
            return view('user.index')->with(['users' => $users]);
        }
        //$users = $this->user->getAll();
        //return view('user.index')->with(['users' => $users]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function add_user()
    {
        $roles = $this->role->getAll();
        return view('user.form')->with(['roles' => $roles]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit_user($id)
    {

        if (isset($id)) {
            $user = $this->user->getById($id);
            //$role_id = $this->role_user->getById($user->id)->role_id;
            //dd($role_id);
            $roles = $this->role->getAll();
            return view('user.form')
                ->with(['user' => $user, 'roles' => $roles]);
        }

    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function user_update_save(Request $request, $id)
    {

        $d = $this->user->getById($id);

        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('users')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'name' => $request->get('name'),
                'employee_no' => $request->get('employee_no'),
                'email' => $request->get('email'),
                'username' => $request->get('username'),
                'birthday' => date('Y-m-d', strtotime($request->get('birthday'))),
                'gender' => $request->get('gender'),
                'marital_status' => $request->get('marital_status'),
                'join_date' => date('Y-m-d', strtotime($request->get('join_date'))),
                'father' => $request->get('father'),
                'mother' => $request->get('mother'),
                'company' => $request->get('company'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'emergency_phone' => $request->get('emergency_phone')
            ];

            $attributes_role = [
                'role_id' => $request->get('user_role')
            ];

            try {
                $this->user->update($d->id, $attributes);
                $this->role_user->update($request->get('id_of_role_user'), $attributes_role);

                return redirect('users')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('users')->withErrors($ex->getMessage());
            }
        }
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {

        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required',
                'password' => 'required|alpha_dash'
            ]
        );

        $validator1 = Validator::make($request->all(),
            [
                'role_id' => 'required',
                'user_id' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('users')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'name' => $request->get('name'),
                'employee_no' => $request->get('employee_no'),
                'email' => $request->get('email'),
                'username' => $request->get('username'),
                'birthday' => date('Y-m-d', strtotime($request->get('birthday'))),
                'gender' => $request->get('gender'),
                'marital_status' => $request->get('marital_status'),
                'join_date' => date('Y-m-d', strtotime($request->get('join_date'))),
                'father' => $request->get('father'),
                'mother' => $request->get('mother'),
                'company' => $request->get('company'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'emergency_phone' => $request->get('emergency_phone'),
                'password' => Hash::make($request->get('password')),
                'is_active' => 1
            ];

            //dd($attributes);

            try {
                $user = $this->user->create($attributes);
                if (!empty($user)) {
                    $attributes_role = [
                        'role_id' => $request->get('user_role'),
                        'user_id' => $user->id
                    ];
                    try {
                        $this->role_user->create($attributes_role);
                        return redirect('users')->with('success', 'Successfully save changed');
                    } catch (\Illuminate\Database\QueryException $ex) {
                        return redirect('users')->withErrors($ex->getMessage());
                    }
                } else {
                    return redirect('add_user');
                }

            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('users')->withErrors($ex->getMessage());
            }
        }

    }

    /**
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $this->user->delete($id);
            return redirect('users')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('users')->withErrors($ex->getMessage());
        }
    }
}
