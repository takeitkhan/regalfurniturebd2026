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
                <li><a href="{{ route('advertisements.index') }}">Advertisements</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row box-1-about">
                 <div class="col-md-12">
                     <div class="advertisement-titel"><h3>TVC</h3></div>
                 </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="single-testmonial">
                                    <div class="single-testmonial_det">
                                        <span class="single-advertisement_img">
                                            <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/rfl-kitchen-rack.jpg" alt="">
                                        </span>
                                    </div>
                                    <div class="single-advertisement_ct">
                                        <h3>
                                            TVC Advertisements
                                        </h3>
                                    </div>
                            </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="single-testmonial">
                                    <div class="single-testmonial_det">
                                        <span class="single-advertisement_img">
                                            <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/rfl-kitchen-rack.jpg" alt="">
                                        </span>
                                    </div>
                                    <div class="single-advertisement_ct">
                                        <h3>
                                            TVC Advertisements
                                        </h3>
                                    </div>
                            </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="single-testmonial">
                                    <div class="single-testmonial_det">
                                        <span class="single-advertisement_img">
                                            <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/rfl-kitchen-rack.jpg" alt="">
                                        </span>
                                    </div>
                                    <div class="single-advertisement_ct">
                                        <h3>
                                            TVC Advertisements
                                        </h3>
                                    </div>
                            </div>
                    </div>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="single-testmonial">
                                    <div class="single-testmonial_det">
                                        <span class="single-advertisement_img">
                                            <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/rfl-kitchen-rack.jpg" alt="">
                                        </span>
                                    </div>
                                    <div class="single-advertisement_ct">
                                        <h3>
                                            TVC Advertisements 
                                        </h3>
                                    </div>
                            </div>
                    </div>
                       <!--  <div class="title-about-us2">
                            <h1>{{ $ad->title }}</h1>
                        </div>
                        @if ($ad->images)
                        <div class="w-image-box">
                            <div class="item-image">
                                @php
                                $images = $ad->images;
                                $ids = explode(',', $images);
                                @endphp
                                <?php if (!empty($ids[0])): ?>
                                <?php $image = \App\Image::where('id', $ids[0])->get()->first();  //dump($image) ?>
                                <img class="pull-right photoside" src="{{ url($image->full_size_directory) }}" />
                                <?php endif; ?>
                                {!! $ad->description !!}
                            </div>
                        </div>
                    @endif -->
                
                <!-- <div class="col-md-3 why-choose-us">
                    <div class="title-about-us">
                        <h2>Recommended Posts</h2>
                    </div>
                    <div class="content-why">
                        @php
                            $reqs = App\Post::where('id', '!=', $ad->id)->inRandomOrder()->take(10)->get();
                        @endphp
                        <ul class="why-list">
                            @foreach ($reqs as $req)
                            <li>
                            <a title="{{ $req->title }}" href="{{ route('advertisements.show', $req->id) }}">{{ $req->title }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div> -->
                
            </div>
        </div>
    </div>
</div>
@endsection