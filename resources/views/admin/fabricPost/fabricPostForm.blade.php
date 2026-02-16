@extends('layouts.app')

@section('title', 'Sofa Fabric')

@section('sub_title', 'manage your Sofa Fabric')

@section('content')

    <div class="row">
        @if (Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
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

        <div class="col-md-8" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($fabric_post->id))
                        slider_form333
                    @else
                        slider_form333
                    @endif
                @endslot
                @slot('title')
                    {{ isset($fabric_post->id) ? 'Edit Data' : 'Add new data' }}
                @endslot

                @slot('route')
                    @if (!empty($fabric_post->id))
                        {{ route('admin.other.fabricPost.update', $fabric_post->id) }}
                    @else
                        {{ route('admin.other.fabricPost.store') }}
                    @endif
                @endslot

                @slot('fields')
                    {{ method_field(isset($district) ? 'PUT' : 'POST') }}
                    {{ csrf_field() }}

                    <div class="form-group">
                        {{ Form::label('title', 'Title', ['class' => 'title']) }}
                        {{ Form::text('title', $fabric_post->title ?? '', ['required', 'class' => 'form-control', 'placeholder' => 'Enter Title']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('images', 'Images', ['class' => 'url']) }}
                        {{ Form::text('images', $fabric_post->images ?? '', ['required', 'id' => 'image_id', 'class' => 'form-control', 'placeholder' => 'Enter Image Id...']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('qty', 'Quantity', ['class' => 'position']) }}
                        {{ Form::text('qty', $fabric_post->qty ?? '', ['required', 'class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('unit', 'Unit', ['class' => 'position']) }}
                        {{ Form::text('unit', $fabric_post->unit ?? '', ['required', 'class' => 'form-control']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::label('active', 'Active', ['class' => 'active']) }}
                        {{ Form::select('active', [0 => 'No', 1 => 'Yes'], $fabric_post->is_active ?? 0, ['required', 'class' => 'form-control']) }}
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-4">
            @component('component.dropzone')
            @endcomponent
            @if (!empty($medias))
                <div class="row" id="reload_me">
                    @foreach ($medias as $media)
                        <div class="col-xs-6 col-md-4">
                            <div href="#" class="thumbnail">
                                <img src="{{ url($media->icon_size_directory) }}" class="img-responsive"
                                    style="max-height: 80px; min-height: 80px;" />
                                <div class="caption text-center">
                                    <p>
                                        <a href="javascript:void(0);" data-id="{{ $media->id }}"
                                            data-option="{{ $media->filename }}" class="btn btn-xs btn-primary"
                                            onclick="get_id(this);" role="button">
                                            Use
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>


@endsection

@push('scripts')
    <script src="{{ asset('public/plugins/dropzone.js') }}"></script>
    <script src="{{ asset('public/js/dropzone-config.js') }}"></script>
    <script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


    <script>
        function get_id(identifier) {


            var dataid = jQuery(identifier).data('id');


            jQuery('#image_id').val(dataid);


        }

        function textColor() {
            let color = jQuery("#textColorId").val();
            if (color != null) {
                jQuery("#text_color").css("color", "#" + color);
            } else {
                jQuery("#text_color").css("color", '');
            }

            console.log(color)
        }
    </script>
@endpush
<style>
    #changeColor {
        height: .5rem;
        width: 100%;
    }
</style>
