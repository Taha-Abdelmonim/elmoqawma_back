@extends('layouts/layoutMaster')

@section('title', trans("global.offers"))

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
	<script src="{{asset('assets/js/offers.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.offers"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_offers"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-datatable table-responsive">
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.name")</th>
					<th class="text-start">@lang("global.price")</th>
					<th class="text-start">@lang("global.subscribers")</th>
					<th class="text-start">@lang("global.stars")</th>
					<th class="text-start">@lang("global.offer_duration")</th>
					<th class="text-start">@lang("global.created_at")</th>
					<th class="text-start">@lang("global.actions")</th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    setTimeout(() => {
      $(".dt-action-buttons").append(`
      <div class="d-flex align-items-center">
        @can("create_offers")
      <a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('offer_create')}}">
          <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.offer_create")}}</span></span>
        </a>
        @endcan
      </div>
`);
    }, 100);
	</script>
@endsection
