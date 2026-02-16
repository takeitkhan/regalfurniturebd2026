@extends('layouts.app')

@section('title', 'District')

@section('sub_title', 'manage your districts')

@section('content')
   
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

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
        
        <div class="col-md-3" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($district->id))
                        district_form333
                    @else
                        district_form333
                    @endif
                @endslot
                @slot('title')
                    {{isset($district) ? 'Edit Data' : 'Add new data'}}
                @endslot

                @slot('route')
                    @if (!empty($district->id))
                        {{route('admin_district.update',$district->id)}}
                    @else
                        {{route('admin_district.store')}}
                    @endif
                @endslot

                @slot('fields')
                    {{ Form::hidden('is_active',$district->is_active??1) }}
                    {{method_field(isset($district) ? 'PUT' : 'POST')}}
                    {{csrf_field()}}
                    <div class="form-group">
                        {{ Form::label('division', 'Division', array('class' => 'division')) }}
                        {{ Form::text('division', $district->division??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Division...']) }}
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('district', 'District', array('class' => 'district')) }}
                        {{ Form::text('district', $district->district??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter District...']) }}
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('thana', 'Thana', array('class' => 'thana')) }}
                        {{ Form::text('thana', $district->thana??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Thana...']) }}
                    </div>
                    
                     <div class="form-group">
                        {{ Form::label('postoffice', 'Post office', array('class' => 'postoffice')) }}
                        {{ Form::text('postoffice', $district->postoffice??'', ['', 'class' => 'form-control', 'placeholder' => 'Enter Post Office...']) }}
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('postcode', 'Post Code', array('class' => 'postcode')) }}
                        {{ Form::number('postcode', $district->postcode??'', ['', 'class' => 'form-control', 'placeholder' => 'Enter Post Code...']) }}
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-9">

            <div class="box box-success">
                <div class="box-header">
                    
                    
                    <h3 class="box-title">
                        District
                        <a href="{{ route('admin_district.index') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>

                    <div class="box-tools row">
                        <form action="" method="" class="form-inline">
                            <div class="input-group input-group-sm">                         
                            <select name="column" required class="form-control">
                                 <option value="division" {{Request::get('column') == 'division' ? 'selected':''}}>Division</option>
                                 <option value="district" {{Request::get('column') == 'district' ? 'selected':''}}>District</option>
                                 <option value="thana" {{Request::get('column') == 'thana' ? 'selected':''}}>Thana</option>
                                 <option value="postoffice" {{Request::get('column') == 'postoffice' ? 'selected':''}}>Post office</option>
                                 <option value="postcode" {{Request::get('column') == 'postcode' ? 'selected':''}}>Post Code</option>
                            </select>
                            
                            </div>
                            
                        <div class="input-group input-group-sm" style="margin-right:20px;">
    
                            <input type="text" name="q" class="form-control" placeholder="Search" value="{{Request::get('q')}}">
                             
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        
                        </div>
                        </form>
                    </div>
                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>Division</th>
                                <th>District</th>
                                <th>Thana</th>
                                <th>Post office</th>
                                <th>Post Code</th>
                                <th>Action</th>
                            </tr>

                            @foreach($districts as $district)
                                <tr>
                                    <td>{{ $district->division }}</td>
                                    <td>{{ $district->district }}</td>
                                    <td>{{ $district->thana }}</td>
                                    <td>{{ $district->postoffice }}</td>
                                    <td>{{$district->postcode}}</td>
                                    <td>

                                        <a class="btn btn-xs btn-info"
                                           href="{{route('admin_district.edit',$district->id)}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $query ? '' : $districts->links('component.paginator', ['object' => $districts]) }}
                        </div>
                        <!-- /.pagination pagination-sm no-margin pull-right -->
                    </div>

                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
   
   
@endsection

@push('scripts')

@endpush
