@extends('layouts.app')


@section('title', 'Flash Shedule')
@section('sub_title', 'all flash shedule management panel')
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
        <div class="col-md-3" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($schedule->id))
                        schedule_form333
                    @else
                        schedule_form333
                    @endif
                @endslot
                @slot('title')
                    Add a new schedule
                @endslot

                @slot('route')
                    @if (!empty($schedule->id))
                        flash_schedule/{{$schedule->id}}/update
                    @else
                        flash_schedule_save
                    @endif
                @endslot

                @slot('fields')
                    {{ Form::hidden('fs_is_active', (!empty($schedule->fs_is_active) ? $schedule->fs_is_active : 0)) }}

                    <div class="form-group">
                        {{ Form::label('fs_name', 'Name', array('class' => 'fs_name')) }}
                        {{ Form::text('fs_name', (!empty($schedule->fs_name) ? $schedule->fs_name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Name...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('fs_description', 'Description', array('class' => 'fs_description')) }}
                        {{ Form::text('fs_description', (!empty($schedule->fs_description) ? $schedule->fs_description : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter Description...']) }}
                    </div>


                    <div class="form-group">
                        {{ Form::label('fs_start_date', 'Start Date', array('class' => 'fs_start_date')) }}
                        <?php
                        if (!empty($schedule)) {
                            if ($schedule->fs_start_date && $schedule->fs_end_date) {
                                $converted = strtotime($schedule->fs_start_date);
                                $start_date = date('m/d/Y h:i A', $converted);

                                $converted1 = strtotime($schedule->fs_end_date);
                                $end_date = date('m/d/Y h:i A', $converted1);
                                
                                $price_time = strtotime($schedule->fs_price_time);
                                $price_time = date('m/d/Y h:i A', $price_time);

                            } else {
                                $start_date = null;
                                $end_date = null;
                                $price_time = null;
                            }
                        }
                        ?>

                        <div class='input-group date' id='datetimepicker1'>

                            {{ Form::text('fs_start_date', (!empty($start_date) ? $start_date : NULL), ['required', 'class' => 'form-control datepicker', 'placeholder' => 'Enter start date...']) }}

                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> </span>
                        </div>

                    </div>
                    <div class="form-group">
                        {{ Form::label('fs_end_date', 'End Date', array('class' => 'fs_end_date')) }}

                        <div class='input-group date' id='datetimepicker2'>
                            {{ Form::text('fs_end_date', (!empty($end_date) ? $end_date : NULL), ['required', 'id' => 'datepicker1', 'class' => 'form-control datepicker', 'placeholder' => 'Enter end date...']) }}
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('fs_price_time', 'Price Time', array('class' => 'fs_price_time')) }}

                        <div class='input-group date' id='datetimepicker2'>
                            {{ Form::text('fs_price_time', (!empty($price_time) ? $price_time : NULL), ['required', 'id' => 'datepicker1', 'class' => 'form-control datepicker', 'placeholder' => 'Enter price time...']) }}
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                        </div>
                    </div>


                @endslot
            @endcomponent
        </div>
        <div class="col-md-9">

            <div class="box box-success">
                <div class="box-header with-border">

                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>Name</th>
                                <th>Discription</th>
                                <th>Start Date</th>
                                <th>End Date</th>

                                <th>Is Active</th>
                                <th>Action</th>
                            </tr>

                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->fs_name }}</td>
                                    <td>{{ $schedule->fs_description }}</td>
                                    <td>{{ $schedule->fs_start_date }}</td>
                                    <td>{{ $schedule->fs_end_date }}</td>
                                    <td>{{ ($schedule->fs_is_active == 1)? 'Active': 'Inactive'}}</td>

                                    <td>

                                        <a class="btn btn-xs btn-info"
                                           href="{{ url("edit_flash_schedule/{$schedule->id}") }}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        @if($schedule->fs_is_active == 1)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("flash_schedule_status/{$schedule->id}/0") }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("flash_schedule_status/{$schedule->id}/1") }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>

                                        @endif

                                        <a class="btn btn-xs btn-info"
                                           href="{{ url("flash_item/{$schedule->id}") }}">
                                            Add Product
                                        </a>


                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{ $schedules->links('component.paginator', ['object' => $schedules]) }}
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" type="text/javascript"></script>
<script src="https://admin.regalfurniturebd.com/public/cssc/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://admin.regalfurniturebd.com/public/cssc/jquery-ui/jquery-ui.min.js"></script>

<!-- datepicker -->
<script src="{{ URL::asset('public/cssc/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <script>
        jQuery(document).ready(function ($) {
            $.noConflict();

            $(function () {
                // $('#datetimepicker1').datetimepicker();
                // $('#datetimepicker2').datetimepicker();
                $('.datepicker').datetimepicker();
            });


        });
    </script>
    <style type="text/css">
        .datepicker.datepicker-dropdown.dropdown-menu.datepicker-orient-left.datepicker-orient-top {
            z-index: 9999 !important;
        }
    </style>
@endpush