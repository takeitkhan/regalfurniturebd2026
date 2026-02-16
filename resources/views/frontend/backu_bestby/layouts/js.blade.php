
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script>window.jQuery || document.write('<script src="{{ URL::asset('public/frontend/js/vendor/jquery-3.3.1.min.js') }}"><\/script>')</script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/main.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('public/frontend/js/plugins.js') }}"></script>



@if(!empty(Session::get('sweet_alert')))
    <script>
        var data = '<?php echo Session::get('sweet_alert') ?>';
        swal("Good job!", data, "success");
    </script>
@endif