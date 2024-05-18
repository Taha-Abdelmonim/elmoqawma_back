<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Staff Get Salaries</title>
	<style>
      body {
          font-family: DejaVu Sans, sans-serif;
      }

      div.invoice-print {
          min-width: 2000px !important;
      }

      .invoice-print svg {
          fill: #6f6b7d !important;
      }

      .invoice-print * {
          border-color: rgba(75, 70, 92, 0.5) !important;
          color: #6f6b7d !important;
      }

      .dark-style .invoice-print th {
          color: #fff !important;
      }

      thead {
          min-width: 2000px !important;
      }

      thead tr {
          background: #dfdfe3 !important;
      }

      thead tr th {
          font-size: 10px !important;
          white-space: nowrap !important;
          padding: 10px !important;

      }

      tbody tr td {
          font-weight: 700 !important;
          font-size: 10px !important;
          white-space: nowrap !important;
          text-align: center !important;
      }

      tbody tr.tr_data {
          background: #e6e6e6;
      }

      /*#dfdfe3*/
      @media print {
          .d-flex {
              display: flex !important;
          }

          .align-items-center {
              align-items: center !important;
          }

          .col-4 {
              width: 33.33333333% !important;
          }

          .row {
              display: flex !important;
              flex-wrap: wrap !important;
          }
      }
	</style>
</head>

<body dir="rtl">
<table class="datatables-ajax table">
	<thead>
	<tr>
		<th class="text-start">@lang('global.bank')</th>
		<th class="text-start"> @lang('global.user_operation')</th>
		<th class="text-start"> @lang('global.the_pay')</th>
		<th class="text-start">@lang('global.type') </th>
		<th class="text-start">{{ trans('global.currency') }}</th>
		<th class="text-start">@lang('global.amount')</th>
		<th class="text-start">@lang('global.balance_bank_after_operation')</th>
		<th class="text-start">{{ trans('global.operation_date') }}</th>
	</tr>
	</thead>
	<tbody>
	@foreach ($bankTransactions as $bankTransaction)
		<tr>
			<td class="text-start">{{ $bankTransaction->bankData["name_".app()->getLocale()] }} </td>
			<td class="text-start">{{ $bankTransaction->userData["name_" . app()->getLocale()] }}</td>
			<td class="text-start">{{ $bankTransaction->userPayData["name_".app()->getLocale()] }}</td>
			<td class="text-start">{{ $bankTransaction->type == 1 ? 'سحب' : 'ايداع' }}</td>
			<td class="text-start">{{ $bankTransaction->currencyData->name . " = " . $bankTransaction->currencyData->currency_symbol}}</td>
			<td class="text-start">{{ $bankTransaction->amount }}</td>
			<td class="text-start">{{ $bankTransaction->bank_amount_after }} </td>
			<td class="text-start">{{ DATE($bankTransaction->created_at) }}</td>
		</tr>
	@endforeach
	@if($bankTransactions->total != null)
		<tr class="tr_data">
			<td class="text-start">@lang("global.push")</td>
			<td class="text-start">{{$bankTransactions->push}}</td>
			<td class="text-start"></td>
			<td class="text-start">@lang("global.pull")</td>
			<td class="text-start">{{$bankTransactions->pull}}</td>
			<td class="text-start"></td>
			<td class="text-start">@lang("global.total")</td>
			<td class="text-start">{{$bankTransactions->total}}</td>
		</tr>
	@endif
	</tbody>
</table>

</body>

</html>
