@extends('layouts/layoutMaster')

@section('title', trans("global.bank_questions"))

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
	<script>
    $(".table").DataTable();
	</script>
@endsection

@section('content')
	@php
		$links = [
		"start" => trans("global.bank_questions"),
		"/" => trans("global.dashboard"),
		"end" => trans("global.bank_questions"),
		]
	@endphp
	@include("layouts.breadcrumbs")

	<div class="d-flex align-items-center justify-content-end my-1 ">
		{{-- @can("create_banks") --}}

		{{-- @endcan --}}
	</div>
	<div class="card">
		<div class="card-datatable table-responsive p-4">
			<a class="btn btn-secondary btn-primary mx-3 position-relative ms-auto d-block w-25" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('bank_categories_create')}}">
				<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_category")}}</span></span>
			</a>
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.name")</th>
					<th class="text-start">@lang("global.questions_count")</th>
					<th class="text-start">@lang("global.created_at")</th>
				</tr>
				</thead>
				@isset($categories)
					<tbody>
					@foreach ( $categories as $category )
						<tr>
							<td class="text-start">{{ $category->id}}</td>
							<td class="text-start"><a href="{{route('bank_questions', $category->id)}}">{{ $category->name }}</a></td>
							<td class="text-start "><span class="fw-bold badge bg-label-success">{{ $category->countQuestions()}}</span></td>
							<td class="text-start" dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}">{{DATE($category->created_at)}}</td>
						</tr>
					@endforeach
					</tbody>
				@endisset
			</table>
		</div>
	</div>
@endsection