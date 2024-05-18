@extends('layouts/layoutMaster')

@section('title', trans("global.create_review"))
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
			"start" => trans("global.reviews"),
			"/" => trans("global.dashboard"),
			"/review" => trans("global.all_reviews"),
			"end" => trans("global.create_review"),
	]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('review_save')}}" method="POST" enctype="multipart/form-data">
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
				<h5 class="card-header">@lang("global.create_review")</h5>
				<div class="card-body">
					<div class="row align-items-center">
						<div class="row col-12 mt-3" id="parent_select_sections">
							<div>
								<label for="section_id" class="form-label">@lang("global.section")</label>
								<select required id="section_id" name="section_id" class="select2 form-select form-select-lg section_id @error('section_id') is-invalid @enderror" data-allow-clear="true">
									<option disabled selected>@lang("global.chose_section")</option>
									@foreach ($sections as $section)
										<option value="{{ $section->id }}" @if (old('section_id') == $section->id) selected @endif>{{ $section["name_".app()->getLocale()] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-12">
							<label for="customRadioTemp1" class="form-label">@lang("global.status")</label>
							<div class="row">
								<div class="col-md mb-md-0 mb-2">
									<div class="form-check custom-option custom-option-basic">
										<label class="form-check-label custom-option-content" for="customRadioTemp1">
											<input name="status" @if(old('status') == "1") checked @endif class="form-check-input" type="radio" value="1" id="customRadioTemp1" checked />
											<span class="custom-option-header">
                    <span class="h6 mb-0">@lang("global.enabled")</span>
                    <i class="fa-solid fa-circle-check text-success"></i>
                  </span>
										</label>
									</div>
								</div>

								<div class="col-6">
									<div class="form-check custom-option custom-option-basic">
										<label class="form-check-label custom-option-content" for="customRadioTemp2">
											<input name="status" @if(old('status') == "0") checked @endif class="form-check-input" type="radio" value="0" id="customRadioTemp2" />
											<span class="custom-option-header">
                    <span class="h6 mb-0">@lang("global.not_enabled")</span>
                    <i class="fa-solid fa-unlock text-danger"></i>
                  </span>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- Full Editor -->
					<div class="col-12 mt-4">
						<div class="card">
							<h5 class="card-header">@lang("global.review")</h5>
							<input type="hidden" name="comment" id="descriptionQuill" value="{{old('comment')}}">
							<div class="card-body">
								<div id="full-editor" class="@error('comment') is-invalid @enderror">
									@php
										echo old('comment');
									@endphp
								</div>
							</div>
						</div>
					</div>
					<div class="pt-4">
						<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>@lang("global.create")</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection
@section("my-script")
	<script>
    function getChildSection(id) {
      $.ajax({
        type: "post",
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
