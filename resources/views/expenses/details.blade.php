@extends('layouts/layoutMaster')
@section('title', trans('global.details_expenses'))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
	@can("export")
		<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
	@endcan
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

@endsection
@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection
@section('page-script')
	<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>

@endsection
@section('content')
	<div class="card my-3 p-4">
		<form method="get">
			<div class="row">
				<div class="col-6 mb-3 mt-0">
					<label for="user_id" class="form-label">{{ trans('global.partners') }}</label>
					<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($users as $user)
							<option value="{{ $user->id }}" @if (old('user_id', Request::input("user_id")) == $user->id) selected @endif>{{ $user->name() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-6 mb-3">
					<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
					<select required id="currency_id" name="currency_id" class="select2 form-select form-select-lg @error('currency_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($currencies as $currency)
							<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id', Request::input("currency_id")) == $currency->id) selected @endif>{{ $currency->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-4">
					<label for="pickup_time" class="form-label">@lang('global.from')</label>
					<input type="text" name="from" value="{{Request::input("from")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-4">
					<label for="pickup_time" class="form-label">@lang('global.to')</label>
					<input type="text" name="to" value="{{Request::input("to")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-4 d-flex align-items-end">
					<button type="submit" class="btn btn-primary w-100">Search</button>
				</div>
			</div>
		</form>
	</div>
	<div class="card">
		{{--    <h3 class="card-header text-center mb-1">@lang('global.details_expenses')</h3>--}}
		<div class="row p-4">
			@php $char = request()->input() == [] ? "?" : "&";@endphp
			@can("print")
				<div class="col-6 my-2">
					<a href="{{ request()->fullUrl() }}{{$char}}claim_pdf=true" class="btn btn-danger w-100" target="_blank">
						<i class="fas fa-file-excel"></i> PDF
					</a>
				</div>
			@endcan
			@can("export")
				<div class="col-6 my-2">
					<a href="{{ request()->fullUrl() }}{{$char}}claim_excel=true" class="btn btn-success w-100" target="_blank">
						<i class="fas fa-file-excel"></i> Excel
					</a>
				</div>
			@endcan
		</div>
		<div class="card-datatable text-nowrap table-responsive">
			<table class="datatables-ajax table" id="expamels">
				<thead>
				<tr>
					<th>@lang("global.number")</th>
					<th class="text-start">{{ trans('global.expenses_name') }}</th>
					<th class="text-start"> @lang('global.the_pay')</th>
					<th class="text-start">{{ trans('global.description') }}</th>
					<th class="text-start">{{ trans('global.amount') }}</th>
					<th class="text-start">{{ trans('global.created_at') }}</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($expenseDetails as $expenseDetail)
					<tr>
						<td class="text-start">{{ $expenseDetail->id }}</td>
						<td class="text-start">{{ $expenseDetail->expense_name }}</td>
						<td class="text-start"><a href="/user/{{$expenseDetail->user->id}}" target="_blank">{{ $expenseDetail->user->name() }}</a></td>
						<td class="text-start">{{ $expenseDetail->description }}</td>
						<td class="text-start">{{ $expenseDetail->amount }}</td>
						<td class="text-start">{{ DATE($expenseDetail->created_at) }}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		@if($push != null)
			<div class="row p-4">
				<div class="col-4 d-flex align-items-center">
					<h4 class="text-start mb-0 me-3">@lang("global.push"): </h4>
					<span class="text-start fs-4 badge bg-label-success"> {{$push}}</span>
				</div>
			</div>
		@endif
	</div>
@endsection
@section('page-script')
	<script>
    $(document).ready(function() {
      $("#expamels").DataTable({
        order: [
          [1, "desc"]
        ],
        dom: "<\"row me-2\"" + "<\"col-md-2\"<\"me-3\"l>>" + "<\"col-md-10\"<\"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0\"fB>>" + ">t" + "<\"row mx-2\"" + "<\"col-sm-12 col-md-6\"i>" + "<\"col-sm-12 col-md-6\"p>" + ">",
        language: {
          sLengthMenu: "_MENU_",
          search: "",
          searchPlaceholder: "بحث.."
        },
        buttons: [
          {
            extend: "collection",
            className: "btn btn-label-secondary dropdown-toggle mx-3",
            text: "<i class=\"ti ti-screen-share me-1 ti-xs\"></i>Export",
            buttons: [
              {
                extend: "print",
                text: "<i class=\"ti ti-printer me-2\" ></i>Print",
                className: "dropdown-item",
                exportOptions: {
                  columns: [0, 1, 2, 3, 4],
                  // prevent photo to be print
                  format: {
                    body: function(inner, coldex, rowdex) {
                      if (inner.length <= 0) return inner;
                      var el = $.parseHTML(inner);
                      var result = "";
                      $.each(el, function(index, item) {
                        if (item.classList !== undefined && item.classList.contains("user-name")) {
                          result = result + item.lastChild.firstChild.textContent;
                        } else if (item.innerText === undefined) {
                          result = result + item.textContent;
                        } else result = result + item.innerText;
                      });
                      return result;
                    }
                  }
                },
                customize: function(win) {
                  //customize print view for dark
                  $(win.document.body).css("color", headingColor).css("border-color", borderColor).css("background-color", bodyBg);
                  $(win.document.body).find("table").addClass("compact").css("color", "inherit").css("border-color", "inherit").css("background-color", "inherit");
                }
              },
              {
                extend: "excel",
                text: "<i class=\"ti ti-file-spreadsheet me-2\"></i>Excel",
                className: "dropdown-item",
                exportOptions: {
                  columns: [0, 1, 2, 3, 4],
                  // prevent photo to be display
                  format: {
                    body: function(inner, coldex, rowdex) {
                      if (inner.length <= 0) return inner;
                      var el = $.parseHTML(inner);
                      var result = "";
                      $.each(el, function(index, item) {
                        if (item.classList !== undefined && item.classList.contains("user-name")) {
                          result = result + item.lastChild.firstChild.textContent;
                        } else if (item.innerText === undefined) {
                          result = result + item.textContent;
                        } else result = result + item.innerText;
                      });
                      return result;
                    }
                  }
                }
              },
              {
                extend: "pdf",
                text: "<i class=\"ti ti-file-code-2 me-2\"></i>Pdf",
                className: "dropdown-item",
                exportOptions: {
                  columns: [0, 1, 2, 3, 4],
                  // prevent photo to be display
                  format: {
                    body: function(inner, coldex, rowdex) {
                      if (inner.length <= 0) return inner;
                      var el = $.parseHTML(inner);
                      var result = "";
                      $.each(el, function(index, item) {
                        if (item.classList !== undefined && item.classList.contains("user-name")) {
                          result = result + item.lastChild.firstChild.textContent;
                        } else if (item.innerText === undefined) {
                          result = result + item.textContent;
                        } else result = result + item.innerText;
                      });
                      return result;
                    }
                  }
                }
              },
              {
                extend: "copy",
                text: "<i class=\"ti ti-copy me-2\" ></i>Copy",
                className: "dropdown-item",
                exportOptions: {
                  columns: [0, 1, 2, 3, 4],
                  // prevent photo to be display
                  format: {
                    body: function(inner, coldex, rowdex) {
                      if (inner.length <= 0) return inner;
                      var el = $.parseHTML(inner);
                      var result = "";
                      $.each(el, function(index, item) {
                        if (item.classList !== undefined && item.classList.contains("user-name")) {
                          result = result + item.lastChild.firstChild.textContent;
                        } else if (item.innerText === undefined) {
                          result = result + item.textContent;
                        } else result = result + item.innerText;
                      });
                      return result;
                    }
                  }
                }
              }
            ]
          }
        ]
      });
    });
	</script>
@endsection
