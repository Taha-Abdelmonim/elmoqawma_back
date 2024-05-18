@extends('layouts/layoutMaster')

@section('title', trans('global.bank_transactions'))

@section('vendor-style')
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
	@can("export")
		<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
	@endcan
	<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

@endsection

@section('vendor-script')
	<script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
	<script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('page-script')
	<script src="{{ asset('assets/js/forms-pickers.js') }}"></script>
	<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
	<script>
    $(".datatables-ajax").DataTable();
	</script>
@endsection

@section('content')

	<div class="card my-3 p-4">
		<form method="get">
			<div class="row">
				<div class="col-6 mb-3 mt-0">
					<label for="user_id" class="form-label">{{ trans('global.partners') }}</label>
					<select id="user_id" name="user_id" class="select2 form-select form-select-lg user_id @error('user_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($users as $user)
							<option value="{{ $user->id }}" @if (old('user_id', Request::input("user_id")) == $user->id) selected @endif>{{ $user->name() }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-6 mb-3">
					<label for="currency_id" class="form-label">{{ trans('global.currency') }}</label>
					<select required id="currency_id" name="currency_id" class="select2 form-select form-select-lg @error('currency_id') is-invalid @enderror" data-allow-clear="true">
						<option disabled selected>@lang("global.select_item")</option>
						@foreach ($currencies as $currency)
							<option value="{{ $currency->id }}" rate="{{$currency->currency_rate}}" symbol="{{$currency->currency_symbol}}" @if (old('currency_id', Request::input("currency_id")) == $currency->id) selected @endif>{{ $currency->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-4">
					<label for="pickup_time" class="form-label">@lang('global.from')</label>
					<input type="text" name="from" value="{{Request::input("from")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-4">
					<label for="pickup_time" class="form-label">@lang('global.to')</label>
					<input type="text" name="to" value="{{Request::input("to")}}" class="form-control flatpickr-input flatpickr-date active pickup_time" placeholder="YYYY-MM-DD HH:MM" id="pickup_time" readonly="readonly">
				</div>
				<div class="col-4 d-flex align-items-end">
					<button type="submit" class="btn btn-primary w-100">Search</button>
				</div>
			</div>
		</form>
	</div>
	<div class="card">
		<div class="card">
			<div class="row p-4">
				@php $char = request()->input() == [] ? "?" : "&";@endphp
				@can("print")
					<div class="col-6 my-2">
						<a href="{{ request()->fullUrl() }}{{$char}}claim_pdf=true" class="btn btn-danger w-100" target="_blank">
							<i class="fas fa-file-excel"></i> PDF
						</a>
					</div>
				@endcan
				@can("export")
					<div class="col-6 my-2">
						<a href="{{ request()->fullUrl() }}{{$char}}claim_excel=true" class="btn btn-success w-100" target="_blank">
							<i class="fas fa-file-excel"></i> Excel
						</a>
					</div>
				@endcan
			</div>
			<div class="card-datatable text-nowrap table-responsive">
				<table class="datatables-ajax table">
					<thead>
					<tr>
						<th class="text-start">@lang('global.bank')</th>
						<th class="text-start"> @lang('global.user_operation')</th>
						<th class="text-start"> @lang('global.the_pay')</th>
						<th class="text-start">@lang('global.type') </th>
						<th class="text-start">{{ trans('global.currency') }}</th>
						<th class="text-start">@lang('global.balance_bank_before_operation')</th>
						<th class="text-start">@lang('global.amount')</th>
						<th class="text-start">@lang('global.balance_bank_after_operation')</th>
						<th class="text-start">{{ trans('global.operation_date') }}</th>
					</tr>
					</thead>
					<tbody>
					@foreach ($bankTransactions as $bankTransaction)
						<tr>
							<td class="text-start">{{ $bankTransaction->bankData["name_".app()->getLocale()] }} </td>
							<td class="text-start"><a href="/user/{{$bankTransaction->userData->id}}" target="_blank">{{ $bankTransaction->userData["name_" . app()->getLocale()] }}</a></td>
							<td class="text-start"><a href="/user/{{$bankTransaction->userPayData->id}}" target="_blank">{{ $bankTransaction->userPayData["name_".app()->getLocale()] }}</a></td>
							<td class="text-start">{{ $bankTransaction->type == 1 ? 'سحب' : 'ايداع' }}</td>
							<td class="text-start">{{ $bankTransaction->currencyData->name . " = " . $bankTransaction->currencyData->currency_symbol}}</td>
							<td class="text-start">{{ $bankTransaction->balanceBankBefor }} </td>
							<td class="text-start">{{ $bankTransaction->amount }}</td>
							<td class="text-start">{{ $bankTransaction->bank_amount_after }} </td>
							<td class="text-start">{{ DATE($bankTransaction->created_at) }}</td>
						</tr>
					@endforeach

					</tbody>
				</table>
			</div>
			@if($total != null)
				<div class="row p-4">
					<div class="col-4 d-flex align-items-center">
						<h4 class="text-start mb-0 me-3">@lang("global.push"): </h4>
						<span class="text-start fs-4 badge bg-label-success"> {{$push}}</span>
					</div>
					<div class="col-4 d-flex align-items-center">
						<h4 class="text-start mb-0 me-3">@lang("global.pull"): </h4>
						<span class="text-start fs-4 badge bg-label-danger"> {{$pull}}</span>
					</div>
					<div class="col-4 d-flex align-items-center">
						<h4 class="text-start mb-0 me-3">@lang("global.total"): </h4>
						<span class="text-start fs-4 badge bg-label-primary"> {{$total}}</span>
					</div>
				</div>
			@endif
		</div>
	</div>
@endsection
