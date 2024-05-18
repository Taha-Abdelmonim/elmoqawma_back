@extends('layouts.layoutMaster')
@foreach ($rooms as $room)
	@if ($room->type_video == "liveStream")
		{{ $room->id }} <br>
	@endif
@endforeach
{{false}}
@section('title', trans("global.rooms"))

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

@section('page-script')
	{{-- <script src="{{asset('assets/js/books.js')}}"></script> --}}
@endsection

@section('content')
	@php
		$links = [
		"start" => trans("global.rooms"),
		"/" => trans("global.dashboard"),
		"end" => trans("global.rooms"),
		]
	@endphp
	@include("layouts.breadcrumbs")

	<div class="d-flex align-items-center justify-content-end my-1 ">
		{{-- @can("create_books") --}}
		<form class="input-group me-5 position-relative w-50 " action="{{  route('rooms') }}" method="GET">
			<input type="search" name="search" class="form-control  me-1 w-50 rounded"
			       placeholder="{{ __('global.search') }}" aria-label="Search" aria-describedby="search-addon" />
			<button type="submit" class="btn btn-outline-primary">{{ __('global.search') }}</button>
		</form>
		{{-- <a class="btn btn-secondary btn-primary mx-3 position-relative" tabindex="0" aria-controls="DataTables_Table_0"
		   href="{{route('rooms.create')}}">
		<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
				class="d-none d-sm-inline-block">{{trans("global.room_create")}}</span></span>
		</a> --}}
		{{-- @endcan --}}
	</div>
	<div class="card">
		<div class="card-datatable table-responsive">
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.lecture")</th>
					<th class="text-start">@lang("global.time")</th>
					<th class="text-start">@lang("global.link_1")</th>
					<th class="text-start">@lang("global.link_2")</th>
					<th class="text-start">@lang("global.created_at")</th>
					{{-- <th class="text-start">@lang("global.actions")</th> --}}
				</tr>
				</thead>
				@isset($rooms)
					<tbody>
					@php $count=1; @endphp
					@foreach ( $rooms as $room )
						<tr>
								<td class="text-start">{{ $count++}}</td>
								<td class="text-start"><a href="{{route('lecture_show', [$room->chapter_id, $room->id])}}">{{ LocalKey($room, "title")}}</a></td>
								<td class="text-start">{{ $room->duration}}</td>
								<td class="text-start"><a target="_blank" href="{{$room->start_url}}">رابط الادمن</a></td>
								<td class="text-start"><a target="_blank" href="{{$room->join_url}}">رابط الطالب</a></td>
								<td class="text-start">{{ $room->created_at}}</td>
								{{-- <td class="text-start">
									<a href="{{route('rooms.edit', [$room->id])}}" class="text-body ms-1"><i
											class="ti ti-edit ti-sm me-2"></i></a>
									<a href="{{route('rooms.destroy', [$room->id])}}"
										 onclick="return confirm('هل انت متأكد')" class="text-body "><i
											class="ti ti-trash ti-sm mx-2"></i></a>
								</td> --}}
						</tr>
					@endforeach
					</tbody>
				@endisset
			</table>
		</div>
	</div>
@endsection