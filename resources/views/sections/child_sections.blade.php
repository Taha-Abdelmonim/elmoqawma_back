<div>
  <label for="section_id" class="form-label">{{ trans('global.sections_branch') }}</label>
  <select name="section_id" class="select2 form-select form-select-lg section_id @error('section_id') is-invalid @enderror" data-allow-clear="true">
    <option value="{{$sections[0]->section_id}}" hidden="hidden" selected>{{$sections[0]->section($sections[0]->section_id)}}</option>
    @foreach ($sections as $section)
      <option value="{{ $section->id }}" >{{ $section["name_".app()->getLocale()] }}</option>
    @endforeach
  </select>
</div>
