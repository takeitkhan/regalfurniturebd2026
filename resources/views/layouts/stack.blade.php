@push('scripts')
    <!-- jQuery 3 -->
    <!-- <script src="{{ URL::asset('public/cssc/jquery/src/jquery.js') }}"></script> -->
    <script src="{{ URL::asset('public/css/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->

    <script src="{{ URL::asset('public/css/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ URL::asset('public/css/bootstrap.min.js') }}"></script>
    <!-- Morris.js charts 
    <script src="{{ URL::asset('public/cssc/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::asset('public/cssc/morris.js/morris.min.js') }}"></script>-->
    <!-- Sparkline -->
    <script src="{{ URL::asset('public/css/jquery.sparkline.min.js') }}"></script>
    <!-- jvectormap -->
    <script src="{{ URL::asset('public/css/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ URL::asset('public/css/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ URL::asset('public/css/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ URL::asset('public/css/moment.min.js') }}"></script>
    <script src="{{ URL::asset('public/css/daterangepicker.js') }}"></script>
    <!-- datepicker -->
    <script src="{{ URL::asset('public/css/bootstrap-datepicker.min.js') }}"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="{{ URL::asset('public/css/bootstrap3-wysihtml5.all.min.js') }}"></script>
    <!-- Slimscroll -->
    <script src="{{ URL::asset('public/css/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ URL::asset('public/css/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ URL::asset('public/css/adminlte.min.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="{{ URL::asset('public/js/pages/dashboard2.js') }}"></script>-->
    <!-- AdminLTE for demo purposes -->
    <!--<script src="{{ URL::asset('public/js/demo.js') }}"></script>-->
@endpush