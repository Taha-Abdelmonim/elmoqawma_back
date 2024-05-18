@extends('layouts.layoutMaster')

@section('title', trans("global.currency"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
	@can("export")
		<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
	@endcan
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />

	{{--<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />--}}

@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

	<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
	{{--<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>--}}
	{{--  <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>--}}
	{{--  <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>--}}
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/currencies.js')}}"></script>
@endsection

@section('content')

	<div class="card">
		<div class="card-header border-bottom">
			<div class="d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-3">{{ trans("global.search_filed") }}</h5>
				<div class="d-flex align-items-center">
					<div class="demo-inline-spacing  me-3">
						<div class="btn-group mt-0">
							<button id="button-actions" type="button"
							        class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown"
							        aria-expanded="false">{{trans("global.action_select")}}</button>
							<ul class="dropdown-menu">
								<li>
									<form action="{{route('currency_delete_all')}}" method="POST" id="form-delete-all"
									      class="position-relative dropdown-item cursor-pointer" tabindex="0"
									      aria-controls="DataTables_Table_0" href="{{route('currency_create')}}">
										<i class="ti ti-trash ti-sm me-2"></i> @csrf
										<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
									</form>
								</li>
								<li>
									<a class="dropdown-item" href="javascript:void(0);"><i class="ti ti-printer me-2"></i>{{trans("global.print")}}</a>
								</li>
							</ul>
						</div>
					</div>
					@can("create_currencies")
						<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0"
						   href="{{route('currency_create')}}">
							<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_currency")}}</span></span>
						</a>
					@endcan
				</div>
			</div>
			<div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
				<div class="col-md-4 user_role"></div>
				<div class="col-md-4 user_plan"></div>
				<div class="col-md-4 user_status"></div>
			</div>
		</div>

		<div class="card">
			<div class="card-datatable text-nowrap table-responsive">
				<table class="datatables-ajax table">
					<thead>
					<tr>
						<th></th>
						<th class="text-start">{{trans("global.number")}}</th>
						<th class="text-start">{{trans("global.name")}}</th>
						<th class="text-start">{{trans("global.symbol")}}</th>
						<th class="text-start">{{trans("global.value")}}</th>
						<th class="text-start">{{trans("global.created_at")}}</th>
						<th class="text-start">{{trans("global.actions")}}</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
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
      $("#form-delete-all").on("click", function() {
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
            console.log("d");
          } else {
            buttonActionDisable(false);
            valueIds = valueIds.filter((ele) => ele != $(this).data("id"));
            inputIdsValue.val(`[${valueIds}]`);
          }
        });
      }, 500);
    });
	</script>
@endsection
