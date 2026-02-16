@extends('frontend.layouts.app')

@section('content')


<!--  <div class="row">
        <div class="col-md-12">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li>News Events</li>
            </ul>
        </div>
    </div>
 -->
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <div class="breadcrumb breadcrumb_one ">
                                    <?php $tksign = '&#2547; '; ?>
                                        <?php
                                        $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                                        $breadcrumbs->setDivider('');
                                         $breadcrumbs->setDivider('');
                                        $breadcrumbs->addCrumb('Home', url('/'))
                                         ->addCrumb('News and Events', 'product');
                                        echo $breadcrumbs->render();
                                        ?>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<div class="main-container container">
    <div class="row">
        <div class="col-sm-12 item-article">

            <div class="row box-1-about box-1-about_onarw">
                <div class="col-md-10 col-lg-11 col-sm-11">
                    <div class="title">
                        <h2>News and Events</h2>
                    </div>
                </div>
                <div class="col-md-2 col-lg-1 col-sm-1">
                    <div class="select-evt">
                        <div class="form-group">
                        <select class="form-control" id="exampleFormControlSelect1" name="year">
                              <option>Select</option>
                              <option valu="2019">2019</option>
                              
                        </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 our-member" style="padding-top:0;">
                    
                    <div class="wp-events">
                        <div class="row">
                            @php 
                             $news_evensts = App\Post::where(['categories' => 661])->orderBy('id','DESC')->get();
                             
                            
                            
                            @endphp
                            
                            @foreach($news_evensts as $ne)
                             @php 
                              $img = App\Image::find($ne->images);
                             @endphp

                            <div class="col-md-12 col-lg-6">
                                <div class="wp-events-single">
                                    <div class="events-single-img">
                                        <a href="{{url('/news_events/'.$ne->seo_url)}}">
                                             @if (!empty($img))
                                            <img src="{{url($img->full_size_directory)}}" alt="">
                                             @endif
                                        </a>
                                    </div>
                                    <div class="events-single-ct">
                                        <a href="{{url('/news_events/'.$ne->seo_url)}}">
                                            <h4>{{$ne->title}}</h4>
                                            <p>{{$ne->short_description}}</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            @endforeach
             

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>




















@endsection