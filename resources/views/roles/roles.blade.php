@php
	$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', trans("global.roles"))

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
	<script src="{{asset('assets/js/roles.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.roles"),
			"/" => trans("global.dashboard"),
			"/users" => trans("global.all_users"),
			"end" => trans("global.all_roles"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="row g-4">
		<div class="col-12">
			<!-- Role Table -->
			<div class="card">
				<div class="card-datatable table-responsive">
					<table class="datatables-basic table text-nowrap">
						<thead>
						<tr>
							<th></th>
							<th class="text-start">@lang("global.number")</th>
							<th class="text-start">@lang("global.name")</th>
							<th class="text-start">@lang("global.created_at")</th>
							<th class="text-start"> @lang("global.actions")</th>
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
        <div class="demo-inline-spacing  me-3">
          <div class="btn-group mt-0">
            <button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown" aria-expanded="false">{{trans("global.action_select")}}</button>
            <ul class="dropdown-menu">
              <li class='form-delete-all'>
                <form action="{{route('roles_delete_all')}}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
                  @csrf
      <i class="ti ti-trash ti-sm me-2"></i>
			<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
      </form>
	</li>
</ul>
</div>
</div>
@can("create_roles")
      <a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('role_create')}}">
          <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create")}}</span></span>
      </a>
      @endcan
      </div>
`);
    }, 100);

    function buttonActionDisable(checked) {
      if (checked) {
        $("#button-actions").removeClass("disabled btn-label-secondary btn-secondary");
        $("#button-actions").addClass("btn-success");
      } else {
        $("#button-actions").addClass("disabled btn-label-secondary btn-secondary");
        $("#button-actions").removeClass("btn-success");
      }
    }

    $(function() {
      $(".check-all > input").on("click", function() {
        let valueIds = [];
        let inputIdsValue = $("#ids-delete");
        if ($(this)[0].checked) {
          $(".delete-all").each(function() {
            $(this).prop("checked", true);
            valueIds.push($(this).data("id"));
            inputIdsValue.val(`[${valueIds}]`);
          });
          buttonActionDisable(true);
        } else {
          buttonActionDisable(false);
          valueIds = [];
          inputIdsValue.val(`[${valueIds}]`);
        }
      });
      $(".card").on("click", "#form-delete-all", function() {
        if (confirm("Are you sure?")) {
          $("#form-delete-all").submit();
        }
        return false;
      });
      setTimeout(() => {
        let inputIdsValue = $("#ids-delete");
        let valueIds = [];
        $(".delete-all").on("change", function() {

          if ($(this)[0].checked) {
            buttonActionDisable(true);
            valueIds.push($(this).data("id"));
            inputIdsValue.val(`[${valueIds}]`);
          } else {
            valueIds = valueIds.filter((ele) => ele != $(this).data("id"));
            if (valueIds.length < 1) {
              buttonActionDisable(false);
            }
            inputIdsValue.val(`[${valueIds}]`);
          }
        });
      }, 500);
    });
	</script>
@endsection
