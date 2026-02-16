@extends('layouts.app')

@section('title', 'Categories Serialise')
@section('sub_title', 'all category management panel')
@section('content')
    <div class="row">

        <div id="success-message" class="col-md-12" style="display: none">
            <div class="callout callout-success">
                <span id="success-text"></span>
            </div>
        </div>

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
            <div class="card">
                <div class="card-header bg-green-active text-white">
                    <div class="card-title">
                        Sortable Product List - {{ $products->first()->term_name ?? '' }} - {{ count($products) }}
                    </div>
                </div>
                <div class="card-body">
                    <ul id="sortable" class="list-group sortable-list">
                        @foreach ($products as $product)
                            <li data-id="{{ $product->id }}"
                                class="list-group-item termdata"
                                style="display: flex; align-items: center; justify-content: space-between">
                                <div>
                                    <i class="fa fa-bars handle mr-2 text-muted"></i>
                                    <strong>ID:</strong> {{ $product->product->id }} |
                                    <strong>Title:</strong> {{ $product->product->title }} |
                                    <strong>SKU:</strong> {{ $product->product->sku }}
                                </div>
                                <span class="badge badge-pill bg-green-active">Sort Order: {{ $product->sort_order ?? 'N/A' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')

    <script>
		$(document).ready(function () {

			$("#sortable").sortable(
				{
					cursor: 'move',
					update: function (event, ui) {

						const childrens = jQuery(event.target).children()
						console.log('childrens', childrens)

						const data = childrens.map(function (index, item) {

							return {
								id: item.dataset.id,
								position: index + 1
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
					url: "{{ url('product/serialise/update') }}",
					data: {
						order: data,
						_token: '{{csrf_token()}}'
					},
					success: function (response) {
						if (response.status === 'success') {
							$('#success-text').text(response.message);
							$('#success-message').fadeIn().delay(3000).fadeOut();
						}
					}
				});

			}
		});
    </script>

    <style type="text/css">
        #sortable {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #sortable li {
            font-size: 1.4em;
            cursor: pointer;
            margin-left: 20px;
            margin: 5px;
            border-bottom: 1px solid #ddd;

        }


    </style>
@endpush