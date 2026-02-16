@extends('frontend.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="breadcrumb-warp section-margin-two">
                <div class="col-md-12">
                    <div class="">
                        <ul class="breadcrumb">
                            <?php
                            $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                            $breadcrumbs->setDivider('');
                            $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                                ->addCrumb($page->title, 'product');
                            echo $breadcrumbs->render();
                            ?>
                         </ul>
                    </div>
                    <!-- breadcrumb  end-->
                </div>
            </div>
        </div>
    </div>
    <div class="about-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="about-text">
                        <?php echo $page->description; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection