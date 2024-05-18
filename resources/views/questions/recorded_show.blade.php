@extends('layouts/layoutMaster')

@section('title', trans("global.all_courses"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}">
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}">
	@can("export")
		<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}">
	@endcan
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />

@endsection

@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave.js')}}"></script>
	<script src="{{asset('assets/vendor/libs/cleavejs/cleave-phone.js')}}"></script>
@endsection



@section('content')
	@php
		$links = [
			"start" => trans("global.recorded_users"),
			"/" => trans("global.dashboard"),
			"/exams/coursesExams" => trans("global.all_courses"),
			"/exams/coursesExams/$course->id/lectures" => $course["name_" . app()->getLocale()],
			"/exams/coursesExams/$course->id/lecture/$lecture->id" => $lecture->title,
			"end" => $user->name() . " => $examID ",
	]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="card">
		<div class="col-12  my-5 ">
			<div class="table-responsive border-top">
				<table class="table m-0">
					<thead>
					<tr>
						<th>@lang("global.answer")</th>
						<th>@lang("global.status")</th>
					</tr>
					</thead>
					<tbody>
					@foreach($questions as $question)
						<tr>
							<td colspan="12" class="text-center text-primary fs-4">
								@php echo $question["question_".app()->getLocale()];@endphp
								<p class="text-start">@lang("global.note")<span class="text-info">{{$question->Justify}}</span></p>
								@if($question->type === "video")
									<div style="height: 400px;  padding:5px; margin:auto;">
										<div style="height: 400px; padding: 5px; margin: auto;">
											<iframe src="{{ asset("images/$question->file") }}" loading="lazy"
											        style="width: 50%; height: 100%;" allowfullscreen="true"></iframe>
										</div>
									</div>
								@elseif($question->type === "audio")
									<div class="w-100">
										<audio controls class="w-50">
											<source src="{{ asset("images/$question->file") }}" type="audio/ogg" />
											<source src="{{ asset("images/$question->file") }}" type="audio/mpeg" />
											Your browser does not support the audio tag.
										</audio>
									</div>
								@elseif($question->type === "image")
									<div class="">
										<a href="{{ asset("images/$question->file") }}" target="_blank">
											<img class="card-img card-img-right w-25 h-100 object-cover mx-auto" src="{{ asset("images/$question->file") }}" alt="Card image" />
										</a>
									</div>
								@endif
							</td>
						</tr>
						@foreach($question->answers as $answer)
							<tr class=" @if(in_array($answer->id, $answersIDS) and $answer->status == 1) border-2 border-success @elseif(in_array($answer->id, $answersIDS) and $answer->status == 0) border-2 border-danger @endif ">

								@if($answer->answer_type === "image")
									<td class="d-flex justify-content-center align-items-center ">
										<a href="{{ asset("images/$answer->answer")  }}" target="_blank" class="w-50 ">
											<img class=" object-cover mx-auto w-100" src="{{ asset("images/$answer->answer") }}" alt="Card image" />
											<span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-{{$answer->status == 1 ? 'success': 'danger'}}  w-100'>{{$answer->status == 1 ? 'صحيح': 'خطاء'}}</span>
										</a>
									</td>
								@elseif($answer->answer_type === "text")
									<td><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-{{$answer->status == 1 ? 'success': 'danger'}} w-25'>{{$answer->answer }}</span></td>
									<td class="p-0"><span class='text-truncate d-flex align-items-center justify-content-center fw-bold badge bg-label-{{$answer->status == 1 ? 'success': 'danger'}}  w-50'>{{$answer->status == 1 ? 'صحيح': 'خطاء'}}</span></td>
								@endif
							</tr>
						@endforeach
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection