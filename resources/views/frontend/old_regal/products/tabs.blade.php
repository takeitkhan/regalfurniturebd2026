<!--description-area section  -->
<section class="description-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="descpition-warp">
                    <div id="exTab1" class="tabs-2">
                        <div class="description-header">
                            <div class="socile-descption pull-right">
                                <p>Share</p>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={!! seo_url_by_id($product->id) !!}">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                                <a href="#">
                                    <i class="fa fa-google-plus"></i>
                                </a>
                            </div>
                            <ul class="nav description-tab">
                                <li class="active">
                                    <a href="#b1" data-toggle="tab">Description</a>
                                </li>
                                <li>
                                    <a href="#b2" data-toggle="tab">Comments/ Question</a>
                                </li>
                                <li>
                                    <a href="#b3" data-toggle="tab">Reviews</a>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane active" id="b1">
                                <div class="Description-det">
                                    {!! $product->description !!}
                                </div>
                            </div>
                            <div class="tab-pane" id="b2">
                                <div class="comment-area">
                                    @include('component.comment_form')
                                    <div class="row">
                                        <div class="col-md-12" id="comments-box">
                                            @include('component.comments')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="b3">
                                <div class="review-det">
                                    <div class="col-md-7">
                                        <div class="main-star">
                                            <h3>average rating</h3>
                                            <i class="fa fa-star"></i>
                                            <p>based on 1 rating</p>
                                        </div>
                                        <div class="lavle-star">

                                            <div class="single-stare">
                                                <ul class="list-unstyled">
                                                    <li class="lavle-star-left">
                                                        <p>5 stars</p>
                                                    </li>
                                                    <li class="lavle-star-mid">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-success"
                                                                 role="progressbar"
                                                                 style="width: 100%"
                                                                 aria-valuenow="100"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </li>
                                                    <li class="lavle-star-right"><span>(2)</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="single-stare">
                                                <ul class="list-unstyled">
                                                    <li class="lavle-star-left">
                                                        <p>4 stars</p>
                                                    </li>
                                                    <li class="lavle-star-mid">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bg-none"
                                                                 role="progressbar"
                                                                 style="width: 100%"
                                                                 aria-valuenow="100"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </li>
                                                    <li class="lavle-star-right"><span>(0)</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="single-stare">
                                                <ul class="list-unstyled">
                                                    <li class="lavle-star-left">
                                                        <p>3 stars</p>
                                                    </li>
                                                    <li class="lavle-star-mid">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bg-none"
                                                                 role="progressbar"
                                                                 style="width: 100%"
                                                                 aria-valuenow="100"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </li>
                                                    <li class="lavle-star-right"><span>(0)</span>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="single-stare">
                                                <ul class="list-unstyled">
                                                    <li class="lavle-star-left">
                                                        <p>2 stars</p>
                                                    </li>
                                                    <li class="lavle-star-mid">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bg-none"
                                                                 role="progressbar"
                                                                 style="width: 100%"
                                                                 aria-valuenow="100"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </li>
                                                    <li class="lavle-star-right"><span>(0)</span>
                                                    </li>
                                                </ul>
                                            </div>


                                            <div class="single-stare">
                                                <ul class="list-unstyled">
                                                    <li class="lavle-star-left">
                                                        <p>1 stars</p>
                                                    </li>
                                                    <li class="lavle-star-mid">
                                                        <div class="progress">
                                                            <div class="progress-bar progress-bg-none"
                                                                 role="progressbar"
                                                                 style="width: 100%"
                                                                 aria-valuenow="100"
                                                                 aria-valuemin="0"
                                                                 aria-valuemax="100"></div>
                                                        </div>
                                                    </li>
                                                    <li class="lavle-star-right"><span>(0)</span>
                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="remamber text-center">
                                            <h3>Have you used this product before?</h3>
                                            <div class="remamber-star">
                                                <a href="#"><i
                                                            class="fa fa-star"></i></a>
                                                <a href="#"><i
                                                            class="fa fa-star"></i></a>
                                                <a href="#"><i
                                                            class="fa fa-star"></i></a>
                                                <a href="#"><i
                                                            class="fa fa-star"></i></a>
                                                <a href="#"><i
                                                            class="fa fa-star"></i></a>
                                            </div>
                                            <div class="remamber-btn">
                                                <a href="#">write a review</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>