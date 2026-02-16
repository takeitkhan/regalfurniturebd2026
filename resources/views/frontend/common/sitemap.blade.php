@extends('frontend.layouts.app')

@section('content')

    <div class="main-container container">
        <ul class="breadcrumb">
            <li><a href="#"><i class="fa fa-home"></i></a></li>
            <li><a href="#">Site Map</a></li>

        </ul>

        <div class="row">

            <div id="content" class="col-sm-12">

                <h2 class="title">Site Map</h2>
                <div class="row">
                    
                     <!-- page list -->
                    <div class="col-md-12">
                        <div class="aracasr">
                            Categories
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row cat-sitemap">
                            @foreach($terms as $item)
                                <div class="col-md-2 col-sm-4">
                                    <ul class="simple-list arrow-list bold-list">
            
                                        <li><a href="{{url('/c/'.$item->seo_url)}}"><strong>{{$item->name}}</strong></a>
                                        
                                        @php 
                                           $s_terms = App\Term::where(['parent' => $item->id])->get();
                                           
                                        @endphp
                                        
                                        @if($s_terms->count() > 0)
                                            <ul>
                                                  @foreach($s_terms as $sub)
                        
                                                <li><a href="{{url('/c/'.$sub->seo_url)}}">{{$sub->name}}</a>
                                                @endforeach
                            
                                            </ul>
                                            
                                            @endif
                                        </li>
                                  
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    
                    
                    <!-- page list -->
                    <div class="col-md-12">
                        <div class="aracasr">
                            Pages
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row page-sitemap">
                            @foreach($pages as $item)
                                <div class="col-md-2 col-sm-4">
                                    <a href="{{url('/page/'.$item->id.'/'.$item->seo_url)}}">{{$item->title}}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Post list -->
                    <div class="col-md-12">
                        <div class="aracasr">
                           Posts
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row post-sitemap">
                            @foreach($posts as $item)
                                <div class="col-md-2 col-sm-4">
                                    <a href="{{url('/post/'.$item->id.'/'.$item->seo_url)}}">{{$item->title}}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>





                    <!--Recent 50 Products -->
                    <!--<div class="col-md-12">-->
                    <!--    <div class="aracasr">-->
                    <!--        Recent Product-->
                    <!--    </div>-->
                    <!--</div>-->

                    <!--<div class="col-md-12">-->
                    <!--<div class="row product-sitemap">-->
                    <!--@foreach($products as $item)-->
                    <!--    <div class="col-md-2 col-sm-4">-->
                    <!--        <a href="{{url('/product/'.$item->seo_url)}}">{{$item->title}}<br>{{$item->sub_title}}</a>-->
                    <!--    </div>-->
                    <!--    @endforeach-->
                    <!--</div>-->
                    <!--</div>-->



                </div>
            </div>

        </div>
    </div>


@endsection