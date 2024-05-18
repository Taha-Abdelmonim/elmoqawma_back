@extends('layouts/layoutMaster')

@section('title', trans('global.edit_teacher'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/form-wizard-numbered.js') }}"></script>
	<script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('content')
	{{--  {{Route::currentRouteName()}} --}}
	@php
		$links = [
				'start' => trans('global.teachers'),
				'/' => trans('global.dashboard'),
				'/teachers' => trans('global.all_teachers'),
				'end' => trans('global.edit_teacher'),
		];
	@endphp
	@include('layouts.breadcrumbs')
	<!-- Multi Column with Form Separator -->
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
			<div class="bs-stepper wizard-numbered mt-2">
				<div class="col-12 ms-4 pt-3 ">
					<h5 class="card-header">{{ trans('global.edit_teacher') }}</h5>
				</div>
				<div class="bs-stepper-header">
					<div class="step" data-target="#step_1">
						<button type="button" class="step-trigger">
							<span class="bs-stepper-circle">1</span>
							<span class="bs-stepper-label">
              <span class="bs-stepper-title">@lang('global.teacher_info')</span>
              <span class="bs-stepper-subtitle">@lang('global.teacher_info_filed')</span>
            </span>
						</button>
					</div>
					<div class="line">
						<i class="ti ti-chevron-right"></i>
					</div>
					<div class="step" data-target="#step_2">
						<button type="button" class="step-trigger">
							<span class="bs-stepper-circle">2</span>
							<span class="bs-stepper-label">
              <span class="bs-stepper-title">@lang('global.teacher_classes')</span>
              <span class="bs-stepper-subtitle">@lang('global.teacher_classes_filed')</span>
            </span>
						</button>
					</div>
					<div class="line">
						<i class="ti ti-chevron-right"></i>
					</div>
					<div class="step" data-target="#step_3">
						<button type="button" class="step-trigger">
							<span class="bs-stepper-circle">3</span>
							<span class="bs-stepper-label">
              <span class="bs-stepper-title">@lang('global.teacher_subject')</span>
              <span class="bs-stepper-subtitle">@lang('global.teacher_subject_filed')</span>
            </span>
						</button>
					</div>
				</div>
				<div class="bs-stepper-content">
					<form class="card-body" action="{{ route('teacher_update', $teacher->id) }}" method="POST" enctype="multipart/form-data">
						@csrf
						{{--      <h6>1. Account Details</h6> --}}
						<div id="step_1" class="content">
							<a href="{{$teacher->photo != null || $teacher->photo != "" ? asset('images/' . $teacher->photo) : $teacher->id}}" target="_blank" class="avatar w-px-200 h-px-200 d-block mx-auto mb-4">
								@if($teacher->photo != null || $teacher->photo != "")
									<img src="{{asset('images/' . $teacher->photo)}}" alt="Avatar" class="rounded-circle object-cover">
								@else
									<span>@lang("global.not_photo")</span>
								@endif
							</a>
							<div class="row">

								<div class="col-6">
									<label class="form-label " for="name_ar">{{ trans('global.name_ar') }}</label>
									<input value="{{ old('name_ar', $teacher->name_ar) }}" name="name_ar" type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" placeholder="{{ trans('global.name_ar') }}" />
								</div>
								<div class="col-6">
									<label class="form-label " for="name_en">{{ trans('global.name_en') }}</label>
									<input value="{{ old('name_en', $teacher->name_en) }}" name="name_en" type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror" placeholder="{{ trans('global.name_en') }}" />
								</div>
								<div class="col-6 mt-3">
									<label for="description_ar" class="form-label fs-4 font-bold mb-3">@lang("global.description_ar")</label>
									<textarea class="form-control description_ar" name="description_ar" id="description_ar" rows="3">{{old('description_ar', $teacher->description_ar)}}</textarea>
								</div>
								<div class="col-6 mt-3">
									<label for="description_en" class="form-label fs-4 font-bold mb-3">@lang("global.description_en")</label>
									<textarea class="form-control description_en" name="description_en" id="description_en" rows="3">{{old('description_en', $teacher->description_en)}}</textarea>
								</div>
								<div class="col-6 mt-3">
									<label class="form-label " for="email">@lang("global.email")</label>
									<input value="{{ old('email', $teacher->email) }}" name="email" type="text" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="ex@gmail.com" />
								</div>
								<div class="col-6 mt-3">
									<label class="form-label " for="phone">@lang("global.phone")</label>
									<input value="{{ old('phone', $teacher->phone) }}" class="form-control  @error('phone') is-invalid @enderror" name="phone" type="tel" placeholder="201090844348" id="phone" />
								</div>
								<div class="col-6 mt-3">
									<label for="formFile" class="form-label">@lang("global.photo")</label>
									<input class="form-control" name="photo" type="file" id="formFile" accept="image/*" />
								</div>

							</div>
							<div class="col-12 d-flex justify-content-between mt-4">
								<button type="button" class="btn btn-label-secondary btn-prev"><i class="ti ti-arrow-left me-sm-1 me-0"></i>
									<span class="align-middle d-sm-inline-block d-none">@lang("global.previous")</span>
								</button>
								<button type="button" class="btn btn-primary btn-next">
									<span class="align-middle d-sm-inline-block d-none me-sm-1">@lang("global.next")</span> <i class="ti ti-arrow-right"></i>
								</button>
							</div>
						</div>
						<div id="step_2" class="content">
							<div class="row">
								<div class="col-12  p-3">
									<div class="row ">
										<h1 class=" text-center">@lang("global.add_classes")</h1>
										<div class="col-6">
											<label for="section_id" class="form-label">{{ trans('global.classes') }}</label>
											<select id="section_id" class="select2 form-select @error('section_id') is-invalid @enderror" data-style="btn-default">
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
											@foreach($teacherSections as $teacherSection)
												<tr>
													<td>
														<span class="badge bg-label-success me-1">{{$teacherSection["name_" . app()->getLocale()]}}</span>
														<input type="hidden" class="input_id" id="section_id_{{$teacherSection->id}}" value="{{$teacherSection->id}}"></td>
													<td>
														<button type="button" class="deleteRowSections btn btn-danger"><i class="fa-solid fa-trash"></i></button>
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-12 d-flex justify-content-between mt-4">
								<button type="button" class="btn btn-label-secondary btn-prev"><i class="ti ti-arrow-left me-sm-1 me-0"></i>
									<span class="align-middle d-sm-inline-block d-none">@lang("global.previous")</span>
								</button>
								<button type="button" class="btn btn-primary btn-next">
									<span class="align-middle d-sm-inline-block d-none me-sm-1">@lang("global.next")</span> <i class="ti ti-arrow-right"></i>
								</button>
							</div>
						</div>
						<div id="step_3" class="content">

							<div class="row">
								<div class="col-12  p-3">
									<div class="row ">
										<h1 class=" text-center">@lang("global.add_subjects")</h1>
										<div class="col-6">
											<label for="subject_id" class="form-label">{{ trans('global.subject') }}</label>
											<select id="subject_id" class="select2 form-select form-select-lg subject_id @error('subject_id') is-invalid @enderror" data-allow-clear="true">
												<option disabled selected>@lang("global.choose_subject")</option>
												@foreach ($subjects as $subject)
													<option value="{{ $subject->id }}" @if (old('subject_id') == $subject->id) selected @endif>{{ $subject["name_".app()->getLocale()] }}</option>
												@endforeach
											</select>
										</div>

										<div class="col-5 d-flex justify-content-center align-items-end">
											<button type="button" class="btn btn-primary w-50" id="add_subject"><i class="ti ti-plus ti-sm"></i>@lang("global.add")</button>
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
											<tbody class="table-border-bottom-0" id="parent_subject">
											@foreach($teacherSubjects as $teacherSubject)
												<tr>
													<td>
														<span class="badge bg-label-success me-1">{{$teacherSubject["name_" . app()->getLocale()]}}</span>
														<input type="hidden" class="input_id" id="subject_id_{{$teacherSubject->id}}" value="{{$teacherSubject->id}}"></td>
													<td>
														<button type="button" class="deleteRowSubjects btn btn-danger"><i class="fa-solid fa-trash"></i></button>
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="col-12 d-flex justify-content-between mt-4">
								<button type="button" class="btn btn-label-secondary btn-prev"><i class="ti ti-arrow-left me-sm-1 me-0"></i>
									<span class="align-middle d-sm-inline-block d-none">@lang("global.previous")</span>
								</button>
								<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
							</div>
						</div>
						<input type="hidden" name="delete_items_sections" id="delete_items_sections" value="[]">
						<input type="hidden" name="delete_items_subjects" id="delete_items_subjects" value="[]">
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    var indexSection = {{$teacherSections->count() == 0 ? 0 : $teacherSections[0]->id}} + 1;
    $("body").on("click", "#add_section", function() {
      var text = $("#section_id").find("option:selected").text();
      var value = $("#section_id").find("option:selected").val();
      var parentSection = $(`#section_id_${value}`).parents("tr");
      let content =
        `
          <tr>
            <td><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="sections[${indexSection}][section_id]" class="section_id" id="section_id_${value}" value="${value}"></td>
            <td><button type="button" class="deleteRowSections btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
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


    var indexSubject = {{$teacherSubjects->count() == 0 ? 0 : $teacherSubjects[0]->id}} + 1;
    $("body").on("click", "#add_subject", function() {
      var text = $("#subject_id").find("option:selected").text();
      var value = $("#subject_id").find("option:selected").val();
      var parentSubject = $(`#subject_id_${value}`).parents("tr");
      let content =
        `
          <tr>
            <td><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="subjects[${indexSubject}][subject_id]" class="subject_id" id="subject_id_${value}" value="${value}"></td>
            <td><button type="button" class="deleteRowSubjects btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
          </tr>
      	`;
      if (parentSubject.length != 0) {
        parentSubject.before(content);
        parentSubject.remove();
      } else {
        $("#parent_subject").append(content);
      }
      indexSubject++;
    });
    $("body").on("click", ".deleteRowSections", function() {
      var parent = $(this).parents("tr");
      var id = parent.find(".input_id").val();
      var values = [...new Set(JSON.parse($("#delete_items_sections").val()).concat([+id]))];
      $("#delete_items_sections").val(JSON.stringify(values));
      parent.remove();
    });
    $("body").on("click", ".deleteRowSubjects", function() {
      var parent = $(this).parents("tr");
      var id = parent.find(".input_id").val();
      var values = [...new Set(JSON.parse($("#delete_items_subjects").val()).concat([+id]))];
      $("#delete_items_subjects").val(JSON.stringify(values));
      parent.remove();
    });

	</script>
@endsection
