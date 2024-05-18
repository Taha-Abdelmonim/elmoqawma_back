@extends('layouts/layoutMaster')

@section('title', trans("global.bank_questions"))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection
@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection
@section('content')
	@php
		$links = [
		"start" => trans("global.bank_questions"),
		"/" => trans("global.dashboard"),
		"/bankCategories" => trans("global.bank_questions"),
		"end" => trans("global.edit_category"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.edit_category') }}</h5>
		<form class="card-body" action="{{ route('bank_categories_update',[$bankCategory->id]) }}" method="POST">
			@csrf
			@method('put')
			{{-- <h6>1. Account Details</h6> --}}
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
				<div class="col-8">
					<label class="form-label " for="title">{{ trans('global.name') }}</label>
					<input value="{{ old('name' ,$bankCategory->name) }}" name="name" type="text" id="name"
					       class="form-control @error('name') is-invalid @enderror"
					       placeholder="{{ trans('global.bankCategory') }}" />
				</div>
				<div class=" col-4 pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>{{ trans('global.edit') }}</button>
				</div>
			</div>
		</form>
	</div>
@endsection
