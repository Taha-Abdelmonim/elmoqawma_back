@php
	$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', trans("global.reviews"))

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
	<script src="{{asset('assets/js/reviews.js')}}"></script>
@endsection

@section('content')
	<div class="d-flex justify-content-between align-items-center mb-4">
		@php
			$links = [
				"start" => trans("global.reviews"),
				"/" => trans("global.dashboard"),
				"end" => trans("global.all_reviews"),
		]
		@endphp
		@include("layouts.breadcrumbs")
		@can("create_reviews")
			<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('review_create')}}">
				<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">@lang("global.create_review")</span></span>
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
							<th class="text-start">@lang("global.username")</th>
							<th class="text-start">@lang("global.review")</th>
							<th class="text-start">@lang("global.status")</th>
							<th class="text-start">@lang("global.section")</th>
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
@section("my-script")
	<script>
    function toggle_active_comment(event, $id) {
      let status = event.checked ? 1 : 0;
      $.ajax({
        url: `/review/toggleStatus/${$id}`,
        data: { status, "_token": "{{ csrf_token() }}" },
        type: "POST",
        cache: false,
        dataType: "json",
        success: function(data) {
          showMessage("success", data.message);
        },
        error: function({ responseJSON }) {
          showMessage("error", responseJSON.message);
        }
      });
    }
	</script>
@endsection
