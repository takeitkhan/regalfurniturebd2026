@extends('layouts.app')


@section('title', 'Blank Template')
@section('sub_title', 'a quick start template of blade for this software')
@section('content')
    <div class="row">

        <div class="col-md-12">
            @foreach($blank as $b)
                <ul>
                    <li>{{ $b }}</li>
                </ul>
            @endforeach

            @component('component.jumbotron')
                @slot('title')
                    Dhaka Onek Dur
                @endslot
                None in default
            @endcomponent
        </div>

    </div>
    <!-- Date dd/mm/yyyy -->
    <div class="form-group">
        <label>Date masks:</label>

        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input id="date" type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
        </div>
        <!-- /.input group -->
    </div>
    <!-- /.form group -->

    <!-- Date dd/mm/yyyy -->
    <div class="form-group">
        <label>Wysiwyg</label>

        <div class="input-group">
            <textarea id="wysiwyg" placeholder="Place some text here"
                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
        </div>
        <!-- /.input group -->
    </div>
    <!-- /.form group -->
@endsection