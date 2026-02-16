@extends('layouts.app')

@section('title', 'Newsletters')
@section('sub_title', 'newsletters management panel')
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
        {{--<div class="col-md-4" id="signupForm">--}}
            {{--@component('component.form')--}}
                {{--@slot('form_id')--}}
                    {{--@if (!empty($schedule->id))--}}
                        {{--schedule_form333--}}
                    {{--@else--}}
                        {{--schedule_form333--}}
                    {{--@endif--}}
                {{--@endslot--}}
                {{--@slot('title')--}}
                    {{--Add a new schedule--}}
                {{--@endslot--}}

                {{--@slot('route')--}}
                    {{--@if (!empty($schedule->id))--}}
                        {{--schedule/{{$schedule->id}}/update--}}
                    {{--@else--}}
                        {{--schedule_save--}}
                    {{--@endif--}}
                {{--@endslot--}}

                {{--@slot('fields')--}}
                    {{--<div class="form-group">--}}
                        {{--{{ Form::label('date', 'Date', array('class' => 'date')) }}--}}
                        {{--{{ Form::text('date', (!empty($schedule->date) ? $schedule->date : NULL), ['required', 'class' => 'form-control', 'id' => 'date', 'placeholder' => 'Enter date...']) }}--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--{{ Form::label('start_hour', 'Start Hour', array('class' => 'start_hour')) }}--}}
                        {{--{{ Form::text('start_hour', (!empty($schedule->start_hour) ? $schedule->start_hour : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter start hour...']) }}--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--{{ Form::label('end_hour', 'End Hour', array('class' => 'end_hour')) }}--}}
                        {{--{{ Form::text('end_hour', (!empty($schedule->end_hour) ? $schedule->end_hour : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter end hour...']) }}--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--{{ Form::label('is_active', 'Will it be active?', array('class' => 'is_active')) }}--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--{{ Form::radio('is_active', 1, (!empty($schedule) ? (($schedule->is_active == 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}--}}
                                {{--Yes. This slot will publish--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--{{ Form::radio('is_active', 0, (!empty($schedule) ? (($schedule->is_active == 0) ? TRUE : FALSE) : null), ['class' => 'radio']) }}--}}
                                {{--No. This slot will save as draft--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--{{ Form::label('is_booked', 'Booked Status', array('class' => 'is_booked')) }}--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--{{ Form::radio('is_booked', 1, (!empty($schedule) ? (($schedule->is_booked == 1) ? TRUE : FALSE) : TRUE), ['class' => 'radio']) }}--}}
                                {{--Yes. This slot still un booked--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="radio">--}}
                            {{--<label>--}}
                                {{--{{ Form::radio('is_booked', 0, (!empty($schedule) ? (($schedule->is_booked == 0) ? TRUE : FALSE) : null), ['class' => 'radio']) }}--}}
                                {{--No. This slot already booked--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}


                {{--@endslot--}}
            {{--@endcomponent--}}
        {{--</div>--}}
        <div class="col-md-12">

            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title box-success">Newsletter &nbsp; &nbsp;
                        {{--<a href="{{ url('schedules') }}" class="btn btn-xs btn-success">--}}
                            {{--<i class="fa fa-plus"></i>--}}
                        {{--</a>--}}
                    </h3>
                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Status</th>
                                <th>Action</th>

                            </tr>
                            {{--{{dd($newsletters)}}--}}

                            @foreach($newsletters as $newsletter)
                                <?php //dump($newsletter);?>
                                <tr>
                                    <td>{{ $newsletter->id }}</td>
                                    <td>{{ $newsletter->email }}</td>
                                    <td>{{ $newsletter->gender }}</td>
                                    <td>


                                        {{ ($newsletter->is_active == 1)? 'Active': 'Inactive' }}

                                    </td>

                                    <td>

                                        @if($newsletter->is_active == 1)
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("newsletter_status/{$newsletter->id}/0") }}">
                                                <i class="fa fa-check"></i>
                                            </a>
                                        @else
                                            <a class="btn btn-xs btn-success"
                                               href="{{ url("newsletter_status/{$newsletter->id}/1") }}">
                                                <i class="fa fa-check-square-o"></i>
                                            </a>

                                        @endif


                                    </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="box-footer clearfix">
                            {{--{{ $schedules->links('component.paginator', ['object' => $schedules]) }}--}}
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
    <script>
        jQuery(document).ready(function ($) {
            $.noConflict();

        });
    </script>
@endpush