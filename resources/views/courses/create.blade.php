@extends('layouts/layoutMaster')

@section('title', trans("global.course_create"))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection
@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/form-wizard-numbered.js') }}"></script>
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
	<script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.courses"),
			"/" => trans("global.dashboard"),
			"/courses" => trans("global.all_courses"),
			"end" => trans("global.course_create"),
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
			<div class="bs-stepper wizard-numbered mt-2">
				<div class="col-12 ms-4 pt-3 ">
					<h5 class="mb-0">@lang('global.course_create')</h5>
				</div>
				<div class="bs-stepper-header">
					<div class="step" data-target="#step_1">
						<button type="button" class="step-trigger">
							<span class="bs-stepper-circle">1</span>
							<span class="bs-stepper-label">
              <span class="bs-stepper-title">@lang('global.course_details')</span>
              <span class="bs-stepper-subtitle">@lang('global.course_details_filed')</span>
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
              <span class="bs-stepper-title">@lang('global.course_price')</span>
              <span class="bs-stepper-subtitle">@lang('global.course_details_filed_2')</span>
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
              <span class="bs-stepper-title">@lang('global.course_description_create')</span>
              <span class="bs-stepper-subtitle">@lang('global.course_description_create')</span>
            </span>
						</button>
					</div>

				</div>
				<div class="bs-stepper-content">
					<form class="card-body row" action="{{route('course_save')}}" method="POST" enctype="multipart/form-data">
						@csrf
						<div id="step_1" class="content">
							<div class="row">
								<div class="col-4">
									<label for="name" class="form-label">@lang("global.name")</label>
									<input type="text" value="{{ old("name") }}" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="name" aria-describedby="defaultFormControlHelp" />
								</div>
								<div class="col-4" id="parent_select_sections">
									<div>
										<label for="subject_id" class="form-label">{{ trans('global.subject') }}</label>
										<select id="subject_id" name="subject_id" class="select2 form-select form-select-lg subject_id @error('subject_id') is-invalid @enderror" data-allow-clear="true">
											@foreach ($subjects as $subject)
												<option value="{{ $subject->id }}" @if (old('subject_id') == $subject->id) selected @endif>{{ $subject["name_".app()->getLocale()] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="col-4" id="parent_select_sections">
									<div>
										<label for="section_id" class="form-label">{{ trans('global.section') }}</label>
										<select id="section_id" name="section_id" class="select2 form-select form-select-lg section_id @error('section_id') is-invalid @enderror" data-allow-clear="true">
											@foreach ($sections as $section)
												<option value="{{ $section->id }}" @if (old('section_id') == $section->id) selected @endif>{{ $section["name_".app()->getLocale()] }}</option>
											@endforeach
										</select>
									</div>
								</div>
								@if(auth()->user()->teacher_id === null)
									<div class="col-4 mt-4">
										<label for="teacher_id" class="form-label">{{ trans('global.coach') }}</label>
										<select id="teacher_id" name="teacher_id" class="select2 form-select form-select-lg teacher_id @error('teacher_id') is-invalid @enderror" data-allow-clear="true">
											<option disabled selected>@lang("global.choose_teacher")</option>
											@foreach ($teachers as $teacher)
												<option value="{{ $teacher->id }}" @if (old('teacher_id') == $teacher->id) selected @endif>{{ $teacher["name_". app()->getLocale()] }}</option>
											@endforeach
										</select>
									</div>
								@endif

								<div class="col-4 mt-4">
									<label for="type" class="form-label">{{ trans('global.type_course') }}</label>
									<select required id="type" name="type" class=" form-select  type @error('type') is-invalid @enderror">
										<option value="1" @if(old("type") === '1') selected @endif >@lang("global.paid")</option>
										<option value="0" @if(old("type") === '0') selected @endif>@lang("global.free")</option>
									</select>
								</div>
								<div class="col-4 mt-4">
									<label for="subscription_duration" class="form-label">{{ trans('global.subscription_duration') }}</label>
									<input name="subscription_duration" value="{{old("subscription_duration")}}" type="number" class="form-control @error('subscription_duration') is-invalid @enderror" placeholder="0 صفر معناها اشتراك مفتوح" id="subscription_duration">
								</div>

								<div class="col-6 mt-4">
									<label for="whatsapp" class="form-label">{{ trans('global.whatsapp_group') }}</label>
									<input name="whatsapp" value="{{old("whatsapp")}}" type="text" class="form-control" placeholder="https://web.whatsapp.com/" id="whatsapp">
								</div>
								<div class="col-6 mt-4">
									<label for="telegram" class="form-label">{{ trans('global.telegram_group') }}</label>
									<input name="telegram" value="{{old("telegram")}}" type="text" class="form-control" placeholder="@Taha_Abdelmoneim" id="telegram">
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
								<div class="col-4 free_course mb-4">
									<label for="price" class="form-label">@lang("global.price")</label>
									<input type="text" value="{{ old("price") }}" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="999.00" aria-describedby="defaultFormControlHelp" />
								</div>
								<div class="col-4 free_course mb-4">
									<label for="sub_price" class="form-label">@lang("global.sub_price")</label>
									<input type="text" value="{{ old("sub_price") }}" class="form-control @error('sub_price') is-invalid @enderror" id="sub_price" name="sub_price" placeholder="999.00" aria-describedby="defaultFormControlHelp" />
								</div>
								<div class="col-4 free_course mb-4">
									<label for="discount" class="form-label">@lang("global.discount")</label>
									<input type="text" value="{{ old("discount") }}" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" placeholder="999.00" aria-describedby="defaultFormControlHelp" />
								</div>

								<div class="col-3 ">
									<label for="subscribers" class="form-label">@lang("global.subscribers")</label>
									<input type="text" value="{{ old("subscribers") }}" class="form-control @error('subscribers') is-invalid @enderror" id="subscribers" name="subscribers" placeholder="999" aria-describedby="defaultFormControlHelp" />
								</div>

								<div class="card-body col-3 p-0 ">
									<label for="customRadioTemp1" class="form-label">@lang("global.status")</label>
									<div class="row">
										<div class="col-md mb-md-0 mb-2">
											<div class="form-check custom-option custom-option-basic">
												<label class="form-check-label custom-option-content pt-1 pb-1" for="customRadioTemp1">
													<input name="status" @if(old('status') == "1") checked @endif class="form-check-input" type="radio" value="1" id="customRadioTemp1" checked />
													<span class="custom-option-header">
				                    <span class="h6 mb-0">@lang("global.enabled")</span>
				                    <i class="fa-solid fa-circle-check text-success"></i>
				                  </span>
												</label>
											</div>
										</div>
										<div class="col-md">
											<div class="form-check custom-option custom-option-basic">
												<label class="form-check-label custom-option-content pt-1 pb-1" for="customRadioTemp2">
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
								<div class="col-3 ">
									<label for="formFile" class="form-label">@lang("global.main_photo")</label>
									<input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="formFile" accept="image/*" />
								</div>
								<div class="col-3 ">
									<label for="formFile" class="form-label">@lang("global.description_photo")</label>
									<input class="form-control @error('description_photo') is-invalid @enderror" name="description_photo" type="file" id="formFile" accept="image/*" />
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
							<div class="col-12 d-flex justify-content-between mt-4">
								<button type="button" class="btn btn-label-secondary btn-prev"><i class="ti ti-arrow-left me-sm-1 me-0"></i>
									<span class="align-middle d-sm-inline-block d-none">@lang("global.previous")</span>
								</button>
								<button type="submit" class="btn btn-success"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>@lang("global.create")</button>
							</div>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    $("body").on("change", ".type", function() {
      let free_course = $(".free_course");
      if ($(this).val() === "1") {
        free_course.show();
        free_course.find("input").val("");
      } else {
        free_course.hide();
        free_course.find("input").val(0);
      }
    });
    $(".type").trigger("change");
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
