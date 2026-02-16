@extends('layouts.app')

@section('title', 'Interior')

@section('sub_title', 'manage your slider')

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

        <div class="col-md-12" id="signupForm">
            <div class="tab mb-5" style="margin:0 0 6px 0;">

                @if (!empty($interior->id))
                <a href="?tab=basic" class="btn btn-sm btn-success">Basic</a>
                <a href="?tab=interior_image" class="btn btn-sm btn-success">Image</a>
            
                @endif


            </div>
            @includeIf($tab)

        </div>

    </div>

@endsection

@push('scripts')
<link rel="stylesheet" href="{{asset('public/cssc/select2/dist/css/select2.min.css')}}">


<style>


    .image-section {
        padding: .4rem;
        border-top: 1px solid #ddd;
        padding-top: 1.4rem;
    }
    #modal_button{
        display:flex;
        float: right;
        border-radius: .5rem;
        margin-bottom: .3rem;
    }

    #td-edit-delete {
        text-align: center;
    }
    </style>
@endpush


