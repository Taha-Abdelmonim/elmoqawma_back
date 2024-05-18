@extends('layouts/layoutMaster')

@section('title', trans("global.users"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection
@section('page-script')
	<script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection
@section('content')
	<div class="card">
		<form action="{{route('send_notifications')}}" method="POST" class=" py-3 ">
			@csrf
			<div class="col-12 ">
				<div class="card">
					<div class="d-flex justify-content-between align-items-center px-2">
						<h5 class="card-header">@lang("global.message")</h5>
						<button type="submit" class="btn btn-success"><i class="menu-icon fa-solid fa-bell me-0 me-sm-1 ti-xs"></i>@lang("global.send")</button>
					</div>
					<input type="hidden" name="message" id="descriptionQuill" value="{{old('message')}}">
					<div class="card-body">
						<div id="full-editor" class="@error('message') is-invalid @enderror">
							@php
								echo old('message')
							@endphp
						</div>
					</div>
				</div>
			</div>
			<input type="text" name="ids" value="" hidden id="ids-delete">

		</form>
	</div>
	<div class="card">
		<div class="card-datatable table-responsive">
			<table class="datatables-users table border-top component_table">
				<thead>
				<tr>
					<th class="text-start check-all"><label for="checkbox_delete"><input type="checkbox" id="checkbox_delete" class="form-check-input me-3">@lang("global.number")</label></th>
					<th class="text-start">{{trans("global.name")}}</th>
					<th class="text-start">{{trans("global.phone")}}</th>
					<th class="text-start">{{trans("global.roles")}}</th>
					<th class="text-start">{{trans("global.photo")}}</th>
					<th class="text-start">{{trans("global.created_at")}}</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($users as $user)
					<tr>
						<td class="text-start"><input type="checkbox" data-id="{{$user->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$user->id}}</td>
						<td class="text-start">
							<a href="{{route("user_show", $user->id)}}" class="text-body text-truncate ">
								<span class="fw-semibold">{{LocalKey($user, "name")}}</span>
							</a>
							<p class="text-muted mb-0 ">{{$user->email}}</p>
						</td>
						<td class="text-start"><a href="https://api.whatsapp.com/send?phone={{$user->phone}}" target="_blank" class="text-truncate d-flex align-items-center ">{{$user->phone}}</a></td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center'>{{$user->roles_name !== null ? $user->roles_name[0] : '--'}}</span></td>
						<td class="text-start">
							<a href="{{asset("attachments/$user->photo")}}" target="_blank" class="avatar avatar-xl d-block ">
								<img src="{{asset("attachments/$user->photo")}}" alt="--" class="rounded-circle object-cover">
							</a>
						</td>
						<td class="text-start">{{DateValue($user->created_at)}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
@section("my-script")
	@include("components.table", ["columns" => '[0, 1, 2, 3, 5]', "nameClass" => "component_table"])
@endsection
