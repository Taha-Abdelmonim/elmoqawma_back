@extends('layouts.layoutMaster')

@section('title', trans('global.edit_ask'))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection
@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
	<script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.asks"),
			"/" => trans("global.dashboard"),
			"/asks" => trans("global.asks"),
			"end" => trans("global.edit_ask"),
	]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('ask_update', $ask->id)}}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="col-12">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="card mb-4">
				<h5 class="card-header">@lang("global.edit_ask")</h5>
				<div class="card-body">
					<div class="row mb-3">
						<div class="col-6">
							<label for="title_ar" class="form-label">@lang("global.title_ar")</label>
							<input type="text" value="{{ old("title_ar", $ask->title_ar) }}" class="form-control @error('title_ar') is-invalid @enderror" id="title_ar" name="title_ar" placeholder="@lang("global.title_ar")"
							       aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6">
							<label for="title_en" class="form-label">@lang("global.title_en")</label>
							<input type="text" value="{{ old("title_en", $ask->title_en) }}" class="form-control @error('title_en') is-invalid @enderror" id="title_en" name="title_en" placeholder="@lang("global.title_en")"
							       aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-12 mt-5">
							<div class="card">
								<h5 class="card-header">@lang("global.description_ar")</h5>
								<input type="hidden" name="description_ar" id="descriptionQuill" value="{{old('description_ar', $ask->description_ar)}}">
								<div class="card-body">
									<div id="full-editor" class="@error('description_ar') is-invalid @enderror">
										@php
											echo old('description_ar', $ask->description_ar)
										@endphp
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 mt-5">
							<div class="card description_en">
								<h5 class="card-header">@lang("global.description_en")</h5>
								<input type="hidden" name="description_en" id="descriptionQuill_2" value="{{old('description_en', $ask->description_en)}}">
								<div class="card-body">
									<div id="full-editor_2" class="@error('description_en') is-invalid @enderror">
										@php
											echo old('description_en', $ask->description_en)
										@endphp
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Full Editor -->
					<div class="pt-4">
						<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection