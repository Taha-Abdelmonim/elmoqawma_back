@php
	$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', trans("global.all_permissions"))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
	@can("export")
		<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
	@endcan
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/permissions.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" =>trans("global.users"),
			"/" => trans("global.dashboard"),
			"/users" => trans("global.all_users"),
			"end" => trans("global.all_permissions"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	{{--  <h4 class="fw-semibold mb-4">@lang("global.all_permissions")</h4>--}}
	<div class="row g-4">
		<div class="col-12">
			<!-- Role Table -->
			<div class="card">
				<div class="card-datatable table-responsive">
					<table class="datatables-basic table text-nowrap">
						<thead>
						<tr>
							{{--              <th class="text-start"></th>--}}
							<th class="text-start">{{trans("global.number")}}</th>
							<th class="text-start">{{trans("global.name")}}</th>
							<th class="text-start">{{trans("global.created_at")}}</th>
							<th class="text-start">{{trans("global.actions")}}</th>
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
@section("my-script")
	<script>
    setTimeout(() => {
      $(".dt-action-buttons").append(`
      <div class="d-flex align-items-center">
        @can("create_permissions")
      <a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('permission_create')}}">
          <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create")}}</span></span>
        </a>
        @endcan
      </div>
`);
    }, 100);
	</script>
@endsection

