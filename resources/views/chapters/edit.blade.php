@extends('layouts/layoutMaster')

@section('title', trans('global.edit_chapter'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('content')
	{{--  {{Route::currentRouteName()}} --}}
	@php
		$links = [
				'start' => trans('global.chapters'),
				'/' => trans('global.dashboard'),
				"/course/$course->id" => $course->name,
				"/course/$course->id/chapters" => trans('global.all_chapters'),
				'end' => trans('global.edit_chapter'),
		];
	@endphp
	@include('layouts.breadcrumbs')
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.edit_chapter') }}</h5>
		<form class="card-body" action="{{ route('chapter_update', [$course->id, $chapter->id]) }}" method="POST" enctype="multipart/form-data">
			@csrf
			{{--      <h6>1. Account Details</h6> --}}
			<div class="row g-3">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<div class="col-6">
					<label class="form-label " for="name_ar">{{ trans('global.name_ar') }}</label>
					<input value="{{ old('name_ar', $chapter->name_ar) }}" name="name_ar" type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" placeholder="{{ trans('global.name_ar') }}" />
				</div>
				<div class="col-6">
					<label class="form-label " for="name_en">{{ trans('global.name_en') }}</label>
					<input value="{{ old('name_en', $chapter->name_en) }}" name="name_en" type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror" placeholder="{{ trans('global.name_en') }}" />
				</div>
				<div class="col-12">
					<label for="order" class="form-label">@lang("global.order")</label>
					<input required type="text" value="{{ old("order", $chapter->order) }}" class="form-control @error('order') is-invalid @enderror" id="order" name="order" placeholder="999" aria-describedby="defaultFormControlHelp" />
				</div>
				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
				</div>
			</div>
		</form>
	</div>
@endsection
