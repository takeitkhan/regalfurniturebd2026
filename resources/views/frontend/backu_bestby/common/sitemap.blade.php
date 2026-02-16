@extends('frontend.layouts.app') 
@section('content')

<div class="main-container container">
	<ul class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i></a></li>
		<li><a href="#">Site Map</a></li>

	</ul>

	<div class="row">
		<div id="content" class="col-sm-12">
			<h2 class="title">Product Categories</h2>
			<div class="row">
				@php
				$terms = App\Term::where('parent', 1)->get(); 
				@endphp @foreach ($terms as $term)
				<div class="col-md-4 col-sm-6">
					<ul class="simple-list arrow-list bold-list">

						<li>
							<a href="{{ url('c/' . $term->seo_url) }}">{{ $term->name }}</a>
							@php
							$subterms = App\Term::where('parent', $term->id)->get();
							@endphp
							<ul>
								@foreach ($subterms as $subterm)
								<li><a href="{{ url('c/' . $subterm->seo_url) }}">{{ $subterm->name }}</a>
									@php $subsubterms = App\Term::where('parent',
									$subterm->id)->get(); 
									@endphp
									<ul>
										@foreach ($subsubterms as $subsubterm)

										<li>
											<a href="{{ url('c/' . $subsubterm->seo_url) }}">{{ $subsubterm->name }}</a>
										</li>
										@endforeach
									</ul>
								</li>
								@endforeach
							</ul>
						</li>
					</ul>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	{{--
	<div class="row">
		<div class="col-md-12">
			<h2 class="title">Post Categories</h2>
			<div class="row">
				@php
				$postTerms = App\Term::where('parent', 2)->get(); 
				@endphp @foreach ($postTerms as $postTerm)
				<div class="col-md-4 col-sm-6">
					<ul class="simple-list arrow-list">
						<li>
							<a href="{{ url('c/'.$postTerm->seo_url) }}">{{ $postTerm->name }}</a>
						</li>
					</ul>
				</div>
				@endforeach
			</div>
		</div>
	</div> --}}
	<div class="row">
		<div class="col-md-12">
			<h2 class="title">Pages</h2>
			<div class="row">
				@php
				$pages = App\Page::where('is_active', 1)->get(); 
				@endphp @foreach ($pages as $pageTerm)
				<div class="col-md-4 col-sm-6">
					<ul class="simple-list arrow-list">
						<li>
							<a href="{{ url('page/' . $pageTerm->id . '/' .$pageTerm->seo_url) }}">{{ $pageTerm->title }}</a>
						</li>
					</ul>
				</div>
				@endforeach
				<div class="col-md-4 col-sm-6">
					<ul class="simple-list arrow-list">
						<li>
							<a href="{{ url('contact') }}">Contact</a>
						</li>
						<li>
							<a href="{{ url('news_events') }}">News & Event</a>
						</li>
						<li>
							<a href="{{ url('affiliates') }}">Affiliates</a>
						</li>
						<li>
							<a href="{{ url('store_location') }}">Store Location</a>
						</li>
						<li>
							<a href="{{ url('track_order') }}">Track Order</a>
						</li>
						<li>
							<a href="{{ url('sitemap') }}">Sitemap</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection