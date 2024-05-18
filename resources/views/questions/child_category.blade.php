<div>
	<label for="selectpickerSelectDeselect" class="form-label">{{ trans('global.questions') }}</label>
	<select id="selectpickerSelectDeselect" name="bank_question_id[]" class="selectpicker w-100 question_id @error('question_id') is-invalid @enderror" data-style="btn-default" data-allow-clear="true" data-actions-box="true" multiple>
		{{--		<option disabled selected>@lang("global.select_item")</option>--}}
		@foreach ($questions as $question)
			<option value="{{ $question->id }}">
				@if($question->type === "text")
					@php echo $question["question_".app()->getLocale()] @endphp
				@else
					{{$question->id}}# @lang("global.$question->type")
				@endif
			</option>
		@endforeach
	</select>
</div>
