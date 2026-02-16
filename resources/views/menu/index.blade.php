@extends('layouts.app')

@section('title', 'Menus')
@section('sub_title', 'a quick start template of blade for this software')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <link href="{{ asset('vendor/harimayco/laravel-menu/assets/style.css') }}" rel="stylesheet">
            {!! Menu::render() !!}


            {{--Pushing JavaScript @ bottom of the page--}}
            @push('scripts')
            <script>
                var menus = {
                    "oneThemeLocationNoMenus": "",
                    "moveUp": "Move up",
                    "moveDown": "Mover down",
                    "moveToTop": "Move top",
                    "moveUnder": "Move under of %s",
                    "moveOutFrom": "Out from under  %s",
                    "under": "Under %s",
                    "outFrom": "Out from %s",
                    "menuFocus": "%1$s. Element menu %2$d of %3$d.",
                    "subMenuFocus": "%1$s.Menusu of subelement %2$d of %3$s."
                };
                var arraydata = [];
                var addcustommenur = '{{ route("haddcustommenu") }}';
                var updateitemr = '{{ route("hupdateitem")}}';
                var generatemenucontrolr = '{{ route("hgeneratemenucontrol") }}';
                var deleteitemmenur = '{{ route("hdeleteitemmenu") }}';
                var deletemenugr = '{{ route("hdeletemenug") }}';
                var createnewmenur = '{{ route("hcreatenewmenu") }}';
                var csrftoken = "{{ csrf_token() }}";
                var menuwr = "{{ url()->current() }}";

                jQuery.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrftoken
                    }
           

                });
            </script>
            {{-- {!! Menu::scripts() !!} --}}
            <script type="text/javascript" src="{{asset('vendor/harimayco/laravel-menu/assets/scripts.js')}}"></script>
            <script type="text/javascript" src="{{asset('vendor/harimayco/laravel-menu/assets/scripts2.js')}}"></script>
            <script type="text/javascript" src="{{asset('vendor/harimayco/laravel-menu/assets/menu.js')}}"></script>
            @endpush
        </div>
    </div>
    {{-- {!! Menu::scripts() !!} --}}
@endsection