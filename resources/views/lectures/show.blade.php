@extends('layouts/layoutMaster')

@section('title', trans('global.show_lecture'))

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
	{{-- {{Route::currentRouteName()}} --}}
	@php
		$links = [
		'start' => trans('global.lectures'),
		'/' => trans('global.dashboard'),
		"/course/$course->id" => $course["name_" . app()->getLocale()],
		"/chapter/$chapter->id/lectures" => $chapter["name_" . app()->getLocale()],
		'end' => trans('global.edit_lecture'),
		];
	@endphp
	@include('layouts.breadcrumbs')
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.show_lecture') }}</h5>
		<form>
			@csrf
			{{-- <h6>1. Account Details</h6> --}}
			<div class="row g-3 p-4">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<div class="col-3 mt-3">
					<label for="type_video" class="form-label">{{ trans('global.type_video') }}</label>
					<select readonly required id="type_video" name="type_video"
					        style="pointer-events: none; user-select: none"
					        class=" form-select  type_video @error('type_video') is-invalid @enderror" disabled>
						<option value="server" @if(old("type_video", $lecture->type_video) === 'server') selected
							@endif>@lang("Server")</option>
						<option value="youtube" @if(old("type_video", $lecture->type_video) === 'youtube') selected
							@endif>@lang("Youtube")</option>
						<option value="zoom" @if(old("type_video", $lecture->type_video) === 'zoom') selected
							@endif>@lang("Zoom")</option>
					</select>
				</div>
				<div class="col-3">
					<label for="type" class="form-label">{{ trans('global.type_lecture') }}</label>
					<select required id="type" name="type" class=" form-select  type @error('type') is-invalid @enderror"
					        disabled>
						<option value="1" @if(old("type", $lecture->type) === 1) selected @endif >@lang("global.paid")
						</option>
						<option value="0" @if(old("type", $lecture->type) === 0) selected @endif>@lang("global.free")
						</option>
					</select>
				</div>
				<div class="col-3">
					<label class="form-label " for="name_ar">{{ trans('global.title') }}</label>
					<input value="{{ old('title', $lecture->title) }}" name="title" type="text" id="title"
					       class="form-control @error('title') is-invalid @enderror" placeholder="{{ trans('global.title') }}"
					       disabled />
				</div>
				<div class="col-3">
					<label for="order" class="form-label">@lang("global.order")</label>
					<input required type="text" value="{{ old(" order", $lecture->order) }}" class="form-control
				@error('order') is-invalid @enderror" id="order" name="order" placeholder="999"
					       aria-describedby="defaultFormControlHelp" disabled />
				</div>
				<div class="col-3" id="parent_price">
					<label for="price" class="form-label">@lang("global.price")</label>
					<input type="text" value="{{ old(" price", $lecture->price) }}" class="form-control @error('price')
				is-invalid @enderror" id="price" name="price" placeholder="999.00"
					       aria-describedby="defaultFormControlHelp" @disabled(true) />
				</div>
				<div class="col-3 mt-3">
					<label for="type_video" class="form-label">{{ trans('global.status') }}</label>
					<select disabled required id="active" name="active"
					        class=" form-select  active @error('active') is-invalid @enderror">
						<option value="1" @if($lecture->active==1)selected@else
							''
						@endif>@lang("active")</option>
						<option value="0" @if($lecture->active==0)selected @else
							''
						@endif>@lang("inactive")</option>
					</select>
				</div>
				<div class="col-2 mt-3">
					<label for="re_exam_count" class="form-label">{{ trans('global.re_exam_count') }}</label>
					<input @disabled(true) name="re_exam_count" value="{{old("re_exam_count", $lecture->re_exam_count)}}" type="number" class="form-control @error('re_exam_count') is-invalid @enderror" placeholder="3" id="re_exam_count">
				</div>
				<div class="col-2 mt-3">
					<label for="count_questions" class="form-label">{{ trans('global.count_questions') }}</label>
					<input @disabled(true) name="count_questions" value="{{old("count_questions", $lecture->count_questions)}}" type="number" class="form-control @error('count_questions') is-invalid @enderror" placeholder="3" id="count_questions">
				</div>
				<div class="col-2 mt-3">
					<label for="flatpickr-time" class="form-label">{{ trans('global.exam_duration') }}</label>
					<input @disabled(true) name="duration_exam" value="{{old("duration_exam", $lecture->duration_exam)}}" type="text" class="form-control @error('duration_exam') is-invalid @enderror" placeholder="MM 0.5 or 1" id="flatpickr-time">
				</div>

				<div class="col-3 zoom" style="display: none">
					<label class="form-label " for="duration">{{ trans('global.duration') }}</label>
					<input value="{{ old('duration', $lecture->duration) }}" name="duration" type="number" id="duration"
					       class="form-control @error('duration') is-invalid @enderror" placeholder="60" disabled />
				</div>
				<div class="col-3  zoom" style="display: none">
					<label for="flatpickr-datetime" class="form-label">@lang("global.start_time")</label>
					<input value="{{ old('start_time', $lecture->start_time) }}" type="text" name="start_time"
					       class="form-control flatpickr-datetime-second text-center" placeholder="YYYY-MM-DD HH:MM"
					       id="flatpickr-datetime" disabled dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}" />
				</div>

				@if($lecture->type_video === "server" || $lecture->type_video === "server_id")
					<div style="height: 400px; padding:5px; margin:auto;">
						<iframe src="https://iframe.mediadelivery.net/embed/{{env("BUNNY_LIBRARY_ID")}}/{{$lecture->videoID}}?autoplay=true&loop=false&muted=false&preload=true"
						        loading="lazy" style="width: 50%; height: 100%;transform: translateX(-50%)"
						        referrerpolicy="origin-when-cross-origin"
						        allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" allowfullscreen="true"></iframe>
					</div>
				@endif
				<div class="mt-3 col-6 youtube" style="display: none">
					<label for="video" id="label_video" class="form-label">@lang("Video ID")</label>
					<div style=" position: relative; ">
						<div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; pointer-events: none"
						     id="parent_icon_video" class="text-bg-danger p-3 rounded-none">
							<i class="fa-brands fa-youtube text-start text-2xl youtube" style="display: none"></i>
						</div>
						<input id="video" name="videoID" type="text"
						       class="form-control rounded-0 @error('video') is-invalid @enderror" value="{{old(" video",
						$lecture->videoID)}}"
						/>
					</div>
				</div>

			</div>
		</form>
	</div>



	<div class="card mb-3">
		<h5 class="card-header">{{ trans('global.views_lecture') }}</h5>
		<div class="card-datatable table-responsive">
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.lecture_views")</th>
					<th class="text-start">@lang("global.user")</th>
					<th class="text-start">@lang("global.order_number")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($lectureViews as $lectureView)
					<tr>
						<td class="text-start"><span class="badge">{{$lectureView->id}}</span></td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success w-25'>{{$lectureView->count}}</span></td>
						<td class="text-start"><a href="/user/{{$lectureView->user->id}}" target="_blank">{{ $lectureView->user["name_" . app()->getLocale()] }}</a></td>
						<td class="text-start">#{{$lectureView->order_id}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="card ">
		<div class="d-flex align-items-center justify-content-end p-4">
			@can("create_lectures")
				<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0"
				   href="{{route('attachments.create', ["lecture", $lecture->id])}}" target="_blank">
				<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
						class="d-none d-sm-inline-block">{{trans("global.create_attachment")}}</span></span>
				</a>
			@endcan
		</div>

		@isset($attachments)
			<div class="card-datatable text-nowrap table-responsive">
				<table class="datatables-ajax table">
					<thead>
					<tr>
						<th class="text-start check-all"><label for="checkbox_delete"><input type="checkbox"
						                                                                     id="checkbox_delete" class="form-check-input me-3">@lang("global.number")</label></th>
						<th class="text-start">@lang("global.name")</th>
						<th class="text-start">@lang("global.created_at")</th>
						<th class="text-start">@lang("global.file")</th>
						<th class="text-start">@lang("global.actions")</th>
					</tr>
					</thead>
					<tbody>

					@foreach ($attachments as $attachment)
						<tr>
							<td class="text-start"><input type="checkbox" data-id="{{$attachment->id}}" name="deleteAll[]"
							                              class="delete-all dt-checkboxes form-check-input me-3">{{$attachment->id}}</td>
							<td class="text-start">{{$attachment->name}}</td>
							<td class="text-start" dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}">
								{{DATE($attachment->created_at)}}</td>
							<td><span class="badge bg-label-success me-1"><a target="_blank" href="{{asset("/images/$attachment->file")}}">file</a></span></td>
							<td class="text-start">
								<div class="d-flex align-items-center">
									<a href="{{ route('attachments.destroy',[ $attachment->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		@endisset
	</div>

@endsection
