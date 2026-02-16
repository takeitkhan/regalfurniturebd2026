@extends('layouts.app')

@section('title', 'Depot Manage')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif


        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-body box-body with-border">
                    <form action="{{!empty($depot) ? route('depot.update') : route('depot.store')}}" method="post">
                        @csrf
                        @if(!empty($depot))
                            <input type="hidden" name="id", value="{{$depot->id}}">
                        @endif
                        <div class="form-group">
                            <label for="depot_name">Name of Depot</label>
                            <input required="" class="form-control" placeholder="Depot Name..." id="depot_name" name="depot_name" type="text" value="{{$depot->name ?? null}}">
                        </div>
                        <div class="form-group">
                            <label for="depot_name">Type of Depot</label>
                            @php
                                $types = ['Main', 'General'];
                            @endphp
                            <select name="depot_type" id="" class="form-control">
                                <option value="">Select a Type</option>
                                @foreach($types as $type)
                                    <option value="{{$type}}" {{!empty($depot) && $depot->type == $type ? 'selected' : null}}>{{$type}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="depot_location">Location</label>
                            @php
                                $locations = \App\Models\District::groupBy('division')->get();
                                $districts = \App\Models\District::get()->groupBy('division');
    //                                dump($locations);
                            @endphp
                            <select name="depot_location" id="depot_location" class="form-control" required>
                                <option value="">Select a location</option>
                                @foreach($locations as $data)
                                    <option value="{{$data->id}}" {{!empty($depot) && $depot->division == $data->id ? 'selected' : null}}>{{$data->division}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="depot_location">Districts</label>
                            <select name="depot_district[]" id="depot_district" class="form-control" multiple="multiple" required>
                                @if(!empty($depot) && $depot->districts)
                                    @foreach(explode(',', $depot->districts) as $dis)
                                        @php
                                            $diss = \App\Models\District::where('district', $dis)->first() ?? null;
                                        @endphp
                                        <option value="{{$diss->district}}" selected>
                                            {{$diss->district}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-xs">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-body box-body with-border">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th title="">ID</th>
                                <th>Depots</th>
                                <th>Districts</th>
                                <th>Action</th>
                            </tr>
                            @php
                                $depots = \App\Models\Depot::latest()->get();
                            @endphp
                            @foreach($depots as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>
                                        <strong>Name:</strong>  {{$data->name}} <br>
                                        <strong>Type:</strong>  {{$data->type}} <br>
                                        <strong>Name:</strong>  {{\App\Models\District::where('id', $data->division)->first()->division ?? null}}
                                    </td>
                                    <td>

                                        @foreach(explode(',', $data->districts) as $dis)
                                            {{\App\Models\District::where('district', $dis)->first()->district. ',' ?? null}}
                                        @endforeach
                                    </td>
                                    <td>
                                        <a class="btn btn-xs btn-success"
                                           href="{{ route("depot.edit", $data->id) }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        {{ Form::open(['method' => 'delete', 'route' => ['depot.destroy', $data->id], 'class' => 'delete_form']) }}
                                        {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                                        {{ Form::close() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('cusjs')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" />
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $(document).on("change", "select#depot_location", function(){
                let id = $(this).find(':selected').text();
                $.ajax({
                    'method' : 'GET',
                    'url' : '{{route('get_district_by_division')}}?division='+id,
                    'success' : function (data){
                        $('select#depot_district').html(data);
                    }
                })
            });
            $('select#depot_district').select2({
                theme: "bootstrap"
            });
        });
    </script>


@endsection
