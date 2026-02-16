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
                <li><a href="{{ route('newsevents.index') }}">News Events</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row box-1-about">
                <div class="col-md-9">
                    <div class="tdvrvdb">
                        <div class="title-about-us5">
                            <h1>{{ $nent->title }}</h1>
                        </div>
                        @if ($nent->images)
                        <div class="w-image-box">
                            <div class="item-image item-image25">
                                @php
                                $images = $nent->images;
                                $ids = explode(',', $images);
                                @endphp
                                <?php if (!empty($ids[0])): ?>
                                <?php $image = \App\Image::where('id', $ids[0])->get()->first();  //dump($image) ?>
                                <img class="photoside" src="{{ url($image->full_size_directory) }}" />
                                <?php endif; ?>
                                {!! $nent->description !!}
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
                <div class="col-md-3 why-choose-us">
                    <div class="title-about-us">
                        <h2>Recommended Posts</h2>
                    </div>
                    <div class="content-why">
                        @php
                            $reqs = App\Post::where('id', '!=', $nent->id)->inRandomOrder()->take(10)->get();
                        @endphp
                        <ul class="why-list list-unstyled">
                            @foreach ($reqs as $req)
                            <li>
                            <a title="{{ $req->title }}" href="{{ route('newsevents.show', $req->id) }}">{{ $req->title }}</a>
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