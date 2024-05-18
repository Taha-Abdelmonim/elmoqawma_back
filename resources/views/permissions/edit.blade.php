@extends('layouts/layoutMaster')

@section('title', trans("global.edit_permission"))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection
@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection

@section('content')
	@php
		$links = [
		 "start" => trans("global.permissions"),
		 "/" => trans("global.dashboard"),
		 "/permissions" => trans("global.all_permissions"),
		 "end" => trans("global.edit_permission"),
		]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('permission_update', $permission->id)}}" method="POST">
		@csrf
		<div class="col-12">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="card mb-4">
				<h5 class="card-header">@lang("global.edit_permission")</h5>
				<div class="card-body">
					<div>
						<label for="name" class="form-label">@lang("global.name")</label>
						<input type="text" value="{{ old("name", $permission->name) }}" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang("global.name")" aria-describedby="defaultFormControlHelp" />
					</div>
					<div class="pt-4">
						<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
					</div>
				</div>
			</div>
		</div>

	</form>
@endsection
