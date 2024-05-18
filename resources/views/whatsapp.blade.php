@extends('layouts.layoutMaster')

@section('title', trans("global.whatsapp"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
	<!-- Page -->
	<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
@endsection

@section('content')

	<div class="row">
		<div class="col-lg-12 mb-4 mb-lg-0">
			<div class="card h-100">
				<div class="card-header d-flex justify-content-between">
					<h5 class="card-title m-0 me-2">@lang("global.users_export")</h5>
					<label for="exel" class="btn btn-secondary btn-primary position-relative">
						<input id="exel" type="file" name="file" hidden="hidden" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
						<i class="fa-solid fa-file-arrow-up me-0 me-sm-1 ti-xs"></i>
						<span class="d-none d-sm-inline-block">{{ trans('global.import_exel') }}</span>
					</label>
				</div>
				<div class="table-responsive">
					<table class="table table-borderless border-top">
						<thead class="border-bottom">
						<tr>
							<th>@lang("global.name")</th>
							<th>@lang("global.phone")</th>
							<th>@lang("global.section")</th>
							<th>@lang("global.actions")</th>
						</tr>
						</thead>
						<tbody id="data_users">
						@foreach($users as $user)
							<tr>
								<td>{{$user->name}}</td>
								<td>{{$user->phone}}</td>
								<td>{{$user->section}}</td>
								<td colspan="5">
									<div class="d-flex align-items-center gap-3">
										<a href="{{route('whatsapp_delete', $user->id)}}" class="deleteRow btn btn-danger bg-transparent"><i class="fa-solid fa-trash text-danger"></i></a>
									</div>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>

				<hr>
				<div class=" p-5">
					<div class="">
						<label for="message" class="form-label fs-4 font-bold mb-3">@lang("global.message")</label>
						<textarea class="form-control message" name="message" id="message" rows="6">{{old('message')}}</textarea>
					</div>
					<button type="submit" class="btn btn-primary me-sm-3 me-1 mt-3"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.send")</button>
				</div>
			</div>

		</div>

	</div>
@endsection
@section("my-script")
	<script>
    $("body").on("change", "#exel", function() {
      const file = this.files[0];
      if (!file) return;
      let formData = new FormData();
      formData.append("file", file);
      formData.append("_token", "{{ csrf_token() }}");
      $.ajax({
        type: "post",
        processData: false,
        contentType: false,
        cache: false,
        enctype: "multipart/form-data",
        url: "{{ route('whatsapp_import') }}",
        data: formData,
        success: function(data) {
          $("#data_users").html(data);
        },
        error: function({ responseJSON }) {
          showMessage("error", responseJSON.message);
        }
      });
    });
    // $("body").on("click", ".deleteRow", function() {
    //   $(this).parents("tr").remove();
    // });
	</script>
@endsection
