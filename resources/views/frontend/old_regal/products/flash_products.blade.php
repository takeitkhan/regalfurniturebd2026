@extends('frontend.layouts.app')

@section('content')
    <?php $tksign = '&#2547; '; ?>
    <div class="container">
        <div class="row">
            <div class="breadcrumb-warp section-margin-two">
                <div class="col-md-12">

                    <div class="breadcrumb">
                        <?php
                        $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                        $breadcrumbs->setDivider(' » &nbsp;');
                        $breadcrumbs->addCrumb('Home', url('/'))
                            ->addCrumb('Flash Sale Products', 'product');
                        echo $breadcrumbs->render();
                        ?>
                    </div>
                    <!-- breadcrumb  end-->
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="about-text">



                </div>
            </div>
        </div>
    </div>
@endsection