@extends('layouts/layoutMaster')

@section('title', trans("global.course_show"))

@section('vendor-style')
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/css/plyr.min.css')}}" />
	<link rel="stylesheet" href="{{asset('assets/vendor/libs/spinkit/spinkit.css')}}" />
@endsection
@section('vendor-script')
	<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
@endsection
@section('page-script')
	{{-- <script src="{{asset('assets/js/forms-file-upload.js')}}"></script>--}}
	<script src="{{asset('assets/js/resumable.min.js')}}"></script>
	<script src="{{asset('assets/js/plyr.min.js')}}"></script>

@endsection
@section('content')
	@php
		$links = [
		"start" => trans("global.courses"),
		"/" => trans("global.dashboard"),
		"/courses" => trans("global.all_courses"),
		"end" => trans("global.course_show"),
		]
	@endphp
	@include("layouts.breadcrumbs")
	<div class="row">
		<!-- Headings -->
		<div class="col-lg">
			<div class="card mb-3 mx-4 p-5">
				<div class="row g-0">
					<div class="col-md-4 h-px-300">
						<img class="card-img card-img-right h-100 object-cover" src="{{asset('images/' . $course->photo)}}"
						     alt="Card image" />
					</div>
					<div class="card-body col-8">
						<h5 class="card-title">@lang("global.name"): {{$course["name_en" ]}}</h5>
						<h5 class="card-title">@lang("global.section"):
							<a href="/sections?section_id={{$course->section->id}}">{{$course->section["name_" .
							app()->getLocale()]}}</a>
						</h5>
						<h5 class="card-title">@lang("global.coach"):
							<a href="/teacher/{{$course->teacher->id}}">{{$course->teacher["name_" .
							app()->getLocale()]}}</a>
						</h5>
						<h5 class="card-title">@lang("global.price"): {{$course->price}}</h5>
						<h5 class="card-title">@lang("global.status"):
							<span class="badge bg-label-{{$course->status === 1 ? 'success' : 'danger'}}">{{$course->status
							=== 1 ? trans("global.enabled") : trans("global.not_enabled")}}</span>
						</h5>
						<h5 class="card-title">@lang("global.video"):
							<a href="{{$course->video}}" target="_blank">@lang("global.view_video")</a>
						</h5>
						<h5 class="card-title">@lang("global.level"):
							@if($course->level == "beginner")
								<span class="badge bg-label-danger">@lang("global.beginner")</span>
							@elseif($course->level == "medium")
								<span class="badge bg-label-primary">@lang("global.medium")</span>
							@elseif($course->level == "professional")
								<span class="badge bg-label-success">@lang("global.professional")</span>
							@endif
						</h5>
					</div>
					<p class="col-12">@php echo $course["description_" . app()->getLocale()] @endphp</p>
				</div>
			</div>


			<div class="card ">
				<div class="d-flex align-items-center justify-content-end p-4">
					@can("create_lectures")
						<a class="btn btn-secondary btn-primary position-relative" tabindex="0"
						   aria-controls="DataTables_Table_0" href="{{route('attachments.create', ["course", $course->id])}}">
					<span><i class="ti ti-plus me-0 me-sm-1 ti-xs"></i><span
							class="d-none d-sm-inline-block">{{trans("global.add_file")}}</span></span>
						</a>
					@endcan
				</div>

				@isset($attachments)
					<div class="card-datatable text-nowrap table-responsive">
						<table class="datatables-ajax table">
							<thead>
							<tr>
								<th class="text-start check-all"><label for="checkbox_delete"><input type="checkbox"
								                                                                     id="checkbox_delete"
								                                                                     class="form-check-input me-3">@lang("global.number")</label></th>
								<th class="text-start">@lang("global.name")</th>
								<th class="text-start">@lang("global.created_at")</th>
								<th class="text-start">@lang("global.file")</th>
								<th class="text-start">@lang("global.actions")</th>
							</tr>
							</thead>
							<tbody>

							@foreach ($attachments as $attachment)
								<tr>
									<td class="text-start"><input type="checkbox" data-id="{{$attachment->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$attachment->id}}</td>
									<td class="text-start">{{$attachment->name}}</td>
									<td class="text-start" dir="{{app()->getLocale() == 'ar' ? 'ltr' : 'rtl'}}">{{DATE($attachment->created_at)}}</td>
									<td><span class="badge bg-label-success me-1"><a href="{{asset('images/' . $attachment->file)}}" target="_blank">file</a></span></td>
									<td class="text-start">
										<div class="d-flex align-items-center">
											<a href="{{ route('attachments.destroy',[ $attachment->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
										</div>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				@endisset
			</div>
			@endsection
			@section("my-script")
				<script>
          $("body").on("click", "#add_file", function() {
            $("#parent_files").append(`
          <tr>
<!--              <td><span class="badge bg-label-success me-1"></span><input type="hidden" name="currencie[id]" class="currency_id" id="currency_id_" value=""></td>-->
              <td>
                <div class="input-group">
                    <label class="input-group-text" for="inputGroupFile01">إضافة ملف</label>
                    <input type="file" name="files[]" class="form-control" id="inputGroupFile01">
                </div>
              </td>
              <td>
                  <button type="button" class="deleteRow btn btn-danger"><i class="fa-solid fa-trash"></i></button>
            </td>
          </tr>
      `);
          });
          $("body").on("click", ".deleteRow", function() {
            var parent = $(this).parents("tr");
            var id = parent.find(".id_item").val();
            var values = [...new Set(JSON.parse($("#delete_ids").val()).concat([+id]))];
            $("#delete_ids").val(JSON.stringify(values));
            parent.remove();
          });
          $("body").on("click", ".chapter_button", function() {
            $("#text_button_active").text($(this).text());
          });

          /* Start Lecture  */


          let controls = [
            "play-large", // The large play button in the center
            "restart", // Restart playback
            "rewind", // Rewind by the seek time (default 10 seconds)
            "play", // Play/pause playback
            "fast-forward", // Fast forward by the seek time (default 10 seconds)
            "progress", // The progress bar and scrubber for playback and buffering
            "current-time", // The current time of playback
            "duration", // The full duration of the media
            "mute", // Toggle mute
            "volume", // Volume control
            "captions", // Toggle captions
            "settings", // Settings menu
            "pip", // Picture-in-picture (currently Safari only)
            "airplay", // Airplay (currently Safari only)
            "download", // Show a download button with a link to either the current source or a custom URL you specify in your options
            "fullscreen" // Toggle fullscreen
          ];

				</script>
@endsection