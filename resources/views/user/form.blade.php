@extends('layouts.app')

@section('title', 'Profile')
@section('sub_title', 'profile add or modification form')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{--@endif--}}
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="col-md-4">

            @component('component.form')
                @slot('form_id')
                    @if (!empty($user->id))
                        user_forms
                    @else
                        user_form
                    @endif
                @endslot
                @slot('title')
                    @if (!empty($user->id))
                        Edit user
                    @else
                        Add a new user
                    @endif

                @endslot

                @slot('route')
                    @if (!empty($user->id))
                        user/{{$user->id}}/update
                    @else
                        user_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="form-group">
                        {{ Form::label('employee_no', 'Employee No', array('class' => 'employee_no')) }}
                        {{ Form::text('employee_no', (!empty($user->employee_no) ? $user->employee_no : NULL), ['class' => 'form-control', 'placeholder' => 'Enter employee no...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('name', 'Name', array('class' => 'name')) }}
                        {{ Form::text('name', (!empty($user->name) ? $user->name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter name...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('email', 'Email', array('class' => 'email')) }}
                        {{ Form::text('email', (!empty($user->email) ? $user->email : NULL), ['required', 'type' => 'email', 'class' => 'form-control', 'placeholder' => 'Enter email...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('username', 'Username', array('class' => 'username')) }}
                        {{ Form::text('username', (!empty($user->username) ? $user->username : NULL), ['class' => 'form-control', 'placeholder' => 'Enter username...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('birthday', 'Birthday', array('class' => 'birthday')) }}
                        {{ Form::text('birthday', (!empty($user->birthday) ? $user->birthday : NULL), ['class' => 'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'autocomplete' => 'off']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('gender', 'Gender', array('class' => 'gender')) }}
                        {{ Form::text('gender', (!empty($user->gender) ? $user->gender : NULL), ['class' => 'form-control', 'placeholder' => 'Enter gender...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('marital_status', 'Marital Status', array('class' => 'marital_status')) }}
                        {{ Form::text('marital_status', (!empty($user->marital_status) ? $user->marital_status : NULL), ['class' => 'form-control', 'placeholder' => 'Enter marital status...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('join_date', 'Joining Date', array('class' => 'join_date')) }}
                        {{ Form::text('join_date', (!empty($user->join_date) ? $user->join_date : NULL), ['class' => 'form-control datepicker', 'placeholder' => 'YYYY-MM-DD', 'autocomplete' => 'off']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('father', 'Father', array('class' => 'father')) }}
                        {{ Form::text('father', (!empty($user->father) ? $user->father : NULL), ['class' => 'form-control', 'placeholder' => 'Enter father...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('mother', 'Mother', array('class' => 'mother')) }}
                        {{ Form::text('mother', (!empty($user->mother) ? $user->mother : NULL), ['class' => 'form-control', 'placeholder' => 'Enter mother...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('phone', 'Phone', array('class' => 'phone')) }}
                        {{ Form::text('phone', (!empty($user->phone) ? $user->phone : NULL), ['class' => 'form-control', 'placeholder' => 'Enter phone...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('emergency_phone', 'Emergency Phone', array('class' => 'emergency_phone')) }}
                        {{ Form::text('emergency_phone', (!empty($user->emergency_phone) ? $user->emergency_phone : NULL), ['class' => 'form-control', 'placeholder' => 'Enter emergency phone...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('address', 'Address', array('class' => 'address')) }}
                        {{ Form::text('address', (!empty($user->address) ? $user->address : NULL), ['class' => 'form-control', 'placeholder' => 'Enter address...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('company', 'Company', array('class' => 'company')) }}
                        {{ Form::text('company', (!empty($user->company) ? $user->company : NULL), ['class' => 'form-control', 'placeholder' => 'Enter company...']) }}
                    </div>


                    <div class="form-group">
                        @php
                            $allowedRoleIds = [1, 2, 4, 7, 8];
                        @endphp
                        @foreach($roles as $key => $val)
                            @if(in_array($val->id, $allowedRoleIds, true))
                                <?php $roless[$val->id] = $val->description ?>
                            @endif
                        @endforeach
                        <?php
                        if (!empty($user)) {
                            $role_id = get_userrole_by_user_id($user->id);
                        }
                        ?>
                        {{ Form::label('user_role', 'User Role', array('class' => 'user_role')) }}
                        {{ Form::select('user_role', $roless, (!empty($role_id->role_id) ? $role_id->role_id : NULL), ['class' => 'form-control', 'required']) }}
                        {{ Form::hidden('id_of_role_user', (!empty($role_id->id) ? $role_id->id : NULL), []) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password', 'Password', array('class' => 'password')) }}
                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => (!empty($user->id) ? 'Leave blank to keep current password' : 'Enter new password...'), 'required' => empty($user->id)]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('password_confirmation', 'Confirm Password', array('class' => 'password_confirmation')) }}
                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => (!empty($user->id) ? 'Repeat new password' : 'Confirm password'), 'required' => empty($user->id)]) }}
                    </div>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@section('cusjs')
    <script>
        if (window.jQuery && jQuery.fn.datepicker) {
            jQuery('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        }
    </script>
@endsection
