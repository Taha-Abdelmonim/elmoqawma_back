@extends('layouts/layoutMaster')

@section('title', trans("global.show_user"))

@section('content')
	@php
		$links = [
			"start" => trans("global.sections"),
			"/" => trans("global.dashboard"),
			"/users" => trans("global.all_users"),
			"end" => trans("global.show_user"),
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="row mt-5">
		<!-- User Sidebar -->
		<div class=" order-1 order-md-0">
			<!-- User Card -->
			<div class="card mb-4">
				<div class="card-body">
					<div class="user-avatar-section border-bottom pb-3">
						<div class=" d-flex align-items-center flex-column">
							<img class="img-fluid rounded mb-3 pt-1 mt-4" src="{{ $user->photo != null ? asset("images/" . $user->photo) : asset('assets/img/avatars/1.png') }}" height="100" width="100" alt="User avatar" />
							<div class="user-info text-center">
								<h4 class="mb-2">{{$user->name()}}</h4>
								<span class="badge bg-label-secondary mt-1">
                  @if(is_array($user->roles_name))
										{{...$user->roles_name}}
									@else
										{{$user->roles_name == null ? "user" : $user->roles_name}}
									@endif
                </span>
							</div>
						</div>
					</div>

					<p class="mt-4 small text-uppercase text-muted">@lang("global.show_user")</p>
					<div class="info-container">
						<ul class="list-unstyled">
							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.number"):</span>
								<span>{{$user->id}}</span>
							</li>
							<li class="mb-2">
								<span class="fw-semibold me-1">@lang("global.name"):</span>
								<span>{{$user->name()}}</span>
							</li>
							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.email"):</span>
								<span>{{$user->email}}</span>
							</li>
							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.balance"):</span>
								<span class="badge bg-label-{{$user->balance <= 0 ? 'danger' : 'success'}}">{{$user->balance}} جنية </span>
							</li>
							<li class="mb-2">
								<span class="fw-semibold me-1">@lang("global.login_from"):</span>
								<span>{{$user->oauth_type == null ? trans("global.site") : $user->oauth_type}}</span>
							</li>
							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.roles"):</span>
								<span>
                  @if(is_array($user->roles_name))
										{{...$user->roles_name}}
									@else
										{{$user->roles_name == null ? "user" : $user->roles_name}}
									@endif
                </span>
							</li>

							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.phone"):</span>
								<span>{{$user->phone}}</span>
							</li>
							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.phone_parent"):</span>
								<span>{{$user->phone_parent}}</span>
							</li>
							<li class="mb-2 pt-1">
								<span class="fw-semibold me-1">@lang("global.created_at"):</span>
								<span>{{$user->created_at}}</span>
							</li>
							<li class="pt-1">
								<span class="fw-semibold me-1">@lang("global.check"):</span>
								<span class="badge bg-label-success">@lang("global.valid_email")</span>
							</li>
						</ul>
						<h1 class="text-center mb-4"> دورات الطالب </h1>
						<div class="card-datatable table-responsive my-5">
							<table class="datatables-ajax table border-top">
								<thead>
								<tr>
									<th class="text-start">@lang("global.number")</th>
									<th class="text-start">@lang("global.course")/@lang("global.subject")</th>
									<th class="text-start">@lang("global.price")</th>
									<th class="text-start">@lang("global.date_subscribed")</th>
								</tr>
								</thead>
								<tbody>
								@foreach ($ordersCourses as $ordersCourse)
									<tr>
										<td class="text-start"><span class="badge">{{$ordersCourse->course_id}}</span></td>
										<td class="text-start"><a href="{{route("course_show", $ordersCourse->course_id)}}" target="_blank"
										                          class="text-truncate d-flex align-items-center">{{$ordersCourse->course->name . " " .$ordersCourse->course->subject["name_" . app()->getLocale()]}}</a>
										</td>
										<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success w-25'>{{$ordersCourse->price}}</span></td>
										<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-primary '>{{$ordersCourse->created_at}}</span></td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>

						<h1 class="text-center my-4"> حصص الطالب </h1>
						<div class="card-datatable table-responsive mb-5">
							<table class="datatables-ajax table border-top">
								<thead>
								<tr>
									<th class="text-start">@lang("global.number")</th>
									<th class="text-start">@lang("global.course")/@lang("global.subject")</th>
									<th class="text-start">@lang("global.lecture")</th>
									<th class="text-start">@lang("global.price")</th>
									<th class="text-start">@lang("global.date_subscribed")</th>
								</tr>
								</thead>
								<tbody>
								@foreach ($ordersLectures as $ordersLecture)
									<tr>
										<td class="text-start"><span class="badge">{{$ordersLecture->lecture_id}}</span></td>
										<td class="text-start"><a href="{{route("course_show", $ordersLecture->course_id)}}" target="_blank"
										                          class="text-truncate d-flex align-items-center">{{$ordersLecture->course->name . " " .$ordersLecture->course->subject["name_" . app()->getLocale()]}}</a></td>
										<td class="text-start"><a href="{{route("lecture_edit", [$ordersLecture->lecture->chapter_id ,$ordersLecture->lecture->id])}}" target="_blank" class="text-truncate d-flex align-items-center">{{$ordersLecture->lecture->title}}</a>
										</td>
										<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-success w-25'>{{$ordersLecture->price}}</span></td>
										<td class="text-start"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-primary '>{{$ordersLecture->created_at}}</span></td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
						<div class="d-flex justify-content-center">
							<a href="{{route('user_edit', $user->id)}}" class="btn btn-primary me-3">تعديل</a>
							<a onclick="return confirm(@lang('global.confirm')" href="{{route('user_delete', $user->id)}}" class="btn btn-label-danger ">@lang("global.delete")</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
