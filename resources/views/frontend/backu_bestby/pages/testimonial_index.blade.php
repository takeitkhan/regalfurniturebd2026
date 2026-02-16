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
                <li>Testimonial</li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 item-article">
            <div class="row box-1-about">
                <div class="col-md-12 our-member" style="padding-top:0;">
                    <div class="title">
                        <h2>Testimonial</h2>
                    </div>
                    <div class="overflow-owl-slider1">
                        <div class="wrapper-owl-slider1">
                            <div class="row slider-ourmember">
                                @forelse ($testimonials as $item)

                                <div class="item-about col-lg-6 col-md-6 col-sm-6">
                                    <div class="item respl-item">
                                        <div class="item-inner">
                                            @if ($item->images)
                                            <div class="w-image-box">
                                                <div class="item-image">
                                                <a title="{{ $item->sub_title }}" href="{{ route('testimonials.show', $item->id) }}">
                                                        @php
                                                            $image = \App\Image::where('id', $item->images)->get()->first();
                                                        @endphp
                                                        <img class="nentphoto" src="{{ asset($image->full_size_directory) }}" alt="Image Client">
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="info-member">
                                                <h2 class="cl-name"><a title="{{ $item->sub_title }}" href="{{ route('testimonials.show', $item->id) }}">{{ $item->title }}</a></h2>
                                                <p class="cl-job">{{ $item->sub_title }}</p>
                                                <p class="cl-des">{{ $item->short_description }}</p>
                                                <ul>
                                                    <li>
                                                    <a class="fa fa-f" title="Facebook" href="http://www.facebook.com/sharer.php?u={{ route('testimonials.show', $item->id) }}" target="__black"></a>
                                                    </li>
                                                    <li>
                                                        <a class="fa fa-t" title="Twitter" href="https://twitter.com/share?url={{ route('testimonials.show', $item->id) }}" target="__black"></a>
                                                    </li>
                                                    <li>
                                                        <a class="fa fa-g" title="google" href="https://plus.google.com/share?url={{ route('testimonials.show', $item->id) }}" target="__blank"></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 col-sm-4 col-xs-6">
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-testmonial_det_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/male-placeholder-image.jpeg" alt="">
                                                </span>
                                                <div class="single-testmonial_det_ct">
                                                    <h4>Ash Robinson</h4>
                                                    <p>Stoke-on-Trent, UK</p>
                                                </div>
                                            </div>
                                            <div class="single-testmonial_ct">
                                                <h3>
                                                    simply dummy text of the printing and typesetting industry

                                                    <!-- <span><i class="fa fa-quote-left"></i></span>
                                                    simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                                                    <span><i class="fa fa-quote-right"></i></span> -->
                                            </h3>
                                            </div>

                                        </a>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-4 col-xs-6">
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-testmonial_det_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/male-placeholder-image.jpeg" alt="">
                                                </span>
                                                <div class="single-testmonial_det_ct">
                                                    <h4>Ash Robinson</h4>
                                                    <p>Stoke-on-Trent, UK</p>
                                                </div>
                                            </div>
                                            <div class="single-testmonial_ct">
                                                <h3>
                                                    simply dummy text of the printing and typesetting industry

                                                    <!-- <span><i class="fa fa-quote-left"></i></span>
                                                    simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                                                    <span><i class="fa fa-quote-right"></i></span> -->
                                            </h3>
                                            </div>

                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-6">
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-testmonial_det_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/male-placeholder-image.jpeg" alt="">
                                                </span>
                                                <div class="single-testmonial_det_ct">
                                                    <h4>Ash Robinson</h4>
                                                    <p>Stoke-on-Trent, UK</p>
                                                </div>
                                            </div>
                                            <div class="single-testmonial_ct">
                                                <h3>
                                                    simply dummy text of the printing and typesetting industry

                                                    <!-- <span><i class="fa fa-quote-left"></i></span>
                                                    simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                                                    <span><i class="fa fa-quote-right"></i></span> -->
                                            </h3>
                                            </div>

                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4 col-xs-6">
                                    <div class="single-testmonial">
                                        <a href="#">
                                            <div class="single-testmonial_det">
                                                <span class="single-testmonial_det_img">
                                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/male-placeholder-image.jpeg" alt="">
                                                </span>
                                                <div class="single-testmonial_det_ct">
                                                    <h4>Ash Robinson</h4>
                                                    <p>Stoke-on-Trent, UK</p>
                                                </div>
                                            </div>
                                            <div class="single-testmonial_ct">
                                                <h3>
                                                    simply dummy text of the printing and typesetting industry

                                                    <!-- <span><i class="fa fa-quote-left"></i></span>
                                                    simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                                                    <span><i class="fa fa-quote-right"></i></span> -->
                                            </h3>
                                            </div>

                                        </a>
                                    </div>
                                </div>




                                @empty
                                <div class="item-about col-lg-6 col-md-6 col-sm-6">
                                    <h3>No Testimonials</h3>
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