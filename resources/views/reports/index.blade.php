@extends('layouts/layoutMaster')

@section('title', trans('global.reports') . " | " . trans("global.$type"))

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
				@if((auth()->user()->teacher_id === null && $type === "teachers"))
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
				@if($type === "courses" || $type === 'lectures')
					<div class="col-3 mb-2 ">
						<label for="course_id" class="form-label">{{ trans('global.course') }}</label>
						<select required id="course_id" name="course_id" class="select2 form-select form-select-lg @error('course_id') is-invalid @enderror" data-allow-clear="true" data-style="btn-default">
							<option disabled selected>@lang("global.choose_course")</option>
							@foreach ($courses as $course)
								<option value="{{ $course->id }}" @if (old('course_id', Request::input("course_id")) == $course->id) selected @endif>{{ $course->name }}</option>
							@endforeach
						</select>
					</div>
				@endif
				@if($type === 'lectures')
					<div class="col-3 mb-2" id="parent_lectures" style="display: none"></div>
				@endif
				@if($type === 'offers')
					<div class="col-3 mb-2 ">
						<label for="offer_id" class="form-label">{{ trans('global.offer') }}</label>
						<select required id="offer_id" name="offer_id" class="select2 form-select form-select-lg offer_id @error('offer_id') is-invalid @enderror" data-allow-clear="true">
							<option disabled selected>@lang("global.chose_offer")</option>
							@foreach ($offers as $offer)
								<option value="{{ $offer->id }}" @if (old('offer_id', Request::input("offer_id")) == $offer->id) selected @endif>{{ $offer["name_".app()->getLocale()] }}</option>
							@endforeach
						</select>
					</div>
				@endif
				@if($type === 'students')
					<div class="col-3 mb-2">
						<label for="user_id" class="form-label">{{ trans('global.students') }}</label>
						<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
							<option disabled selected>@lang("global.chose_students")</option>
							@foreach ($users as $user)
								<option value="{{ $user->id }}" @if (old('user_id', Request::input("user_id")) == $user->id) selected @endif>{{ $user->name() }}</option>
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
		<div class="card">
			<div class="card-datatable text-nowrap table-responsive">
				<table class="datatables-ajax table">
					<thead>
					<tr>
						<th class="text-start">@lang("global.number")</th>
						<th class="text-start">@lang("global.user")</th>
						<th class="text-start">@lang("global.type")</th>
						@if($type === "teachers")
							<th class="text-start">@lang("global.course")/@lang("global.lecture")/@lang("global.offer")</th>
						@else
							<th class="text-start">@lang("global.$type")</th>
						@endif
						<th class="text-start">@lang("global.price")</th>
						<th class="text-start">@lang("global.card_type")</th>
						<th class="text-start">@lang("global.photo")</th>
						<th class="text-start">@lang("global.coupon")</th>
						<th class="text-start">@lang("global.subscription_date")</th>
					</tr>
					</thead>
					<tbody>
					@if($orders->isNotEmpty())
						@foreach ($orders as $order)
							<tr class="text-nowrap">
								<td class="text-start ">{{$order->id}}</td>
								<td class="text-start">
									<a href="{{route("user_show", $order->user->id)}}" class="text-body text-truncate ">
										<span class="fw-semibold">{{$order->user["name_" . app()->getLocale()]}}</span>
									</a>
									<p class="text-muted mb-0 ">{{$order->user->email}}</p>
									<a href="https://api.whatsapp.com/send?phone={{$order->user->phone}}" target="_blank" class="text-truncate d-flex align-items-center ">{{$order->user->phone}}</a>
								</td>
								<td class="text-start">@lang("global.$order->type")</td>
								<td class="text-start">
									@if($order->type === 'course')
										@if(!empty($order->course))
											<a href="{{route("course_show", $order->course->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->course->name . " " .$order->course->subject["name_" . app()->getLocale()]}}</a>
										@else
											<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Course::withTrashed()->find($order->course_id)->name}}</p>
										@endif
									@elseif($order->type === 'lecture')
										@if(!empty($order->lecture))
											<a href="{{route("lecture_edit", [$order->lecture->chapter_id,$order->lecture->id])}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->lecture->course->name . " " .$order->lecture->title}}</a>
										@else
											<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Lecture::withTrashed()->find($order->lecture_id)->title}}</p>
										@endif
									@elseif($order->type === 'offer')
										@if(!empty($order->offer))
											<a href="{{route("offer_edit", $order->offer->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->offer["name_" . app()->getLocale()]}}</a>
										@else
											<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Offer::withTrashed()->find($order->offer_id)["name_" . app()->getLocale()]}}</p>
										@endif
									@endif
								</td>
								<td class="text-start "><p class='text-truncate  fw-bold badge bg-label-success w-px-100 overflow-hidden m-0'>{{$order->price}}</p></td>
								<td class="text-start "><p class='text-truncate  fw-bold badge bg-label-info w-px-100 overflow-hidden m-0'>{{trans("global.$order->card_type")}}</p></td>
								<td class="text-start">
									<a href="{{$order->photo != null || $order->photo != '' ? asset("images/$order->photo") : asset("images/global/not_found.png")}}" target="_blank" class="avatar avatar-xl d-block ">
										<img src="{{$order->photo != null || $order->photo != '' ? asset("images/$order->photo") : asset("images/global/not_found.png")}}" alt="Avatar" class="rounded-circle object-cover">
									</a>
								</td>
								<td class="text-start "><p class='text-truncate  fw-bold badge bg-label-success w-px-150 overflow-hidden m-0'>{{$order->code ?? "--"}}</p></td>
								<td class="text-start">{{DATE($order->created_at)}}</td>

							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
			<div class="row p-4">
				<div class="col-4 d-flex align-items-center">
					<h4 class="text-start mb-0 me-3">@lang("global.total"): </h4>
					<span class="text-start fs-4 badge bg-label-success"> {{$total}}</span>
				</div>
			</div>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    if ("{{$type}}" === "lectures") {


      function getLecture(id) {
        $.ajax({
          type: "get",
          url: `/orders/getLecture/${id}`,
          data: {
            "_token": "{{ csrf_token() }}",
            "id": id
          },
          success: function(data) {
            $("#parent_lectures").html(data);
            $("#parent_lectures").show();
            $("#lecture_id").select2();
          },
          error: function({ responseJSON }) {
            showMessage("error", responseJSON.message);
          }
        });
      }

      $("#course_id").on("change", function() {
        getLecture($(this).val());
      });
    }
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
                  columns: [0, 1, 2, 3, 4, 5, 7, 8],
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
                  columns: [0, 1, 2, 3, 4, 5, 7, 8],
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
                  columns: [0, 1, 2, 3, 4, 5, 7, 8],
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
                  columns: [0, 1, 2, 3, 4, 5, 7, 8],
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
