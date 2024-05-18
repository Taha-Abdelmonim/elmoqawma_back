@extends('layouts/layoutMaster')

@section('title', trans("global.edit_role"))
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
	<script src="{{asset('assets/js/form-basic-inputs.js')}}"></script>
@endsection

@section('content')
	{{--    {{Route::currentRouteName()}}--}}
	@php
		$links = [
			 "start" => trans("global.roles"),
			"/" => trans("global.dashboard"),
			"/roles" => trans("global.all_roles"),
			"end" => trans("global.edit_role"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<form class="card-body row" id="save_role" action="{{route('role_update',$role->id )}}" method="POST">
		@csrf
		<div class="col-12">
			<div class="card mb-4">
				<h5 class="card-header">{{trans("global.edit_role")}}  </h5>
				<div class="card-body">
					<div>
						<label for="name" class="form-label">{{trans("global.name")}} </label>
						<input type="text" value="{{ old("name",$role->name) }}" class="form-control" id="name" name="name" placeholder="{{trans("global.name")}}" aria-describedby="defaultFormControlHelp" />
					</div>
					<!-- Project table -->
					<div class="card my-4">
						<!-- Notifications -->
						{{--<div class="d-flex align-items-center justify-content-between">
							<h5 class="card-header">@lang("global.permission_role")</h5>
							<button type="submit" class="btn btn-primary me-3">Save changes</button>
						</div>--}}
						<div class="card-body">
							<span>@lang("global.text_permission_role")</span>
						</div>
						<div class="table-responsive">
							<table class="table table-striped border-top">
								<thead>
								<tr>
									<th class="text-nowrap cursor-pointer user-select-none" id="all_th" checkedall="0">@lang("global.name")</th>
									<th class="text-nowrap text-center cursor-pointer user-select-none" id="show_th" checkedall="0">@lang("global.show")</th>
									<th class="text-nowrap text-center cursor-pointer user-select-none" id="create_th" checkedall="0">@lang("global.create")</th>
									<th class="text-nowrap text-center cursor-pointer user-select-none" id="edit_th" checkedall="0">@lang("global.edit")</th>
									<th class="text-nowrap text-center cursor-pointer user-select-none" id="delete_th">@lang("global.delete")</th>
								</tr>
								</thead>
								<tbody>
								@foreach($permissionsGroup as $key => $permission)
									<tr checkedvalue="0">
										<td class="text-nowrap cursor-pointer select_name user-select-none">@lang("global.".$key)</td>
										<td>
											<div class="form-check d-flex justify-content-center">
												<input class="form-check-input showValue" type="checkbox" name="permission[]" value="{{$permission[0]->id}}" {{ in_array($permission[0]->id,$rolePermissions)?"checked":""  }} />
											</div>
										</td>
										<td>
											<div class="form-check d-flex justify-content-center">
												<input class="form-check-input createValue" type="checkbox" name="permission[]" value="{{$permission[1]->id}}" {{ in_array($permission[1]->id,$rolePermissions)?"checked":""  }} />
											</div>
										</td>
										<td>
											<div class="form-check d-flex justify-content-center">
												<input class="form-check-input editValue" type="checkbox" name="permission[]" value="{{$permission[2]->id}}" {{ in_array($permission[2]->id,$rolePermissions)?"checked":""  }} />
											</div>
										</td>
										<td>
											<div class="form-check d-flex justify-content-center">
												<input class="form-check-input deleteValue" type="checkbox" name="permission[]" value="{{$permission[3]->id}}" {{ in_array($permission[3]->id,$rolePermissions)?"checked":""  }} />
												<input hidden="hidden" type="checkbox" id="defaultCheck2" />
											</div>
										</td>
									</tr>
								@endforeach
								@foreach($permissions as $key => $permission)
									<tr>
										<td class="text-nowrap cursor-pointer select_name">@lang("global.".$permission->name)</td>
										<td>
											<div class="form-check d-flex justify-content-center">
												<input class="form-check-input showValue" type="checkbox" name="permission[]" value="{{$permission->id}}" id="defaultCheck10" {{ in_array($permission->id,$rolePermissions)?"checked":""  }} />
											</div>
										</td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="pt-4">
						<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
					</div>
				</div>
			</div>
		</div>

	</form>
@endsection
@section("my-script")
	<script>
    $("#save_role").on("click", "#all_th", function() {
      if ($(this).attr("checkedall") == "1") {
        $("#save_role").find("[type=\"checkbox\"]").prop("checked", true);
        $(this).attr("checkedall", "0");
      } else {
        $("#save_role").find("[type=\"checkbox\"]").prop("checked", false);
        $(this).attr("checkedall", "1");
      }
    });
    $("#save_role").on("click", "#show_th", function() {
      if ($(this).attr("checkedall") == "1") {
        $("#save_role").find(".showValue").prop("checked", true);
        $(this).attr("checkedall", "0");
      } else {
        $("#save_role").find(".showValue").prop("checked", false);
        $(this).attr("checkedall", "1");
      }
    });
    $("#save_role").on("click", "#create_th", function() {
      if ($(this).attr("checkedall") == "1") {
        $("#save_role").find(".createValue").prop("checked", true);
        $(this).attr("checkedall", "0");
      } else {
        $("#save_role").find(".createValue").prop("checked", false);
        $(this).attr("checkedall", "1");
      }
    });
    $("#save_role").on("click", "#edit_th", function() {
      if ($(this).attr("checkedall") == "1") {
        $("#save_role").find(".editValue").prop("checked", true);
        $(this).attr("checkedall", "0");
      } else {
        $("#save_role").find(".editValue").prop("checked", false);
        $(this).attr("checkedall", "1");
      }
    });
    $("#save_role").on("click", "#delete_th", function() {
      if ($(this).attr("checkedall") == "1") {
        $("#save_role").find(".deleteValue").prop("checked", true);
        $(this).attr("checkedall", "0");
      } else {
        $("#save_role").find(".deleteValue").prop("checked", false);
        $(this).attr("checkedall", "1");
      }
    });


    $("#save_role").on("click", ".select_name", function() {
      if ($(this).parents("tr").attr("checkedvalue") == "1") {
        $(this).parents("tr").find("[type=\"checkbox\"]").prop("checked", true);
        $(this).parents("tr").attr("checkedvalue", "0");
      } else {
        $(this).parents("tr").find("[type=\"checkbox\"]").prop("checked", false);
        $(this).parents("tr").attr("checkedvalue", "1");
      }
    });
	</script>
@endsection
