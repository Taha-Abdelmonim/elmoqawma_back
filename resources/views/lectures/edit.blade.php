@extends('layouts/layoutMaster')

@section('title', trans('global.edit_lecture'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
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
	<script src="{{asset('assets/js/forms-pickers.js')}}"></script>
@endsection

@section('content')
	{{--  {{Route::currentRouteName()}} --}}
	@php
		$links = [
				'start' => trans('global.lectures'),
				'/' => trans('global.dashboard'),
				"/course/$course->id" => $course->name,
				"/chapter/$chapter->id/lectures" => $chapter["name_" . app()->getLocale()],
				'end' => trans('global.edit_lecture'),
		];
	@endphp
	@include('layouts.breadcrumbs')
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<form class="card-body" id="form_update_lecture" action="{{ route('lecture_update', [$chapter->id, $lecture->id]) }}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="col-12 d-flex justify-content-between align-items-center">
				<h5 class="card-header">@lang("global.edit_lecture")</h5>
				<div class="col-8 mt-2 row text-nowrap me-3 justify-content-end">
					<div class="col-3 mb-md-0 mb-2">
						<div class="form-check custom-option custom-option-basic">
							<label class="form-check-label custom-option-content py-2" for="enabled">
								<input name="status" @if(old('status', $lecture->status) == "1") checked @endif class="form-check-input" type="radio" value="1" id="enabled" />
								<span class="custom-option-header">
                  <span class="h6 mb-0 me-3">@lang("global.enabled")</span>
										<i class="fa-solid fa-circle-check text-success"></i>
                </span>
							</label>
						</div>
					</div>
					<div class="col-3">
						<div class="form-check custom-option custom-option-basic">
							<label class="form-check-label custom-option-content py-2" for="not_enabled">
								<input name="status" @if(old('status', $lecture->status) == "0") checked @endif class="form-check-input" type="radio" value="0" id="not_enabled" />
								<span class="custom-option-header">
                  <span class="h6 mb-0 me-3">@lang("global.not_enabled")</span>
									<i class="fa-solid fa-unlock text-danger"></i>
                </span>
							</label>
						</div>
					</div>
				</div>
			</div>
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
				<div class="col-4 mt-3">
					<label for="type_video" class="form-label">{{ trans('global.type_video') }}</label>
					<select readonly required id="type_video" name="type_video" style="pointer-events: none; user-select: none" class=" form-select  type_video @error('type_video') is-invalid @enderror">
						<option value="server" @if(old("type_video", $lecture->type_video) === 'server') selected @endif>@lang("Server")</option>
						<option value="server_id" @if(old("type_video", $lecture->type_video) === 'server_id') selected @endif>@lang("Server ID")</option>
						<option value="vimeo" @if(old("type_video", $lecture->type_video) === 'vimeo') selected @endif>@lang("Vimeo")</option>
						<option value="youtube" @if(old("type_video", $lecture->type_video) === 'youtube') selected @endif>@lang("Youtube")</option>
						<option value="zoom" @if(old("type_video", $lecture->type_video) === 'zoom') selected @endif>@lang("Zoom")</option>
						<option value="liveStream" @if(old("type_video", $lecture->type_video)==='liveStream' ) selected @endif>@lang("liveStream")</option>
					</select>
				</div>
				<div class="col-4">
					<label for="type" class="form-label">{{ trans('global.type_lecture') }}</label>
					<select required id="type" name="type" class=" form-select  type @error('type') is-invalid @enderror">
						<option value="1" @if(old("type", $lecture->type) === 1) selected @endif >@lang("global.paid")</option>
						<option value="0" @if(old("type", $lecture->type) === 0) selected @endif>@lang("global.free")</option>
					</select>
				</div>
				<div class="col-4">
					<label class="form-label " for="title">{{ trans('global.title') }}</label>
					<input value="{{ old('title', $lecture->title) }}" name="title" type="text" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="{{ trans('global.title') }}" />
				</div>
				<div class="col-3 mt-4">
					<label class="form-label " for="exam_google">{{ trans('global.exam_google') }}</label>
					<input value="{{ old('exam_google', $lecture->exam_google) }}" name="exam_google" type="text" id="exam_google" class="form-control @error('exam_google') is-invalid @enderror" placeholder="https://docs.google.com/forms/d/e/..." />
				</div>
				<div class="col-3 mt-4">
					<label for="order" class="form-label">@lang("global.order")</label>
					<input required type="text" value="{{ old("order", $lecture->order) }}" class="form-control @error('order') is-invalid @enderror" id="order" name="order" placeholder="999" aria-describedby="defaultFormControlHelp" />
				</div>
				<div class="col-3 mt-4" id="parent_price">
					<label for="price" class="form-label">@lang("global.price")</label>
					<input type="text" value="{{ old("price", $lecture->price) }}" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="999.00" aria-describedby="defaultFormControlHelp" />
				</div>

				<div class="col-3 mt-4">
					<label for="flatpickr-time" class="form-label">{{ trans('global.exam_duration') }}</label>
					<input name="duration_exam" value="{{old("duration_exam", $lecture->duration_exam)}}" type="text" class="form-control" placeholder="MM 0.5 or 1" id="flatpickr-time">
				</div>
				<div class="col-2 mt-4">
					<label for="lecture_views" class="form-label">{{ trans('global.lecture_views') }}</label>
					<input name="lecture_views" value="{{old("lecture_views", $lecture->lecture_views)}}" type="number" class="form-control @error('lecture_views') is-invalid @enderror" placeholder="3" id="lecture_views">
				</div>
				<div class="col-2 mt-4">
					<label for="count_questions" class="form-label">{{ trans('global.count_questions') }}</label>
					<input name="count_questions" value="{{old("count_questions", $lecture->count_questions)}}" type="number" class="form-control" placeholder="3" id="count_questions">
				</div>
				<div class="col-2 mt-4">
					<label for="re_exam_count" class="form-label">{{ trans('global.re_exam_count') }}</label>
					<input name="re_exam_count" value="{{old("re_exam_count", $lecture->re_exam_count)}}" type="number" class="form-control" placeholder="3" id="re_exam_count">
				</div>
				<div class="col-3 mt-4 zoom" style="display: none">
					<label class="form-label " for="duration">{{ trans('global.duration') }}</label>
					<input value="{{ old('duration', $lecture->duration) }}" name="duration" type="number" id="duration" class="form-control @error('duration') is-invalid @enderror" placeholder="60" />
				</div>
				<div class="col-3 mt-4  zoom" style="display: none">
					<label for="flatpickr-datetime" class="form-label">@lang("global.start_time")</label>
					<input value="{{ old('start_time', $lecture->start_time) }}" type="text" name="start_time" class="form-control flatpickr-datetime-second text-center" placeholder="YYYY-MM-DD HH:MM" id="flatpickr-datetime"
					       dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}" />
				</div>
				<div class="mt-4 col-6 youtube" style="display: none">
					<label for="video" id="label_video" class="form-label">@lang("Video ID")</label>
					<div style=" position: relative; ">
						<div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; pointer-events: none" id="parent_icon_video" class="text-bg-danger p-3 rounded-none">
							<i class="fa-brands fa-youtube text-start text-2xl youtube" style="display: none"></i>
						</div>
						<input
							id="video"
							type="text"
							class="form-control rounded-0 @error('video') is-invalid @enderror"
							value="{{old("videoID", $lecture->videoID)}}"
						/>
					</div>
				</div>
				<div class="mt-4 col-6 server_id" style="display: none">
					<label for="server_id" id="label_video" class="form-label">@lang("Server ID")</label>
					<div style=" position: relative; ">
						<div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; pointer-events: none" id="parent_icon_video" class="text-bg-info p-3 rounded-none">
							<i class="fa-solid fa-square-rssvimeo text-start text-2xl  server_id"></i>
						</div>
						<input
							id="server_id"
							name="videoID"
							type="text"
							class="form-control rounded-0 @error('video') is-invalid @enderror"
							placeholder="12a0b28a-335d-4ab5-9b75-5ae8fb4640ca"
							value="{{old("videoID", $lecture->videoID)}}"
						/>
					</div>
				</div>
				<div class="mt-4 col-6 vimeo" style="display: none">
					<label for="video" id="label_video" class="form-label">@lang("Vimeo ID")</label>
					<div style=" position: relative; ">
						<div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; pointer-events: none" id="parent_icon_video" class="text-bg-info p-3 rounded-none">
							<i class="fa-brands fa-vimeo text-start text-2xl  vimeo"></i>
						</div>
						<input
							id="video"
							name="videoID"
							type="text"
							class="form-control rounded-0 @error('video') is-invalid @enderror"
							placeholder="867209126"
							value="{{old("video", $lecture->videoID)}}"
						/>
					</div>
				</div>
				@if($lecture->type_video === "server" || $lecture->type_video === "server_id")
					<div style="height: 400px;">
						<iframe src="https://iframe.mediadelivery.net/embed/{{env("BUNNY_LIBRARY_ID")}}/{{$lecture->videoID}}?autoplay=true&loop=false&muted=false&preload=true"
						        loading="lazy" style="width: 50%; height: 100%;transform: translateX(-50%)"
						        referrerpolicy="origin-when-cross-origin"
						        allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe>
					</div>
				@endif
				<div class="col-12 mt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1 submit">
						<i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>
						@lang("global.save")
						<div class="col spinner_parent" hidden="hidden">
							<!-- Wave -->
							<div class="sk-wave sk-primary">
								<div class="sk-wave-rect"></div>
								<div class="sk-wave-rect"></div>
								<div class="sk-wave-rect"></div>
								<div class="sk-wave-rect"></div>
								<div class="sk-wave-rect"></div>
							</div>
						</div>
					</button>

				</div>
			</div>
		</form>
	</div>
@endsection
@section("my-script")
	<script>
    $("body").on("change", ".type", function() {
      if ($(this).val() === "1") {
        $("#parent_price").show();
      } else {
        $("#parent_price").hide();
      }
    });
    $(".type").trigger("change");

    $("body").on("change", "#type_video", function() {
      if ($(this).val() === "zoom" || $(this).val() === "liveStream") {
        $(".youtube").hide();
        $(".server").hide();
        $(".vimeo").hide();
        $(".zoom").show();
      } else if ($(this).val() === "youtube") {
        $(".zoom").hide();
        $(".server").hide();
        $(".youtube").show();
        $(".vimeo").hide();
        $("#vimeo_input").removeAttr("name");
        $("#youtube_input").attr("name", "videoID");
      } else if ($(this).val() === "server") {
        $(".zoom").hide();
        $(".youtube").hide();
        $(".vimeo").hide();
        $(".server").show();
      } else if ($(this).val() === "vimeo") {
        $(".zoom").hide();
        $(".youtube").hide();
        $(".server").hide();
        $(".vimeo").show();
        $("#youtube_input").removeAttr("name");
        $("#vimeo_input").attr("name", "videoID");
      }
    });
    $("#type_video").trigger("change");

    const disabledSpiner = (type) => {
      var parent = $("form#form_update_lecture");
      if (type == "disabled") {
        parent.find("button.submit").attr("disabled", "disabled");
        parent.find("button.submit").removeClass("btn-primary");
        parent.find("button.submit").addClass("btn-secondary");
        parent.find("button.cancel").attr("disabled", "disabled");
        parent.find(".spinner_parent").removeAttr("hidden");
      } else if (type == "enable") {
        parent.find("button.submit").removeAttr("disabled");
        parent.find("button.submit").removeClass("btn-secondary");
        parent.find("button.submit").addClass("btn-primary");
        parent.find("button.cancel").removeAttr("disabled");
        parent.find(".spinner_parent").attr("hidden", "hidden");
      }
    };

    $("form#form_update_lecture").submit((e) => {
      disabledSpiner("disabled");
    });
	</script>
@endsection
