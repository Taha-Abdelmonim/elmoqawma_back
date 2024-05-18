@foreach ($questions as $question)
	<tr>
		<td class="text-start"><input type="checkbox" data-id="{{$question->id}}" name="deleteAll[]" class="delete-all dt-checkboxes form-check-input me-3">{{$question->id}}</td>
		<td class="text-start">{{$question->order}}</td>
		<td class="text-start ">
			@if($question->type === "text")
				<span class='text-truncate  fw-bold badge bg-label-success w-100 overflow-hidden m-0' style="height: 100px">@php echo $question["question_" . app()->getLocale()] @endphp</span>
			@elseif ($question->type === "image")
				<a href="{{asset("images/$question->file")}}" target="_blank" class="avatar avatar-xl d-block ">
					<img src="{{asset("images/$question->file")}}" alt="Avatar" class="rounded-circle object-cover">
				</a>
			@elseif ($question->type === "video")
				<a href="{{asset("images/$question->file")}}" target="_blank" class="avatar avatar-xl d-block ">@lang("global.video")</a>
			@elseif ($question->type === "audio")
				<audio controls class="w-100">
					<source src="{{asset("images/$question->file")}}" type="audio/mpeg">
					Your browser does not support the audio tag.
				</audio>
			@endif
		</td>
		<td class="text-start">{{DATE($question->created_at)}}</td>
		<td class="text-start">
			<div class="d-flex align-items-center">
				<a href="{{route('bank_question_edit', [$bank_category_id, $question->id])}}" class="text-body ms-1"><i class="ti ti-edit ti-sm me-2"></i></a>
				<a href="{{route('bank_question_delete', [$bank_category_id, $question->id])}}" onclick="return confirm('هل انت متأكد')" class="text-body "><i class="ti ti-trash ti-sm mx-2"></i></a>
			</div>
		</td>
	</tr>
@endforeach