@extends('layouts/layoutMaster')

@section('title', trans('global.transactions'))

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
				<div class="col-3 mb-3 mt-0">
					<label for="user_id" class="form-label">{{ trans('global.partners') }}</label>
					<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($users as $user)
							<option value="{{ $user->id }}" @if (old('user_id', Request::input("user_id")) == $user->id) selected @endif>{{ $user->name() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-3">
					<label for="pickup_time" class="form-label">@lang('global.from')</label>
					<input type="text" name="from" value="{{Request::input("from")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-3">
					<label for="pickup_time" class="form-label">@lang('global.to')</label>
					<input type="text" name="to" value="{{Request::input("to")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-3 d-flex align-items-center h-fit">
					<button type="submit" class="btn btn-primary w-100">Search</button>
				</div>
			</div>
		</form>
	</div>
	<div class="card">
		<div class="card">
			<div class="card-datatable text-nowrap table-responsive">
				<table class="datatables-ajax table">
					<thead>
					<tr>
						<th class="text-start">#</th>
						<th class="text-start">@lang('global.db_name')</th>
						<th class="text-start"> @lang('global.description')</th>
						<th class="text-start">@lang('global.user_operation') </th>
						<th class="text-start">@lang('global.operation_date') </th>
					</tr>
					</thead>
					<tbody>
					@foreach ($transactions as $transaction)
						<tr>
							<td class="text-start">{{ $transaction->id }}</td>
							<td class="text-start">{{ $transaction->db_name }}</td>
							<td class="text-start">{{ $transaction->description }}</td>
							<td class="text-start">
								@if(!empty($transaction->user))
									<a href="/user/{{$transaction->user->id}}" target="_blank">{{ $transaction->user->name() }}</a>
								@else
									--
								@endif
							</td>
							<td class="text-start">{{ $transaction->created_at }}</td>
						</tr>
					@endforeach

					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    $(".datatables-ajax").DataTable({
      order: [[0, "desc"]],
      dom: "<\"card-header flex-column flex-md-row\"<\"head-label text-center\"><\"dt-action-buttons text-end pt-3 pt-md-0\"B>><\"row\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end\"f>>t<\"row\"<\"col-sm-12 col-md-6\"i><\"col-sm-12 col-md-6\"p>>",
      displayLength: 10,
      language: {
        sLengthMenu: `{{trans("global.show")}}_MENU_`,
        search: `{{trans("global.search")}}`,
        searchPlaceholder: `{{trans("global.search")}}`
      },
      lengthMenu: [7, 10, 25, 50, 75, 100],
      buttons: [
        {
          extend: "collection",
          className: "btn btn-label-primary dropdown-toggle me-2",
          text: "<i class=\"ti ti-file-export me-sm-1\"></i> <span class=\"d-none d-sm-inline-block\">Export</span>",
          buttons: [
            {
              extend: "print",
              text: "<i class=\"ti ti-printer me-1\" ></i>Print",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
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
                $(win.document.body)
                  .css("color", config.colors.headingColor)
                  .css("border-color", config.colors.borderColor)
                  .css("background-color", config.colors.bodyBg);
                $(win.document.body)
                  .find("table")
                  .addClass("compact")
                  .css("color", "inherit")
                  .css("border-color", "inherit")
                  .css("background-color", "inherit");
              }
            },
            {
              extend: "csv",
              text: "<i class=\"ti ti-file-text me-1\" ></i>Csv",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
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
              extend: "excel",
              text: "<i class=\"ti ti-file-spreadsheet me-1\"></i>Excel",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
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
              text: "<i class=\"ti ti-file-description me-1\"></i>Pdf",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
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
              text: "<i class=\"ti ti-copy me-1\" ></i>Copy",
              className: "dropdown-item",
              exportOptions: {
                columns: [0, 1, 2, 3, 4],
                // prevent avatar to be display
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
    $("div.head-label").html(`<h5 class="card-title mb-0">{{trans("global.search_filed")}}</h5>`);
	</script>
@endsection
