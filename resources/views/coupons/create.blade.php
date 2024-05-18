@extends('layouts/layoutMaster')

@section('title', trans("global.create_coupon"))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection
@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.coupons"),
			"/" => trans("global.dashboard"),
			"/coupons" => trans("global.all_coupons"),
			"end" => trans("global.create_coupon"),
		]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('coupon_save')}}" method="POST" enctype="multipart/form-data">
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
				<div class="col-12 d-flex justify-content-between align-items-center">
					<h5 class="card-header">@lang("global.create_coupon")</h5>
					<div class="col-8 mt-2 row text-nowrap me-3">
						<div class="col-md">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content py-2" for="course">
									<input name="type" checked class="form-check-input type" type="radio" value="course" id="course" />
									<span class="custom-option-header">
                  <span class="h6 mb-0 me-3">@lang("global.course")</span>
									<i class="fa-solid fa-clapperboard text-danger"></i>
                </span>
								</label>
							</div>
						</div>
						<div class="col-4 mb-md-0 mb-2">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content py-2" for="lecture">
									<input name="type" class="form-check-input type" type="radio" @if(old('type') === "lecture") checked @endif value="lecture" id="lecture" />
									<span class="custom-option-header">
                  <span class="h6 mb-0 me-3">@lang("global.lecture")</span>
										<i class="fa-solid fa-users-rectangle text-info"></i>
                </span>
								</label>
							</div>
						</div>
						<div class="col-md">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content py-2" for="offer">
									<input name="type" @if(old('type') === "offer") checked @endif class="form-check-input type" type="radio" value="offer" id="offer" />
									<span class="custom-option-header">
                  <span class="h6 mb-0 me-3">@lang("global.offer")</span>
										<i class="fa-solid fa-gift text-primary"></i>
                </span>
								</label>
							</div>
						</div>
						<div class="col-md">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content py-2" for="wallet">
									<input name="type" @if(old('type') === "wallet") checked @endif class="form-check-input type" type="radio" value="wallet" id="wallet" />
									<span class="custom-option-header">
                  <span class="h6 mb-0 me-3">@lang("global.wallet")</span>
										<i class="fa-solid fa-money-check-dollar text-success"></i>
                </span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-4">
							<label for="number" class="form-label">@lang("global.number_coupons")</label>
							<input type="number" value="{{ old("number") }}" class="form-control @error('number') is-invalid @enderror" id="number" name="number" placeholder="10" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4">
							<label for="discount" class="form-label">@lang("global.discount")</label>
							<input type="number" value="{{ old("discount", 0) }}" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" placeholder="999.00" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-4">
							<label for="code" class="form-label">@lang("global.code")</label>
							<input type="text" value="{{ old("code", '') }}" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="OFF" />
						</div>
						<div class="col-6 mt-4" id="parent_course" style="display: none">
							<label for="course_id" class="form-label">{{ trans('global.course') }}</label>
							<select required id="course_id" name="course_id" class="select2 form-select form-select-lg course_id  @error('course_id') is-invalid @enderror" data-allow-clear="true">
								<option disabled selected>@lang("global.chose_course")</option>
								@foreach ($courses as $course)
									<option value="{{ $course->id }}" @if (old('course_id') == $course->id) selected @endif>{{ $course->name }}</option>
								@endforeach
							</select>
						</div>
						{{--lecture--}}
						<div class="col-6 mt-4" id="parent_lecture"></div>

						<div class="pt-4">
							<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>@lang("global.create")</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection

@section("my-script")
	<script>
    function getCourseLectures(course_id) {
      $.ajax({
        type: "GET",
        url: `/getCourseLectures/${course_id}`,
        data: { "_token": "{{ csrf_token() }}" },
        success: function(data) {
          if (data != undefined) {
            $("#parent_lecture").html(data);
            $("#lecture_id").select2();
          }
        },
        error: function({ responseJSON }) {
          showMessage("error", responseJSON.message);
        }
      });
    }

    $("body").on("change", "#course_id", function() {
      getCourseLectures($(this).val());
    });
    $("body").on("change", ".type", function() {
      if ($(this).val() === "lecture") {
        $("#parent_course").show();
      } else {
        $("#parent_course").hide();
      }
    });
	</script>
@endsection
