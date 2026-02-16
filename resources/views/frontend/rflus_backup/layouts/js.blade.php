<script type="text/javascript" src="{{ URL::asset('public/frontend/js/jquery-2.2.4.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/bootstrap.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/owl-carousel/owl.carousel.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/slick-slider/slick.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/themejs/libs.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/unveil/jquery.unveil.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/countdown/jquery.countdown.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('public/frontend/js/dcjqaccordion/jquery.dcjqaccordion.2.8.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/datetimepicker/moment.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('public/frontend/js/datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/jquery-ui/jquery-ui.min.js') }}"></script>

<script type="text/javascript" src="{{ URL::asset('public/frontend/js/modernizr/modernizr-2.6.2.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('public/frontend/js/minicolors/jquery.miniColors.min.js') }}"></script>

<!-- Theme files
============================================-->

<script type="text/javascript" src="{{ URL::asset('public/frontend/js/themejs/application.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/themejs/so_megamenu.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/themejs/addtocart.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/themejs/homepage.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/themejs/toppanel.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/style.js') }}"></script>



@if(!empty(Session::get('sweet_alert')))
    <script>
        var data = '<?php echo Session::get('sweet_alert') ?>';
        swal("Good job!", data, "success");
    </script>
@endif
