@extends('layouts/layoutMaster')

@section('title', trans("global.edit_currency"))

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
			"start" => trans("global.currency"),
			"/dashboard" => trans("global.dashboard"),
			"/currency" => trans("global.all_currency"),
			"end" => trans("global.edit_currency"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">{{trans("global.edit_currency")}}</h5>
		<form class="card-body" action="work_section_id_required{{route('currency_update', $currency->id)}}" method="POST" enctype="multipart/form-data">
			@csrf
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
					<label class="form-label " for="multicol-username">{{trans("global.name")}}</label>
					<input required value="{{ old('name', $currency->name) }}" name="name" type="text" id="multicol-username" class="form-control @error('name') is-invalid @enderror" placeholder="{{trans("global.name")}}" />
				</div>
				<div class="col-md-6">
					<label class="form-label " for="multicol-username">{{trans("global.symbol")}}</label>
					<input required value="{{ old('currency_symbol', $currency->currency_symbol) }}" name="currency_symbol" type="text" id="multicol-username" class="form-control @error('currency_symbol') is-invalid @enderror"
					       placeholder="{{trans("global.symbol")}}" />
				</div>
				<div class="col-md-12">
					<label class="form-label " for="multicol-username">{{trans("global.value")}}</label>
					<input value="{{ old('currency_rate', $currency->currency_rate) }}" name="currency_rate" type="number" step="any" id="multicol-username" class="form-control @error('currency_rate') is-invalid @enderror" placeholder="EX: 31.75" />
				</div>
				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>{{trans("global.save")}}</button>
				</div>
			</div>
		</form>
	</div>
@endsection

