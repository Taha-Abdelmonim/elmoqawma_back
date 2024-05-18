@php
	$customizerHidden = 'customizer-hide';
	$configData = Helper::appClasses();
@endphp

@extends('layouts.layoutMaster')

@section('title', trans("global.login"))

@section('vendor-style')
	<!-- Vendor -->
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
@endsection

@section('page-style')
	<!-- Page -->
	<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
	<div class="authentication-wrapper authentication-cover authentication-bg">
		<div class="authentication-inner row">
			<!-- Login -->
			<div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-0">
				<div class="w-px-500 mx-auto">
					<!-- Logo -->
					<div class="app-brand mb-4">
						<a href="{{url('/')}}" class="app-brand-link gap-2">
              <span
	              class="app-brand-logo demo">@include('layouts.macros',["height"=>20,"withbg"=>'fill: #fff;'])</span>
						</a>
					</div>
					<!-- /Logo -->
					<h3 class=" mb-1 fw-bold">@lang("global.welcome") @lang("global.site_name") 👋</h3>
					<p class="mb-4">@lang("global.all_reversed")</p>

					<form id="formAuthentication" class="mb-3" action="{{route('check_login')}}" method="POST">
						@csrf
						<div class="mb-3">
							<div class="d-flex justify-content-between items-center">
								<label for="email" class="form-label"> {{trans("global.email")}}</label>
								{{--								<input readonly style="background: transparent;border: 0 !important;width: 80%;margin: 0 !important; text-align: end" type="text" name="email" id="email_value" class="focus-none  text-fp2 font-bold me-2 -mb-1" />--}}
							</div>
							<div class="mb-3" style=" position: relative;">
								<div style="position: absolute; top: 0; bottom: 0; left: 0; display: flex; align-items: center; pointer-events: none; height: 38px" class="text-bg-primary p-3 rounded-none">
									<i class="fa-solid fa-envelope text-start text-2xl"></i>
								</div>
								<input
									id="email"
									name="email"
									type="email"
									class="form-control rounded-0 @error('email') is-invalid @enderror"
									placeholder="email"
									value="{{old('email')}}"
									autofocus
									required
								/>
							</div>
						</div>

						{{--@error('email')
						<div class="fv-plugins-message-container invalid-feedback">
							<div data-field="password" data-validator="stringLength">{{$message}}</div>
						</div>
						@enderror--}}
						<div class="mb-3 form-password-toggle">
							<div class="d-flex justify-content-between">
								<label class="form-label" for="password"> {{trans("global.password")}}</label>
								<a href="{{url('auth/forgot-password-cover')}}">
									<small>{{trans("global.forget_password")}} </small>
								</a>
							</div>
							<div class="input-group input-group-merge">
								<input style="    border-left: 0 !important;border-radius: 0 !important;" type="password" id="password"
								       class="form-control border rounded-3 pe-2 @error('password') is-invalid @enderror"
								       name="password"
								       placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
								       aria-describedby="password" />
								<span style="border-radius: 0; border-right: 0 !important;" class="input-group-text cursor-pointer border text-bg-primary"><i class="ti ti-eye-off"></i></span>
							</div>
							@error('password')
							<div class="fv-plugins-message-container invalid-feedback">
								<div data-field="password" data-validator="stringLength">{{$message}}</div>
							</div>
							@enderror
						</div>
						<div class="mb-3">
							<div class="form-check">
								<input @if(old('remember_token')) checked @endif class="form-check-input"
								       type="checkbox" id="remember-me" name="remember_token">
								<label class="form-check-label" for="remember-me">
									{{trans("global.remember")}}
								</label>
							</div>
						</div>
						<button class="btn btn-primary d-grid w-100">
							{{trans("global.login")}}
						</button>
					</form>

					{{--<p class="text-center">
							<span>New on our platform?</span>
							<a href="{{url('auth/register-cover')}}">
									<span>Create an account</span>
							</a>
					</p>

					<div class="divider my-4">
							<div class="divider-text">or</div>
					</div>

					<div class="d-flex justify-content-center">
							<a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
									<i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
							</a>

							<a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
									<i class="tf-icons fa-brands fa-google fs-5"></i>
							</a>

							<a href="javascript:;" class="btn btn-icon btn-label-twitter">
									<i class="tf-icons fa-brands fa-twitter fs-5"></i>
							</a>
					</div>
				--}}
				</div>
			</div>
			<!-- /Login -->

			<div class="d-none d-lg-flex col-lg-7 p-0">
				<div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
					<img src="{{ asset('assets/img/illustrations/auth-login-illustration-'.$configData['style'].'.png') }}"
					     alt="auth-login-cover" class="img-fluid my-5 auth-illustration"
					     data-app-light-img="illustrations/auth-login-illustration-light.png"
					     data-app-dark-img="illustrations/auth-login-illustration-dark.svg">

					<img src="{{ asset('assets/img/illustrations/bg-shape-image-'.$configData['style'].'.png') }}"
					     alt="auth-login-cover" class="platform-bg"
					     data-app-light-img="illustrations/bg-shape-image-light.png"
					     data-app-dark-img="illustrations/bg-shape-image-dark.png">
				</div>
			</div>
		</div>
	</div>
@endsection

{{--@section("my-script")
	<script>
    let domain = '{{env("DOMAIN")}}';
    $("#email_value").val(`${domain}@`);
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
