@extends('layouts/layoutMaster')

@section('title', trans("global.users"))

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
	<script src="{{asset('assets/js/users.js')}}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.sections"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_users"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-datatable table-responsive">
			<table class="datatables-basic table text-nowrap">
				<thead>
				<tr>
					<th></th>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.name")</th>
					<th class="text-start">@lang("global.phone")</th>
					<th class="text-start">@lang("global.roles")</th>
					<th class="text-start">@lang("global.balance")</th>
					<th class="text-start">@lang("global.photo")</th>
					<th class="text-start">@lang("global.teacher")</th>
					<th class="text-start">@lang("global.created_at")</th>
					<th class="text-start">@lang("global.actions")</th>
				</tr>
				</thead>
			</table>
		</div>

	</div>
	@include("users/wallet")
@endsection
@section("my-script")
	<script>
    var myModal = document.getElementById("wallet");
    myModal.addEventListener("shown.bs.modal", function() {
      var button = event.relatedTarget;
      var id = $(button).attr("data-id");
      var formWallet = $("#form_wallet");
      formWallet.attr("action", `/user/wallet/${id}`);
    });
    myModal.addEventListener("hidden.bs.modal", function() {
      var formWallet = $("#form_wallet");
      formWallet.removeAttr("action");
      formWallet.find("#code").val("");
    });
    setTimeout(() => {
      $(".dt-action-buttons").append(`
      <div class="d-flex align-items-center">
        <div class="demo-inline-spacing  me-3">
          <div class="btn-group mt-0">
            <button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown" aria-expanded="false">{{trans("global.action_select")}}</button>
            <ul class="dropdown-menu">
              <li class='form-delete-all'>
                <form action="{{route('users_delete_all')}}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
                  @csrf
      <i class="ti ti-trash ti-sm me-2"></i>
			<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
      </form>
		</li>
	</ul>
</div>
</div>
@can("create_users")
      <a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('user_create')}}">
        <span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_user")}}</span></span>
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
