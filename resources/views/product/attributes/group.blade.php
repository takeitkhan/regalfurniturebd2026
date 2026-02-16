@extends('layouts.app')

@section('title', 'Group of Attribute')
@section('sub_title', 'Create or update attribute group of Product')
@section('content')
<div class="row">
    @if(Session::has('success'))
        <div class="col-md-12">
            <div class="callout callout-success">
                {{ Session::get('success') }}
            </div>
        </div>
    @endif

        @if(Session::has('error'))
            <div class="col-md-12">
                <div class="callout callout-danger">
                    {{ Session::get('error') }}
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


    <div class="col-md-4">
        <form action="{{route('product.attribute.group.store.update')}}" method="post">
            @csrf
            @if($editGroup)
                <input type="hidden" name="group_id" value="{{$editGroup->id}}">
            @endif
            <div class="form-group">
                <label for="">Group Name</label>
                <input required type="text" name="group_name" class="form-control" value="{{$editGroup->group_name ?? null}}">
            </div>
            <div class="form-group">
                <label for="">Slug</label>
                <input type="text" name="group_slug" class="form-control" value="{{$editGroup->group_slug ?? null}}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-sm">Submit</button>
            </div>
        </form>
    </div>

    <div class="col-md-8">
        <div class="box">
           <div class="box-body table-responsive no-padding">
               <table class="table table-hover">
                   <thead>
                   <tr>
                       <td>Name</td>
                       <td>Slug</td>
                       <td>Items</td>
                       <td>Action</td>
                   </tr>
                   </thead>
                   @php
                    $datas = \App\Models\ProductAttributeGroup::orderBy('id', 'desc')->get();
                   @endphp
                   <tbody>
                    @foreach($datas as $data)
                        <tr>
                            <td>{{$data->group_name}}</td>
                            <td>{{$data->group_slug}}</td>
                            <td>
                                <a href="{{route('product.attribute.group.item', $data->id)}}">Configure Items</a>
                            </td>
                            <td>
                                <a href="{{route('product.attribute.group.edit', $data->id)}}">Edit</a>
                                 |
                                <form  onclick="return  confirm('Want to delete?');" id="delete-form" method="POST" action="{{route('product.attribute.group.destroy', $data->id)}}" style="display: inline-block;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                        <input type="submit" style="    display: inline-block; background: none; border: none; color: #f44336;" value="Delete">
                                </form>
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
