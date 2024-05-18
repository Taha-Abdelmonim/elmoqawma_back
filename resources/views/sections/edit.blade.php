@extends('layouts.layoutMaster')

@section('title', trans("global.section_edit"))
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
			"end" => trans("global.section_edit"),
	]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('section_update', $editSection->id)}}" method="POST" enctype="multipart/form-data">
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
				<h5 class="card-header">@lang("global.section_edit")</h5>
				<a href="{{$editSection->photo != null || $editSection->photo != "" ? asset('images/' . $editSection->photo) : $editSection->id}}" target="_blank" class="avatar w-px-200 h-px-200 d-block mx-auto mb-4">
					@if($editSection->photo != null || $editSection->photo != "")
						<img src="{{asset('images/' . $editSection->photo)}}" alt="Avatar" class="rounded-circle object-cover">
					@else
						<span>@lang("global.not_photo")</span>
					@endif
				</a>
				<div class="card-body">

					<div class="row mb-3">

						<div class="col-6">
							<label for="name_ar" class="form-label">@lang("global.name_ar")</label>
							<input type="text" value="{{ old("name_ar", $editSection->name_ar) }}" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" placeholder="@lang("global.name_ar")"
							       aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6">
							<label for="name_en" class="form-label">@lang("global.name_en")</label>
							<input type="text" value="{{ old("name_en", $editSection->name_en) }}" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" placeholder="@lang("global.name_en")"
							       aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6 mt-3">
							<label for="photo" class="form-label">@lang("global.photo")</label>
							<input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="photo" accept="image/*" />
						</div>
						{{--<div class="col-6 mt-3" id="parent_select_sections">
							<div>
								<label for="section_id" class="form-label">@lang('global.sections_branch')</label>
								<select required name="section_id" class="select2 form-select form-select-lg section_id @error('section_id') is-invalid @enderror" data-allow-clear="true">
									<optgroup label="@lang('global.main_sections')">
										<option value="1">@lang("global.main_section")</option>
										@foreach ($sections as $section)
											<option value="{{ $section->id }}" @if(old("section_id", $editSection->id) == $section->id) selected @endif>{{ $section["name_".app()->getLocale()] }}</option>
										@endforeach
									</optgroup>
									@if($selectedSection != null)
										<optgroup label="@lang('global.chooses_select_items')">
											<option value="{{ $selectedSection->id }}" selected>{{ $selectedSection["name_".app()->getLocale()] }}</option>
										</optgroup>
									@endif
								</select>
							</div>
						</div>--}}
						<!-- Full Editor -->
						<div class="col-12 mt-5">
							<div class="card">
								<h5 class="card-header">@lang("global.description_ar")</h5>
								<input type="hidden" name="description_ar" id="descriptionQuill" value="{{old('description_ar', $editSection->description_ar)}}">
								<div class="card-body">
									<div id="full-editor" class="@error('description_ar') is-invalid @enderror">
										@php
											echo old('description_ar', $editSection->description_ar)
										@endphp
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 mt-5">
							<div class="card">
								<h5 class="card-header">@lang("global.description_en")</h5>
								<input type="hidden" name="description_en" id="descriptionQuill_2" value="{{old('description_en', $editSection->description_en)}}">
								<div class="card-body">
									<div id="full-editor_2" class="@error('description_en') is-invalid @enderror">
										@php
											echo old('description_en', $editSection->description_en)
										@endphp
									</div>
								</div>
							</div>
						</div>
						<div class="pt-4">
							<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
						</div>
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
        error: function(reject) {
        }
      });
    }

    $("#parent_select_sections").on("change", ".section_id", function() {
      getChildSection($(this).val());
    });
	</script>
@endsection
