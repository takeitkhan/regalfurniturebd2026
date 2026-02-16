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
                <li><a href="{{ route('testimonials.index') }}">Testimonials</a></li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="row box-1-about">



                <div class="col-md-9">

                       <!--  <div class="title-about-us2">
                            <h1>{{ $testi->title }}</h1>
                        </div>
                        @if ($testi->images)
                        <div class="w-image-box">
                            <div class="item-image">
                                @php
                                $images = $testi->images;
                                $ids = explode(',', $images);
                                @endphp
                                <?php if (!empty($ids[0])): ?>
                                <?php $image = \App\Image::where('id', $ids[0])->get()->first();  //dump($image) ?>
                                <img class="pull-right photoside" src="{{ url($image->full_size_directory) }}" />
                                <?php endif; ?>
                                {!! $testi->description !!}
                            </div>
                        </div>
                    @endif -->
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="single-testmonial">
                            <div class="single-testmonial_ct">
                                <p>
                                    <span><i class="fa fa-quote-left"></i></span>
                                    simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                                    <span><i class="fa fa-quote-right"></i></span>
                                </p>
                            </div>
                            <div class="single-testmonial_det_sp">
                                <span class="single-testmonial_det_img_sp">
                                    <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/male-placeholder-image.jpeg" alt="">
                                </span>
                                <div class="single-testmonial_det_ct_sp">
                                    <h4>Ash Robinson</h4>
                                    <p>Stoke-on-Trent, UK</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="col-md-3 why-choose-us">
                    <div class="title-about-us">
                        <h2>Recommended Posts</h2>
                    </div>
                    <div class="content-why">
                        @php
                            $reqs = App\Post::where('id', '!=', $testi->id)->inRandomOrder()->take(10)->get();
                        @endphp
                        <ul class="why-list">
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