@extends('layouts.app')

@section('title', 'Sofa Fabric')

@section('sub_title', 'manage your Sofa Fabric')

@section('content')
   
    <div class="row">
        @if(Session::has('success'))
            <div class="col-md-12">
                <div class="callout callout-success">
                    {{ Session::get('success') }}
                </div>
            </div>
        @endif

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
        
        
        <div class="col-md-12">

            <div class="box box-success">
                <div class="box-header">
                    
                    
                    <h3 class="box-title">
                        Fabric Post
                        <a href="{{ route('admin.edit.other.fabricPost') }}" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </h3>

                    <div class="box-tools row">
                    </div>
                </div>
                <div class="box-body" style="">
                    <div class="box-body table-responsive no-padding" id="reload_me">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                               
                                <th>Title</th>
                                <th>Image</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>

                            @foreach($fabrics as $fabric)
                                <tr>
                                    
                                    <td>{{$fabric['title']}}</td>
                                    <td>
                                        <img src="{{asset($fabric['image']??'')}}" alt="" height="80px" width="150px" class="img-fluid">
                                    </td>
                                    <td>{{ $fabric['qty'] }}</td>
                                    <td>{{ $fabric['unit'] }}</td>
                                    <td>
                                      @if($fabric['is_active'])
                                       Yes
                                      @else
                                       No
                                      @endif
                                    </td>
                                    <td>

                                        <a class="btn btn-xs btn-info"
                                           href="{{route('admin.edit.other.fabricPost')}}?id={{$fabric['id']}}">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>

                                        <a class="btn btn-xs btn-danger"
                                           href="{{route('admin.other.fabricPost.delete',$fabric['id'])}}">
                                            <i class="fa fa-trash-o fa-lg"></i>
                                        </a>

                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- <div class="box-footer clearfix">
                            {{ $query ? '' : $sliders->links('component.paginator', ['object' => $sliders]) }}
                        </div> --}}
                        <!-- /.pagination pagination-sm no-margin pull-right -->
                    </div>

                </div>
                <div class="box-footer clearfix">
                </div>
            </div>
        </div>
    </div>
   
   
@endsection

@push('scripts')
 
    <script>

        function colorCode () {
            let color = jQuery("#colorCodeId").val();
            if(color != null){
                jQuery("#changeColor").css("background-color", "#"+color);
            }else{
                jQuery("#changeColor").css("background-color", '');
            }
            

        }

        function textColor () {
            let color = jQuery("#textColorId").val();
            if(color != null){
                jQuery("#text_color").css("color", "#"+color);
            }else{
                jQuery("#text_color").css("color", '');
            }
            
                console.log(color)
        }

        
    </script>

@endpush
<style>
    #changeColor{
        height: .5rem;
        width: 100%; 
    }
</style>
