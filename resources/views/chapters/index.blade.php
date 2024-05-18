@extends('layouts/layoutMaster')

@section('title', trans("global.chapters"))

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
			"start" => trans("global.chapters"),
			"/" => trans("global.dashboard"),
			"/course/$course->id" => $course->name,
			"end" => trans("global.all_chapters"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="d-flex align-items-center justify-content-end p-4">
			<div class="demo-inline-spacing  me-3">
				<div class="btn-group mt-0">
					<button id="button-actions" type="button" class="btn btn-label-secondary btn-secondary dropdown-toggle disabled" data-bs-toggle="dropdown" aria-expanded="false">{{trans("global.action_select")}}</button>
					<ul class="dropdown-menu">
						<li class='form-delete-all'>
							<form action="{{route('chapters_delete_all', $course->id)}}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
								@csrf
								<i class="ti ti-trash ti-sm me-2"></i>
								<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
							</form>
						</li>
					</ul>
				</div>
			</div>
			@can("create_chapters")
				<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('chapter_create', $course->id)}}">
					<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_chapter")}}</span></span>
				</a>
			@endcan
		</div>
		<div class="card-datatable text-nowrap table-responsive">

			<table class="datatables-ajax table">
				<thead>
				<tr>
					{{--					<th class="text-start"></th>--}}
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.name")</th>
					<th class="text-start">@lang("global.order")</th>
					<th class="text-start">@lang("global.created_at")</th>
					<th class="text-start">@lang("global.actions")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($chapters as $chapter)
					<tr>
						{{--						<td class="text-start"></td>--}}
						<td class="text-start"><input type="checkbox" data-id="{{$chapter->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$chapter->id}}</td>
						<td class="text-start">{{$chapter["name_" . app()->getLocale()]}}</td>
						<td class="text-start">{{$chapter->order}}</td>
						<td class="text-start">{{DATE($chapter->created_at)}}</td>
						<td class="text-start">
							<div class="d-flex align-items-center">
								<a href="{{url("/chapter/$chapter->id/lectures")}}" class="text-body ms-1"><i class="fa-solid fa-clapperboard fa-xl me-2"></i></a>
								<a href="{{route('chapter_edit', [$course->id, $chapter->id])}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
								<a href="{{route('chapter_delete', [$course->id, $chapter->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
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
    $(".datatables-ajax").DataTable();


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
          $(".delete-all").each(function() {
            $(this).prop("checked", false);
          });
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