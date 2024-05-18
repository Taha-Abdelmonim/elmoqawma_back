@extends('layouts/layoutMaster')

@section('title', trans("global.setting_edit"))
@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
@endsection
@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
@endsection
@section('page-script')
	<script src="{{asset('assets/js/forms-editors.js')}}"></script>
@endsection
@section('content')
	@php
		$links = [
			"start" => trans("global.settings"),
				"/" => trans("global.dashboard"),
			"/setting" => trans("global.all_settings"),
			"end" => trans("global.setting_edit"),
	]
	@endphp
	@include("layouts.breadcrumbs")

	<form class="card-body row" action="{{route('setting_update', $setting->id)}}" method="POST" enctype="multipart/form-data">
		@csrf
		<div class="col-12">
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="card mb-4">
				<h5 class="card-header my-0 ">@lang("global.setting_create")</h5>
				<div class="card-body">
					@if($setting->type == "photo")
						<a href="{{$setting->photo != null || $setting->photo != "" ? asset('images/' . $setting->photo) : $setting->id}}" target="_blank" class="avatar w-px-200 h-px-200 d-block mx-auto mb-4">
							@if($setting->photo != null || $setting->photo != "")
								<img src="{{asset('images/' . $setting->photo)}}" alt="Avatar" class="rounded-circle object-cover">
							@else
								<span>@lang("global.not_photo")</span>
							@endif
						</a>
					@endif
					<div class="row">
						<div class="col-6 pe-4">
							<label for="name" class="form-label">@lang("global.name")</label>
							<input type="text" value="{{ old("name", $setting->name) }}" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="@lang("global.setting_name")" aria-describedby="defaultFormControlHelp" />
						</div>
						<div class="col-6">
							<label for="key" class="form-label">@lang("global.key")</label>
							<input readonly type="text" value="{{ old("key", $setting->key) }}" class="form-control @error('key') is-invalid @enderror" id="key" name="key" placeholder="@lang("global.ex") name_site" aria-describedby="defaultFormControlHelp" />
						</div>
						@if($setting->type == "photo")
							<div class="col-6 mt-4">
								<label for="formFile" class="form-label">@lang("global.photo")</label>
								<input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" id="formFile" accept="image/*" />
							</div>
						@endif
						<input type="hidden" name="type" value="{{$setting->type}}" id="type_content">
						@if($setting->type != "photo")
							<div class="col-12 mt-4 p-0  pe-2 ps-2" id="type_content_switch">
								<label for="customRadioTemp1" class="form-label">@lang("global.type")</label>
								<div class="row">
									<div class="col-md">
										<div class="form-check custom-option custom-option-basic">
											<label class="form-check-label custom-option-content py-1" for="type_1">
												<input name="type" class="form-check-input type_input" type="radio" value="text" id="type_1" @if($setting->type == 'text') checked @endif />
												<span class="custom-option-header">
			                <span class="h6 mb-0">Text</span>
			                <i class="fa-solid fa-circle-check text-success"></i>
			              </span>
											</label>
										</div>
									</div>
									<div class="col-md mb-md-0 mb-2">
										<div class="form-check custom-option custom-option-basic">
											<label class="form-check-label custom-option-content py-1" for="type_2">
												<input name="type" class="form-check-input type_input" type="radio" value="html" id="type_2" @if($setting->type == 'html') checked @endif />
												<span class="custom-option-header">
			                <span class="h6 mb-0">Html</span>
			                <i class="fa-solid fa-circle-check text-info"></i>
			              </span>
											</label>
										</div>
									</div>

								</div>
							</div>

							<div class="col-12 " id="text_parent" @if($setting->type == 'html') hidden="hidden" @endif>
								<div class="mt-4">
									<label for="setting_value_ar" class="form-label fs-4 font-bold mb-3">@lang("global.setting_value_ar")</label>
									<textarea class="form-control text_value_ar" @if($setting->type == 'text') name="value_ar" @endif  id="setting_value_ar" rows="6">@if($setting->type == 'text')
											{{old('value_ar', $setting->value_ar)}}
										@endif</textarea>
								</div>
								<div class="mt-4">
									<label for="setting_value_en" class="form-label fs-4 font-bold mb-3">@lang("global.setting_value_en")</label>
									<textarea class="form-control text_value_en" @if($setting->type == 'text') name="value_en" @endif id="setting_value_en" rows="6">@if($setting->type == 'text')
											{{old('value_en', $setting->value_en)}}
										@endif</textarea>
								</div>
							</div>
							<div @if($setting->type == 'text') hidden="hidden" @endif  id="html_parent">
								<div class="col-12 mt-4">
									<div class="card">
										<h5 class="card-header">@lang("global.setting_value_ar")</h5>
										<input type="hidden" class="html_value_ar" id="descriptionQuill" @if($setting->type == 'html') name="value_ar" value="{{old('value_ar', $setting->value_ar)}}" @endif>
										<div class="card-body">
											<div id="full-editor" class="@error('value_aren') is-invalid @enderror">
												@php
													if ($setting->type == 'html') {
														echo old('value_ar', $setting->value_ar);
													}
												@endphp
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 mt-4">
									<div class="card">
										<h5 class="card-header">@lang("global.setting_value_en")</h5>
										<input type="hidden" class="html_value_en" id="descriptionQuill_2" @if($setting->type == 'html') name="value_en" value="{{old('value_en', $setting->value_en)}}" @endif>
										<div class="card-body">
											<div id="full-editor_2" class="@error('value_en') is-invalid @enderror">
												@php
													if ($setting->type == 'html') {
														echo old('value_en', $setting->value_en);
													}
												@endphp
											</div>
										</div>
									</div>
								</div>
							</div>
						@endif
					</div>
					<div class="pt-4">
						<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
					</div>
				</div>
			</div>
		</div>
	</form>
@endsection
@section("my-script")
	<script>
    let index = 0;
    $("body").on("change", "#formFile", function() {
      $("#type_content").val("photo");

      $("#html_parent").remove();
      $("#text_parent").remove();
      $("#type_content_switch").remove();

    });
    $("body").on("change", ".type_input", function() {
      if ($(this).val() == "text") {
        $("#text_parent").attr("hidden", false);
        $(".text_value_ar").attr("name", "value_ar");
        $(".text_value_en").attr("name", "value_en");

        $("#type_content").val("text");

        $("#html_parent").attr("hidden", true);
        $(".html_value_ar").removeAttr("name");
        $(".html_value_en").removeAttr("name");
      } else {
        $("#html_parent").attr("hidden", false);
        $(".html_value_ar").attr("name", "value_ar");
        $(".html_value_en").attr("name", "value_en");

        $("#type_content").val("html");

        $("#text_parent").attr("hidden", true);
        $(".text_value_ar").removeAttr("name");
        $(".text_value_en").removeAttr("name");
      }
    });
	</script>
@endsection