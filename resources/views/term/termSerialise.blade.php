@extends('layouts.app')

@section('title', 'Categories Serialise')
@section('sub_title', 'all category management panel')
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
        <div class="col-md-12" id="signupForm">
            
                <div class="thumbnail" style="float: right; margin-right: .9rem;">
                    
                    <a class="btn btn-success" href=" {{ url('terms') }} ">Category</a>
                </div>
            <div class="thumbnail">
                
                    <ul id="sortable">
                        @foreach ($termList as $term)
                        <li data-id="{{ $term->id }}" class="ui-state-default termdata"> ({{ $term->id }}) {{ $term->name }}</li>
                        @endforeach
                    
                      </ul>
                       
                
            </div>
            
        </div>
        
    </div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function() {

            $( "#sortable" ).sortable(
                {
                // items: "tr",
                // cursor: 'move',
                // opacity: 0.6,
                update: function(event,ui) {

                    const childrens = jQuery(event.target).children()

                    const data = childrens.map(function(index,item){

                        return {
                            id: item.dataset.id,
                            position: index+1
                        }
                    });

                       sendOrderToServer(data.toArray());
                }
                }
            );
            function sendOrderToServer(data) {

        
                
                jQuery.ajax({
                type: "POST", 
                dataType: "json", 
                url: "{{ url('terms/serialise/update') }}",
                data: {
                  
                    order: data,
                    _token: '{{csrf_token()}}'
                },
                success: function(response) {
                    if (response.status == "success") {
                        console.log(response);
                    } else {
                        console.log(response);
                    }
                }
                });

                }
        } );
    </script>

    <style type="text/css">
      #sortable { list-style-type: none; margin: 0; padding: 0; margin-left: 20px; }
      #sortable li { 
                     font-size: 1.4em;
                     cursor: pointer;
                     margin-left: 20px;
                     margin: 5px;
                     border-bottom:1px solid #ddd;

       }

      
    </style>
@endpush