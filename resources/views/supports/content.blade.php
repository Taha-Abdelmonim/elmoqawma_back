<div class="chat-history-wrapper">
	<div class="chat-history-header border-bottom">
		<div class="d-flex justify-content-between align-items-center">
			<div class="d-flex overflow-hidden align-items-center">
				<i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar"
				   data-overlay data-target="#app-chat-contacts"></i>
				<div class="flex-shrink-0 avatar">
					<img src="{{$chat->user->photo != null ? asset("images/" . $chat->user->photo) : asset('assets/img/avatars/14.png')}}" alt="Avatar" class="rounded-circle"
					     data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right">
				</div>
				<div class="chat-contact-info flex-grow-1 ms-2">
					<a href="{{route('user_show', $chat->user->id)}}" class="d-block">
						{{$chat->user->name()}}
					</a>
					<small class="user-status text-muted">{{$chat->user->phone}}</small>
				</div>
			</div>
			<div>
				@if($chat->done_contact == 0)
					<button type="button" class="btn rounded-pill btn-primary waves-effect waves-light" data-target="/doneContact/{{$chat->id}}" onclick="ajaxProssess(this)">
						<span class="ti-xs ti ti-mail me-1"></span>@lang("global.support_done_contact")
					</button>
				@elseif($chat->done_contact == 0 and $chat->done_problem == 0)
					<div class="d-flex align-items-center lh-1 me-3 mb-3 mb-sm-0">
						<span class="badge badge-dot bg-primary me-1"></span>@lang("global.support_done_contact_admin_process")
					</div>
				@endif
				@if($chat->done_problem == 0)
					<button type="button" class="btn rounded-pill btn-success waves-effect waves-light ms-3" data-target="/doneProblem/{{$chat->id}}" onclick="ajaxProssess(this)">
						<span class="ti-xs ti ti-star me-1"></span>@lang("global.support_done_problem")<span class="ti-xs ti ti-check me-1"></span>
					</button>
				@else
					<span class="badge badge-center rounded-pill bg-success"><i class="ti ti-check"></i></span>
				@endif
			</div>
		</div>
	</div>
	<div class="chat-message-text my-like-bg w-fit p-2 rounded-3 mt-4 mx-auto">
		<p class="mb-0 fs-4 ">{{$chat->title}}</p>
	</div>
	<div class="chat-history-body bg-body">
		<ul class="list-unstyled chat-history">
			<li class="chat-message">
				<div class="d-flex overflow-hidden">
					<div class="user-avatar flex-shrink-0 me-3">
						<div class="avatar avatar-sm">
							<img src="{{$chat->user->photo != null ? asset("images/" . $chat->user->photo) : asset('assets/img/avatars/14.png')}}" alt="Avatar" class="rounded-circle">
						</div>
					</div>
					<div class="chat-message-wrapper flex-grow-1">
						<div class="chat-message-text">
							<p class="mb-0">{{$chat->message}}</p>
						</div>
						<div class="text-muted mt-1">
							<small>{{$chat->user->created_at}}</small>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>
