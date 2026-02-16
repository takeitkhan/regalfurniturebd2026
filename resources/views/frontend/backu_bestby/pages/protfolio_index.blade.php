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
                <li>Protfolios</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 item-article">
            <div class="row box-1-about">
                <div class="col-md-12 our-member" style="padding-top:0;">
                    <div class="title">
                        <h2>Protfolios</h2>
                    </div>
                    <div class="overflow-owl-slider1">
                        <div class="wrapper-owl-slider1">
                            <div class="row slider-ourmember">
                                @forelse ($protfolios as $item)
                                <div class="item-about col-lg-6 col-md-6 col-sm-6">
                                    <div class="item respl-item">
                                        <div class="item-inner">
                                            @if ($item->images)
                                            <div class="w-image-box">
                                                <div class="item-image">
                                                <a title="{{ $item->sub_title }}" href="{{ route('protfolios.show', $item->id) }}">
                                                        @php
                                                            $image = \App\Image::where('id', $item->images)->get()->first();
                                                        @endphp
                                                        <img class="nentphoto" src="{{ asset($image->full_size_directory) }}" alt="Image Client">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="info-member">
                                                <h2 class="cl-name"><a title="{{ $item->sub_title }}" href="{{ route('protfolios.show', $item->id) }}">{{ $item->title }}</a></h2>
                                                <p class="cl-job">{{ $item->sub_title }}</p>
                                                <p class="cl-des">{{ $item->short_description }}</p>
                                                <ul>
                                                    <li>
                                                    <a class="fa fa-f" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ route('protfolios.show', $item->id) }}" target="__black"></a>
                                                    </li>
                                                    <li>
                                                        <a class="fa fa-t" title="Twitter" href="https://twitter.com/share?url={{ route('protfolios.show', $item->id) }}" target="__black"></a>
                                                    </li>
                                                    <li>
                                                        <a class="fa fa-g" title="google" href="https://plus.google.com/share?url={{ route('protfolios.show', $item->id) }}" target="__blank"></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="item-about col-lg-6 col-md-6 col-sm-6">
                                    <h3>No Protfolios</h3>
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