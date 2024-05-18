@extends('layouts/layoutMaster')

@section('title', trans('global.question_edit'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/form-layouts.js') }}"></script>
	<script src="{{ asset('assets/js/form-wizard-numbered.js') }}"></script>
	<script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection

@section('content')
	{{-- {{Route::currentRouteName()}} --}}
	@php
		$links = [
		'start' => trans('global.questions'),
		'/' => trans('global.dashboard'),
		"/bank_questions/categories" => $category->name,
		"/bank_questions/$bank_category_id" => trans("global.bank_questions"),
		'end' => trans('global.question_edit'),
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
					<h5 class="mb-0">{{ trans('global.question_edit') }}</h5>
				</div>
				<div class="bs-stepper-header">
					<div class="step" data-target="#step_1">
						<button type="button" class="step-trigger">
							<span class="bs-stepper-circle">1</span>
							<span class="bs-stepper-label">
							<span class="bs-stepper-title">@lang('global.question_details')</span>
							<span class="bs-stepper-subtitle">@lang('global.question_details_filed')</span>
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
							<span class="bs-stepper-title">@lang('global.answers')</span>
							<span class="bs-stepper-subtitle">@lang('global.answers')</span>
						</span>
						</button>
					</div>
				</div>
				<div class="bs-stepper-content">
					<form class="card-body"
					      action="{{ route('bank_question_update', [$bank_category_id, $question->id]) }}" method="POST"
					      enctype="multipart/form-data">
						@csrf
						<input type="hidden" name="delete_answers" id="delete_answers" value="[]">
						<div id="step_1" class="content">
							<div class="row py-3">

								<div class="col-4 mb-3">
									<label class="form-label" for="content_type">@lang("global.type")</label>
									<select id="type" class="form-control" name="type">
										<option value="text" @if( $question->type=='text' )selected @else
											''
										@endif >@lang("global.text")</option>
										<option value="audio" @if( $question->type=='audio' )selected @else
											''
										@endif >@lang("global.audio")</option>
										<option value="video" @if( $question->type=='video' )selected @else
											''
										@endif >@lang("global.video")</option>
										<option value="image" @if( $question->type=='image' )selected @else
											''
										@endif >@lang("global.image")</option>
									</select>
								</div>
								<div class="col-4 ">
									<label for="order" class="form-label">{{ trans('global.order') }}</label>
									<input name="order" value="{{old("order", $question->order)}}" type="number" class="form-control @error('order') is-invalid @enderror" placeholder="3" id="order">
								</div>

								<div class="col-4">
									<label class="form-label" for="Justify">@lang("global.Justify")</label>
									<input type="text" value="{{old("Justify", $question->Justify)}}" id="Justify" name="Justify" class="form-control">
								</div>
								<div class="col-6 mb-3" id="file_input" style="display: none;">
									<label class="form-label" for="content_file">@lang("global.question")</label>
									<input type="file" name="file" id="content_file" class="form-control">
								</div>
								<div class="col-6 ">
									<label for="bank_category_id" class="form-label">{{ trans('global.categories') }}</label>
									<select id="bank_category_id" name="bank_category_id" class="select2 form-select form-select-lg @error('bank_category_id') is-invalid @enderror">
										@foreach ($bank_categories as $bank_category)
											<option value="{{ $bank_category->id }}" @if (old('bank_category_id',$question->bank_category_id) == $bank_category->id) selected @endif>{{ $bank_category->name }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="row">
								<div class="row" id="question_text">
									<div class="col-12">
										<div class="card">
											<h5 class="card-header">@lang("global.question_ar")</h5>
											<input type="hidden" name="question_ar" id="descriptionQuill" value="{{old('question_ar', $question->question_ar)}}">
											<div class="card-body">
												<div id="full-editor" class="@error('question_ar') is-invalid @enderror">
													@php
														echo old('question_ar', $question->question_ar)
													@endphp
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 mt-5">
										<div class="card">
											<h5 class="card-header">@lang("global.question_en")</h5>
											<input type="hidden" name="question_en" id="descriptionQuill_2" value="{{old('question_en', $question->question_en)}}">
											<div class="card-body">
												<div id="full-editor_2" class="@error('question_en') is-invalid @enderror">
													@php
														echo old('question_en', $question->question_en)
													@endphp
												</div>
											</div>
										</div>
									</div>
								</div>


								<div class="col-12 d-flex justify-content-between mt-5">
									<button type="button" class="btn btn-label-secondary btn-prev"><i
											class="ti ti-arrow-left me-sm-1 me-0"></i>
										<span class="align-middle d-sm-inline-block d-none">@lang("global.previous")</span>
									</button>
									<button type="button" class="btn btn-primary btn-next">
									<span
										class="align-middle d-sm-inline-block d-none me-sm-1">@lang("global.next")</span>
										<i class="ti ti-arrow-right"></i>
									</button>
								</div>
							</div>
						</div>
						<div id="step_2" class="content">
							<div class="row">

								<div class="col-2">
									<label class="form-label" for="answer_type">@lang("global.answer_type")</label>
									<select id="answer_type" class="form-control px-2">
										<option value="text" selected>@lang("global.text")</option>
										<option value="image">@lang("global.image")</option>
									</select>
								</div>
								<div class="col-4" id="answer_text_input">
									<label class="form-label" for="answer_text">@lang("global.answer")</label>
									<input type="text" name="answer" id="answer" class="form-control">
								</div>
								<div class="col-4" id="answer_image_input" style="display: none;">
									<label class="form-label" for="answer_image">@lang("global.answer")</label>
									<input type="hidden" name="answer" id="answer_image" placeholder="image" disabled
									       class="form-control"></div>
								<div class="col-4 p-0  pe-2 ps-2">
									<label for="statusTrue" class="form-label">@lang("global.status")</label>
									<div class="row">
										<div class="col-md mb-md-0 mb-2">
											<div class="form-check custom-option custom-option-basic">
												<label class="form-check-label custom-option-content py-1"
												       for="statusTrue">
													<input name="status" class="form-check-input status_input" type="radio"
													       value="1" id="statusTrue" checked />
													<span class="custom-option-header">
													<span class="h6 mb-0">@lang("global.true")</span>
													<i class="fa-solid fa-circle-check text-success"></i>
												</span>
												</label>
											</div>
										</div>
										<div class="col-md">
											<div class="form-check custom-option custom-option-basic">
												<label class="form-check-label custom-option-content py-1"
												       for="statusFalse">
													<input name="status" class="form-check-input status_input" type="radio"
													       value="0" id="statusFalse" />
													<span class="custom-option-header">
													<span class="h6 mb-0">@lang("global.false")</span>
													<i class="fa-solid fa-unlock text-danger"></i>
												</span>
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="col-2 d-flex justify-content-center align-items-end">
									<button type="button" class="btn btn-primary w-100" id="add_answer"><i
											class="ti ti-plus ti-sm"></i>@lang("global.add")</button>
								</div>
								<div class="table-responsive text-nowrap mt-3">
									<table class="table">
										<thead>
										<tr>
											<th>@lang("global.answer")</th>
											<th>@lang("global.status")</th>
											<th>@lang("global.actions")</th>
										</tr>
										</thead>
										<tbody class="table-border-bottom-0" id="parent_answers">
										@foreach($answers as $answer)
											<tr>
												<td>
													@if($answer->answer_type === "text")
														<span class="badge bg-label-{{$answer->status == 1 ? 'success' : 'danger' }} me-1">{{$answer->answer}}</span>
													@else
														<a href="{{asset("images/$answer->answer")}}" target="_blank" class="avatar avatar-xl d-block ">
															<img src="{{asset("images/$answer->answer")}}" alt="Avatar" class="rounded-circle object-cover">
														</a>
													@endif
													<input type="hidden" class="answer_id" value="{{$answer->id}}">
													{{--													<input type="hidden" name="answers[{{$answer->id}}][answer]" value="{{$answer->answer}}">--}}
													{{--													<input type="hidden" name="answers[{{$answer->id}}][answer_type]" value="{{$answer->answer_type}}">--}}
												</td>
												<td>
													<span class="badge bg-label-{{$answer->status == 1 ? 'success' : 'danger' }} me-1">
														{{$answer->status == 1 ? trans("global.true") : trans("global.false")}}
													</span>
												</td>
												<td>
													<button type="button" class="deleteRow btn btn-danger"><i class="fa-solid fa-trash"></i></button>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
								<div class="col-12 d-flex justify-content-between mt-4">
									<button type="button" class="btn btn-label-secondary btn-prev"><i
											class="ti ti-arrow-left me-sm-1 me-0"></i>
										<span class="align-middle d-sm-inline-block d-none">@lang("global.previous")</span>
									</button>
									<button type="submit" class="btn btn-primary me-sm-3 me-1"><i
											class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
								</div>
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
    $("body").on("change", "#type", function() {
      var selectedType = $(this).val();
      if (selectedType === "audio" || selectedType === "video" || selectedType === "image") {
        $("#file_input").show();
        $("#question_text").hide();
      } else {
        $("#question_text").show();
        $("#file_input").hide();
      }
    });
    $("#type").trigger("change");
    $("body").on("change", "#answer_type", function() {
      var selectedType = $(this).val();
      if (selectedType === "text") {
        // Show the text input if the selected answer type is text
        $("#answer_text_input").show();
        $("#answer_image_input").hide();
      } else if (selectedType === "image") {
        // Show the file input if the selected answer type is image
        $("#answer_text_input").hide();
      }
    });

    // Declare indexAnswer as a global variable
    let indexAnswer = 0;

    $("body").on("click", "#add_answer", function() {
      var answer = $("#answer").val();
      var answer_type = $("#answer_type").val();
      if ((answer_type === "text" && answer === "")) return;
      var status = $(".status_input:checked").val();
      var statusText = status == 1 ? "@lang('global.true')" : "@lang('global.false')";
      let arrStatusColor = ["danger", "success"];
      if (answer_type === "text") {
        let content = `
					<tr>
						<td>
							<span class="badge bg-label-${arrStatusColor[status]} me-1">${answer}</span>
							<input type="hidden" name="answers[${indexAnswer}][answer_type]" value="${answer_type}">
							<input type="hidden" name="answers[${indexAnswer}][answer]" value="${answer}">
						</td>
						<td><span class="badge bg-label-${arrStatusColor[status]} me-1">${statusText}</span><input type="hidden" name="answers[${indexAnswer}][status]" value="${status}"></td>
						<td><button type="button" class="deleteRow btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
					</tr>
				`;
        $("#parent_answers").append(content);
        indexAnswer++;
        $("#answer").val("");
      } else if (answer_type === "image") {
        answer_type = "imageNew";
        answer = "image";
        let content = `
					<tr>
						<td>
							<input type="hidden" name="answers[${indexAnswer}][answer_type]" value="${answer_type}" />
							<input type="file" class="form-control" name="answers[${indexAnswer}][answer]" />
							<span class="badge bg-label-${arrStatusColor[status]} me-1">${answer}</span>
						</td>
						<td><span class="badge bg-label-${arrStatusColor[status]} me-1">${statusText}</span><input type="hidden" name="answers[${indexAnswer}][status]" value="${status}"></td>
						<td><button type="button" class="deleteRow btn btn-danger"><i class="fa-solid fa-trash"></i></button></td>
					</tr>
				`;
        $("#parent_answers").append(content);
        indexAnswer++;
      }
    });


    $("body").on("click", ".deleteRow", function() {
      var parent = $(this).parents("tr");
      var id = parent.find(".answer_id").val();
      var values = [...new Set(JSON.parse($("#delete_answers").val()).concat([+id]))];
      $("#delete_answers").val(JSON.stringify(values));
      parent.remove();
      indexAnswer--;
    });
	</script>
@endsection