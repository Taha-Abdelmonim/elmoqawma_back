<label for="lecture_id" class="form-label">{{ trans('global.lecture') }}</label>
<select required id="lecture_id" name="lecture_id" class="select2 form-select form-select-lg lecture_id  @error('lecture_id') is-invalid @enderror" data-allow-clear="true">
	<option disabled selected>@lang("global.chose_lecture")</option>
	@foreach ($lectures as $lecture)
		<option price="{{$lecture->price}}" currency="1" value="{{ $lecture->id }}" @if (old('lecture_id') == $lecture->id) selected @endif>{{ $lecture->title }}</option>
	@endforeach
</select>