@extends('layouts.layoutMaster')

@section('title', trans("global.messages_contact"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css')}}" />
@endsection

@section('page-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-chat.css')}}" />
@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js')}}"></script>
@endsection

@section('page-script')
	<script src="{{asset('assets/js/app-chat.js')}}"></script>
@endsection

@section('content')
	<div class="d-flex justify-content-between align-items-center mb-4">
		@php
			$links = [
				"start" => trans("global.messages_contact"),
				"/" => trans("global.dashboard"),
				"/supports" => trans("global.contact_us"),
				"end" => trans("global.". Route::current()->getName()),
		]
		@endphp
		@include("layouts.breadcrumbs")
		<a class="btn btn-secondary btn-primary position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/doneRead">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_done_read")</span></span>
		</a>
		<a class="btn btn-danger position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/unRead">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_un_read")</span></span>
		</a>
		<a class="btn btn-success position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/doneContact">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_done_contact")</span></span>
		</a>
		<a class="btn btn-gray position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/unContact">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_un_contact")</span></span>
		</a>
		<a class="btn btn-info position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/doneProblem">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_done_problem")</span></span>
		</a>
		<a class="btn btn-warning position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/unProblem">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_un_problem")</span></span>
		</a>
		<a class="btn btn-danger position-relative" tabindex="0" aria-controls="DataTables_Table_0" href="/doneDeleted">
			<span><span class="d-none d-sm-inline-block">@lang("global.support_done_deleted")</span></span>
		</a>
	</div>
	<div class="app-chat card overflow-hidden">
		<div class="row g-0">
			<!-- Chat & Contacts -->
			<div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end" id="app-chat-contacts">
				<div class="sidebar-header">
					<div class="d-flex align-items-center me-3 me-lg-0">
						<div class="flex-shrink-0 avatar avatar-online me-3" data-bs-toggle="sidebar" data-overlay="app-overlay-ex"
						     data-target="#app-chat-sidebar-left">
							<img class="user-avatar rounded-circle cursor-pointer" src="{{asset('assets/img/avatars/1.png')}}"
							     alt="Avatar">
						</div>
						<div class="flex-grow-1 input-group input-group-merge rounded-pill">
							<span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
							<input type="text" class="form-control" placeholder="بحث..." aria-label="بحث..." aria-describedby="basic-addon-search31">
						</div>
					</div>
					<i class="ti ti-x cursor-pointer mt-2 me-1 d-lg-none d-block position-absolute top-0 end-0" data-overlay
					   data-bs-toggle="sidebar" data-target="#app-chat-contacts"></i>
				</div>
				<hr class="container-m-nx m-0">
				<div class="sidebar-body">
					<!-- Contacts -->
					<ul class="list-unstyled chat-contact-list mb-0" id="contact-list">
						<li class="chat-contact-list-item chat-contact-list-item-title">
							<h5 class="text-primary mb-0">@lang("global.contact_messages")</h5>
						</li>
						<li class="chat-contact-list-item contact-list-item-0 d-none">
							<h6 class="text-muted mb-0">@lang("global.not_found_messages")</h6>
						</li>
						<span class="form-send-message"></span>
						@foreach($supports as $support)
							<li class="chat-contact-list-item user-chat" data-id="{{$support->id}}">
								<a class="d-flex align-items-center">
									<div class="flex-shrink-0 avatar avatar-{{$support->user->status == 1 ? 'online' : 'busy'}}">
										<img src="{{$support->user->photo != null ? asset("images/" . $support->user->photo) : asset('assets/img/avatars/14.png')}}" alt="Avatar" class="rounded-circle">
									</div>
									{{--                busy--}}
									<div class="chat-contact-info flex-grow-1 ms-2">
										<h6 class="chat-contact-name text-truncate m-0">{{$support->user->name()}}</h6>
										<p class="chat-contact-status text-muted text-truncate mb-0">{{$support->user->email}}</p>
									</div>
								</a>
								@if($support->done_problem == 1)
									<span class="badge badge-center rounded-pill bg-success"><i class="ti ti-check"></i></span>
								@endif
								@if($support->done_contact == 1 && $support->done_problem == 0)
									<span class="badge badge-center rounded-pill bg-primary"><i class="ti ti-star"></i></span>
								@elseif($support->done_read == 0 && $support->done_contact == 0 && $support->done_problem == 0 || $support->done_contact == 0 && $support->done_read == 1 )
									<span class="badge badge-center rounded-pill bg-danger"><i class="ti ti-clock"></i></span>
								@endif
							</li>
							@if(isset($deleted))
								<div class="ms-4  me-4  d-flex justify-content-between">
									@can("remove_chat_never")
										<button type="button" class="btn rounded-pill btn-label-danger waves-effect" data-target="/forceDelete/{{$support->id}}" onclick="ajaxProssess(this)">@lang("global.deleted_chat_never")</button>
									@endcan
									@can("restore_chat")
										<button type="button" class="btn rounded-pill btn-label-success waves-effect" data-target="/supportRestore/{{$support->id}}" onclick="ajaxProssess(this)">@lang("global.restore")</button>
									@endcan
								</div>
							@endif
						@endforeach
					</ul>
				</div>
			</div>
			<!-- /Chat contacts -->

			<!-- Chat History -->
			<div class="col app-chat-history bg-body" id="content_chat">
				<div class="chat-history-wrapper">
					<div class="chat-history-header border-bottom">
						<div class="d-flex justify-content-between align-items-center">
							<div class="d-flex overflow-hidden align-items-center">
								<i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar"
								   data-overlay data-target="#app-chat-contacts"></i>
								<div class="flex-shrink-0 avatar">
									<img src="{{Auth::user()->photo != null ? asset("images/" . Auth::user()->photo) : asset('assets/img/avatars/14.png')}}" alt="Avatar" class="rounded-circle"
									     data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right">
								</div>
								<div class="chat-contact-info flex-grow-1 ms-2">
									<h6 class="m-0">{{Auth::user()->name()}}</h6>
									<small class="user-status text-muted">{{Auth::user()->phone}}</small>
								</div>
							</div>
						</div>
					</div>
					<div class="chat-history-body bg-body">
						<ul class="list-unstyled chat-history">
							<li class="chat-message">
								<div class="d-flex overflow-hidden">
									<div class="user-avatar flex-shrink-0 me-3">
										<div class="avatar avatar-sm">
											<img src="{{Auth::user()->photo != null ? asset("images/" . Auth::user()->photo) : asset('assets/img/avatars/14.png')}}" alt="Avatar" class="rounded-circle">
										</div>
									</div>
									<div class="chat-message-wrapper flex-grow-1">
										<div class="chat-message-text">
											<p class="mb-0">@lang("global.click_user")</p>
										</div>
										<div class="text-muted mt-1">
											<small>{{Auth::user()->created_at}}</small>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- /Chat History -->
		</div>
	</div>
