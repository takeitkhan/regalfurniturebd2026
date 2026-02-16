@extends('layouts.app')

@section('title', 'Medias')
@section('sub_title', 'manage your files')
@section('content')
    <div class="row">
        <div class="col-md-4">
            @component('component.dropzone')
            @endcomponent
        </div>

        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Media files</h3>

                    <div class="box-tools">
                        {{ Form::open(array(url('medias/all'), 'method' => 'post', 'value' => 'PATCH', 'id' => 'search_media')) }}
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <input type="text" name="q" class="form-control pull-right" placeholder="Search">
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th style="max-width: 100px;"></th>
                            <th>ID</th>
                            <th title="File Name, Type, Extension, Uploader Name, Status, Uploaded Date">
                                File Name & Others
                            </th>
                            <th>Directory</th>
                            <th>Action</th>
                        </tr>
                        @foreach($medias as $media)
                            <tr>
                                <td>
                                    <img class="img-responsive" style="max-width: 150px; max-height: 150px;"
                                         src="{{ url($media->icon_size_directory) }}"/>
                                </td>
                                <td>{{ $media->id }}</td>
                                <td>

                                    <small>
                                        <b title="File Name">File Name: </b>
                                        {{ $media->filename }}
                                    </small>
                                    <br/>
                                    <small>
                                        <b title="File Name">File Type: </b>
                                        {{ $media->file_type }}
                                    </small>
                                    <br/>
                                    <small>
                                        <b title="File Name">File Type: </b>
                                        {{ $media->file_size }}
                                    </small>
                                    <br/>
                                    <small>
                                        <b title="File Name">File Type: </b>
                                        {{ strtoupper($media->file_extension) }}
                                    </small>
                                    <br/>
                                    <small>
                                        <b title="Status">Uploader: </b>
                                        {{ $media->user->name }}
                                    </small>
                                    <br/>
                                    <small>
                                        <b title="Status">Status: </b>
                                        @if($media->status == 1)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-default">Deactive</span>
                                        @endif
                                    </small>
                                    <br/>
                                    <small>
                                        <b title="Status">Uploaded: </b>
                                        {{ $media->created_at }}
                                    </small>

                                </td>

                                <td>
                                    <small>
                                        <i class="fa fa-camera-retro fa-lg" title="Full Size"></i>
                                        <input
                                                type="text"
                                                title="Full Size"
                                                onClick="this.setSelectionRange(0, this.value.length)"
                                                value="{{ url($media->full_size_directory) }}"/>
                                    </small>
                                    <br/><br/>
                                    <small>
                                        <i class="fa fa-compress fa-lg" title="Thumbnail Size"></i>
                                        <input
                                                type="text"
                                                title="Thumbnail Size"
                                                onClick="this.setSelectionRange(0, this.value.length)"
                                                value="{{ url($media->icon_size_directory) }}"/>
                                    </small>
                                </td>

                                <td>
                                    <a class="btn btn-xs btn-danger"
                                       href="{{ url('medias/upload/delete/'.$media->id) }}"
                                       onclick="return confirm('Are you sure?');">
                                        <span class="fa fa-remove"></span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {{ $medias->links('component.paginator', ['object' => $medias]) }}
                    </div>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>

        </div>
    </div>
@endsection
@push('scripts')

<script src="{{ asset('public/plugins/dropzone.js') }}"></script>
<script src="{{ asset('public/js/dropzone-config.js') }}"></script>
<script src="{{ asset('public/js/jquery.czMore-latest.js') }}"></script>


@endpush
