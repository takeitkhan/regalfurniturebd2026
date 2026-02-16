<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script src="https://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/vue"></script>--}}
{{--<script src="{{ URL::asset('node_modules/vee-validate/dist/vee-validate.js')  }}"></script>--}}
<!-- Morris.js charts
<script src="{{ URL::asset('/css/from_live/js/raphael.min.js') }}"></script>
<script src="{{ URL::asset('/css/from_live/js/morris.min.js') }}"></script>-->
<script src="{{ URL::asset('/public/js/select2.full.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- InputMask -->
<script src="{{ URL::asset('/public/js/jquery.inputmask.js') }}"></script>
<script src="{{ URL::asset('/public/js/jquery.inputmask.date.extensions.js') }}"></script>
<script src="{{ URL::asset('/public/js/jquery.inputmask.extensions.js') }}"></script>
<!-- Sparkline -->
<script src="{{ URL::asset('/public/js/jquery.sparkline.min.js') }}"></script>

<!-- jvectormap -->
<script src="{{ URL::asset('/public/js/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ URL::asset('/public/js/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ URL::asset('/public/js/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ URL::asset('/public/js/moment.min.js') }}"></script>
<script src="{{ URL::asset('/public/js/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ URL::asset('/public/js/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="{{ URL::asset('/public/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- bootstrap time picker -->
<script src="{{ URL::asset('/public/js/bootstrap-timepicker.min.js') }}"></script>


<!-- Bootstrap WYSIHTML5 || Wysiwyg Editor Plugin -->
{{--<script src="{{ URL::asset('/public/js/bootstrap3-wysihtml5.all.min.js') }}"></script>--}}
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.js"></script>--}}
<script src="{{ URL::asset('/public/js/trumbowyg.min.js') }}"></script>

<!-- Slimscroll -->
<script src="{{ URL::asset('/public/js/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ URL::asset('/public/js/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('/public/js/adminlte.min.js') }}"></script>
<script src="{{ URL::asset('/public/js/tritiyo_fun.js') }}"></script>
<script src="{{ URL::asset('/public/js/tritiyo_app.js') }}"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="{{ URL::asset('/public/js/pages/dashboard2.js') }}"></script>-->
<!-- AdminLTE for demo purposes -->
{{--<script src="{{ URL::asset('/public/js/demo.js') }}"></script>--}}
@stack('scripts')

@if(!empty(Session::get('sweet_alert')))
    <script>
        var data = '<?php echo Session::get('sweet_alert') ?>';
        swal("Good job!", data, "success");
    </script>
@endif
