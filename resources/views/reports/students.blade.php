@extends('layouts/layoutMaster')

@section('title', trans('global.reports') . " | " . trans("global.students"))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
	@can("export")
		<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
	@endcan
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

@endsection

@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>

@endsection

@section('content')
	<div class="card my-3 p-4">
		<form method="get">
			<div class="row">
				<div class="col-3 mb-2">
					<label for="user_id" class="form-label">{{ trans('global.students') }}</label>
					<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.chose_students")</option>
						@foreach ($users as $item)
							<option value="{{ $item->id }}" @if (old('user_id', Request::input("user_id")) == $item->id) selected @endif>{{ $item->name() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-3 ">
					<label for="pickup_time" class="form-label">@lang('global.from')</label>
					<input type="text" name="from" value="{{Request::input("from")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-3 ">
					<label for="pickup_time" class="form-label">@lang('global.to')</label>
					<input type="text" name="to" value="{{Request::input("to")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-3 mt-4">
					<button type="submit" class="btn btn-primary w-100">@lang("global.search")</button>
				</div>
			</div>
		</form>
	</div>
	<div class="card">
		<div class="card">
			<div class="card-datatable text-nowrap table-responsive">
				<table class="datatables-ajax table component_table">
					<thead>
					<tr>
						<th class="text-start">@lang("global.number")</th>
						<th class="text-start">@lang("global.user")</th>
						<th class="text-start">@lang("global.type")</th>
						<th class="text-start">@lang("global.students")</th>
						<th class="text-start">@lang("global.price")</th>
						<th class="text-start">@lang("global.card_type")</th>
						<th class="text-start">@lang("global.photo")</th>
						<th class="text-start">@lang("global.coupon")</th>
						<th class="text-start">@lang("global.subscription_date")</th>
					</tr>
					</thead>
					<tbody>
					@if($orders->isNotEmpty())
						@foreach ($orders as $order)
							<tr class="text-nowrap">
								<td class="text-start ">{{$order->id}}</td>
								<td class="text-start">
									<a href="{{route("user_show", $order->user->id)}}" class="text-body text-truncate ">
										<span class="fw-semibold">{{LocalKey($order->user, "name")}}</span>
									</a>
									<p class="text-muted mb-0 ">{{$order->user->email}}</p>
									<a href="https://api.whatsapp.com/send?phone={{$order->user->phone_parent}}" target="_blank" class="text-truncate d-flex align-items-center ">{{$order->user->phone}}</a>
								</td>
								<td class="text-start">@lang("global.$order->type")</td>
								<td class="text-start">
									@if($order->type === 'course')
										@if(!empty($order->course))
											<a href="{{route("course_show", $order->course->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->course["name_" . app()->getLocale()]}}</a>
										@else
											<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Course::withTrashed()->find($order->course_id)->name}}</p>
										@endif
									@elseif($order->type === 'lecture')
										@if(!empty($order->lecture))
											<a href="{{route("lecture_edit", [$order->lecture->chapter_id,$order->lecture->id])}}" target="_blank"
											   class="text-truncate d-flex align-items-center">{{$order->lecture->course->name . " " . LocalKey($order->lecture, "title")}}</a>
										@else
											<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Lecture::withTrashed()->find($order->lecture_id)->title}}</p>
										@endif
									@elseif($order->type === 'offer')
										@if(!empty($order->offer))
											<a href="{{route("offer_edit", $order->offer->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->offer["name_" . app()->getLocale()]}}</a>
										@else
											<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Offer::withTrashed()->find($order->offer_id)["name_" . app()->getLocale()]}}</p>
										@endif
									@endif
								</td>
								<td class="text-start ">
									<p class=' fw-bold badge bg-label-success  overflow-hidden m-0'>
										{{$order->price}}
									</p>
								</td>
								<td class="text-start "><p class='text-truncate  fw-bold badge bg-label-info w-px-100 overflow-hidden m-0'>{{trans("global.$order->card_type")}}</p></td>
								<td class="text-start">
									<a href="{{$order->photo != null || $order->photo != '' ? asset("attachments/$order->photo") : asset("attachments/global/not_found.png")}}" target="_blank" class="avatar avatar-xl d-block ">
										<img src="{{$order->photo != null || $order->photo != '' ? asset("attachments/$order->photo") : asset("attachments/global/not_found.png")}}" alt="Avatar" class="rounded-circle object-cover">
									</a>
								</td>
								<td class="text-start "><p class='text-truncate  fw-bold badge bg-label-success w-px-150 overflow-hidden m-0'>{{$order->code ?? "--"}}</p></td>
								<td class="text-start">{{DATE($order->created_at)}}</td>

							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<div class="row p-4">
				<div class="col-4 d-flex align-items-center">
					<h4 class="text-start mb-0 me-3">@lang("global.total"): </h4>
					<span class="text-start fs-4 badge bg-label-success"> {{$total}}</span>
				</div>
			</div>
		</div>
	</div>
	<div class="card mt-3">
		<div class="card-datatable text-nowrap table-responsive">
			<table class="datatables-ajax table component_table_2">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.course")</th>
					<th class="text-start">@lang("global.lecture")</th>
					<th class="text-start">@lang("global.lecture_views")</th>
					<th class="text-start">@lang("global.score_exam_user")</th>
					<th class="text-start">@lang("global.info_exam_user")</th>
					<th class="text-start">@lang("global.count_exam_user")</th>
					<th class="text-start">@lang("global.date_show")</th>
				</tr>
				</thead>
				<tbody>
				@if($user && $user->lectureViews->count())
					@foreach ($user->lectureViews->unique("lecture_id") as $lectureView)
						<tr class="text-nowrap">
							<td class="text-start ">{{$lectureView->id}}</td>
							<td class="text-start ">
								<p class=' fw-bold badge bg-label-success  overflow-hidden m-0'>
									{{$lectureView->lecture->course->name}}
								</p>
							</td>
							<td class="text-start ">
								<p class=' fw-bold badge bg-label-primary  overflow-hidden m-0'>
									{{$lectureView->lecture->title}}
								</p>
							</td>
							<td class="text-start "><p class='fw-bold badge bg-label-info w-px-100 overflow-hidden m-0'>{{$lectureView->count}}</p></td>
							<td class="text-start "><p class='fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{$user->latestScore($lectureView->lecture_id)["score"]}}</p></td>
							<td class="text-start "><p class='fw-bold badge bg-label-info w-px-100 overflow-hidden m-0'>@php echo $user->latestScore($lectureView->lecture_id)["info"] @endphp</p></td>
							<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-primary w-50'>{{$user->latestScore($lectureView->lecture_id)["count_exam"]}}</span></td>
							<td class="text-start">{{DateValue($lectureView->created_at)}}</td>
						</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>

	</div>
@endsection
@section("my-script")
	@include("components.table", ["columns" => '[0, 1, 2, 3, 4, 5, 6, 8]', "nameClass" => "component_table"]);
	@include("components.table", ["columns" => '[0, 1, 2, 3, 4, 6, 7, 8]', "nameClass" => "component_table_2"]);
@endsection
