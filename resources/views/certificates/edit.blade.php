@extends('layouts/layoutMaster')

@section('title', trans("global.all_courses"))
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
		"start" => trans("global.certificates"),
		"/" => trans("global.dashboard"),
		"/certificates/courses" => trans("global.all_courses"),
		"/certificates/course/$course->id" => $course->name,
		"end" => trans("global.certificate_create"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.certificate_create') }}</h5>
		<form class="card-body" action="{{ route('certificate_update', [$course->id, $certificate->id]) }}" method="POST" enctype="multipart/form-data">
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
				<div class="col-6">
					<label class="form-label " for="username">{{ trans('global.username') }}</label>
					<input value="{{ old('username', $certificate->username) }}" name="username" type="text" id="username" class="form-control @error('username') is-invalid @enderror" placeholder="{{ trans('global.username') }}" />
				</div>
				<div class="col-6">
					<label class="form-label " for="score">{{ trans('global.score') }}</label>
					<input value="{{ old('score', $certificate->score) }}" name="score" type="number" id="score" class="form-control @error('score') is-invalid @enderror" placeholder="{{ trans('global.score') }}" />
				</div>
				<div class="col-6  mt-3">
					<label for="user_id" class="form-label">{{ trans('global.students') }}</label>
					<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($users as $user)
							<option value="{{ $user->id }}" @if (old('user_id', $certificate->user_id) === $user->id) selected @endif>{{ $user->name() }}</option>
						@endforeach
					</select>
				</div>
				<div class="card-body col-3 p-0">
					<label for="customRadioTemp1" class="form-label">@lang("global.status")</label>
					<div class="row">
						<div class="col-md mb-md-0 mb-2">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content pt-1 pb-1"
								       for="customRadioTemp1">
									<input name="status" @if(old('status', $certificate->status) == "1" ) checked @endif
									class="form-check-input" type="radio" value="1"
									       id="customRadioTemp1" checked />
									<span class="custom-option-header">
													<span class="h6 mb-0">@lang("global.enabled")</span>
													<i class="fa-solid fa-circle-check text-success"></i>
												</span>
								</label>
							</div>
						</div>
						<div class="col-md">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content pt-1 pb-1"
								       for="customRadioTemp2">
									<input name="status" @if(old('status', $certificate->status)=="0" ) checked @endif
									class="form-check-input" type="radio" value="0"
									       id="customRadioTemp2" />
									<span class="custom-option-header">
													<span class="h6 mb-0">@lang("global.not_enabled")</span>
													<i class="fa-solid fa-unlock text-danger"></i>
												</span>
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
				</div>

			</div>
		</form>
	</div>
@endsection
@section("my-script")
	<script>
    $("#sub_price").on("keyup", function() {
      $("#discount").val(+$(this).val() - +$("#price").val());
    });

    function getChildSection(id) {
      $.ajax({
        type: "POST",
        url: "{{ route('sections_get_child') }}",
        data: {
          "_token": "{{ csrf_token() }}",
          "id": id
        },
        success: function({ data }) {
          if (data != undefined) {
            $("#parent_select_sections").html(data);
            $(".section_id").select2();
          }
        },
        error: function({ responseJSON }) {
          showMessage("error", responseJSON.message);
        }
      });
    }

    $("#parent_select_sections").on("change", ".section_id", function() {
      getChildSection($(this).val());
    });
	</script>
@endsection