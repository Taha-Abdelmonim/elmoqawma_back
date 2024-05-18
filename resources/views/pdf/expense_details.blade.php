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
		<th>@lang("global.number")</th>
		<th class="text-start">{{ trans('global.expenses_name') }}</th>
		<th class="text-start"> @lang('global.the_pay')</th>
		<th class="text-start">{{ trans('global.description') }}</th>
		<th class="text-start">{{ trans('global.amount') }}</th>
		<th class="text-start">{{ trans('global.created_at') }}</th>
	</tr>
	</thead>
	<tbody>
	@foreach ($expenseDetails as $expenseDetail)
		<tr>
			<td class="text-start">{{ $expenseDetail->id }}</td>
			<td class="text-start">{{ $expenseDetail->expense_name }}</td>
			<td class="text-start">{{ $expenseDetail->user->name() }}</td>
			<td class="text-start">{{ $expenseDetail->description }}</td>
			<td class="text-start">{{ $expenseDetail->amount }}</td>
			<td class="text-start">{{ DATE($expenseDetail->created_at) }}</td>
		</tr>
	@endforeach
	@if($expenseDetails->push != null)
		<tr class="tr_data">
			<td colspan="4" class="text-start">@lang("global.push")</td>
			<td colspan="2" class="text-start">{{$expenseDetails->push}}</td>
		</tr>
	@endif
	</tbody>
</table>

</body>

</html>
