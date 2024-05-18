@php
	$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', trans("global.comments"))

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
	<script src="{{asset('assets/js/comments.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.comments"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_comments"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<!-- Role cards -->
	<div class="row g-4">
		<div class="col-12">
			<!-- Role Table -->
			<div class="card">
				<div class="card-datatable table-responsive">
					<table class="datatables-users table border-top">
						<thead>
						<tr>
							{{--							<th class="text-start"></th>--}}
							<th class="text-start">@lang("global.number")</th>
							<th class="text-start">@lang("global.username")</th>
							<th class="text-start">@lang("global.status")</th>
							<th class="text-start">@lang("global.courses")</th>
							<th class="text-start">@lang("global.comment")</th>
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
        url: `/comment/toggleStatus/${$id}`,
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
