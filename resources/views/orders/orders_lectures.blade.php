@extends('layouts/layoutMaster')

@section('title', trans("global.orders_lectures"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
	@can("export")
		<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
	@endcan
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />

@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
@endsection



@section('content')
	@php
		$links = [
			"start" => trans("global.lectures"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.orders_lectures"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-datatable table-responsive">
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.title")</th>
					<th class="text-start">@lang("global.active_subscriptions")</th>
					<th class="text-start">@lang("global.inactive_subscriptions")</th>
					<th class="text-start">@lang("global.actions")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($lectures as $lecture)
					<tr class="text-nowrap">
						<td class="text-start"><span class="badge">{{$lecture->id}}</span></td>
						<td class="text-start"><a href="{{route("lecture_edit", [$courseID, $lecture->id])}}" target="_blank" class="text-truncate d-flex align-items-center">{{$lecture->title}}</a></td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success w-25'>{{$lecture->active}}</span></td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-danger w-25'>{{$lecture->inactive}}</span></td>
						<td class="text-start">
							<div class="d-flex align-items-center">
								<a href="{{route('orders', ['lecture', "ID=$lecture->id&status=1"])}}" title="الاشتراكات المفعلة" class="badge badge-center rounded-pill bg-label-success w-px-30 h-px-30 me-2"><i class="ti ti-circle-check ti-sm"></i></a>
								<a href="{{route('orders', ['lecture', "ID=$lecture->id&status=0"])}}" title="الاشتراكات المفعلة" class="badge badge-center rounded-pill bg-label-danger w-px-30 h-px-30 me-2">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-letter-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
									     stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
										<path d="M10 8l4 8"></path>
										<path d="M10 16l4 -8"></path>
									</svg>
								</a>
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
