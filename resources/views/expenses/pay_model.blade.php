<!-- Edit User Modal -->
<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-simple modal-edit-user">
		<div class="modal-content p-3 p-md-5">
			<div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<form action="{{ route('pay_expenses') }}" method="POST" class="row g-3">
					@csrf
					<div class="col-6">
						<label class="form-label" for="amount_input_model">@lang('global.amount')</label>
						<input required type="number" value="0" name="amount" id="amount_input_model" class="form-control" />
					</div>
					<div class="col-6">
						<label class="form-label" for="expense_id">@lang('global.expenses_name')</label>
						<select required class="form-control select2" name="expense_id" id="expense_id">
							@foreach ($expenses as $exp)
								<option value="{{ $exp->id }}">{{ $exp['name_' . App::getLocale()] }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-6">
						<label class="form-label" for="bank_id"> @lang('global.bank')</label>
						<select name="bank_id" id="bank_id" class="selectpicker w-100 form-control bank_id" data-style="btn-default">
							@foreach($banks as $bank)
								<option value="{{$bank->id}}">{{$bank["name_".app()->getLocale()]}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-6 mb-3">
						<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
						<select required id="currency_id" name="currency_id" class="select2 form-select form-select-lg @error('currency_id') is-invalid @enderror" data-allow-clear="true">
							@foreach ($currencies as $currency)
								<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id') == $currency->id) selected @endif>{{ $currency->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-12 mt-0">
						<label for="user_id" class="form-label">{{ trans('global.partners') }}</label>
						<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
							{{--                            <option disabled selected>@lang("global.select_item")</option>--}}
							@foreach ($users as $user)
								<option value="{{ $user->id }}" @if (old('user_id') == $user->id) selected @endif>{{ $user->name() }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-12 text-center">
						<button type="submit" class="btn btn-primary me-sm-3 me-1">@lang('global.pay')</button>
						<button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">@lang("global.cancel")</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--/ Edit User Modal -->
