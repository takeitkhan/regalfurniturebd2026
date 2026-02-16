@extends('frontend.layouts.app')

@section('content')
<div class="main-container container">
    <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li><a href="{{ route('protfolios.index') }}">Protfolios</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row box-1-about">
                <div class="col-md-9">
                        <div class="title-about-us2">
                            <h1>{{ $pro->title }}</h1>
                        </div>
                        @if ($pro->images)
                        <div class="w-image-box">
                            <div class="item-image">
                                @php
                                $images = $pro->images;
                                $ids = explode(',', $images);
                                @endphp
                                <?php if (!empty($ids[0])): ?>
                                <?php $image = \App\Image::where('id', $ids[0])->get()->first();  //dump($image) ?>
                                <img class="pull-right photoside" src="{{ url($image->full_size_directory) }}" />
                                <?php endif; ?>
                                {!! $pro->description !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-3 why-choose-us">
                    <div class="title-about-us">
                        <h2>Recommended Posts</h2>
                    </div>
                    <div class="content-why">
                        @php
                            $reqs = App\Post::where('id', '!=', $pro->id)->inRandomOrder()->take(10)->get();
                        @endphp
                        <ul class="why-list">
                            @foreach ($reqs as $req)
                            <li>
                            <a title="{{ $req->title }}" href="{{ route('protfolios.show', $req->id) }}">{{ $req->title }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection