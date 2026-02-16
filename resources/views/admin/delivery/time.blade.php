@extends('layouts.app')

@section('title', 'Timespan')
@section('sub_title', '')
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
        
        <div class="col-md-4">
                       @component('component.form')
                @slot('form_id')
                        district_form333
                @endslot
                @slot('title')
                    {{isset($timespan) ? 'Edit' : 'Add'}} Timespan
                @endslot

                @slot('route')
                    @if (!empty($timespan->id))
                        {{route('admin.delivery.timespan.update',$timespan->id)}}
                    @else
                        {{route('admin.delivery.timespan.store')}}
                    @endif
                @endslot

                @slot('fields')
                    {{csrf_field()}}
                    <div class="form-group">
                        {{ Form::label('timespan', 'Timespan', array('class' => 'timespan')) }}
                        {{ Form::text('timespan', $timespan->timespan??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter timespan...']) }}
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('description', 'Description', array('class' => 'description')) }}
                        {{ Form::textarea('description', $timespan->description??'', ['required', 'class' => 'form-control', 'placeholder' => 'Enter description...']) }}
                    </div>
                @endslot
            @endcomponent
        </div>

        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Timespan</h3>

                    <div class="box-tools">
                        {{ Form::open(array(url('medias/all'), 'method' => 'post', 'value' => 'PATCH', 'id' => 'search_media')) }}
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="q" class="form-control pull-right" placeholder="Search">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th title="File Name, Type, Extension, Uploader Name, Status, Uploaded Date">
                                Timespan
                            </th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        
                        @foreach($timespans as $timespan)
                        <tr>
                            <td>{{$timespan->id}}</td>
                            <td>{{$timespan->timespan}}</td>
                            <td>{{$timespan->is_active?'Active':'Not active'}}</td>
                            <td>
                                <a href="{{route('admin.delivery.timespan.status',$timespan->id)}}" class="btn btn-xs btn-success">
                                    <i class="fa {{$timespan->is_active?'fa-check-circle':'fa-check'}}" aria-hidden="true"></i>
                                </a>
                                <a href="{{route('admin.delivery.timespan')}}?id={{$timespan->id}}" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
                                <a href="{{route('admin.delivery.timespan.delete',$timespan->id)}}" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        
                        
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $timespans->links('component.paginator', ['object' => $timespans]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>

        </div>
    </div>
@endsection
@push('scripts')

@endpush
