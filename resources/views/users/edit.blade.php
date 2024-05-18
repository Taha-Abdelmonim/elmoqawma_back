@extends('layouts/layoutMaster')

@section('title', trans("global.edit_user"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>

@endsection

@section('page-script')
	<script src="{{asset('assets/js/form-layouts.js')}}"></script>
	<script src="{{asset('assets/js/forms-pickers.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>

@endsection

@section('content')
	@php
		$links = [
			"start" => trans("global.sections"),
			"/" => trans("global.dashboard"),
			"/users" => trans("global.all_users"),
			"#" => trans("global.edit_user"),
			"end" => $user->name(),
]
	@endphp
	@include("layouts.breadcrumbs")
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">@lang("global.edit_user")</h5>
		<form class="card-body" action="{{route('user_update', $user->id)}}" method="POST" enctype="multipart/form-data">
			@csrf
			@if($user->photo != null)
				<a href="{{$user->photo != null ? asset('images/' . $user->photo) : '#'}}" target="_blank" class="avatar w-px-200 h-px-200 d-block mx-auto mb-4">
					<img src="{{$user->photo != null ? asset('images/' . $user->photo) : '#'}}" alt="Avatar" class="rounded-circle object-cover">
				</a>
			@else
				<span>@lang("global.not_photo")</span>
			@endif
			<div class="row g-3">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif


				<div class="col-md-6">
					<label class="form-label " for="name_ar">@lang("global.name_ar")</label>
					<input value="{{ old('name_ar', $user->name_ar) }}" name="name_ar" type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" placeholder="@lang("global.name_ar")" />
				</div>
				<div class="col-md-6">
					<label class="form-label " for="name_en">@lang("global.name_en")</label>
					<input value="{{ old('name_en', $user->name_en) }}" name="name_en" type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror" placeholder="@lang("global.name_en")" />
				</div>
				<div class="col-md-6">
					<label for="select2Multiple" class="form-label">@lang("global.roles")</label>
					<select name="roles_name[]" id="select2Multiple" class="select2 form-select @error('roles_name') is-invalid @enderror" multiple>
						<optgroup label="@lang("global.chose_roles")">
							@foreach($roles as $role)
								@php
									if(!empty($user->roles_name)) {
										 if (in_array($role, $user->roles_name)) {
											 echo "<option value='$role'  selected >$role</option>";
										 } else {
											 echo "<option value='$role'>$role</option>";
										 }
									}else {
										 echo "<option value='$role'>$role</option>";
									 }
								@endphp
							@endforeach
						</optgroup>
					</select>
				</div>
				<div class="col-6">
					<label for="formFile" class="form-label">@lang("global.update_photo")</label>
					<input class="form-control" name="photo" type="file" id="formFile" accept="image/*" />
				</div>
				<div class="col-md-6">
					<label class="form-label " for="email">@lang("global.email")</label>
					{{--<input value="{{ old('email', $user->email) }}" readonly style="background: transparent;border: 0 !important;width: 86%;margin: 0 !important; text-align: end" type="text" name="email" id="email_value"
					       class="focus-none  text-fp2 font-bold me-2 -mb-1" />--}}
					<input value="{{ old('email', $user->email) }}" type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="email" />
				</div>


				<div class="col-md-6">
					<div class="form-password-toggle">
						<label class="form-label" for="multicol-password">@lang("global.password")</label>
						<div class="input-group input-group-merge">
							<input type="password" name="password" id="multicol-password" class="form-control @error('password') is-invalid @enderror" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
							       aria-describedby="multicol-password2" />
							<span class="input-group-text cursor-pointer" id="multicol-password2"><i class="ti ti-eye-off"></i></span>
						</div>
					</div>
				</div>

				<div class="mt-3 col-md-6 pe-2">
					<label for="html5-tel-input" class="col-md-2 col-form-label">@lang("global.phone")</label>
					<input class="form-control  @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" name="phone" type="tel" placeholder="201090844348" id="html5-tel-input" />
				</div>
				<div class="mt-3 col-md-6 ">
					<label for="html5-tel-input" class="col-md-2 col-form-label">@lang("global.phone_parent")</label>
					<input class="form-control  @error('phone_parent') is-invalid @enderror" value="{{ old('phone_parent', $user->phone_parent) }}" name="phone_parent" type="tel" placeholder="201090844348" id="html5-tel-input" />
				</div>
				<div class="col-6 mt-4">
					<label for="balance" class="form-label mb-2">@lang("global.balance")</label>
					<input type="text" value="{{ old("balance", $user->balance) }}" class="form-control @error('balance') is-invalid @enderror" id="balance" name="balance" placeholder="999.00" aria-describedby="defaultFormControlHelp" />
				</div>
				<div class="col-6 mt-4">
					<label for="flatpickr-date" class="form-label py-1">@lang("global.birth")</label>
					<input type="text" value="{{ old("birth", $user->birth) }}" name="birth" class="form-control flatpickr-date" placeholder="YYYY-MM-DD" id="flatpickr-date" dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}" />
				</div>
				<div class="col-6 ">
					<label for="teacher_id" class="form-label">{{ trans('global.coach') }}</label>
					<select id="teacher_id" name="teacher_id" class="select2 form-select form-select-lg teacher_id @error('teacher_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.choose_teacher")</option>
						@foreach ($teachers as $teacher)
							<option value="{{ $teacher->id }}" @if (old('teacher_id', $user->teacher_id) === $teacher->id) selected @endif>{{ $teacher["name_". app()->getLocale()] }}</option>
						@endforeach
					</select>
				</div>
				<div class="card-body col-md-6 p-0 mt-3 pe-2 ps-2">
					<label for="customRadioTemp1" class="form-label">@lang("global.status")</label>
					<div class="row">
						<div class="col-md mb-md-0 mb-2">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content py-sm-2" for="customRadioTemp1">
									<input name="status" @if(old('status', $user->status) == "1") checked @endif class="form-check-input" type="radio" value="1" id="customRadioTemp1" checked />
									<span class="custom-option-header">
                  <span class="h6 mb-0">@lang("global.enabled")</span>
                  <i class="fa-solid fa-circle-check text-success"></i>
                </span>
								</label>
							</div>
						</div>

						<div class="col-md">
							<div class="form-check custom-option custom-option-basic">
								<label class="form-check-label custom-option-content py-sm-2" for="customRadioTemp2">
									<input name="status" @if(old('status', $user->status) == "0") checked @endif class="form-check-input" type="radio" value="0" id="customRadioTemp2" />
									<span class="custom-option-header">
                  <span class="h6 mb-0">@lang("global.not_enabled")</span>
                  <i class="fa-solid fa-unlock text-danger"></i>
                </span>
								</label>
							</div>
						</div>
					</div>
				</div>


				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
				</div>
			</div>
		</form>
	</div>
@endsection

{{--
@section("my-script")
	<script>
    let domain = '{{env("DOMAIN")}}';
    $("#email").on("input", function() {
      let value = $(this).val();
      if (value != "" && /\w/g.test(value)) {
        value = value.match(/\w/g).join("");
        $("#email_value").val(`${value}@${domain}`);
      } else if (value == "") {
        $("#email_value").val(`${value}@${domain}`);
      }
    });
	</script>
@endsection--}}
