@extends('layouts/layoutMaster')

@section('title', trans("global.coupons"))

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


@section('content')
	@php
		$links = [
			"start" => trans("global.coupons"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_coupons"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-datatable table-responsive">
			<div class="d-flex align-items-center justify-content-end p-4">
				<div class="demo-inline-spacing  me-3">
					<div class="btn-group mt-0">
						<button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown" aria-expanded="false">{{trans("global.action_select")}}</button>
						<ul class="dropdown-menu">
							<li class='form-delete-all'>
								<form action="{{route('coupons_delete_all')}}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
									@csrf
									<i class="ti ti-trash ti-sm me-2"></i>
									<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
								</form>
							</li>
						</ul>
					</div>
				</div>
				@can("show_coupons")
					@if(!$used_coupons)
						<a class="btn btn-secondary btn-danger position-relative me-3" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('coupons', 'used_coupons')}}">
							<span><i class="fa-solid fa-circle-dollar-to-slot me-2"></i><span class="d-none d-sm-inline-block">{{trans("global.used_coupons")}}</span></span>
						</a>
					@endif
				@endcan
				@can("show_coupons")
					@if($used_coupons)
						<a class="btn btn-secondary btn-success position-relative me-3" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('coupons')}}">
							<span><i class="fa-solid fa-circle-dollar-to-slot me-2"></i><span class="d-none d-sm-inline-block">{{trans("global.not_used_coupons")}}</span></span>
						</a>
					@endif
				@endcan
				@can("create_coupons")
					<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('coupon_create')}}">
						<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_coupon")}}</span></span>
					</a>
				@endcan
			</div>
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					@if($used_coupons)
						<th class="text-start">@lang("global.user")</th>
					@endif
					<th class="text-start">@lang("global.code")</th>
					<th class="text-start">@lang("global.type")</th>
					<th class="text-start">@lang("global.lecture")</th>
					<th class="text-start">@lang("global.discount")</th>
					@if($used_coupons)
						<th class="text-start">@lang("global.course") / @lang("global.offer") / @lang("global.wallet")</th>
					@endif
					<th class="text-start">
						@if($used_coupons)
							@lang("global.used_at")
						@else
							@lang("global.created_at")
						@endif
					</th>
					@if(!$used_coupons)
						<th class="text-start">@lang("global.actions")</th>
					@endif
				</tr>
				</thead>
				<tbody>
				@foreach ($coupons as $index => $coupon)
					<tr>
						<td class="text-start"><input type="checkbox" data-id="{{$coupon->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{++$index}}</td>
						@if($used_coupons)
							<td class="text-start ">
								@if(!empty($coupon->user))
									<a href="{{route("user_show", $coupon->user->id)}}" class="text-body text-truncate ">
										<span class="fw-semibold">{{$coupon->user["name_" . app()->getLocale()]}}</span>
									</a>
									<p class="text-muted mb-0 ">{{$coupon->user->email}}</p>
									<a href="https://api.whatsapp.com/send?phone={{$coupon->user->phone}}" target="_blank" class="text-truncate d-flex align-items-center ">{{$coupon->user->phone}}</a>
								@else
									<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>تم حزفة</p>
								@endif
							</td>
						@endif
						<td class="text-start"><span class='d-flex align-items-center justify-content-center fw-bold badge bg-label-success'>{{$coupon->code}}</span></td>
						<td class="text-start"><span class="text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success">@lang("global.".$coupon->type)</span></td>
						<td class="text-start">
							@if($coupon->type === "lecture" && $coupon->lecture)
								{{$coupon->lecture->title}}
							@else
								--
							@endif
						</td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center'>{{$coupon->discount}}</span></td>
						@if($used_coupons and empty($coupon->user))
							<td class="text-start"><p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>لم يستخدم</p></td>
						@endif
						@if($used_coupons and !empty($coupon->user))
							<td class="text-start">
								@if(($coupon->type === 'course' or $coupon->type === 'lecture'))
									@if(!empty($coupon->course))
										<a href="{{route("course_show", $coupon->course->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$coupon->course->name . " " .$coupon->course->subject["name_" . app()->getLocale()]}}</a>
									@else
										<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Course::withTrashed()->find($coupon->course_id)->name}}</p>
									@endif
								@elseif($coupon->type === 'offer')
									@if(!empty($coupon->offer))
										<a href="{{route("offer_edit", $coupon->offer->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$coupon->offer["name_" . app()->getLocale()]}}</a>
									@else
										<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Offer::withTrashed()->find($coupon->offer_id)["name_" . app()->getLocale()]}}</p>
									@endif
								@elseif($coupon->type === 'wallet')
									محفظة
								@endif
							</td>
						@endif
						<td class="text-start">
							<span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success '>
								@if($used_coupons)
									{{date_format($coupon->updated_at,"h:i:s Y/m/d A")}}
								@else
									{{date_format($coupon->created_at,"h:i:s Y/m/d A")}}
								@endif
							</span>
						</td>
						@if(!$used_coupons)
							<td class="text-start">
								<div class="d-flex align-items-center">
									<a href="{{route('coupon_edit', $coupon->id)}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
									<a href="{{route('coupon_delete', $coupon->id)}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
								</div>
							</td>
						@endif
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    let columns = {{$used_coupons ? "[0, 1, 2, 3, 4, 5, 6]" : "[0, 1, 2, 3, 4]"}};
    $(".table").DataTable({
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
                columns: columns,
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
                columns: columns,
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
                columns: columns,
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
                columns: columns,
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
