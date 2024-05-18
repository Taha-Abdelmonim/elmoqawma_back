@extends('layouts.layoutMaster')

@section('title', 'انشاء قسم')
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
			"start" => trans("global.sections"),
			"/" => trans("global.dashboard"),
			"/sections" => trans("global.sections"),
			"end" => trans("global.section_create"),
	]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('section_save')}}" method="POST" enctype="multipart/form-data">
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
				<h5 class="card-header">@lang("global.section_create")</h5>
				<div class="card-body">
					<div class="row mb-3">
						<div class="col-6">
							<label for="name_ar" class="form-label">@lang("global.name_ar")</label>
							<input type="text" value="{{ old("name_ar") }}" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" placeholder="@lang("global.name_ar")" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6">
							<label for="name_en" class="form-label">@lang("global.name_en")</label>
							<input type="text" value="{{ old("name_en") }}" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" placeholder="@lang("global.name_en")" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6 mt-3">
							<label for="photo" class="form-label">@lang("global.photo")</label>
							<input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="photo" accept="image/*" />
						</div>
						{{--<div class="row col-6 mt-3" id="parent_select_sections">
							<div>
								<label for="section_id" class="form-label">{{ trans('global.section') }}</label>
								<select required id="section_id" name="section_id" class="select2 form-select form-select-lg section_id @error('section_id') is-invalid @enderror" data-allow-clear="true">
									@foreach ($sections as $section)
										<option value="{{ $section->id }}" @if (old('section_id', 1) == $section->id) selected @endif>{{ $section["name_".app()->getLocale()] }}</option>
									@endforeach
								</select>
							</div>
						</div>--}}


						<div class="col-12 mt-5">
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
						<div class="col-12 mt-5">
							<div class="card description_en">
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
					</div>
					<!-- Full Editor -->
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
