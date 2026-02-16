@extends('frontend.layouts.app')

@section('content')
    <section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-warp">
                        <div class="breadcrumb-one">
                            <nav aria-label="breadcrumb">
                                <div class="breadcrumb breadcrumb_one ">
                                    <?php
                                        $breadcrumbs = new Creitive\Breadcrumbs\Breadcrumbs;

                                        $breadcrumbs->setDivider('');
                                        $breadcrumbs->addCrumb('<i class="fa fa-home"></i>', url('/'))
                                            ->addCrumb($page->title, 'product');
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