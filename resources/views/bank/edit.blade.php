@extends('layouts/layoutMaster')

@section('title', trans('global.edit_bank'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('content')
	{{--  {{Route::currentRouteName()}} --}}
	@php
		$links = [
				'start' => trans('global.bank'),
				'/' => trans('global.dashboard'),
				'/bank' => trans('global.all_bank'),
				'end' => trans('global.edit_bank'),
		];
	@endphp
	@include('layouts.breadcrumbs')
	<!-- Multi Column with Form Separator -->
	<div class="card mb-4">
		<h5 class="card-header">{{ trans('global.edit_bank') }}</h5>
		<form class="card-body" action="{{ route('bank_update', $bank->id) }}" method="POST"
		      enctype="multipart/form-data">
			@csrf
			{{--      <h6>1. Account Details</h6> --}}
			<div class="row g-3">
				@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif
				<div class="col-md-6">
					<label class="form-label" for="name_ar">{{ trans('global.name_ar') }}</label>
					<input value="{{ old('name_ar', $bank->name_ar) }}" name="name_ar" type="text" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror" placeholder="{{ trans('global.name_ar') }}" />
				</div>
				<div class="col-md-6">
					<label class="form-label " for="name_en">{{ trans('global.name_en') }}</label>
					<input value="{{ old('name_en', $bank->name_en) }}" name="name_en" type="text" id="name_en" class="form-control @error('name_en') is-invalid @enderror" placeholder="{{ trans('global.name_en') }}" />
				</div>
				<div class="row g-3">
					<div class="col-12">
						<table class="table table-striped table-bordered">
							<tbody>
							<tr>
								<td>@lang("global.balance")</td>
								<td id="td_payment_type">
									<div class="row">
										<div class="col-6">
											<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
											<select required id="currency_id" name="currency_id" class="select2 form-select @error('currency_id') is-invalid @enderror" data-allow-clear="true" data-style="btn-default">
												@foreach ($currencies as $currency)
													<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id') == $currency->id) selected @endif>{{ $currency->name }}</option>
												@endforeach
											</select>
										</div>
										<div class="col-5">
											<label for="input_amount" class="form-label text-nowrap me-3">@lang('global.amount')</label>
											<input type="text" class="form-control" value="0" id="input_amount">
										</div>
										<div class="col-1 d-flex justify-content-center align-items-end">
											<button type="button" class="btn btn-primary w-75" id="add_payment"><i class="ti ti-plus ti-sm"></i></button>
										</div>
									</div>
								</td>
							<tr>
								<div class="table-responsive text-nowrap">
									<table class="table">
										<thead>
										<tr>
											<th colspan="15">@lang("global.balance")</th>
											<th colspan="15">@lang("global.amount")</th>
											<th colspan="5">@lang("global.actions")</th>
										</tr>
										</thead>
										<tbody class="table-border-bottom-0" id="parent_payment">
										@foreach($amountBanks as $amountBank)
											<tr>
												<td colspan="15"><span class="badge bg-label-success me-1">{{$amountBank->currency->name}}</span><input type="hidden" name="currencies[{{$amountBank->id}}][id]" class="currency_id"
												                                                                                                        id="currency_id_{{$amountBank->currency->id}}" value="{{$amountBank->currency->id}}"></td>
												<td colspan="15">{{$amountBank->amount}}<input type="hidden" name="currencies[{{$amountBank->id}}][amount]" class="amount_payment" value="{{$amountBank->amount}}"></td>
												<td colspan="5">
													<div class="d-flex align-items-center gap-3">
														<button type="button" class="deleteRowPayment btn btn-danger bg-transparent"><i class="fa-solid fa-trash text-danger"></i></button>
													</div>
												</td>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="pt-4">
					<button type="submit" class="btn btn-primary me-sm-3 me-1"><i class="fa-solid fa-floppy-disk me-0 me-sm-1 ti-xs"></i>@lang("global.save")</button>
				</div>
			</div>
		</form>
	</div>
@endsection
@section("my-script")
	<script>
    var indexPayment = {{$amountBanks->count() == 0 ? 0 : $amountBanks[0]->id}} + 1;
    $("body").on("click", "#add_payment", function() {
      var text = $("#currency_id").find("option:selected").text();
      var value = $("#currency_id").find("option:selected").val();
      var amount = +$("#input_amount").val();
      var parentPayment = $(`#currency_id_${value}`).parents("tr");
      if (parentPayment.length != 0) {
        parentPayment.before(`
                    <tr>
                        <td colspan="15"><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="currencies[${indexPayment}][id]" class="currency_id" id="currency_id_${value}" value="${value}"></td>
                        <td colspan="15">${amount}<input type="hidden" name="currencies[${indexPayment}][amount]" class="amount_payment" value="${amount}"></td>
                        <td colspan="5">
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="deleteRowPayment btn btn-danger bg-transparent"><i class="fa-solid fa-trash text-danger"></i></button>
                            </div>
                        </td>
                    </tr>
                `);
        parentPayment.remove();
      } else {
        $("#parent_payment").append(`
                    <tr>
                        <td colspan="15"><span class="badge bg-label-success me-1">${text}</span><input type="hidden" name="currencies[${indexPayment}][id]" class="currency_id" id="currency_id_${value}" value="${value}"></td>
                        <td colspan="15">${amount}<input type="hidden" name="currencies[${indexPayment}][amount]" class="amount_payment" value="${amount}"></td>
                        <td colspan="5">
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" class="deleteRowPayment btn btn-danger bg-transparent"><i class="fa-solid fa-trash text-danger"></i></button>
                            </div>
                        </td>
                    </tr>
                `);
      }
      $("#input_amount").val(0);
      indexPayment++;
    });
    $("body").on("click", ".deleteRowPayment", function() {
      $(this).parents("tr").remove();
    });
	</script>
@endsection
