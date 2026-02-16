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
                <li>Advertisement</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 item-article">
            <div class="row box-1-about">
                <div class="col-md-12 our-member" style="padding-top:0;">
                    <div class="title">
                        <h2>Advertisement</h2>
                    </div>
                    <div class="overflow-owl-slider1">
                        <div class="wrapper-owl-slider1">
                            <div class="row slider-ourmember">
                                @forelse ($ads as $item)

                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="advertisement-titel"><h3>TVC</h3></div>
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-advertisement_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/rfl-kitchen-rack.jpg" alt="">
                                                </span>
                                            </div>
                                            <div class="single-advertisement_ct">
                                                <h3>
                                                    simply dummy text of the printing and typesetting industry 
                                                </h3>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="advertisement-titel"><h3>Press Advertisement</h3></div>
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-advertisement_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/classic-chair-press.jpg" alt="">
                                                </span>
                                                
                                            </div>
                                            <div class="single-advertisement_ct">
                                                <h3>simply dummy text of the printing and typesetting industry</h3>
                                            </div>

                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-6">
                                    <div class="advertisement-titel"><h3>Digital Advertisement</h3></div>
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-advertisement_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/x20160112341bb1.jpg" alt="">
                                                </span>
                                            </div>
                                            <div class="single-advertisement_ct">
                                                <h3>
                                                    simply dummy text of the printing and typesetting industry
                                            </h3>
                                            </div>

                                        </a>
                                    </div>
                                </div>

                                <div class="item-about col-lg-6 col-md-6 col-sm-6">

                                    <div class="item respl-item">
                                        <div class="item-inner">
                                            @if ($item->images)
                                            <div class="w-image-box">
                                                <div class="item-image">
                                                <a title="{{ $item->sub_title }}" href="{{ route('advertisements.show', $item->id) }}">
                                                        @php
                                                            $image = \App\Image::where('id', $item->images)->get()->first();
                                                        @endphp
                                                        <img class="nentphoto" src="{{ asset($image->full_size_directory) }}" alt="Image Client">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="info-member">
                                                <h2 class="cl-name"><a title="{{ $item->sub_title }}" href="{{ route('advertisements.show', $item->id) }}">{{ $item->title }}</a></h2>
                                                <p class="cl-job">{{ $item->sub_title }}</p>
                                                <p class="cl-des">{{ $item->short_description }}</p>
                                                <ul>
                                                    <li>
                                                    <a class="fa fa-f" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ route('advertisements.show', $item->id) }}" target="__black"></a>
                                                    </li>
                                                    <li>
                                                        <a class="fa fa-t" title="Twitter" href="https://twitter.com/share?url={{ route('advertisements.show', $item->id) }}" target="__black"></a>
                                                    </li>
                                                    <li>
                                                        <a class="fa fa-g" title="google" href="https://plus.google.com/share?url={{ route('advertisements.show', $item->id) }}" target="__blank"></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="item-about col-lg-6 col-md-6 col-sm-6">
                                    <h3>No Advertisement</h3>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
@endsection