@endsection
@section("my-script")
	<script>
    function ajaxProssess(element) {
      let url = element.getAttribute("data-target");
      element.setAttribute("disabled", true);
      $(function() {
        $.ajax({
          url,
          data: { "_token": "{{ csrf_token() }}" },
          type: "POST",
          cache: false,
          dataType: "json",
          success: function(data) {
            showMessage("success", data.message);
          },
          error: function({ responseJSON }) {
            showMessage("error", responseJSON.message);
          }
        });
      });
    }

    $(function() {
      $(".user-chat").on("click", function(element) {
        element = element.delegateTarget;
        console.log(element);
        let $id = element.dataset.id;
        $.ajax({
          url: `/getChat/${$id}`,
          data: { "_token": "{{ csrf_token() }}" },
          type: "POST",
          cache: false,
          dataType: "html",
          success: function(data) {
            $("#content_chat").html(data);
          }
        });
      });
      $(".user-chat").on("dblclick", function(element) {
        element = element.delegateTarget;
        let $id = element.dataset.id;
        $.ajax({
          url: `/chatDelete/${$id}`,
          data: { "_token": "{{ csrf_token() }}" },
          type: "POST",
          cache: false,
          dataType: "json",
          success: function(data) {
            element.remove();
            showMessage("success", data.message);
          },
          error: function({ responseJSON }) {
            showMessage("error", responseJSON.message);
          }
        });
      });
    });
	</script>
@endsection
