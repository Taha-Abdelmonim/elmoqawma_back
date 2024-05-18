@extends('layouts/layoutMaster')

@section('title', trans("global.create_payment_method"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/form-layouts.js')}}"></script>
@endsection

@section('content')
	{{--  {{Route::currentRouteName()}}--}}
	@php
		$links = [
			"start" => trans("global.payment_method"),
			"/dashboard" => trans("global.dashboard"),
			"/payment_method" => trans("global.all_payment_method"),
			"end" => trans("global.create_payment_method"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">{{trans("global.create_payment_method")}}</h5>
		<form class="card-body" action="{{route('payment_method_save')}}" method="POST" enctype="multipart/form-data">
			@csrf
			{{--      <h6>1. Account Details</h6>--}}
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
				<div class="col-md-6">
					<label class="form-label " for="name_ar">{{trans("global.name_ar")}}</label>
					<input value="{{ old('name_ar') }}" name="name_ar" type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" placeholder="{{trans("global.name_ar")}}" />
				</div>
				<div class="col-md-6">
					<label class="form-label " for="name_en">{{trans("global.name_en")}}</label>
					<input value="{{ old('name_en') }}" name="name_en" type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror" placeholder="{{trans("global.name_en")}}" />
				</div>
				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>{{trans("global.create")}}</button>
				</div>
			</div>
		</form>
	</div>
@endsection

