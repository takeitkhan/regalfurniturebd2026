@extends('layouts.app')

@section('title', 'Banks')
@section('sub_title', 'all bank management panel')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

        {{--@endif--}}
        @if($errors->any())
            <div class="col-md-12">
                <div class="callout callout-danger">
                    <h4>Warning!</h4>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <div class="col-md-8" id="signupForm">
            @component('component.form')
                @slot('form_id')
                    @if (!empty($bank->id))
                        bank_form333
                    @else
                        bank_form333
                    @endif
                @endslot
                @slot('title')
                    Add a new bank
                @endslot

                @slot('route')
                    @if (!empty($bank->id))
                        bank/{{$bank->id}}/update
                    @else
                        bank_save
                    @endif
                @endslot

                @slot('fields')
                    <div class="form-group">
                        {{ Form::label('bank_name', 'Bank Name', array('class' => 'bank_name')) }}
                        {{ Form::text('bank_name', (!empty($bank->name) ? $bank->name : NULL), ['required', 'class' => 'form-control', 'placeholder' => 'Enter bank name...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('emi_message', 'EMI Message', array('class' => 'emi_message')) }}
                        {{ Form::text('emi_message', (!empty($bank->emi_message) ? $bank->emi_message : NULL), ['required', 'data-type' => (!empty($product->id) ? 'update' : 'create'), 'id' => 'emi_message', 'class' => 'form-control', 'placeholder' => 'Enter emi message...']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('bank_icon', 'Bank Icon', array('class' => 'bank_icon')) }}
                        {{ Form::text('bank_icon', (!empty($bank->bank_icon) ? $bank->bank_icon : NULL), ['class' => 'form-control', 'placeholder' => 'icons']) }}
                    </div>
                @endslot
            @endcomponent
        </div>
        <div class="col-md-4">

            <div class="box">
                <div class="box-header">
                    <a href="{{ url('banks') }}" class="btn btn-xs btn-success pull-right">
                        <i class="fa fa-plus"></i> Add New Bank
                    </a>
                    <h3 class="box-title">
                        All Banks
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <table class="table table-condensed">
                        <tbody>

                        <tr>
                            <th>#</th>
                            <th>Bank Name</th>
                            <th style="width: 100px;">Action</th>
                        </tr>
                        @foreach($banks as $bank)
                            <tr>
                                <td>{{ $bank->id }}</td>
                                <td>{{ $bank->name }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success" href="{{ url('edit_bank/' . $bank->id) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a class="btn btn-xs btn-danger" href="{{ url('delete_bank/' . $bank->id) }}"
                                       onclick="alert('Are you sure?');">
                                        <i class="fa fa-times-circle"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script>

        <?php if(!empty($bank->id)) { ?>

        <?php } else { ?>

        jQuery(document).ready(function ($) {
            $.noConflict();

            $('#bank_name').blur(function () {
                var m = $(this).val();
                var cute1 = m.toLowerCase().replace(/ /g, '-').replace(/&amp;/g, 'and').replace(/&/g, 'and').replace(/ ./g, 'dec');
                var cute = cute1.replace(/[`~!@#$%^&*()_|+\=?;:'"”,.<>\{\}\[\]\\\/]/gi, '');

                $('#term_css_class, #term_css_id, #seo_url').val(cute);
            });


            $('#term_name').blur(function () {
                var seo_url = $('#seo_url').val();
                var type = $('#seo_url').data('type');

                if (type == 'create') {
                    var data = {
                        'seo_url': seo_url
                    };

                    //console.log(data);

                    jQuery.ajax({
                        url: baseurl + '/check_if_cat_url_exists',
                        method: 'get',
                        data: data,
                        success: function (data) {
                            $('#seo_url').val(data.url);
                        },
                        error: function () {
                            // showError('Sorry. Try reload this page and try again.');
                            // processing.hide();
                        }
                    });
                }

            });
        });
        <?php } ?>


    </script>
    <style type="text/css">
        ul.on_terms {
            margin: 0;
            padding-left: 20px;
        }

        ul.on_terms li {
            border: 1px solid #EEE;
            margin: 2px;
            padding: 3px;
            border-right: 0;
            border-top: 0;
            border-left: 0;
        }
    </style>
@endpush