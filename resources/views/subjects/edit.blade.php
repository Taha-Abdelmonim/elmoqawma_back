@extends('layouts/layoutMaster')

@section('title', trans('global.edit_subject'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('content')
	{{--  {{Route::currentRouteName()}} --}}
	@php
		$links = [
				'start' => trans('global.subject'),
				'/' => trans('global.dashboard'),
				'/subject' => trans('global.all_subjects'),
				'end' => trans('global.edit_subject'),
		];
	@endphp
	@include('layouts.breadcrumbs')
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.edit_subject') }}</h5>
		<form class="card-body" action="{{ route('subject_update', $subject->id) }}" method="POST" enctype="multipart/form-data">
			@csrf
			<a href="{{$subject->photo != null || $subject->photo != "" ? asset('images/' . $subject->photo) : $subject->id}}" target="_blank" class="avatar w-px-200 h-px-200 d-block mx-auto mb-4">
				@if($subject->photo != null || $subject->photo != "")
					<img src="{{asset('images/' . $subject->photo)}}" alt="Avatar" class="rounded-circle object-cover">
				@else
					<span>@lang("global.not_photo")</span>
				@endif
			</a>
			{{--      <h6>1. Account Details</h6> --}}
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
					<label class="form-label " for="name_ar">{{ trans('global.name_ar') }}</label>
					<input value="{{ old('name_ar', $subject->name_ar) }}" name="name_ar" type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" placeholder="{{ trans('global.name_ar') }}" />
				</div>
				<div class="col-6">
					<label class="form-label " for="name_en">{{ trans('global.name_en') }}</label>
					<input value="{{ old('name_en', $subject->name_en) }}" name="name_en" type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror" placeholder="{{ trans('global.name_en') }}" />
				</div>
				<div class="col-12 mt-3">
					<label for="formFile" class="form-label">@lang("global.photo")</label>
					<input class="form-control" name="photo" type="file" id="formFile" accept="image/*" />
				</div>
				<div class="col-12  p-3">
					<div class="row ">
						<h1 class=" text-center">@lang("global.add_classes")</h1>
						<div class="col-6">
							<label for="section_id" class="form-label">{{ trans('global.classes') }}</label>
							<select id="section_id" class="select2 form-select @error('section_id') is-invalid @enderror" data-style="btn-default">
								<option value="0" disabled selected>@lang("global.choose_section")</option>
								@foreach ($sections as $section)
									<option value="{{ $section->id }}" @if (old('section_id') == $section->id) selected @endif>{{ $section["name_" . app()->getLocale()] }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-5 d-flex justify-content-center align-items-end">
							<button type="button" class="btn btn-primary w-50" id="add_section"><i class="ti ti-plus ti-sm"></i>@lang("global.add")</button>
						</div>
					</div>
					<div class="table-responsive text-nowrap">
						<table class="table">
							<thead>
							<tr>
								<th>@lang("global.name")</th>
								<th>@lang("global.actions")</th>
							</tr>
							</thead>
							<tbody class="table-border-bottom-0" id="parent_section">
							@foreach($subjectSections as $subjectSection)
								<tr>
									<td>
										<span class="badge bg-label-success me-1">{{$subjectSection["name_" . app()->getLocale()]}}</span>
										<input type="hidden" class="input_id" id="section_id_{{$subjectSection->id}}" value="{{$subjectSection->id}}"></td>
									<td>
										<button type="button" class="deleteRow btn btn-danger"><i class="fa-solid fa-trash"></i></button>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<input type="hidden" name="delete_items" id="delete_items" value="[]">
				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
				</div>
			</div>
		</form>
	</div>
@endsection
@section("my-script")
	<script>
    var indexSection = {{$subjectSections->count() == 0 ? 0 : $subjectSections[0]->id}} + 1;
    $("body").on("click", "#add_section", function() {
      var text = $("#section_id").find("option:selected").text();
      var value = $("#section_id").find("option:selected").val();
      if (value == "0") {
        return showMessage("error", '@lang("global.choose_subject")');
      }
      var parentSection = $(`#section_id_${value}`).parents("tr");
      let content =
        `
          <tr>
            <td><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="sections[${indexSection}][section_id]" class="section_id" id="section_id_${value}" value="${value}"></td>
            <td><button type="button" class="deleteRow btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
          </tr>
      	`;
      if (parentSection.length != 0) {
        parentSection.before(content);
        parentSection.remove();
      } else {
        $("#parent_section").append(content);
      }
      indexSection++;
    });

    $("body").on("click", ".deleteRow", function() {
      var parent = $(this).parents("tr");
      var id = parent.find(".input_id").val();
      var values = [...new Set(JSON.parse($("#delete_items").val()).concat([+id]))];
      $("#delete_items").val(JSON.stringify(values));
      parent.remove();
    });

	</script>
@endsection