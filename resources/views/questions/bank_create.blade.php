@extends('layouts/layoutMaster')

@section('title', trans("global.bank_question_create"))
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
		"/questions/course/$course->id/lectures" => $course["name_" . app()->getLocale()],
		"/questions/course/$course->id/lecture/1" => $lecture->title,
		"end" => trans("global.bank_question_create"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.bank_questions') }}</h5>
		<form class="card-body" action="{{ route('bank_questions_save', [$course->id, $lecture->id]) }}" method="POST" enctype="multipart/form-data">
			@csrf
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
				<div class="card-body col-12 px-2 pb-2">
					<label for="customRadioTemp1" class="form-label">@lang("global.related")</label>
					<div class="row">
						<div class="col-md mb-md-0 mb-2">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content "
								       for="customRadioTemp1">
									<input name="related" checked
									       class="form-check-input" type="radio" value="exams"
									       id="customRadioTemp1" />
									<span class="custom-option-header">
													<span class="h6 mb-0">@lang("global.exams")</span>
													<i class="fa-solid fa-question text-success"></i>
												</span>
								</label>
							</div>
						</div>
						<div class="col-md">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content "
								       for="customRadioTemp2">
									<input name="related"
									       class="form-check-input" type="radio" value="assignments"
									       id="customRadioTemp2" />
									<span class="custom-option-header">
													<span class="h6 mb-0">@lang("global.assignments")</span>
													<i class="fa-solid fa-file-pen text-info"></i>
												</span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6 mt-0">
					<label for="bank_category_id" class="form-label">{{ trans('global.categories') }}</label>
					<select id="bank_category_id" name="bank_category_id" class="select2 form-select form-select-lg @error('bank_category_id') is-invalid @enderror">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($bank_categories as $bank_category)
							<option value="{{ $bank_category->id }}" @if (old('bank_category_id') == $bank_category->id) selected @endif>{{ $bank_category->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-6 mt-1" id="parent_questions"></div>
			</div>
			<div class="pt-4 col-4">
				<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>{{ trans('global.create') }}</button>
			</div>
		</form>
	</div>
@endsection
@section("my-script")
	<script>


    function getChildCategory(id) {
      $.ajax({
        type: "post",
        url: "{{ route('category_get_child') }}",
        data: {
          "_token": "{{ csrf_token() }}",
          "id": id
        },
        success: function({ data }) {
          $("#parent_questions").html("");
          if (data != undefined) {
            $("#parent_questions").html(data);
            $(".question_id").selectpicker();
          } else {
            showMessage("error", "لا يوجد أسئلة في هذه الفئة");
          }
        },
        error: function({ responseJSON }) {
          showMessage("error", responseJSON.message);
        }
      });
    }

    $("#bank_category_id").on("change", function() {
      getChildCategory($(this).val());
    });
	</script>
@endsection