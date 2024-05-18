@extends('layouts/layoutMaster')
@section('title', trans('global.expenses'))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
	@can("export")
		<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
	@endcan
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection
@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
	<script src="{{ asset('assets/js/modal-edit-user.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/expenses.js') }}"></script>
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
@endsection
@section('content')
	<div class="card">
		<div class="card-header border-bottom">
			<div class="d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-3">{{ trans('global.search_filed') }}</h5>
				<div class="d-flex align-items-center">
					<div class="demo-inline-spacing  me-3">
						<div class="btn-group mt-0">
							<button id="button-actions" type="button"
							        class="btn btn-label-secondary btn-secondary dropdown-toggle disabled"
							        data-bs-toggle="dropdown"
							        aria-expanded="false">{{ trans('global.action_select') }}</button>
							<ul class="dropdown-menu">
								<li>
									<form action="{{ route('expenses_delete_all') }}" method="POST" id="form-delete-all"
									      class="position-relative dropdown-item cursor-pointer" tabindex="0"
									      aria-controls="DataTables_Table_0" href="{{ route('expenses_create') }}">
										<i class="ti ti-trash ti-sm me-2"></i> @csrf
										<input type="text" name="ids" value="" hidden
										       id="ids-delete">{{ trans('global.delete') }}
									</form>
								</li>
								<li>
									<a class="dropdown-item" href="javascript:void(0);"><i
											class="ti ti-printer me-2"></i>{{ trans('global.print') }}</a>
								</li>

							</ul>
						</div>
					</div>
					@can("create_expenses")
						<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{ route('expenses_create') }}">
							<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{ trans('global.create_expenses') }}</span></span>
						</a>
					@endcan
					@can("pay_expenses")
						<button class="btn btn-success text-wight position-relative mx-2" data-bs-toggle="modal" data-bs-target="#editUser">
							<span class="d-none d-sm-inline-block">{{ trans('global.pay_expenses') }}</span>
						</button>
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
						<th class="text-start">{{ trans('global.number') }}</th>
						<th class="text-start">{{ trans('global.name') }}</th>
						<th class="text-start">{{ trans('global.created_at') }}</th>
						<th class="text-start">{{ trans('global.actions') }}</th>
					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
@endsection
@include('expenses.pay_model')
@section('my-script')
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

    /*var indexPayment = 0;
		$("body").on("click","#add_payment", function () {
				var text = $("#payment_mehod").find("option:selected").text();
				var value = $("#payment_mehod").find("option:selected").val();
				var amount = +$("#input_amount").val();
				var amount_input_model = +$("#amount_input_model").val();
				var totalAmountPayment = 0

				var parentPayment = $(`#payment_mehod_id_${value}`).parents("tr");
				if (parentPayment.length != 0) {
						$(".amount_payment").each(function () {
								totalAmountPayment += +$(this).val();
						})
						totalAmountPayment += amount;
						totalAmountPayment -= parentPayment.find(".amount_payment").val();
						if (totalAmountPayment > amount_input_model) {
								return showMessage("error", "المبلغ المدفوع اكبر من مبلغ المصروف");
						}
						parentPayment.before(`
								<tr>
										<td><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="payments[${indexPayment}][id]" class="payment_mehod_id" id="payment_mehod_id_${value}" value="${value}"></td>
										<td>${amount}<input type="hidden" name="payments[${indexPayment}][amount]" class="amount_payment" value="${amount}"></td>
								</tr>
						`)
						parentPayment.remove()
				} else {
						$(".amount_payment").each(function () {
								totalAmountPayment += +$(this).val();
						})
						totalAmountPayment += amount;
						if (totalAmountPayment > amount_input_model) {
								return showMessage("error", "المبلغ المدفوع اكبر من مبلغ المصروف");
						}
						$("#parent_payment").append(`
								<tr>
										<td><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="payments[${indexPayment}][id]" class="payment_mehod_id" id="payment_mehod_id_${value}" value="${value}"></td>
										<td>${amount}<input type="hidden" name="payments[${indexPayment}][amount]" class="amount_payment" value="${amount}"></td>
								</tr>
						`)
				}
				$("#input_amount").val(0)
				indexPayment++;
		})*/
	</script>
@endsection
