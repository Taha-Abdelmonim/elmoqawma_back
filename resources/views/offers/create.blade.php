@extends('layouts/layoutMaster')

@section('title', trans("global.offer_create"))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/pickr/pickr-themes.css')}}" />
@endsection
@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
	<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js')}}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
	<script src="{{asset('assets/js/forms-editors.js')}}"></script>
	<script src="{{asset('assets/js/forms-pickers.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.offers"),
			"/" => trans("global.dashboard"),
			"/offers" => trans("global.all_offers"),
			"end" => trans("global.offer_create"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="row mt-3">
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
			<div class="card mt-4">
				<h5 class="card-header">@lang("global.offer_create")</h5>
				<form class=" card-body row " action="{{route('offer_save')}}" method="POST" enctype="multipart/form-data">
					@csrf
					<div class="row">
						<div class="col-4">
							<label for="name_ar" class="form-label">@lang("global.name_ar")</label>
							<input type="text" value="{{ old("name_ar") }}" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" placeholder="@lang("global.name_ar")" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4">
							<label for="name_en" class="form-label">@lang("global.name_en")</label>
							<input type="text" value="{{ old("name_en") }}" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" placeholder="@lang("global.name_en")" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4">
							<label for="flatpickr-range" class="form-label">@lang("global.offer_duration")</label>
							<input type="text" value="{{ old("duration") }}" name="duration" class="form-control" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}" />
						</div>
						<div class="col-4 mt-4">
							<label for="price" class="form-label">@lang("global.price")</label>
							<input type="text" value="{{ old("price") }}" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="999" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4 mt-4">
							<label for="stars" class="form-label">@lang("global.stars")</label>
							<input type="text" value="{{ old("stars") }}" class="form-control @error('stars') is-invalid @enderror" id="stars" name="stars" placeholder="999" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4 mt-4">
							<label for="subscribers" class="form-label">@lang("global.subscribers")</label>
							<input type="text" value="{{ old("subscribers") }}" class="form-control @error('subscribers') is-invalid @enderror" id="subscribers" name="subscribers" placeholder="999" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4 mt-4">
							<label for="course_id" class="form-label">{{ trans('global.courses') }}</label>
							<select id="course_id" name="course_id[]" multiple class="select2 form-select form-select-lg h-px-100 course_id @error('course_id') is-invalid @enderror" data-allow-clear="true" placeholder="@lang("global.choose_course")">
								@foreach ($courses as $course)
									<option value="{{ $course->id }}" @if (old('course_id')===$course->id) selected
										@endif>{{ $course->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-4 mt-4">
							<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
							<select required id="currency_id" name="currency_id" class="select2 form-select form-select-lg @error('currency_id') is-invalid @enderror" data-allow-clear="true" data-style="btn-default">
								@foreach ($currencies as $currency)
									<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id') == $currency->id) selected @endif>{{ $currency->name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-4 mt-4">
							<label for="formFile" class="form-label">@lang("global.main_photo")</label>
							<input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="formFile" accept="image/*" />
						</div>
						<div class="col-12 my-5">
							<div class="card">
								<h5 class="card-header">@lang("global.description_ar")</h5>
								<input type="hidden" name="description_ar" id="descriptionQuill" value="{{old('description_ar')}}">
								<div class="card-body">
									<div id="full-editor" class="@error('description_ar') is-invalid @enderror">
										@php
											echo old('description_ar')
										@endphp
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 ">
							<div class="card">
								<h5 class="card-header">@lang("global.description_en")</h5>
								<input type="hidden" name="description_en" id="descriptionQuill_2" value="{{old('description_en')}}">
								<div class="card-body">
									<div id="full-editor_2" class="@error('description_en') is-invalid @enderror">
										@php
											echo old('description_en')
										@endphp
									</div>
								</div>
							</div>
						</div>
						<div class="pt-4">
							<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>@lang("global.create")</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection
