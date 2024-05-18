@extends('layouts/layoutMaster')
@section('title', trans('global.question'))
@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
	@can("export")
		<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
	@endcan
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection
@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.questions"),
			"/" => trans("global.dashboard"),
			"/questions/courses" => trans("global.all_courses"),
			"/questions/course/$course->id/lectures" => $course->name,
			"/questions/course/$course->id/lecture/$lecture->id" => $lecture->title,
			"end" => trans("global.all_question"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="card-header border-bottom">
			<div class="d-flex align-items-center justify-content-between">
				<h5 class="card-title mb-3">{{ trans('global.search_filed') }}</h5>
				<div class="d-flex align-items-center">
					<div class="demo-inline-spacing  me-3">
						<div class="btn-group mt-0">
							<button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown" aria-expanded="false">{{ trans('global.action_select') }}</button>
							<ul class="dropdown-menu">
								<li>
									<form action="{{ route('questions_delete_all', [$course->id, $lecture->id]) }}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
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
					<a class="btn btn-secondary btn-success position-relative " tabindex="0" aria-controls="DataTables_Table_0" href="{{route('questions_export_exel', [$course->id, $lecture->id])}}">
						<span><i class="fa-solid fa-file-arrow-up me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.export_exel")}}</span></span>
					</a>
					<a class="btn btn-secondary btn-primary position-relative mx-4" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('bank_questions_create', [$course->id, $lecture->id])}}">
						<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.bank_question_create")}}</span></span>
					</a>
					<a class="btn btn-secondary btn-primary position-relative " tabindex="0" aria-controls="DataTables_Table_0" href="{{route('question_create', [$course->id, $lecture->id])}}">
						<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.question_create")}}</span></span>
					</a>

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
				<table class="datatables-ajax table ">
					<thead>
					<tr>
						<th class="text-start check-all"><label for="checkbox_delete"><input type="checkbox" id="checkbox_delete" class="form-check-input me-3">@lang("global.number")</label></th>
						<th class="text-start">{{ trans('global.question') }}</th>
						<th class="text-start">{{ trans('global.related') }}</th>
						<th class="text-start">{{ trans('global.created_at') }}</th>
						<th class="text-start">{{ trans('global.actions') }}</th>
					</tr>
					</thead>
					<tbody>
					@foreach ($questions as $question)
						<tr>
							<td class="text-start"><input type="checkbox" data-id="{{$question->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$question->id}}</td>
							{{--							d-flex align-items-center justify-content-center--}}
							<td class="text-start ">
								@if($question->type === "text")
									<span class='text-truncate  fw-bold badge bg-label-success w-100 overflow-hidden m-0 text-center' style="height: 100px">@php echo $question["question_" . app()->getLocale()] @endphp</span>
								@elseif ($question->type === "image")
									<a href="{{asset("images/$question->file")}}" target="_blank" class="avatar avatar-xl d-block ">
										<img src="{{asset("images/$question->file")}}" alt="Avatar" class="rounded-circle object-cover">
									</a>
								@elseif ($question->type === "video")
									<a href="{{asset("images/$question->file")}}" target="_blank" class="avatar avatar-xl d-block ">@lang("global.video")</a>
								@elseif ($question->type === "audio")
									<audio controls class="w-100">
										<source src="{{asset("images/$question->file")}}" type="audio/mpeg">
										Your browser does not support the audio tag.
									</audio>
								@endif
							</td>
							<td class="text-start">@lang("global.$question->related")</td>
							<td class="text-start">{{DATE($question->created_at)}}</td>
							<td class="text-start">
								<div class="d-flex align-items-center">
									<a href="{{route('question_edit', [$course->id, $lecture->id, $question->id])}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
									<a href="{{route('question_delete', [$course->id, $lecture->id, $question->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
								</div>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection
@section('my-script')
	<script>
    $(".datatables-ajax").DataTable({
      order: [[0, "desc"]],
      dom: "<\"card-header flex-column flex-md-row\"<\"head-label text-center\"><\"dt-action-buttons text-end pt-3 pt-md-0\"B>><\"row\"<\"col-sm-12 col-md-6\"l><\"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end\"f>>t<\"row\"<\"col-sm-12 col-md-6\"i><\"col-sm-12 col-md-6\"p>>",
      displayLength: 100,
      language: {
        sLengthMenu: `{{trans("global.show")}}_MENU_`,
        search: `{{trans("global.search")}}`,
        searchPlaceholder: `{{trans("global.search")}}`
      },
      lengthMenu: [7, 10, 25, 50, 75, 100, 1000, 5000, 10000],
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
                columns: [0, 1, 2, 3],
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
                columns: [0, 1, 2, 3],
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
                columns: [0, 1, 2, 3],
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
                columns: [0, 1, 2, 3],
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

      $("body").on("click", ".check-all input", function() {
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
          $(".delete-all").each(function() {
            $(this).prop("checked", false);
          });
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
