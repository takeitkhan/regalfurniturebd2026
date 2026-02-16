@extends('layouts.app')

@section('title', 'Pages')
@section('sub_title', 'Pages management panel')
@section('content')
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="callout callout-success" id="copied">
                You can copy SEO URL or Name download below by clicking copy icon
            </div>
        </div>
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Pages
                        <a href="{{ url('add_page') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>

                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>Copy Name</th>
                            <th>Title/Sub Title</th>
                            <th>SF URL</th>
                            <th>Copy SF URL</th>
                            <th>Description</th>
                            <th>Is Sticky</th>
                            <th>Is Active</th>
                            <th>Date Created</th>
                            <th>Date Updated</th>
                            <th>Action</th>
                        </tr>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $page->id }}</td>
                                <td>
                                    <input type="hidden"
                                           value="{{ $page->title }}"
                                           id="copyNameID{{ $page->id }}">
                                    <small>
                                        <button onclick="copyName({{ $page->id }})">
                                            <i class="fa fa-copy"></i>
                                        </button>
                                    </small>
                                </td>
                                <td>
                                    {{ $page->title }}
                                    <br/>
                                    {{ $page->sub_title }}

                                </td>
                                <td>
                                    <small>
                                        <b>Slug: </b> {{ $page->seo_url }}
                                    </small>
                                    <br/>
                                    <small>
                                        <b>URL: </b><?php echo url('page/' . $page->id . '/' . $page->seo_url); ?>
                                    </small>
                                </td>
                                <td>
                                    <input type="hidden"
                                           value="<?php echo url('page/' . $page->id . '/' . $page->seo_url); ?>"
                                           id="myInput{{ $page->id }}">
                                    <button onclick="myFunction({{ $page->id }})">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </td>
                                <td>{!!  limit_text($page->description, 20)  !!}</td>
                                <td>{{ $page->is_sticky }}</td>
                                <td>{{ $page->is_active }}</td>
                                <td>{{ $page->created_at }}</td>
                                <td>{{ $page->updated_at }}</td>
                                <td>
                                    <a class="btn btn-xs btn-success"
                                       href="{{ url("edit_page/{$page->id}") }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    {{ Form::open(['method' => 'delete', 'route' => ['delete_page', $page->id], 'class' => 'delete_form']) }}
                                    {{ Form::button('<i class="fa fa-times"></i>', array('type' => 'submit', 'class' => 'btn btn-xs btn-danger')) }}
                                    {{ Form::close() }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $pages->links('component.paginator', ['object' => $pages]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
@endsection



@section('cusjs')
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $.noConflict();


        });

        function myFunction(id) {
            /* Get the text field */
            var copyText = document.getElementById("myInput" + id);

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");

            document.getElementById('copied').innerText = 'Copied the URL: ' + copyText.value;
            /* Alert the copied text */
            //alert("Copied the text: " + copyText.value);
            //http://103.218.26.178:2145/pourashova/page/42/physical-infrastructure
        }

        function copyName(id) {
            /* Get the text field */
            var copyText = document.getElementById("copyNameID" + id);

            /* Select the text field */
            copyText.select();

            /* Copy the text inside the text field */
            document.execCommand("copy");

            document.getElementById('copied').innerText = 'Copied the name: ' + copyText.value;
            /* Alert the copied text */
            //alert("Copied the text: " + copyText.value);
            //http://103.218.26.178:2145/pourashova/page/42/physical-infrastructure
        }
    </script>
@endsection
