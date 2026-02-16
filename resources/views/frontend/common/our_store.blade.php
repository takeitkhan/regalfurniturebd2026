@extends('frontend.layouts.app')

@section('content')
  <div class="main-container container">
 <!-- breadcrumb area -->
    <section class="breadcrumb-area">
        <div class="">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Our Showroom</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area -->
    <br>
    <br>
    <div class="row">
            <div id="content" class="col-sm-12 item-article">
                <div class="row box-1-about">
                    <div class="col-md-4 col-sm-4 shop_ara">
                        <div class="title-about_one-wp">
                             <div class="title-about-us2">
                                <div class="title-about-us9">
                                  <h3>Shop type :</h3>
                                </div>
                            </div>

                            

                            <div class="title-about-us3">

                                @php
                                    $i = 1;
                                @endphp

                                @foreach ( $shop_types = get_shop_type() as $shop_type)
                                <div class="form-check form-check1">
                                  <input name="shop_type[]" class="form-check-input get_shop_type"  type="checkbox"
                                         value="{{$shop_type}}" {{($i == 2)? 'checked': ''}}>
                                  <label class="form-check-label" for="defaultCheck1">
                                    {{$shop_type}}
                                  </label>
                                </div>
                                    @php
                                        ++$i;
                                    @endphp
                                @endforeach
                              
                          </div>

                        </div>
                         <div class="title-about_one-wp">
                            <div class="title-about-us2">
                              <div class="title-about-us9">
                                <h3>District : </h3>
                              </div>
                            </div>
                            <div class="title-about-us3">
                                <select id="district" class="form-control">
                                  <option> Choose a District</option>
                                  @foreach ($districts as $district)
                                      <option value="{{ $district->district }}" {{ ( $district->district=='Dhaka')? 'selected':''}}> {{ $district->district }} </option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="title-about_one-wp">
                        <div class="title-about-us2">
                          <div class="title-about-us9">
                            <h3>Thana : </h3>
                          </div>
                        </div>

                        <div class="title-about-us3">
                          <select id="thana" class="form-control">

                          </select>
                        </div>

                      </div>
                    </div>
                
                    <div class="col-md-4 col-sm-4">
                      <div class="service_point">
                        <div class="service_point-area">
                            <ul class="list-unstyled" id="shop_location">
                                @php
                                    $i = 1;

                                @endphp
                                @foreach($stores as $store)



                                    <li> {{$i}}. {{$store->title}}<br>


                                        {{$store->address}}<br>
                                        Phone: {{$store->author}} ({{$store->phone}})


                                    </li>

                                    @php

                                        ++$i;
                                    @endphp

                                @endforeach


                            </ul>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4 col-sm-4 why-choose-us">
                        
                        <div class="content-why">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.045360866231!2d90.42316481429754!3d23.781398993509793!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c796c76c1a8b%3A0x7ff1d179fba4c47c!2sPRAN-RFL%20GROUP!5e0!3m2!1sen!2sbd!4v1583217403411!5m2!1sen!2sbd" width="100%" height="530" frameborder="0" style="border:0" allowfullscreen></iframe>
                            <!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.7933752855015!2d90.37348185096074!3d23.754746594450555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8ace8ac43b3%3A0x9d1155a7322c28d4!2sBest+Buys+Cameras+%26+Photos!5e0!3m2!1sen!2sbd!4v1544333463440" width="100%" height="530" frameborder="0" style="border:0" allowfullscreen></iframe>-->
                        </div>
                    </div>
                </div>
            </div>
    </div>
  </div>

@section('cusjs')
  <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();

            get_thana_by_district();
            get_shop_by_filter();

        $('#district').on('change', function () {
            get_thana_by_district();
            get_shop_by_filter();
      });
      
      $(".get_shop_type").on('change',function(){
            get_thana_by_district();
            get_shop_by_filter();
      })


      $('#thana').on('change', function () {
          get_shop_by_filter();

    });


      function get_thana_by_district() {
          var district = $('#district').val();

          $.ajax({
              url: baseurl + '/get_thana_by_district',
              method: 'get',
              data: {district: district},
              success: function (data) {
                  $('#thana').html(data);
              },
              error: function () {
              }
          });
      }

      function get_shop_by_filter() {

          var district = $('#district').val();
          var thana = $('#thana').val();
          var shop_type = [];
          $(".get_shop_type:checked").each(function(i){
              shop_type.push( this.value );
          });
          
            /*$.ajax({
                url: `/shop_type?district=${district?district:''}&thana=${thana?thana:''}&shop_type=${shop_type?shop_type:''}`,
                
                success: function(result){
               console.log(result)
                }
                
            });*/

          $.ajax({
              url: baseurl + '/shop_type',
              method: 'get',
              data: {
                  district : district,
                  thana: thana,
                  shop_type: shop_type
              },
              success: function (data) {
                  $('#shop_location').html(data);
                  //console.log(data)
              },
              error: function (e) {
                  $('#shop_location').html(e);
              }
          });
      }

    });

    // function shopType(v){
    //   jQuery(document).ready(function ($) {
    //     $.noConflict();
    //
    //
    //
    //     var shop_type = [];
    //     $(".get_shop_type:checked").each(function(i){
    //          shop_type.push( this.value );
    //     });
    //
    //   //alert(shop_type );
    //
    //     var thana = $('#thana').val();
    //      $.ajax({
    //         url: baseurl + '/shop_type',
    //         method: 'get',
    //         data: {thana: thana, shop_type: shop_type},
    //         success: function (data) {
    //             $('#shop_location').html(data);
    //         },
    //         error: function (e) {
    //           $('#shop_location').html(e);
    //         }
    //     });
    //   })
    // }

  </script>
@endsection

@endsection