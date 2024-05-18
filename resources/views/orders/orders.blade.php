@extends('layouts/layoutMaster')

@section('title', trans("global.all_subscriptions"))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
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



@section('content')
	@php
		$links = [
			"start" => trans("global.subscriptions"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_subscriptions"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-datatable table-responsive">
			<div class="d-flex align-items-center justify-content-between p-4">
				<h5 class="card-title mb-3">{{ trans('global.search_filed') }}</h5>
				<div class="d-flex align-items-center">
					<div class="demo-inline-spacing  me-3">
						<div class="btn-group mt-0">
							<button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown" aria-expanded="false">{{ trans('global.action_select') }}</button>
							<ul class="dropdown-menu">
								<li>
									<form action="{{ route('orders_delete_all', $type) }}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
										<i class="ti ti-trash ti-sm me-2"></i> @csrf
										<input type="text" name="ids" value="" hidden id="ids-delete">{{ trans('global.delete') }}
									</form>
								</li>
								<li>
									<a class="dropdown-item" href="javascript:void(0);"><i class="ti ti-printer me-2"></i>{{ trans('global.print') }}</a>
								</li>
							</ul>
						</div>
					</div>
					<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('order_create', $type)}}">
						<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_subscription")}}</span></span>
					</a>
				</div>
			</div>
			<table class="table border-top table-striped pre-line">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.user")</th>
					{{--					<th class="text-start">@lang("global.phone")</th>--}}
					@if($type === 'lecture')
						<th class="text-start">@lang("global.course")/@lang("global.subject")</th>
					@endif
					<th class="text-start">@lang("global.". $type)</th>
					<th class="text-start">@lang("global.price")</th>
					<th class="text-start">@lang("global.card_type")</th>
					<th class="text-start">@lang("global.status")</th>
					<th class="text-start">@lang("global.photo")</th>
					<th class="text-start">@lang("global.coupon")</th>
					<th class="text-start">@lang("global.created_at")</th>
					<th class="text-start">@lang("global.actions")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($orders as $order)
					<tr class="text-nowrap">
						<td class="text-start ">
							<div class="d-flex">
								<input id="input_{{$order}}" type="checkbox" data-id="{{$order->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">
								<label for="input_{{$order}}">{{$order->id}}</label>
							</div>
						</td>
						<td class="text-start ">
							@if(!empty($order->user))
								<a href="{{route("user_show", $order->user->id)}}" class="text-body text-truncate ">
									<span class="fw-semibold">{{$order->user["name_" . app()->getLocale()]}}</span>
								</a>
								<p class="text-muted mb-0 ">{{$order->user->email}}</p>
								<a href="https://api.whatsapp.com/send?phone={{$order->user->phone}}" target="_blank" class="text-truncate d-flex align-items-center ">{{$order->user->phone}}</a>
							@else
								<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\User::withTrashed()->find($order->user_id)["name_" . app()->getLocale()]}}</p>
							@endif
						</td>
						@if($type === 'lecture')
							<td class="text-start">
								@if(!empty($order->lecture))
									<a href="{{route("course_show", $order->lecture->course->id)}}" target="_blank"
									   class="text-truncate d-flex align-items-center">{{$order->lecture->course->name . " " .$order->lecture->course->subject["name_" . app()->getLocale()]}}</a>
								@else
									<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Course::withTrashed()->find($order->course_id)->name}}</p>
								@endif
							</td>
						@endif
						<td class="text-start">
							@if($type === 'course')
								@if(!empty($order->course))
									<a href="{{route("course_show", $order->course->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->course->name . " " .$order->course->subject["name_" . app()->getLocale()]}}</a>
								@else
									<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Course::withTrashed()->find($order->course_id)->name}}</p>
								@endif
							@elseif($type === 'lecture')
								@if(!empty($order->lecture))
									<a href="{{route("lecture_edit", [$order->lecture->chapter_id,$order->lecture->id])}}" target="_blank" class="text-truncate d-flex align-items-center">{{$order->lecture->title}}</a>
								@else
									<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Lecture::withTrashed()->find($order->lecture_id)->title}}</p>
								@endif
							@elseif($type === 'offer')
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
							<label class="switch cursor-pointer">
								<input type="checkbox" class="switch-input" @if($order->status) checked @endif onchange="toggle_active_order(this, {{$order->id}})" />
								<span class="switch-toggle-slider">
                  <span class="switch-on">
                    <i class="ti ti-check"></i>
                  </span>
                  <span class="switch-off">
                    <i class="ti ti-x"></i>
                  </span>
                </span>
							</label>
						</td>
						<td class="text-start">
							<a href="{{$order->photo != null || $order->photo != '' ? asset("images/$order->photo") : asset("images/global/not_found.png")}}" target="_blank" class="avatar avatar-xl d-block ">
								<img src="{{$order->photo != null || $order->photo != '' ? asset("images/$order->photo") : asset("images/global/not_found.png")}}" alt="Avatar" class="rounded-circle object-cover">
							</a>
						</td>
						<td class="text-start "><p class='fw-bold badge bg-label-success w-px-150 overflow-hidden m-0'>{{$order->code ?? "--"}}</p></td>
						<td class="text-start">{{DATE($order->created_at)}}</td>
						<td class="text-start">
							<div class="d-flex align-items-center">
								<a href="{{route('order_edit', [$type, $order->id])}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
								<a href="{{route('order_delete', [$type, $order->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
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

    function toggle_active_order(event, $id) {
      let status = event.checked ? 1 : 0;
      $.ajax({
        url: `/orders/toggleStatus/${$id}`,
        data: { status, "_token": "{{ csrf_token() }}" },
        type: "POST",
        cache: false,
        dataType: "json",
        success: function(data) {
          showMessage("success", data.message);
        },
        error: function({ responseJSON }) {
          showMessage("error", responseJSON.message);
        }
      });
    }

    // $(".datatables-ajax").DataTable();
    $(document).ready(function() {
      $(".table").DataTable({
        order: [
          [0, "desc"]
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
                  columns: [0, 1, 2, 3, 4, 7, 8],
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
                  columns: [0, 1, 2, 3, 4, 7, 8],
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
                  columns: [0, 1, 2, 3, 4, 7, 8],
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
                  columns: [0, 1, 2, 3, 4, 7, 8],
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
