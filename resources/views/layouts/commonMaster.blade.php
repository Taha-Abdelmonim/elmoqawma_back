<!DOCTYPE html>
{{--dir="{{ $configData['textDirection'] }}"--}}
<html dir="{{ $configData['textDirection'] }}" lang="{{ session()->get('locale') ?? app()->getLocale() }}"
      class="{{ $configData['style'] }}-style {{ $navbarFixed ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}" data-theme="{{ $configData['theme'] }}"
      data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{url('/')}}" data-framework="laravel" data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">
{{--dir="{{ $configData['textDirection'] }} session()->get('locale') ?? app()->getLocale()"--}}
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

	<title>@yield('title') |
		{{ config('variables.templateName') ? config('variables.templateName') : 'TemplateName' }}</title>
	<meta name="description" content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
	<meta name="keywords" content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
	<!-- laravel CRUD token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />

	<!-- Canonical SEO -->
	<link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
	<!-- Favicon -->
	<link rel="icon" type="image/png" sizes="180x180" href="{{ asset('/logo.png') }}" />


	<!-- Include Styles -->
	@include('layouts/sections/styles')

	<!-- Include Scripts for customizer, helper, analytics, config -->
	@include('layouts/sections/scriptsIncludes')
</head>

<body>

<!-- Layout Content -->
@yield('layoutContent')
<!--/ Layout Content -->
@yield('print')

<!-- Include Scripts -->
@include('layouts/sections/scripts')
@extends("layouts/alert")
@yield("my-style")
@yield("my-script")
@yield("component-script")
@yield("component-style")

<script>
  if (document.documentElement.lang == "ar" && !window.Helpers.isRtl()) {
    window.templateCustomizer.setRtl(true);
  } else if (document.documentElement.lang == "en" && window.Helpers.isRtl()) {
    window.templateCustomizer.setRtl(false);
  }
</script>
</body>

</html>
