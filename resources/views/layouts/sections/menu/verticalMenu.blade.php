@php
	$supports_count = App\Models\Support::where('done_read', 0)->whereDate("created_at", date("Y-m-d"))->get()->count();
	$reviews_count = App\Models\Review::where('status', 0)->whereDate("created_at", date("Y-m-d"))->get()->count();
	$comments_count = App\Models\Comment::where('status', 0)->whereDate("created_at", date("Y-m-d"))->get()->count();
	$comments_course_count = App\Models\Comment::where('status', 0)->whereDate("created_at", date("Y-m-d"))->get()->count();

	$orders_courses_count = App\Models\Order::where('status', 0)->whereNotNull("course_id")->whereNull("lecture_id")->whereDate("created_at", date("Y-m-d"))->get()->count();
	$orders_lectures_count = App\Models\Order::where('status', 0)->whereNotNull("lecture_id")->whereDate("created_at", date("Y-m-d"))->get()->count();
	$orders_offers_count = App\Models\Order::where('status', 0)->whereNotNull("offer_id")->whereDate("created_at", date("Y-m-d"))->get()->count();
	$orders_count = $orders_courses_count + $orders_lectures_count + $orders_offers_count;
	$ordersRoutes = isset(\Request::route()->parameters["type"]) ? \Request::route()->parameters["type"] : '';
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

	<!-- ! Hide app brand if navbar-full -->
	@if(!isset($navbarFull))
		<div class="app-brand demo">
			<a href="{{url('/')}}" class="app-brand-link">
      <span class="app-brand-logo demo " style="overflow: inherit;">
	      <img src="{{asset('/logo.png')}}" style="object-fit: cover;width: 38px;">
      </span>
				<span
					class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName_' . app()->getLocale())}}</span>
			</a>

			<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
				<i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
				<i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
			</a>
		</div>
	@endif


	<div class="menu-inner-shadow"></div>

	<ul class="menu-inner py-1">
		<li class="nav-item mx-auto container-brand-logo">
			<a class="navbar-brand logo-menu" href="/">
				<h2 class="brand-text p-0"><img src="{{asset('logo.png')}}" alt=""
				                                class=" rounded-circle image-brand"></h2>
			</a>
		</li>
		@can("dashboard")
			<li class="menu-item @if(Route::currentRouteName() == "dashboard") active @endif">
				<a href="/" class="menu-link">
					<i class="menu-icon tf-icons ti ti-smart-home"></i>
					<div>@lang('global.dashboard')</div>
				</a>
			</li>
		@endcan
		@canany("show_users", "create_users", "show_roles", "show_permissions")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["users", "user_create", "user_edit", "user_show", "roles", "role_create", "role_edit", "permissions", "permission_create", "permission_edit"])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon tf-icons ti ti-users me-3 fs-3"></i>
					<div style="font-size: 14px;white-space: nowrap;">@lang('global.users')</div>
				</a>

				<ul class="menu-sub">
					@can("show_users")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['users', 'user_create', 'user_edit', "user_show"])) active @endif">
							<a href="/users" class="menu-link">@lang('global.users_dashboard')</a>
						</li>
					@endcan

					@can("show_roles")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['roles', 'role_create', 'role_edit'])) active @endif">
							<a href="/roles" class="menu-link">@lang('global.roles')</a>
						</li>
					@endcan
					{{--@can("show_permissions")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['permissions', 'permission_create', 'permission_edit'])) active @endif">
							<a href="/permissions" class="menu-link">@lang('global.permissions')</a>
						</li>
					@endcan--}}
				</ul>
			</li>
		@endcan

		@canany("show_banks", "bank_transactions")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["bank", "bank_create", "bank_edit", "bank_transactions"])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon fa-solid fa-building-columns me-3 fs-3"></i>
					<div>@lang('global.bank')</div>
				</a>

				<ul class="menu-sub">
					@can("show_banks")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['bank', "bank_create", "bank_edit"])) active @endif">
							<a href="/bank" class="menu-link">@lang('global.all_bank')</a>
						</li>
					@endcan
					@can("bank_transactions")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['bank_transactions'])) active @endif">
							<a href="/bank/transactions" class="menu-link">@lang('global.bank_transactions')</a>
						</li>
					@endcan
				</ul>
			</li>
		@endcan
		@canany("show_expenses", "details_expenses")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["expenses", "expenses_create", "expenses_edit", "expenses_details"])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon fa-solid fa-money-bill me-3 fs-3"></i>
					<div>@lang('global.expenses')</div>
				</a>

				<ul class="menu-sub">
					@can("show_expenses")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['expenses', "expenses_create", "expenses_edit"])) active @endif">
							<a href="/expenses" class="menu-link">@lang('global.all_expenses')</a>
						</li>
					@endcan
					@can("details_expenses")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['expenses_details'])) active @endif">
							<a href="/expenses/details" class="menu-link">@lang('global.details_expenses')</a>
						</li>
					@endcan
				</ul>
			</li>
		@endcan
		@canany("show_orders", "create_orders")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(),
					[
						"orders_courses",
						"orders_courses_lectures",
						"orders_course_lectures",
						"orders_offers",
						"orders",
						"order_create",
						'order_save',
						'order_edit',
						'order_update',
						'order_delete',
					])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon tf-icons fa-solid fa-file-circle-check me-3 fs-4"></i>
					<div>@lang('global.all_subscriptions')</div>
					@if($orders_count !== 0)
						<div class="badge bg-label-success rounded-pill ms-auto">{{$orders_count}}</div>
					@endif
				</a>
				<ul class="menu-sub text-nowrap">
					<li class="menu-item menu-item-child @if($ordersRoutes === 'course' or in_array(Route::currentRouteName(),["orders_courses"])) open @endif">
						<a href="javascript:void(0);" class="menu-link menu-toggle ">
							<i class="menu-icon tf-icons fa-solid fa-cash-register me-2 fs-4"></i>
							<div class="d-flex align-items-center justify-content-between w-100 ">
								<span class="">@lang('global.courses_subscriptions')</span>
								@if($orders_courses_count !== 0)
									<span class="badge bg-label-success rounded-pill ms-auto">{{$orders_courses_count}}</span>
								@endif
							</div>
						</a>
						<ul class="menu-sub">
							<li class="menu-item @if($ordersRoutes === 'course') active @endif">
								<a href="{{route('orders', "course")}}" class="menu-link">@lang('global.subscriptions')</a>
							</li>
							<li class="menu-item @if(in_array(Route::currentRouteName(), ['orders_courses'])) active @endif">
								<a href="{{route('orders_courses')}}" class="menu-link">@lang('global.orders_courses')</a>
							</li>
						</ul>
					</li>
					<li class="menu-item menu-item-child @if($ordersRoutes === 'lecture' or in_array(Route::currentRouteName(),["orders_lectures", 'orders_courses_lectures', 'orders_course_lectures'])) open @endif">
						<a href="javascript:void(0);" class="menu-link menu-toggle">
							<i class="menu-icon tf-icons fa-solid fa-users-rectangle me-3 fs-4"></i>
							<div class="d-flex align-items-center justify-content-between w-100">
								<span class="">@lang('global.lectures_subscriptions')</span>
								@if($orders_lectures_count !== 0)
									<span class="badge bg-label-success rounded-pill ms-auto">{{$orders_lectures_count}}</span>
								@endif
							</div>
						</a>
						<ul class="menu-sub">
							<li class="menu-item @if($ordersRoutes === 'lecture') active @endif">
								<a href="{{route('orders', "lecture")}}" class="menu-link">@lang('global.subscriptions')</a>
							</li>
							<li class="menu-item @if(in_array(Route::currentRouteName(), ['orders_lectures', 'orders_courses_lectures', 'orders_course_lectures'])) active @endif">
								<a href="{{route('orders_courses_lectures')}}" class="menu-link">@lang('global.orders_lectures')</a>
							</li>
						</ul>
					</li>
					<li class="menu-item menu-item-child @if($ordersRoutes === 'offer' or in_array(Route::currentRouteName(),["orders_offers"])) open @endif">
						<a href="javascript:void(0);" class="menu-link menu-toggle">
							<i class="menu-icon tf-icons fa-solid fa-gift me-2 fs-4"></i>
							<div class="d-flex align-items-center justify-content-between w-100">
								<span class="">@lang('global.offers_subscriptions')</span>
								@if($orders_offers_count !== 0)
									<span class="badge bg-label-success rounded-pill ms-auto">{{$orders_offers_count}}</span>
								@endif
							</div>
						</a>
						<ul class="menu-sub">
							<li class="menu-item @if($ordersRoutes === 'offer') active @endif">
								<a href="{{route('orders', "offer")}}" class="menu-link">@lang('global.subscriptions')</a>
							</li>
							<li class="menu-item @if(in_array(Route::currentRouteName(), ['orders_offers'])) active @endif">
								<a href="{{route('orders_offers')}}" class="menu-link">@lang('global.orders_offers')</a>
							</li>
						</ul>
					</li>
				</ul>

			</li>
		@endcanany
		@canany("show_reports")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ['reports_training_hours',"reports_teachers", "reports_courses", "reports_lectures", "reports_offers", "reports_students"])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon fa-solid fa-file-signature me-3 fs-3"></i>
					<div>@lang('global.reports')</div>
				</a>
				<ul class="menu-sub">
					<li class="menu-item @if (in_array(Route::currentRouteName(), ['reports_training_hours'])) active @endif">
						<a href="{{ route('reports_training_hours') }}" class="menu-link">@lang('global.reports_training_hours')</a>
					</li>
					@can("reports_teachers")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['reports_teachers'])) active @endif">
							<a href="{{route('reports_teachers')}}" class="menu-link">@lang('global.teachers')</a>
						</li>
					@endcan
					@can("reports_courses")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['reports_courses'])) active @endif">
							<a href="{{route('reports_courses')}}" class="menu-link">@lang('global.courses')</a>
						</li>
					@endcan
					@can("reports_lectures")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['reports_lectures'])) active @endif">
							<a href="{{route('reports_lectures')}}" class="menu-link">@lang('global.lectures')</a>
						</li>
					@endcan
					@can("reports_offers")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['reports_offers'])) active @endif">
							<a href="{{route('reports_offers')}}" class="menu-link">@lang('global.offers')</a>
						</li>
					@endcan
					@can("reports_students")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['reports_students'])) active @endif">
							<a href="{{route('reports_students')}}" class="menu-link">@lang('global.students')</a>
						</li>
					@endcan
				</ul>
			</li>
		@endcan
		@canany("show_bank_categories", "show_bank_questions")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["bank_questions_categories", "bank_categories_index", "bank_categories_create", "bank_categories_edit", "bank_questions", "bank_question_create", "bank_question_edit"])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon fa-solid fa-question me-3 fs-3"></i>
					<div>@lang('global.bank_questions')</div>
				</a>

				<ul class="menu-sub">
					@can("show_bank_categories")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ["bank_categories_index", "bank_categories_create", "bank_categories_edit"])) active @endif">
							<a href="{{route('bank_categories_index')}}" class="menu-link">@lang('global.categories')</a>
						</li>
					@endcan
					@can("show_bank_questions")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ["bank_questions_categories", "bank_questions", "bank_question_create", "bank_question_edit"])) active @endif">
							<a href="{{route('bank_questions_categories')}}" class="menu-link">@lang('global.bank_questions')</a>
						</li>
					@endcan
				</ul>
			</li>
		@endcan
		@canany("show_settings", "create_settings", "setting_backup_database", "setting_backup_files", "setting_clear_cash")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["settings", "setting_create","setting_edit", "setting_show", "setting_backup_database", "setting_backup_files", "setting_clear_cash"])) open @endif">
				<a href="javascript:void(0);" class="menu-link menu-toggle">
					<i class="menu-icon tf-icons ti ti-settings me-3 fs-3"></i>
					<div>@lang('global.setting')</div>
				</a>

				<ul class="menu-sub">
					@can("show_settings")
						<li
							class="menu-item @if(in_array(Route::currentRouteName(), ['settings', "setting_create","setting_edit", "setting_show"])) active @endif">
							<a href="/settings" class="menu-link">@lang('global.setting_site')</a>
						</li>
					@endcan
					{{--@can("setting_backup_database")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['setting_backup_database'])) active @endif">
							<a href="/setting/backupDatabase" class="menu-link">@lang('global.setting_backup_database')</a>
						</li>
					@endcan--}}

					@can("setting_clear_cash")
						<li class="menu-item @if(in_array(Route::currentRouteName(), ['setting_clear_cash'])) active @endif">
							<a href="/setting/clearCash" class="menu-link">@lang('global.setting_clear_cash')</a>
						</li>
					@endcan
				</ul>
			</li>
		@endcan
		@can("show_asks")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["asks", "ask_create", "ask_edit", "ask_show"])) active @endif">
				<a href="/asks" class="menu-link">
					<i class="menu-icon fa-solid fa-question me-3 fs-3"></i>
					<div>@lang('global.asks')</div>
				</a>
			</li>
		@endcan
		@can("show_sections")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["sections", "section_create", "section_edit"])) active @endif">
				<a href="/sections" class="menu-link">
					<i class="menu-icon tf-icons ti ti-layout-grid me-3 fs-3"></i>
					<div>@lang('global.sections')</div>
				</a>
			</li>
		@endcan
		@can("show_subjects")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["subjects", "subject_create", "subject_edit", "subject_show"])) active @endif">
				<a href="/subjects" class="menu-link">
					<i class="menu-icon fa-solid fa-book-open me-3 fs-3"></i>
					<div>@lang('global.subjects')</div>
				</a>
			</li>
		@endcan
		@can("show_teachers")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ['teachers', 'teacher_create', 'teacher_edit', "teacher_show"])) active @endif">
				<a href="/teachers" class="menu-link">
					<i class="menu-icon fa-solid fa-person-chalkboard me-3 fs-3"></i>
					<div>@lang('global.teachers')</div>
				</a>
			</li>
		@endcan
		@can("show_courses")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["courses", "course_create", "course_edit", "course_show", "chapters", "chapter_create", "chapter_edit", "lectures", "lecture_create", "lecture_edit"])) active @endif">
				<a href="/courses" class="menu-link">
					<i class="menu-icon fa-solid fa-clapperboard me-3 fs-3"></i>
					<div>@lang('global.courses')</div>
				</a>
			</li>
		@endcan
		@can('show_lectures')
			<li class="menu-item @if (in_array(Route::currentRouteName(), ['rooms'])) active @endif">
				<a href="{{route('rooms')}}" class="menu-link">
					<i class="fa-solid fa-users me-3 fs-2"></i>
					<div>@lang('global.rooms')</div>
				</a>
			</li>
		@endcan
		@can("show_questions")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["questions_courses", "course_lectures","questions", "question_create", "question_edit", "question_show", "bank_questions_create"])) active @endif">
				<a href="{{route('questions_courses')}}" class="menu-link">
					<i class="menu-icon fa-solid fa-circle-question me-3 fs-3"></i>
					<div>@lang('global.questions')</div>
				</a>
			</li>
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["recorded_exams_courses", "recorded_exams_lectures", "recorded_exams_users", "recorded_exams","recorded_show"])) active @endif">
				<a href="{{route('recorded_exams_courses')}}" class="menu-link">
					<i class="menu-icon fa-solid fa-circle-question me-3 fs-3"></i>
					<div>@lang('global.recorded_users')</div>
				</a>
			</li>
		@endcan
		@can("show_certificates")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["certificates_courses","certificates", "certificates_course_lectures" ,"certificate_create", "certificate_save" ,"certificate_edit", "certificate_show"])) active @endif">
				<a href="{{route('certificates_courses')}}" class="menu-link">
					<i class="menu-icon ti ti-certificate me-3 fs-2"></i>
					<div>@lang('global.certificates')</div>
				</a>
			</li>
		@endcan

		@can("show_offers")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["offers", "offer_create", "offer_edit", "offer_show"])) active @endif">
				<a href="/offers" class="menu-link">
					<i class="menu-icon fa-solid fa-gift me-3 fs-3"></i>
					<div>@lang('global.offers')</div>
				</a>
			</li>
		@endcan
		@can("show_coupons")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["coupons", "coupon_create", "coupon_edit", "coupon_show"])) active @endif">
				<a href="/coupons" class="menu-link">
					<i class="menu-icon fa-solid fa-gift me-3 fs-3"></i>
					<div>@lang('global.coupons')</div>
				</a>
			</li>
		@endcan
		@canany("show_comments")
			<li class="menu-item @if(Request::route()->type === "courses") active @endif">
				<a href="{{route('comments_courses', 'courses')}}" class="menu-link">
					<i class="menu-icon fa-solid fa-comments fs-4 me-3"></i>
					@lang('global.comments_courses')
					<div class="badge bg-label-success rounded-pill ms-auto">{{$comments_course_count}}</div>
				</a>
			</li>
		@endcan
		@can("show_reviews")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["reviews", "review_create", "review_show"])) active @endif">
				<a href="/reviews" class="menu-link">
					<i class="menu-icon tf-icons ti ti-stars me-3 fs-3"></i>
					<div>@lang('global.reviews')</div>
					<div class="badge bg-label-success rounded-pill ms-auto">{{$reviews_count}}</div>
				</a>
			</li>
		@endcan
		@can("show_supports")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["supports", "support_done_read", "support_un_read", "done_contact", "support_un_contact", "done_problem", "support_done_problem", "support_un_problem", "support_done_deleted", "get_chat", "chat_delete", "support_done_contact", "support_force_delete", "support_restore"])) active @endif">
				<a href="/supports" class="menu-link">
					<i class="menu-icon tf-icons ti ti-mail me-3 fs-3"></i>
					<div>@lang('global.contact_us')</div>
					<div class="badge bg-label-success rounded-pill ms-auto">{{$supports_count}}</div>
				</a>
			</li>
		@endcan
		@can("whatsapp")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["whatsapp", "send_whatsapp"])) active @endif">
				<a href="{{route('whatsapp')}}" class="menu-link">
					<i class="menu-icon fa-brands fa-square-whatsapp me-3 fs-3"></i>
					<div>@lang('global.whatsapp')</div>
				</a>
			</li>
		@endcan
		<li
			class="menu-item @if(in_array(Route::currentRouteName(), ["notifications", "send_notifications"])) active @endif">
			<a href="{{route('notifications')}}" class="menu-link">
				<i class="menu-icon fa-solid fa-bell me-3 fs-3"></i>
				<div>@lang('global.notifications')</div>
			</a>
		</li>
		@can("show_payment_methods")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["payment_method", "payment_method_create", "payment_method_edit"])) active @endif">
				<a href="/payment_method" class="menu-link">
					<i class="menu-icon tf-icons ti ti-cash me-3 fs-3"></i>
					<div>@lang('global.payment_method')</div>
				</a>
			</li>
		@endcan
		@can("show_currencies")
			<li
				class="menu-item @if(in_array(Route::currentRouteName(), ["currency", "currency_create", "currency_edit"])) active @endif">
				<a href="/currency" class="menu-link">
					<i class="menu-icon fa-brands fa-bitcoin me-3 fs-3"></i>
					<div>@lang('global.all_currency')</div>
				</a>
			</li>
		@endcan
		@can("transactions")
			<li class="menu-item @if(in_array(Route::currentRouteName(), ["transactions"])) active @endif">
				<a href="/transactions" class="menu-link">
					<i class="menu-icon fa-solid fa-file-signature me-3 fs-4"></i>
					<div>@lang('global.transactions')</div>
				</a>
			</li>
		@endcan


	</ul>
</aside>
