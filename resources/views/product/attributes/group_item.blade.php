@extends('layouts.app')

@section('title', 'Attribute Item of Attribute Group')
@section('sub_title', 'Create or update attribute group item')
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
            <form action="{{route('product.attribute.group.item.store.update')}}" method="post">
                @csrf
                @if($editItem)
                    <input type="hidden" name="item_id" value="{{$editItem->id}}">
                @endif
                <input type="hidden" name="group_id" value="{{$group_id ?? $editItem->group_id}}">
                <div class="form-group">
                    <label for="">Item Name</label>
                    <input required type="text" name="item_name" class="form-control" value="{{$editItem->item_name ?? null}}">
                </div>
                <div class="form-group">
                    <label for="">Slug</label>
                    <input type="text" name="item_slug" class="form-control" value="{{$editItem->item_slug ?? null}}">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                </div>
            </form>
        </div>

        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Attribute Items

                        <a href="{{route('product.attribute.group.item', $group_id)}}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a href="{{route('product.attribute.group')}}">Back to group</a>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <td>Name</td>
                            <td>Slug</td>
                            <td>Attribute Group</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        @php
                            $datas = \App\Models\ProductAttributeGroupItem::where('group_id', $group_id)->orderBy('id', 'desc')->get();
                        @endphp
                        <tbody>
                        @foreach($datas as $data)
                            <tr>
                                <td>{{$data->item_name}}</td>
                                <td>{{$data->item_slug}}</td>
                                <td>
                                    {{\App\Models\ProductAttributeGroup::where('id', $data->group_id)->first()->group_name}}
                                </td>
                                <td>
                                    <a href="{{route('product.attribute.group.edit', $data->id)}}?group_id={{$data->group_id}}">Edit</a>
                                    |
                                    <form  onclick="return  confirm('Want to delete?');" id="delete-form" method="POST" action="{{route('product.attribute.group.item.destroy', $data->id)}}" style="display: inline-block;">
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
