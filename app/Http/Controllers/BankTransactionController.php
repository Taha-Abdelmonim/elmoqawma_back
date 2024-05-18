<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\BankTransactionExport;
use App\Models\AmountBank;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Currency;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class BankTransactionController extends Controller
{

	public function bankTransactions(Request $request)
	{
		$this->authorize('bank_transactions');
		$bankTransactions = BankTransaction::orderBy("type", "DESC")->get();
		$from = $request->from;
		$to = $request->to;
		$user = $request->user_id;
		$currency = $request->currency_id;
		$pull = 0;
		$push = 0;
		$total = null;
		if ($user != null) {
			if ($currency != null) {
				$bankTransactions = BankTransaction::where("user_pay_id", $user)->where("currency_id", $currency)->orderBy("id", "DESC")->get();
				$pull = $bankTransactions->where("type", 1)->sum("amount");
				$push = $bankTransactions->where("type", 2)->sum("amount");
				$total = $push - $pull;
			} else {
				$bankTransactions = BankTransaction::where("user_pay_id", $user)->orderBy("id", "DESC")->get();
			}
		}
		if ($from != null && $to != null) {
			if ($user != null) {
				if ($currency != null) {
					$bankTransactions = BankTransaction::where("user_pay_id", $user)->where("currency_id", $currency)->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->orderBy("id", "DESC")->get();
					$pull = $bankTransactions->where("type", 1)->sum("amount");
					$push = $bankTransactions->where("type", 2)->sum("amount");
					$total = $push - $pull;
				} else {
					$bankTransactions = BankTransaction::where("user_pay_id", $user)->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->orderBy("id", "DESC")->get();
				}
			} else {
				$bankTransactions = BankTransaction::orderBy("id", "DESC")->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->get();
			}
		}

		foreach ($bankTransactions as $bankTransaction) {
			$bankTransaction->bankData = $bankTransaction->bank;
			$bankTransaction->currencyData = $bankTransaction->currency;
			$bankTransaction->userData = $bankTransaction->user;
			$bankTransaction->userPayData = $bankTransaction->userPay($bankTransaction->user_pay_id);
			$bankTransaction->balanceBankBefor = $bankTransaction->type == 2 ? $bankTransaction->bank_amount_after - $bankTransaction->amount : $bankTransaction->bank_amount_after + $bankTransaction->amount;
		}
		if ($request->has("claim_excel")) {
			$description = " تم طباعة تقرير كامل Excel لحركة الخزنة ";
			transaction("banks", $description);
			$bankTransactions->pull = $pull;
			$bankTransactions->push = $push;
			$bankTransactions->total = $total;
			return Excel::download(
				new BankTransactionExport([
					"bankTransactions" => $bankTransactions,
				]),
				"bankTransactions" . Carbon::now() . ".xlsx"
			);
		}
//    dd($request);
		if ($request->has("claim_pdf")) {
			$description = " تم طباعة تقرير كامل PDF لحركة الخزنة ";
			transaction("banks", $description);
			$bankTransactions->pull = $pull;
			$bankTransactions->push = $push;
			$bankTransactions->total = $total;
			$pdf = PDF::loadView("pdf.bank_transactions", [
				"bankTransactions" => $bankTransactions,
			]);
//			$uniq = uniqid();
			return $pdf->download("bank_transactions" . Carbon::now() . ".pdf");
		}
		$users = getUsersRole("partner");
		$currencies = Currency::orderBy('id', 'ASC')->get();
		return view("bank.bank_transactions", compact("bankTransactions", "users", "currencies", "pull", "push", "total"));
	}

	public function checkBankMoney(Request $request)
	{
		$amountBank = AmountBank::where("bank_id", $request->bank_id)->where("currency_id", $request->currency_id)->first();
		$currencyName = $amountBank->currency->name;
		$check = $amountBank->amount >= $request->amount ? true : false;
		$message = "";
		if (!$check) {
			$message = "المبلغ المتاح في الخزنة ($amountBank->amount) بعملة $currencyName ";
		}
		return returnData($check, $message);
	}

	public function store(Request $request)
	{
		$this->authorize('bank_transaction_operation');
		$bank = Bank::find($request->bank_id);
		$amountBank = AmountBank::where("bank_id", $request->bank_id)->where("currency_id", $request->currency_id)->first();
		$message = "تم سحب مبلغ " . $request->amount . " من " . $bank["name_" . app()->getLocale()];
		if ($request->type == 1) {

			$amountBank->amount = $amountBank->amount - $request->amount;
			$amountBank->save();
			BankTransaction::create(
				[
					'statement' => $request->statement,
					'bank_id' => $bank->id,
					'user_id' => Auth::user()->id,
					'user_pay_id' => $request->user_pay_id,
					'amount' => $request->amount,
					'bank_amount_after' => $amountBank->amount,
					'currency_id' => $request->currency_id,
					'type' => 1,
				]
			);
		} else {
			$message = "تم ايداع مبلغ " . $request->amount . " الي " . $bank["name_" . app()->getLocale()];
			$amountBank->amount = $amountBank->amount + $request->amount;
			$amountBank->save();
			BankTransaction::create(
				[
					'statement' => $request->statement,
					'bank_id' => $bank->id,
					'user_id' => Auth::user()->id,
					'user_pay_id' => $request->user_pay_id,
					'amount' => $request->amount,
					'bank_amount_after' => $amountBank->amount,
					'currency_id' => $request->currency_id,
					'type' => 2,
				]);
		}
		transaction("banks", $message);
		$request->session()->put("success", $message);
		return redirect()->back();
	}
}
