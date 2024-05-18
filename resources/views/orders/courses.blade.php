@extends('layouts/layoutMaster')

@section('title', trans("global.all_courses"))

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
			"start" => trans("global.questions"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_courses"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-datatable table-responsive">
			<table class="datatables-ajax table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.name")</th>
					<th class="text-start">@lang("global.lectures_count")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($courses as $course)
					<tr>
						<td class="text-start"><span class="badge">{{$course->id}}</span></td>
						<td class="text-start"><a href="{{route('orders_course_lectures', $course->id)}}" class="text-truncate d-flex align-items-center">{{$course->name}}</a></td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success w-25'>{{$course->lectures_count}}</span></td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    $(".datatables-ajax").DataTable();
	</script>
@endsection