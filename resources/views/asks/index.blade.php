@extends('layouts/layoutMaster')

@section('title', trans("global.asks"))

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
			"start" => trans("global.asks"),
			"/" => trans("global.dashboard"),
			"end" => trans("global.all_asks"),
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
								<form action="{{route('asks_delete_all')}}" method="POST" id="form-delete-all" class="position-relative dropdown-item cursor-pointer" tabindex="0" aria-controls="DataTables_Table_0">
									@csrf
									<i class="ti ti-trash ti-sm me-2"></i>
									<input type="text" name="ids" value="" hidden id="ids-delete">{{trans("global.delete")}}
								</form>
							</li>
						</ul>
					</div>
				</div>
				@can("create_asks")
					<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="{{route('ask_create')}}">
						<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span class="d-none d-sm-inline-block">{{trans("global.create_ask")}}</span></span>
					</a>
				@endcan
			</div>
			<table class="datatables-users table border-top">
				<thead>
				<tr>
					<th class="text-start">@lang("global.number")</th>
					<th class="text-start">@lang("global.title")</th>
					<th class="text-start">@lang("global.description")</th>
					<th class="text-start">@lang("global.created_at")</th>
					<th class="text-start">@lang("global.actions")</th>
				</tr>
				</thead>
				<tbody>
				@foreach ($asks as $ask)
					<tr>
						<td class="text-start"><input type="checkbox" data-id="{{$ask->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$ask->id}}</td>
						<td class="text-start"><span class='text-truncate d-flex align-items-center fw-bold badge bg-label-success'>{{$ask["title_" . app()->getLocale()]}}</span></td>
						<td class="text-start ">
							<span class='text-truncate fw-bold badge bg-label-info w-px-200 overflow-hidden m-0' style="max-height: 40px">@php echo $ask["description_" . app()->getLocale()] @endphp</span>
						</td>
						<td class="text-start">
							<span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success '>{{date_format($ask->created_at,"h:i:s Y/m/d A")}}</span>
						</td>
						<td class="text-start">
							<div class="d-flex align-items-center">
								<a href="{{route('ask_edit', $ask->id)}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
								<a href="{{route('ask_delete', $ask->id)}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
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
    $(".table").DataTable();


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