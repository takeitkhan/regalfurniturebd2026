@extends('layouts.app')

@section('title', 'Users')
@section('sub_title', 'list of all users')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h5 class="box-title">Advanced Search</h5>
                </div>
                <div class="box-body">

                    {{ Form::open(array('url' => '/search_users', 'method' => 'post', 'value' => 'PATCH', 'id' => '')) }}
                    <div class="row">
                        <div class="col-xs-2">
                            <select name="column" required class="form-control select2" style="width: 100%;">
                                <option value="Name" {{ (Request::post('column') == 'name') ? 'selected="selected"' : 'selected="selected"' }}>
                                    Name
                                </option>
                                <option value="nidno" {{ (Request::post('column') == 'nidno') ? 'selected="selected"' : '' }}>
                                    NID
                                </option>
                                <option value="passportno" {{ (Request::post('column') == 'passportno') ? 'selected="selected"' : '' }}>
                                    Passport No.
                                </option>
                                <option value="birthcertificateno" {{ (Request::post('column') == 'birthcertificateno') ? 'selected="selected"' : '' }}>
                                    Birth Certificate No.
                                </option>
                                <option value="email" {{ (Request::post('column') == 'email') ? 'selected="selected"' : '' }}>
                                    Email
                                </option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            {{ Form::text('search_key', Request::post('search_key'), ['required', 'class' => 'form-control', 'placeholder' => 'Search Keys...']) }}
                        </div>
                        <div class="col-xs-1">
                            {{ Form::submit('Search', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Users
                        <a href="{{ url('add_user') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th colspan="2">Action</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at }}</td>
                                <td>{{ $user->updated_at }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url("edit_user/{$user->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    {{ Form::open(['method' => 'delete', 'route' => ['delete_user', $user->id], 'class' => 'delete_form']) }}
                                    {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                                    {{ Form::close() }}
                                </td>
                                <td>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $users->links('component.paginator', ['object' => $users]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection