@php
	$containerNav = $containerNav ?? 'container-fluid';
	$navbarDetached = ($navbarDetached ?? '');
@endphp

	<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
	<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme"
	     id="layout-navbar">
		@endif
		@if(isset($navbarDetached) && $navbarDetached == '')
			<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
				<div class="{{$containerNav}}">
					@endif

					<!--  Brand demo (display only for navbar-full and hide on below xl) -->
					@if(isset($navbarFull))
						<div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
							<a href="{{url('/')}}" class="app-brand-link gap-2">
			          <span class="app-brand-logo demo">
				          <img src="{{asset('/logo.png')}}">
				          {{--			            @include('layouts.macros',["height"=>20])--}}
			          </span>
								<span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
							</a>
						</div>
					@endif

					<!-- ! Not required for layout-without-menu -->
					@if(!isset($navbarHideToggle))
						<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
							<a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
								<i class="ti ti-menu-2 ti-sm"></i>
							</a>
						</div>
					@endif

					<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

						<!-- Style Switcher -->
						<div class="navbar-nav align-items-center">
							<a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
								<i class='ti ti-sm'></i>
							</a>
						</div>
						<!--/ Style Switcher -->

						<ul class="navbar-nav flex-row align-items-center ms-auto">
							<!-- Language -->
							<li class="nav-item dropdown-language dropdown me-2 me-xl-0">
								<a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
									<i class='fi fi-us fis rounded-circle me-1 fs-3'></i>
								</a>
								<ul class="dropdown-menu dropdown-menu-end">
									<li>
										<a class="dropdown-item" href="{{route('languageConverter', 'en')}}" data-language="en">
											<i class="fi fi-us fis rounded-circle me-1 fs-3"></i>
											<span class="align-middle">{{trans("global.en")}}</span>
										</a>
									</li>
									<li>
										<a class="dropdown-item" href="{{route('languageConverter', 'ar')}}" data-language="ar">
											<i class="fi fi-eg fis rounded-circle me-1 fs-3"></i>
											<span class="align-middle">{{trans("global.ar")}}</span>
										</a>
									</li>

								</ul>
							</li>
							<!-- User -->
							<li class="nav-item navbar-dropdown dropdown-user dropdown">
								<a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
								   data-bs-toggle="dropdown">
									<div class="avatar avatar-online">
										<img src="{{ Auth::user()->photo != null ? asset("images/" . Auth::user()->photo) : asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle">
									</div>
								</a>
								<ul class="dropdown-menu dropdown-menu-end">
									<li>
										<a class="dropdown-item"
										   href="{{route('user_edit', Auth::user()->id)}}">
											<div class="d-flex">
												<div class="flex-shrink-0 me-3">
													<div class="avatar avatar-online">

														<img src="{{ Auth::user()->photo != null ? asset("images/" . Auth::user()->photo) : asset('assets/img/avatars/1.png') }}"
														     alt class="w-px-40 h-auto rounded-circle">
													</div>
												</div>
												<div class="flex-grow-1">
                          <span class="fw-semibold d-block">
                            @if (Auth::check())
		                          {{ Auth::user()->name() }}
	                          @else
		                          John Doe
	                          @endif
                          </span>
													<small class="text-muted">
														@if(isset(Auth::user()->roles_name))
															@if(count(Auth::user()->roles_name) > 1)
																@foreach(Auth::user()->roles_name as $role)
																	,{{$role}}
																@endforeach
															@else
																{{Auth::user()->roles_name[0]}}
															@endif
														@endif
													</small>
												</div>
											</div>
										</a>
									</li>
									<li>
										<div class="dropdown-divider"></div>
									</li>
									<li>
										<a class="dropdown-item"
										   href="{{route('user_show', Auth::user()->id)}}">
											<i class="ti ti-user-check me-2 ti-sm"></i>
											<span class="align-middle">My Profile</span>
										</a>
									</li>

									{{--<li>
											<a class="dropdown-item" href="javascript:void(0);">
												<span class="d-flex align-items-center align-middle">
													<i class="flex-shrink-0 ti ti-credit-card me-2 ti-sm"></i>
													<span class="flex-grow-1 align-middle">Billing</span>
													<span class="flex-shrink-0 badge badge-center rounded-pill bg-label-danger w-px-20 h-px-20">2</span>
												</span>
											</a>
									</li>--}}

									<li>
										<div class="dropdown-divider"></div>
									</li>
									@if (Auth::check())
										<li>
											<a class="dropdown-item" href="{{ route('logout') }}"
											   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
												<i class='ti ti-logout me-2'></i>
												<span class="align-middle">Logout</span>
											</a>
										</li>
										<form method="POST" id="logout-form" action="{{ route('logout') }}">
											@csrf
										</form>
									@else
										<li>
											<a class="dropdown-item" href="{{ route('login') }}">
												<i class='ti ti-login me-2'></i>
												<span class="align-middle">Login</span>
											</a>
										</li>
									@endif

								</ul>

							</li>

						</ul>

					</div>

					@if(!isset($navbarDetached))
				</div>
				@endif
			</nav>
			<!-- / Navbar -->
