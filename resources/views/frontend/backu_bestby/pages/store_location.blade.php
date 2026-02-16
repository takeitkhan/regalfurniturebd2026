@extends('frontend.layouts.app')

@section('content')
<div class="main-container container">
    <ul class="breadcrumb">
            <?php
                $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;
                 $breadcrumbs->setDivider('');
                 $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                    ->addCrumb('Our Store Location', 'store_location');
                echo $breadcrumbs->render();
            ?>
        </ul>

		<div class="row">
        <div id="content" class="col-sm-12 item-article">
            <div class="row box-1-about">
                <div class="col-md-9">
                  <div class="title-about-us_one">
                    <h3>Our Store Location</h3>
                  </div>

                    <div class="welcome-about-us-header">
                    <div class="row">
                      <div class="select-area">
                            <div class="select-area_warp">
                              <form action="">
                                <div class="col-md-3">
                                  <div class="title-about-us1">
                                    <h4>Select Division</h4>
                                  </div>
                                    <select class="form-control">
                                      <option>Default select</option>
                                      <option>Default select</option>
                                      <option>Default select</option>
                                      <option>Default select</option>
                                      <option>Default select</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                  <div class="title-about-us1">
                                  <h4>District</h4>
                                </div>
                                    <select class="form-control">
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                  </select>
                              </div>
                              <div class="col-md-3">
                                <div class="title-about-us1">
                                  <h4>Thana</h4>
                                </div>
                                <select class="form-control">
                                  <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                </select>
                              </div>
                              <div class="col-md-3">
                                <div class="title-about-us1">
                                  <h4>Upazila</h4>
                                </div>
                                <select class="form-control">
                                  <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                    <option>Default select</option>
                                </select>
                              </div>
                            </form>
                          </div>
                        </div>
                        <div class="col-md-12">
                            <div class="single-shop-areai">
                              <div class="col-md-9">
                                <div class="title-about-us1">
                                  <h3>RFL Best Buy - Town Hall</h3>
                                </div>
                                  <div class="">Nulla auctor mauris ut dui luctus semper. In hac habitasse platea dictumst. Duis pellentesque ligula a    risus suscipit dignissim. Nunc non nisl lacus. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.
                                      <br>
                                      <br>Pellentesque semper congue sodales. In consequat, metus eget con sequat ornare, augue dolor blandit purus, vitae lacinia nisi tellus in erat. Nulla ac justo eget massa aliquet sodales. Maecenas mattis male suada sem, in fringilla massa dapibus quis. Suspendisse aliquam leo id neque auctor molestie. Etiam at nulla tellus.
                                  </div>
                              </div>
                              <div class="col-md-3">
                                  <div class="content-about-us_one">
                                      <div class="image-about-us image-stor-us">
                                          <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/best.jpg" alt="Image Client">
                                      </div>
                                  </div>
                               </div>
                            </div>

                            <div class="single-shop-areai">
                              <div class="col-md-9">
                                <div class="title-about-us1">
                                  <h3>RFL Best Buy Mirpur 12</h3>
                                </div>
                                  <div class="">Nulla auctor mauris ut dui luctus semper. In hac habitasse platea dictumst. Duis pellentesque ligula a    risus suscipit dignissim. Nunc non nisl lacus. Integer pharetra lacinia dapibus. Donec eu dolor dui, vel posuere mauris.
                                      <br>
                                      <br>Pellentesque semper congue sodales. In consequat, metus eget con sequat ornare, augue dolor blandit purus, vitae lacinia nisi tellus in erat. Nulla ac justo eget massa aliquet sodales. Maecenas mattis male suada sem, in fringilla massa dapibus quis. Suspendisse aliquam leo id neque auctor molestie. Etiam at nulla tellus.

                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="content-about-us">
                                    <div class="image-about-us image-stor-us">
                                        <img src="http://103.218.26.178:2145/bestbuy/storage/uploads/fullsize/2019-03/best.jpg" alt="Image Client">
                                    </div>
                                </div>
                               </div>
                            </div>
                          </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 why-choose-us">
                    <div class="title-about-us_one">
                        <h3>Our Store Location Map</h3>
                    </div>
                    <div class="content-why">
                       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.7933752855015!2d90.37348185096074!3d23.754746594450555!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8ace8ac43b3%3A0x9d1155a7322c28d4!2sBest+Buys+Cameras+%26+Photos!5e0!3m2!1sen!2sbd!4v1544333463440" width="100%" height="530" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
  @endsection