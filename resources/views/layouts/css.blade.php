{{-- <link rel="stylesheet" href="{{ URL::asset('/cssc/bootstrap/dist/css/bootstrap.min.css') }}"> --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
{{-- <link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/font-awesome.min.css') }}"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/AdminLTE.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/select2.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/skins/_all-skins.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/cssc/jvectormap/jquery-jvectormap.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/bootstrap-colorpicker.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/from_live/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/dropzone.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('/public/css/tritiyo_app.css') }}">


<!-- bootstrap wysihtml5 - text editor || Wysiwyg Editor Plugin -->
{{-- <link rel="stylesheet" href="{{ URL::asset('/public/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}"> --}}
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.8/summernote.css" rel="stylesheet"> --}}
<link href="{{ URL::asset('/public/css/ui/trumbowyg.min.css') }}" rel="stylesheet">


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements Mediaedia queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


<style>
    /* The checkbox-container */
    .checkbox-container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .checkbox-container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: rgb(209, 201, 201);
        border-radius: 2px;
    }

    /* On mouse-over, add a grey background color */
    .checkbox-container:hover input~.checkmark {
        background-color: #ccc;
    }

    /* When the checkbox is checked, add a blue background */
    .checkbox-container input:checked~.checkmark {
        background-color: green;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .checkbox-container input:checked~.checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .checkbox-container .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>
