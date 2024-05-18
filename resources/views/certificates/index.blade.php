@extends('layouts/layoutMaster')
@section('title', trans('global.certificate'))
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
			"start" => trans("global.certificates"),
			"/" => trans("global.dashboard"),
			"/certificates/courses" => trans("global.all_courses"),
			"/certificates/course/$course->id" => $course->name,
			"end" => trans("global.all_certificate"),
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
									<form action="{{ route('certificates_delete_all', $course->id) }}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
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
					<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('certificate_create', $course->id)}}">
						<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.certificate_create")}}</span></span>
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
						<th class="text-start">{{ trans('global.number') }}</th>
						<th class="text-start">{{ trans('global.user') }}</th>
						<th class="text-start">{{ trans('global.username') }}</th>
						<th class="text-start">{{ trans('PDF') }}</th>
						<th class="text-start">{{ trans('PNG') }}</th>
						<th class="text-start">{{ trans('global.status') }}</th>
						<th class="text-start">{{ trans('global.score') }}</th>
						<th class="text-start">{{ trans('global.course') }}</th>
						<th class="text-start">{{ trans('global.created_at') }}</th>
						<th class="text-start">{{ trans('global.actions') }}</th>
					</tr>
					</thead>
					<tbody>
					@foreach ($certificates as $certificate)
						<tr>
							<td class="text-start"><input type="checkbox" data-id="{{$certificate->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$certificate->id}}</td>
							<td class="text-start"><a href="{{route('user_show', $certificate->user->id)}}" target="_blank">{{ $certificate->user->name() }}</a></td>
							<td class="text-start "><span class='text-truncate  fw-bold badge bg-label-success w-px-200 overflow-hidden m-0'>{{$certificate->username}}</span></td>
							<td class="text-start"><a href="{{ asset("images/certificates/$certificate->file.pdf") }}" target="_blank">@lang("PDF")</a></td>
							<td class="text-start"><a href="{{ asset("images/certificates/$certificate->file.png") }}" target="_blank">@lang("PNG")</a></td>
							<td class="text-start">
								<label class="switch cursor-pointer">
									<input type="checkbox" class="switch-input" onchange="toggle_active_certificate(this, {{$certificate->id}})" @if($certificate->status === 1) checked @endif />
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
							<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-{{$certificate->score >= env("SCORE") ? 'success': 'danger'}} w-75'>{{$certificate->score}}</span></td>
							<td class="text-start">
								@if(!empty($certificate->course))
									<a href="{{route("course_show", $certificate->course->id)}}" target="_blank" class="text-truncate d-flex align-items-center">{{$certificate->course->name}}</a>
								@else
									<p class='text-truncate  fw-bold badge bg-label-danger w-px-100 overflow-hidden m-0'>{{\App\Models\Course::withTrashed()->find($certificate->course_id)["name_" . app()->getLocale()]}}</p>
								@endif
							</td>
							<td class="text-start">{{DATE($certificate->created_at)}}</td>
							<td class="text-start">
								<div class="d-flex align-items-center">
									<a href="{{route('certificate_edit', [$course->id, $certificate->id])}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
									<a href="{{route('certificate_delete', [$course->id, $certificate->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
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

    $(".datatables-ajax").DataTable();

    function toggle_active_certificate(event, $id) {
      let status = event.checked ? 1 : 0;
      $.ajax({
        url: `/certificates/toggleStatus/${$id}`,
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
