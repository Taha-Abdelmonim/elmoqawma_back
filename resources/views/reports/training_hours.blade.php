@extends('layouts/layoutMaster')

@section('title', trans('global.reports') . " | " . trans("global.reports_training_hours"))

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
				@if(auth()->user()->teacher_id === null)
					<div class="col-3 mb-2">
						<label for="teacher_id" class="form-label">{{ trans('global.coach') }}</label>
						<select required id="teacher_id" name="teacher_id" class="select2 form-select form-select-lg teacher_id @error('teacher_id') is-invalid @enderror" data-allow-clear="true">
							<option disabled selected>@lang("global.choose_teacher")</option>
							@foreach ($teachers as $teacher)
								<option value="{{ $teacher->id }}" @if (old('teacher_id', Request::input("teacher_id")) == $teacher->id) selected @endif>{{ $teacher["name_". app()->getLocale()] }}</option>
							@endforeach
						</select>
					</div>
				@endif
				<div class="col-3 ">
					<label for="pickup_time" class="form-label">@lang('global.from')</label>
					<input type="text" name="from" value="{{Request::input("from")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-3 ">
					<label for="pickup_time" class="form-label">@lang('global.to')</label>
					<input type="text" name="to" value="{{Request::input("to")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-3 mt-4">
					<button type="submit" class="btn btn-primary w-100">@lang("global.search")</button>
				</div>
			</div>
		</form>
	</div>
	<div class="card">
		<div class="card-datatable text-nowrap table-responsive">

			<table class="datatables-ajax table">
				<thead>
				<tr>
					<th style="width:10px" class="text-start check-all"><label for="checkbox_delete"><input type="checkbox" id="checkbox_delete" class="form-check-input me-3">@lang("global.number")</label></th>
					<th class="text-start">@lang("global.title")</th>
					<th class="text-start">@lang("global.type_lecture")</th>
					<th class="text-start">@lang("global.status")</th>
					<th class="text-start">@lang("global.type_video")</th>
					<th class="text-start">@lang("global.price")</th>
					<th class="text-start">@lang("global.video_id")</th>
					<th class="text-start">@lang("global.order")</th>
					<th class="text-start">@lang("global.created_at")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($lectures as $lecture)
					<tr>
						<td class="text-start"><input type="checkbox" data-id="{{$lecture->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$lecture->id}}</td>
						<td class="text-start">{{LocalKey($lecture, "title")}}</td>
						<td><span class="badge bg-label-primary me-1">{{$lecture->getType()}}</span></td>
						<td><span class="badge bg-label-primary me-1">{{$lecture->active?'active':'inactive'}}</span></td>
						<td><span class="badge bg-label-info me-1">{{$lecture->type_video}}</span></td>
						<td><span class="badge bg-label-success me-1">{{$lecture->price}}</span></td>
						<td><span class="badge bg-label-primary me-1">@php echo $lecture->videoID @endphp</span></td>
						<td class="text-start">{{$lecture->order}}</td>
						<td class="text-start" dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}">{{DATE($lecture->created_at)}}</td>

					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<div class="row p-4">
			<div class="col-4 d-flex align-items-center text-nowrap">
				<h4 class="text-start mb-0 me-3">@lang("global.reports_training_hours"): </h4>
				<span class="text-start fs-4 badge bg-label-success"> {{convertMinutes($lectures->sum("duration") ?? 0)}} </span>
			</div>
		</div>
	</div>
@endsection
@section("my-script")
	<script>

    $(document).ready(function() {
      $(".datatables-ajax").DataTable({
        order: [
          [0, "asc"]
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
                  columns: [0, 1, 2, 3, 4, 5, 6, 8],
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
                  columns: [0, 1, 2, 3, 4, 5, 6, 8],
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
                  columns: [0, 1, 2, 3, 4, 5, 6, 8],
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
