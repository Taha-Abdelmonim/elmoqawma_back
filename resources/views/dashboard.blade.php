@extends('layouts.layoutMaster')

@section('title', trans("global.dashboard"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
@endsection

@section('page-style')
	<!-- Page -->
	<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-advance.css')}}">
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/chartjs/chartjs.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/dashboards-crm.js')}}"></script>
	<script src="{{asset('assets/js/dashboards-analytics.js')}}"></script>
@endsection

@section('content')

	<div class="row">
		@can("dashboard")
			<!-- Website Analytics -->
			<div class="col-lg-6 mb-4">
				<div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg h-100"
				     id="swiper-with-pagination-cards">
					<div class="swiper-wrapper">
						<div class="swiper-slide">
							<div class="row">
								<div class="col-12">
									<h5 class="text-white mb-0 mt-2">@lang("global.analysis_site")</h5>
									<small>@lang("global.lorem")</small>
								</div>
								<div class="row">
									<div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
										<h6 class="text-white mt-0 mt-md-3 mb-3">Traffic</h6>
										<div class="row">
											<div class="col-6">
												<ul class="list-unstyled mb-0">
													<li class="d-flex mb-4 align-items-center">
														<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">28%</p>
														<p class="mb-0">Sessions</p>
													</li>
													<li class="d-flex align-items-center mb-2">
														<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
														<p class="mb-0">Leads</p>
													</li>
												</ul>
											</div>
											<div class="col-6">
												<ul class="list-unstyled mb-0">
													<li class="d-flex mb-4 align-items-center">
														<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">3.1k</p>
														<p class="mb-0">Page Views</p>
													</li>
													<li class="d-flex align-items-center mb-2">
														<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12%</p>
														<p class="mb-0">Conversions</p>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
										<img src="{{asset('assets/img/illustrations/card-website-analytics-1.png')}}"
										     alt="Website Analytics" width="170" class="card-website-analytics-img">
									</div>
								</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="row">
								<div class="col-12">
									<h5 class="text-white mb-0 mt-2">Website Analytics</h5>
									<small>Total 28.5% Conversion Rate</small>
								</div>
								<div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
									<h6 class="text-white mt-0 mt-md-3 mb-3">Spending</h6>
									<div class="row">
										<div class="col-6">
											<ul class="list-unstyled mb-0">
												<li class="d-flex mb-4 align-items-center">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12h</p>
													<p class="mb-0">Spend</p>
												</li>
												<li class="d-flex align-items-center mb-2">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">127</p>
													<p class="mb-0">Order</p>
												</li>
											</ul>
										</div>
										<div class="col-6">
											<ul class="list-unstyled mb-0">
												<li class="d-flex mb-4 align-items-center">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">18</p>
													<p class="mb-0">Order Size</p>
												</li>
												<li class="d-flex align-items-center mb-2">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">2.3k</p>
													<p class="mb-0">Items</p>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
									<img src="{{asset('assets/img/illustrations/card-website-analytics-2.png')}}"
									     alt="Website Analytics" width="170" class="card-website-analytics-img">
								</div>
							</div>
						</div>
						<div class="swiper-slide">
							<div class="row">
								<div class="col-12">
									<h5 class="text-white mb-0 mt-2">Website Analytics</h5>
									<small>Total 28.5% Conversion Rate</small>
								</div>
								<div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
									<h6 class="text-white mt-0 mt-md-3 mb-3">Revenue Sources</h6>
									<div class="row">
										<div class="col-6">
											<ul class="list-unstyled mb-0">
												<li class="d-flex mb-4 align-items-center">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">268</p>
													<p class="mb-0">Direct</p>
												</li>
												<li class="d-flex align-items-center mb-2">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">62</p>
													<p class="mb-0">Referral</p>
												</li>
											</ul>
										</div>
										<div class="col-6">
											<ul class="list-unstyled mb-0">
												<li class="d-flex mb-4 align-items-center">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">890</p>
													<p class="mb-0">Organic</p>
												</li>
												<li class="d-flex align-items-center mb-2">
													<p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
													<p class="mb-0">Campaign</p>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
									<img src="{{asset('assets/img/illustrations/card-website-analytics-3.png')}}"
									     alt="Website Analytics" width="170" class="card-website-analytics-img">
								</div>
							</div>
						</div>
					</div>
					<div class="swiper-pagination"></div>
				</div>
			</div>
			<!--/ Website Analytics -->
		@endcan
		@can("show_supports")
			<!-- Earning Reports -->
			<div class="col-lg-6 mb-4">
				<div class="card h-100">
					<div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
						<div class="card-title mb-0">
							<h5 class="mb-0">@lang("global.reports_contact")</h5>
							<small class="text-muted">@lang("global.access_only_admin")</small>
						</div>

					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-md-4 d-flex flex-column align-self-end">
								<div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
									<h1 class="mb-0">{{$supports_count}}</h1>
									<div class="badge rounded bg-label-success">@lang("global.count_message")</div>
								</div>
								<small class="text-muted">@lang("global.reports_messages")</small>
							</div>
							<div class="col-12 col-md-8">
								<div id="weeklyEarningReports"></div>
							</div>
						</div>
						<div class="border rounded p-3 mt-2">
							<div class="row gap-4 gap-sm-0">
								<div class="col-12 col-sm-4">
									<div class="d-flex gap-2 align-items-center">
										<div class="badge rounded bg-label-primary p-1"><i
												class="ti ti-currency-dollar ti-sm"></i></div>
										<h6 class="mb-0">@lang("global.support_done_problem")</h6>
									</div>
									<h4 class="my-2 pt-1">{{$supports_done_problem}}</h4>
									<div class="progress w-75" style="height:4px">
										<div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65"
										     aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
								<div class="col-12 col-sm-4">
									<div class="d-flex gap-2 align-items-center">
										<div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i>
										</div>
										<h6 class="mb-0">@lang("global.new_messages")</h6>
									</div>
									<h4 class="my-2 pt-1">{{$supports_unread}}</h4>
									<div class="progress w-75" style="height:4px">
										<div class="progress-bar bg-info" role="progressbar" style="width: 50%"
										     aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
								<div class="col-12 col-sm-4">
									<div class="d-flex gap-2 align-items-center">
										<div class="badge rounded bg-label-danger p-1"><i
												class="ti ti-ban ti-sm"></i></div>
										<h6 class="mb-0">@lang("global.support_un_read")</h6>
									</div>
									<h4 class="my-2 pt-1">{{$supports_deleted}}</h4>
									<div class="progress w-75" style="height:4px">
										<div class="progress-bar bg-danger" role="progressbar" style="width: 65%"
										     aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endcan
		{{-- Cards --}}
		@can("show_expenses")
			<div class="col-xl-3 col-md-4">
				<div class="card bg-danger text-white mb-4">
					<div class="card-body">@lang("global.expense_today")
						<h5 class="mt-3">{{ $totalExpense }} EGP</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between">
						<a class="small text-white stretched-link" target="_blank" href="{{route('expenses_details')}}">@lang("global.view_details")</a>
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
		@endcan
		@can("show_banks")
			<div class="col-xl-3 col-md-4">
				<div class="card bg-success text-white mb-4">
					<div class="card-body">@lang("global.push_bank_today")
						<h5 class="mt-3 text-white">{{ $pushBankTransactions }} EGP</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between">
						<a class="small text-white stretched-link" target="_blank" href="{{route('bank_transactions')}}">@lang("global.view_details")</a>
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-4">
				<div class="card bg-primary text-white mb-4">
					<div class="card-body">@lang("global.pull_bank_today")
						<h5 class="mt-3 text-white">{{ $pullBankTransactions }} EGP</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between">
						<a class="small text-white stretched-link" target="_blank" href="{{route('bank_transactions')}}">@lang("global.view_details")</a>
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-4">
				<div class="card bg-warning text-white mb-4">
					<div class="card-body">@lang("global.difference_bank_today")
						<h5 class="mt-3 text-white">{{ $totalBankTransactions }} EGP</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between">
						<a class="small text-white stretched-link" target="_blank" href="{{route('bank_transactions')}}">@lang("global.view_details")</a>
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
		@endcan
		@can("show_orders")

			<div class="col-xl-3 col-md-4">
				<div class="card bg-info text-white mb-4">
					<div class="card-body">@lang("global.orders_count_today")
						<h5 class="mt-3 text-white">{{ $orders->count() }}</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between">
						{{--						<a class="small text-white stretched-link" target="_blank" href="{{route('courses_all_orders')}}">@lang("global.view_details")</a>--}}
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-4">
				<div class="card bg-dark text-black mb-4">
					<div class="card-body text-black">@lang("global.orders_total_today")
						<h5 class="mt-3 text-black">{{ $totalOrders }} EGP</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between text-black">
						{{--						<a class="small text-black stretched-link " target="_blank" href="{{route('courses_all_orders')}}">@lang("global.view_details")</a>--}}
						<div class="small text-black"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-4">
				<div class="card bg-success text-white mb-4">
					<div class="card-body text-white">@lang("global.orders_active")
						<h5 class="mt-3 text-white">{{ $ordersActive }}</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between text-white">
						{{--						<a class="small text-white stretched-link " target="_blank" href="{{route('courses_all_orders')}}">@lang("global.view_details")</a>--}}
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
			<div class="col-xl-3 col-md-4">
				<div class="card bg-danger text-white mb-4">
					<div class="card-body text-white">@lang("global.orders_in_active")
						<h5 class="mt-3 text-white">{{ $ordersInActive }}</h5>
					</div>
					<div class="card-footer d-flex align-items-center justify-content-between text-white">
						{{--						<a class="small text-white stretched-link " target="_blank" href="{{route('courses_all_orders')}}">@lang("global.view_details")</a>--}}
						<div class="small text-white"><i class="fas fa-angle-right"></i></div>
					</div>
				</div>
			</div>
		@endcan
		{{--      charts ==============================================================--}}


		@can("show_roles")
			<!-- Sessions Last month -->
			<div class="col-xl-2 col-md-4 col-6 mb-4">
				<div class="card">
					<div class="card-header pb-0">
						<h5 class="card-title mb-0">@lang("global.roles")</h5>
						<small class="text-muted">@lang("global.last_week")</small>
					</div>
					<div class="card-body">
						<div id="sessionsLastMonth"></div>
						<div class="d-flex justify-content-between align-items-center mt-3 gap-3">
							<h4 class="mb-0">{{$roles_count}}</h4>
							<small class="text-success">+12.6%</small>
						</div>
					</div>
				</div>
			</div>

			<!-- Total Profit -->
			<div class="col-xl-2 col-md-4 col-6 mb-4">
				<div class="card">
					<div class="card-body">
						<div class="badge p-2 bg-label-danger mb-2 rounded"><i class="ti ti-currency-dollar ti-md"></i></div>
						<h5 class="card-title mb-1 pt-2">@lang("global.permissions")</h5>
						<small class="text-muted">@lang("global.last_week")</small>
						<p class="mb-2 mt-1">{{$permissions_count}}</p>
						<div class="pt-1">
							<span class="badge bg-label-secondary">{{$permissions_count}}</span>
						</div>
					</div>
				</div>
			</div>
		@endcan
		@can("show_sections")
			<!-- Total Sales -->
			<div class="col-xl-2 col-md-4 col-6 mb-4">
				<div class="card">
					<div class="card-body">
						<div class="badge p-2 bg-label-info mb-2 rounded"><i class="ti ti-chart-bar ti-md"></i></div>
						<h5 class="card-title mb-1 pt-2">@lang("global.sections")</h5>
						<small class="text-muted">@lang("global.last_week")</small>
						<p class="mb-2 mt-1">{{$sections_count}}</p>
						<div class="pt-1">
							<span class="badge bg-label-secondary">{{$sections_count}}</span>
						</div>
					</div>
				</div>
			</div>
		@endcan
		@can("show_reviews")
			<!-- Total Profit -->
			<div class="col-xl-2 col-md-4 col-6 mb-4">
				<div class="card">
					<div class="card-body">
						<div class="badge p-2 bg-label-danger mb-2 rounded"><i class="ti ti-currency-dollar ti-md"></i></div>
						<h5 class="card-title mb-1 pt-2">@lang("global.reviews")</h5>
						<small class="text-muted">@lang("global.last_week")</small>
						<p class="mb-2 mt-1">{{$review_count}}</p>
						<div class="pt-1">
							<span class="badge bg-label-secondary">{{$review_count}}</span>
						</div>
					</div>
				</div>
			</div>
		@endcan
		@can("show_comments")

			<!-- Total Sales -->
			<div class="col-xl-2 col-md-4 col-6 mb-4">
				<div class="card">
					<div class="card-body">
						<div class="badge p-2 bg-label-info mb-2 rounded"><i class="ti ti-chart-bar ti-md"></i></div>
						<h5 class="card-title mb-1 pt-2">@lang("global.comments")</h5>
						<small class="text-muted">@lang("global.last_week")</small>
						<p class="mb-2 mt-1">{{$comment_count}}</p>
						<div class="pt-1">
							<span class="badge bg-label-secondary">{{$comment_count}}</span>
						</div>
					</div>
				</div>
			</div>
		@endcan
		@can("show_sections")
			<!-- Total Sales -->
			<div class="col-xl-2 col-md-4 col-6 mb-4">
				<div class="card">
					<div class="card-body">
						<div class="badge p-2 bg-label-info mb-2 rounded"><i class="ti ti-chart-bar ti-md"></i></div>
						<h5 class="card-title mb-1 pt-2">@lang("global.main_sections")</h5>
						<small class="text-muted">@lang("global.last_week")</small>
						<p class="mb-2 mt-1">{{$sections_count_active}}</p>
						<div class="pt-1">
							<span class="badge bg-label-secondary">{{$sections_count_active}}</span>
						</div>
					</div>
				</div>
			</div>
		@endcan

		<div class=" d-flex justify-content-between">
			@can("show_users")
				<!-- Sales last year -->
				<div class="col-xl-2 col-md-4 col-6 mb-4">
					<div class="card">
						<div class="card-header pb-0">
							<h5 class="card-title mb-0">@lang("global.users")</h5>
							<small class="text-muted">@lang("global.last_week")</small>
						</div>
						<div id="salesLastYear"></div>
						<div class="card-body pt-0">
							<div class="d-flex justify-content-between align-items-center mt-3 gap-3">
								<h4 class="mb-0">{{$users_count}}</h4>
								<small class="text-danger">-16.2%</small>
							</div>
						</div>
					</div>
				</div>
			@endcan
			@can("show_settings")
				<!-- Sales Overview -->
				<div class="col-lg-3 col-sm-6 mb-4">
					<div class="card">
						<div class="card-header">
							<div class="d-flex justify-content-between">
								{{-- <small class="d-block mb-1 text-muted">Sales Overview</small> --}}
								<p class="card-text text-success">@lang("global.access_only_admin")</p>
							</div>
							<h4 class="card-title mb-1">@lang("global.setting_site")</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-4">
									<div class="d-flex gap-2 align-items-center mb-2">
										<span class="badge bg-label-info p-1 rounded"><i class="ti ti-shopping-cart ti-xs"></i></span>
										<p class="mb-0">@lang("global.keys")</p>
									</div>
									<h5 class="mb-0 pt-1 text-nowrap">{{$settings_count}}</h5>
									{{-- <small class="text-muted">6,440</small> --}}
								</div>
								<div class="col-4">
									<div class="divider divider-vertical">
										<div class="divider-text">
											<span class="badge-divider-bg bg-label-secondary">و</span>
										</div>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="d-flex gap-2 justify-content-end align-items-center mb-2">
										<p class="mb-0">@lang("global.value")</p>
										<span class="badge bg-label-primary p-1 rounded"><i class="ti ti-link ti-xs"></i></span>
									</div>
									<h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">{{$settings_count}}</h5>
									{{-- <small class="text-muted">12,749</small> --}}
								</div>
							</div>
							<div class="d-flex align-items-center mt-4">
								<div class="progress w-100" style="height: 8px;">
									<div class="progress-bar bg-info" style="width: 70%" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-primary" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endcan
			@can("show_supports")

				<div class="col-lg-3 col-sm-6 mb-4">
					<div class="card">
						<div class="card-header">
							<div class="d-flex justify-content-between">
								{{-- <small class="d-block mb-1 text-muted">Sales Overview</small> --}}
								<p class="card-text text-success">@lang("global.access_only_admin")</p>
							</div>
							<h4 class="card-title mb-1">@lang("global.contact_us")</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-4">
									<div class="d-flex gap-2 align-items-center mb-2">
										<span class="badge bg-label-info p-1 rounded"><i class="ti ti-shopping-cart ti-xs"></i></span>
										<small class="mb-0" style="white-space: nowrap">@lang("global.support_done_contact")</small>
									</div>
									<h5 class="mb-0 pt-1 text-nowrap">{{$supports_done_contact}}</h5>
									{{-- <small class="text-muted">6,440</small> --}}
								</div>
								<div class="col-4">
									<div class="divider divider-vertical">
										<div class="divider-text">
											<span class="badge-divider-bg bg-label-secondary">و</span>
										</div>
									</div>
								</div>
								<div class="col-4 text-end">
									<div class="d-flex gap-2 justify-content-end align-items-center mb-2">
										<small style="white-space: nowrap" class="mb-0">@lang("global.support_un_contact")</small>
										<span class="badge bg-label-primary p-1 rounded"><i class="ti ti-link ti-xs"></i></span>
									</div>
									<h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">{{$supports_uncontact}}</h5>
									{{-- <small class="text-muted">12,749</small> --}}
								</div>
							</div>
							<div class="d-flex align-items-center mt-4">
								<div class="progress w-100" style="height: 8px;">
									<div class="progress-bar bg-info" style="width: 70%" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
									<div class="progress-bar bg-primary" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endcan
		</div>
		@can("show_banks")
			<div>
				<canvas id="myChart" width="400" height="150"></canvas>
			</div>
		@endcan


		{{-- ============================= latest =================================================== --}}
		@can("show_users")
			<!-- Last users -->
			<div class="col-lg-6 mb-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.users")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.name")</th>
								<th>@lang("global.email")</th>
								<th>@lang("global.status")</th>
								<th>@lang("global.register_from")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_users as $user)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">
											<div class="me-3">
												<img src="{{ $user->photo != null ? asset('images/' . $user->photo) : asset('assets/img/avatars/1.png') }}" alt="Visa" height="30">
											</div>
											<div class="d-flex flex-column">
												<a href="{{route('user_show', $user->id)}}" class="mb-0 fw-semibold">{{ $user->name() }}</a><small class="text-muted">{{$user->id}}</small>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<p class="mb-0 fw-semibold">{{$user->email}}</p>
											<small class="text-muted text-nowrap">{{$user->created_at}}</small>
										</div>
									</td>
									{{-- danger --}}
									<td><span class="badge bg-label-{{$user->status == 1 ? 'success' : 'danger'}}">{{$user->status == 1 ? trans("global.enabled") : trans("global.not_enabled")}}</span></td>
									<td>
										<p class="mb-0 fw-semibold">{{$user->oauth_type != null ? $user->oauth_type : trans("global.site")}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
		@can("show_roles")
			<!-- Last roles -->
			<div class="col-lg-6 mb-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.roles")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.name")</th>
								<th>@lang("global.permissions")</th>
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_roles as $role)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">

											<div class="d-flex flex-column">
												<span class="mb-0 fw-semibold">{{$role->id}}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<p class="mb-0 fw-semibold">{{$role->name}}</p>
										</div>
									</td>
									{{-- danger --}}
									<td><span class="badge bg-label-success">{{$role->guard_name}}</span></td>
									<td>
										<p class="mb-0 fw-semibold">{{$role->created_at}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Last roles -->
			<div class="col-lg-6 mb-4 mt-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.permissions")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.name")</th>
								<th>@lang("global.permissions")</th>
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_permissions as $permission)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">

											<div class="d-flex flex-column">
												<span class="mb-0 fw-semibold">{{$permission->id}}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<p class="mb-0 fw-semibold">{{$permission->name}}</p>
										</div>
									</td>
									{{-- danger --}}
									<td><span class="badge bg-label-success">{{$permission->guard_name}}</span></td>
									<td>
										<p class="mb-0 fw-semibold">{{$permission->created_at}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
		@can("show_sections")
			<!-- Last sections -->
			<div class="col-lg-6 mb-4 mt-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.sections")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.name")</th>
								{{--              <th>@lang("global.url")</th>--}}
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_sections as $section)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">

											<div class="d-flex flex-column">
												<span class="mb-0 fw-semibold">{{$section->id}}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<a href="#" class="mb-0 fw-semibold">{{$section->name_ar}}</a>
										</div>
									</td>
									{{-- danger --}}
									{{--                <td><span class="badge bg-label-success">{{$section->url}}</span></td>--}}
									<td>
										<p class="mb-0 fw-semibold">{{$section->created_at}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
		@can("show_comments")

			<!-- Last comments -->
			<div class="col-lg-6 mb-4 mt-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.comments")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.comment")</th>
								<th>@lang("global.course")</th>
								<th>@lang("global.status")</th>
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@if (count($latest_comments))
								@foreach ($latest_comments as $comment)
									<tr>
										<td>
											<div class="d-flex justify-content-start align-items-center">
												<div class="d-flex flex-column">
													<span class="mb-0 fw-semibold">{{$comment->id}}</span>
												</div>
											</div>
										</td>
										<td>
											<div class="d-flex flex-column">
												<a href="{{ route('comment_show', $comment->id)}}" class="mb-0 fw-semibold fs-6 whitespace-nowrap my-fs-small">{{ mb_strimwidth($comment->comment, 0, 14, ' ...') }}</a>
											</div>
										</td>
										<td>
											<div class="d-flex flex-column">
												@if(!empty($comment->course))
													<a href="{{ route('course_show', $comment->course_id )}}" class="mb-0 fw-semibold whitespace-nowrap my-fs-small">{{$comment->course->name_ar}}</a>
												@else
													--
												@endif
											</div>
										</td>
										{{-- danger --}}
										<td><span class="badge bg-label-{{$comment->status == 1 ? 'success' : 'danger'}}">{{$comment->status == 1 ? 'مفعل' : 'غير مفعل'}}</span></td>
										<td>
											<p class="mb-0 fw-semibold my-fs-small">{{$comment->created_at}}</p>
										</td>
									</tr>
								@endforeach
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
		@can("show_reviews")
			<!-- Last reviews -->
			<div class="col-lg-6 mb-4 mt-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.reviews")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.comment")</th>
								<th>@lang("global.section")</th>
								<th>@lang("global.status")</th>
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_reviews as $review)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">
											<div class="d-flex flex-column">
												<span class="mb-0 fw-semibold">{{$review->id}}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<a href="{{ route('review_show', $review->id)}}" class="mb-0 fw-semibold fs-6 whitespace-nowrap my-fs-small">{{ mb_strimwidth($review->comment, 0, 16, ' ...')}}</a>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<a href="/{{$review->section->url}}" class="mb-0 fw-semibold whitespace-nowrap my-fs-small">{{$review->section->name_ar}}</a>
										</div>
									</td>
									{{-- danger --}}
									<td><span class="badge bg-label-{{$review->status == 1 ? 'success' : 'danger'}}">{{$review->status == 1 ? 'مفعل' : 'غير مفعل'}}</span></td>
									<td>
										<p class="mb-0 fw-semibold my-fs-small">{{$review->created_at}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
		@can("show_settings")
			<!-- Last settings -->
			<div class="col-lg-6 mb-4 mt-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.setting")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.name")</th>
								<th>@lang("global.value")</th>
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_settings as $setting)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">
											<div class="d-flex flex-column">
												<span class="mb-0 fw-semibold">{{$setting->id}}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<a href="{{ route('setting_show', $setting->id)}}" class="mb-0 fw-semibold fs-6 whitespace-nowrap my-fs-small">{{$setting->name}}</a>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<p class="mb-0 fw-semibold whitespace-nowrap my-fs-small"> {{ mb_strimwidth($setting->value_ar, 0, 20, ' ...')}}</p>
										</div>
									</td>
									{{-- danger --}}
									<td>
										<p class="mb-0 fw-semibold my-fs-small">{{$setting->created_at}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
		@can("show_supports")
			<!-- Last supports -->
			<div class="col-lg-6 mb-4 mt-4 mb-lg-0">
				<div class="card h-100">
					<div class="card-header d-flex justify-content-between">
						<h5 class="card-title m-0 me-2">@lang("global.latest") {{$limit}} @lang("global.messages_contact")</h5>
					</div>
					<div class="table-responsive">
						<table class="table table-borderless border-top">
							<thead class="border-bottom">
							<tr>
								<th>@lang("global.number")</th>
								<th>@lang("global.title")</th>
								<th>@lang("global.contact_messages")</th>
								<th>@lang("global.created_at")</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($latest_supports as $support)
								<tr>
									<td>
										<div class="d-flex justify-content-start align-items-center">
											<div class="d-flex flex-column">
												<span class="mb-0 fw-semibold">{{$support->id}}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<a href="{{ route('supports')}}" class="mb-0 fw-semibold fs-6 whitespace-nowrap my-fs-small">{{$support->title}}</a>
										</div>
									</td>
									<td>
										<div class="d-flex flex-column">
											<p class="mb-0 fw-semibold whitespace-nowrap my-fs-small"> {{ mb_strimwidth($support->message, 0, 20, ' ...')}}</p>
										</div>
									</td>
									{{-- danger --}}
									<td>
										<p class="mb-0 fw-semibold my-fs-small">{{$support->created_at}}</p>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		@endcan
	</div>
@endsection
@section("my-script")
	<script>
    const ctx = document.getElementById("myChart");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["2023", "2024", "2025"],
        datasets: [{
          label: "@lang("global.course_orders")",
          data: [{{$totalOrdersYear_2023}}, {{$totalOrdersYear_2024}}, {{$totalOrdersYear_2025}}],
          backgroundColor: [
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)"],
          borderColor: ["rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)",
            "rgb(115 103 240)"
          ],
          borderWidth: 1
        },
          {
            labels: ["2023", "2024", "2025"],
            label: "@lang("global.push_bank")",
            data: [{{$pushBankTransactions_2023}}, {{$pushBankTransactions_2024}}, {{$pushBankTransactions_2025}}],
            backgroundColor: [
              "rgba(0,207,232,1)",
              "rgba(0,207,232,1)",
              "rgba(0,207,232,1)",
              "rgba(0,207,232,1)",
              "rgba(0,207,232,1)",
              "rgba(0,207,232,1)"],
            borderColor: ["rgba(0,207,232,1)",
              "rgba(0,207,232,0.85)",
              "rgba(0,207,232,0.85)",
              "rgba(0,207,232,0.85)",
              "rgba(0,207,232,0.85)",
              "rgba(0,207,232,0.85)",
              "rgba(0,207,232,0.85)"
            ],
            borderWidth: 1
          },
          {
            labels: ["2023", "2024", "2025"],
            label: "@lang("global.pull_bank")",
            data: [{{$pullBankTransactions_2023}}, {{$pullBankTransactions_2024}}, {{$pullBankTransactions_2025}}],
            backgroundColor: [
              "rgba(255, 99, 132, 1)",
              "rgba(255, 99, 132, 1)",
              "rgba(255, 99, 132, 1)",
              "rgba(255, 99, 132, 1)",
              "rgba(255, 99, 132, 1)",
              "rgba(255, 99, 132, 1)"],
            borderColor: ["rgba(182, 40, 145, 0.8)",
              "rgba(182, 40, 145, 1)",
              "rgba(182, 40, 145, 1)",
              "rgba(182, 40, 145, 1)",
              "rgba(182, 40, 145, 1)",
              "rgba(182, 40, 145, 1)",
              "rgba(182, 40, 145, 1)"
            ],
            borderWidth: 1
          },
          {
            labels: ["2023", "2024", "2025"],
            label: "@lang("global.all_expenses_dashboard")",
            data: [{{$totalExpense_2023}}, {{$totalExpense_2024}}, {{$totalExpense_2025}}],
            backgroundColor: [
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)"],
            borderColor: ["rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)",
              "rgb(253 126 20)"
            ],
            borderWidth: 1
          }

        ]
      },
      options: {
        scales: {
          y: {
            // beginAtZero: true
            max: 500000,
            min: 0,
            ticks: {
              stepSize: 100000
            }
          }
        }
      }
    });
	</script>
@endsection
