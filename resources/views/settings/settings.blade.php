@php
	$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', trans("global.site_setting"))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
	@can("export")
		<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
	@endcan
@endsection


@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/settings.js')}}"></script>
@endsection

@section('content')
	<div class="d-flex justify-content-between align-items-center mb-4">
		@php
			$links = [
				"start" => trans("global.settings"),
				"/" => trans("global.dashboard"),
				"end" => trans("global.site_setting"),
		]
		@endphp
		@include("layouts.breadcrumbs")
		@can("create_settings")
			<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('setting_create')}}">
				<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">@lang("global.setting_create")</span></span>
			</a>
		@endcan
	</div>

	<!-- Role cards -->
	<div class="row g-4">


		<div class="col-12">
			<!-- Role Table -->
			<div class="card">
				<div class="card-datatable table-responsive">
					<table class="datatables-users table border-top">
						<thead>
						<tr>
							<th class="text-start"></th>
							<th class="text-start">@lang("global.number")</th>
							<th class="text-start">@lang("global.name")</th>
							<th class="text-start">@lang("global.key")</th>
							<th class="text-start">@lang("global.value")/@lang("global.photo")</th>
							<th class="text-start">@lang("global.created_at")</th>
							<th class="text-start">@lang("global.actions")</th>
						</tr>
						</thead>
					</table>
				</div>
			</div>
			<!--/ Role Table -->
		</div>
	</div>
	<!--/ Role cards -->

@endsection

