@extends('layouts.layoutMaster')

@section('title', trans("global.dashboard"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
	<!-- Page -->
	<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/chartjs/chartjs.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/dashboards-crm.js')}}"></script>
	<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
	<script src="{{asset('assets/js/bunnycdn.js')}}"></script>
@endsection

@section('content')

	<div class="row">
		<form class="card-body row" action="{{route('test')}}" method="POST" enctype="multipart/form-data">
			@method("put")
			@csrf
			<div class="mb-3">
				<label for="formFile" class="form-label">Default file input example</label>
				<input class="form-control" name="video" type="file" id="formFile">
			</div>
			<button type="submit" class="btn btn-success"><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>@lang("global.create")</button>
		</form>
	</div>
@endsection

