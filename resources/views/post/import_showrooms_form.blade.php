@extends('layouts.app')

@section('title', 'Import Showrooms')
@section('sub_title', 'excel Showrooms import functionality')
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

        <div class="col-md-8">
            @component('component.form')
                @slot('form_id')
                    product_form
                @endslot
                @slot('title')
                    Import Showrooms
                @endslot

                @slot('route')
                    import_showrooms_save
                @endslot

                @slot('fields')

                    <div class="form-group">
                        {{ Form::hidden('user_id', (!empty(\Auth::user()->id) ? \Auth::user()->id : NULL), ['type' => 'hidden']) }}
                        {{ Form::hidden('lang', (!empty($post->lang) ? $post->lang : 'en'), ['type' => 'hidden']) }}
                    </div>
                    <div class="form-group">
                        {!! Form::label('import_file', "Upload your modified file:") !!}
                        <input class="btn btn-lg" type="file" name="import_file"/>
                    </div>

                @endslot
            @endcomponent
        </div>
        <div class="col-md-4">

        </div>

    </div>
@endsection
@push('scripts')
    <script type="text/javascript">

    </script>
    <style type="text/css">
        .aladagrey {
            background: lightgrey;
        }

        .recordset, .czContainer {
            position: relative;
        }

        .recordset {
            border-bottom: 4px solid white;
        }
    </style>
@endpush
