@extends('layouts.app')

@section('title', 'Delivery Time set')
@section('sub_title', '')
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

        <form method="POST" action="{{route('admin.delivery.termsTimeSpan')}}">
            {{csrf_field()}}
        <div class="col-md-8">
                <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Categories</h3>
                    <div class="box-tools">
                        <input type="text" id="term_search" class="" placeholder="Search">
                        <input type="submit" class="btn btn-sm btn-success" value="Submit" name="Submit">
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" style="max-height:700px;overflow:auto;" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th>
                                Title
                            </th>
                            <th>Timespan</th>
                        </tr>

                        @foreach($terms as $term)
                        <tr class="term_category" data-search="{{$term->id}} {{$term->name}}">
                            <td><input type="checkbox" name="category[]" value="{{$term->id}}"></td>
                            <td>{{$term->name??''}}</td>
                            <td><font color="red">{{$term->timespan->timespan??''}}</font></td>
                        </tr>
                        @endforeach


                        </tbody>
                    </table>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="col-md-4">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Timespan</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" id="reload_me">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>#</th>
                            <th title="File Name, Type, Extension, Uploader Name, Status, Uploaded Date">
                                Timespan
                            </th>
                        </tr>
                        <tr>
                        <td><input type="radio" name="timspan" value="0" ></td>
                            <td>None</td>
                        </tr>
                        @foreach($timespans as $timespan)
                        <tr>
                            <td><input type="radio" name="timespan" value="{{$timespan->id}}"></td>
                            <td>{{$timespan->timespan}}</td>
                        </tr>
                        @endforeach


                        </tbody>
                    </table>
                    <!-- /.pagination pagination-sm no-margin pull-right -->
                </div>
                <!-- /.box-body -->
            </div>

        </div>

    </form>
    </div>
@endsection
@push('scripts')
<script>
    document.getElementById('term_search').addEventListener('keyup',function(){
        const keyword = this.value.toLowerCase()
        document.querySelectorAll('.term_category').forEach(function(item){
           const string = item.dataset.search.toLowerCase()
          if(string.indexOf(keyword) >= 0){
              item.classList.remove('hide')
          }else{
              item.classList.add('hide')
          }


        })
    })
</script>
@endpush
