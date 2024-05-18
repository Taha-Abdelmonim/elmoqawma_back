<div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-simple modal-edit-user">
		<div class="modal-content p-3 p-md-5">
			<div class="modal-body">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<form class="row g-3" action="{{ route('bank_transaction_save') }}" id="bank_money_transaction" method="POST">
					@csrf
					<div class="col-12">
						<label class="form-label" for="statement">@lang('global.statement')</label>
						<input type="text" name="statement" id="statement" placeholder="@lang('global.statement')" class="form-control" />
					</div>
					<div class="col-6">
						<label for="amount_trans" class="form-label">@lang('global.amount')</label>
						<input type="text" value="0" name="amount" id="amount_trans" class="form-control" />
					</div>
					<div class="col-6">
						<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
						<select required id="currency_id" name="currency_id" class="selectpicker w-100 form-control @error('currency_id') is-invalid @enderror" data-allow-clear="true" data-style="btn-default">
							@foreach ($currencies as $currency)
								<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id') == $currency->id) selected @endif>{{ $currency->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-6">
						<label class="form-label" for="bank_id"> @lang('global.bank')</label>
						<select name="bank_id" id="bank_id" class="selectpicker w-100 form-control bank_id">
							@foreach($banks as $bank)
								<option value="{{$bank->id}}">{{$bank["name_".app()->getLocale()]}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-6">
						<label for="type" class="form-label"> @lang('global.operation')</label>
						<select id="type" name="type" class="selectpicker w-100 form-control type" data-style="btn-default">
							<option value="1">سحب</option>
							<option value="2">ايداع</option>
						</select>
					</div>
					<div class="col-12 mt-3">
						<label for="user_pay_id" class="form-label">{{ trans('global.partners') }}</label>
						<select id="user_pay_id" name="user_pay_id" class="select2 form-select form-select-lg user_pay_id @error('user_pay_id') is-invalid @enderror" data-allow-clear="true">
							@foreach ($users as $user)
								<option value="{{ $user->id }}" @if (old('user_pay_id') == $user->id) selected @endif>{{ $user->name() }}</option>
							@endforeach
						</select>
					</div>
					<div class="col-12 text-center">
						<button id="save_bank_money" type="submit" class="btn btn-primary me-sm-3 me-1 w-100"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang('global.save')</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
