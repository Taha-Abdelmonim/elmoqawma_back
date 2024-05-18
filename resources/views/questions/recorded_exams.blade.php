@extends('layouts/layoutMaster')

@section('title', trans("global.all_courses"))

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
			"start" => trans("global.recorded_users"),
			"/" => trans("global.dashboard"),
			"/exams/coursesExams" => trans("global.all_courses"),
			"/exams/coursesExams/$course->id/lectures" => $course->name,
			"/exams/coursesExams/$course->id/lecture/$lecture->id" => $lecture->title,
			"end" => $user->name(),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="col-12  my-5 ">
			<div class="table-responsive">
				<table class="table table-bordered table-striped text-start">
					<thead>
					<tr class="text-nowrap text-start">
						<th class="text-start">@lang("global.number")</th>
						<th class="text-start">@lang("global.score")</th>
						<th class="text-start">@lang("global.correct")</th>
						<th class="text-start">@lang("global.mistake")</th>
						<th class="text-start">@lang("global.result")</th>
					</tr>
					</thead>
					<tbody class="mb-5 service_table text-start">
					@foreach($detailExams as $detailExam)
						<tr>
							<td><a href="{{route('recorded_show', [$course->id, $lecture->id, $user->id, $detailExam->id])}}" class="text-truncate d-flex align-items-center">{{$detailExam->id}}</a></td>
							<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-{{$detailExam->score > env("SCORE") ? 'success': 'danger'}} w-25'>{{$detailExam->score}}</span></td>
							<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success w-25'>{{$detailExam->correct}}</span></td>
							<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-danger w-25'>{{$detailExam->mistake}}</span></td>
							<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-{{$detailExam->score >= env("SCORE") ? 'success': 'danger'}} w-25'>{{$detailExam->score > 70 ? 'ناجح': 'ساقط'}}</span></td>
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
    $(document).ready(function() {
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
                  columns: [0, 1, 2, 3],
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
                  columns: [0, 1, 2, 3],
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
                  columns: [0, 1, 2, 3],
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
                  columns: [0, 1, 2, 3],
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
