<?php

namespace App\Http\Controllers;

use App\Exports\BankTransactionExport;
use App\Exports\ExpenseDetailsExport;
use App\Http\Requests\ExpenseDetailsRequest;
use App\Models\AmountBank;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\Expense;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseRequest;
use App\Models\ExpenseDetails;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ExpensesController extends Controller
{
	public function getExpenses(Request $request)
	{
		// Expenses
		$expenses = Expense::orderBy('id', 'DESC')->get();
		return response(["data" => $expenses]);
	}

	public function index()
	{
		$this->authorize('show_expenses');
		$expenses = Expense::orderBy('id', 'DESC')->get();
		$currencies = Currency::orderBy('id', 'ASC')->get();
		$payment_methods = PaymentMethod::orderBy("id", "DESC")->get();
		$banks = Bank::orderBy("id", "DESC")->get();
		$users = getUsersRole("partner");
		return view("expenses.index", compact('expenses', "currencies", "payment_methods", "banks", "users"));
	}


	public function create()
	{
		$this->authorize('create_expenses');
		return view("expenses.create");
	}


	public function store(ExpenseRequest $request)
	{
		$this->authorize('create_expenses');
		$expense = Expense::create([
			"name_ar" => $request->name_ar,
			"name_en" => $request->name_en
		]);
		$description = " تم إنشاء مصروف " . $expense->name_ar;
		transaction("expenses", $description);
		$request->session()->put("success", trans("global.success_create"));
		return redirect()->route("expenses");
	}


	public function show(Expense $expenses)
	{
		//
	}


	public function edit($id)
	{
		$this->authorize('edit_expenses');
		$expense = Expense::findorFail($id);
		return view("expenses.edit", compact("expense"));
	}


	public function update($id, ExpenseRequest $request)
	{
		$this->authorize('edit_expenses');
		$expense = Expense::findorFail($id);
		$expense->update([
			"name_ar" => $request->name_ar,
			"name_en" => $request->name_en
		]);
		$description = " تم تعديل مصروف " . $expense->name_ar;
		transaction("expenses", $description);
		$request->session()->put("success", trans("global.success_update"));
		return redirect()->route("expenses");
	}

	public function pay_expenses(Request $request)
	{
		$this->authorize('pay_expenses');
		$amountBank = AmountBank::where("bank_id", $request->bank_id)->where("currency_id", $request->currency_id)->first();
		$currencyName = $amountBank->currency->name;
		$check = $amountBank->amount >= $request->amount ? true : false;
		if (!$check) {
			return redirect()->back()->with("error", "المبلغ المتاح في الخزنة ($amountBank->amount) بعملة $currencyName ");
		}
		$bank = Bank::find($request->bank_id);
		$expense = Expense::find($request->expense_id);
		$currency = Currency::find($request->currency_id);
		$amountBank->amount = $amountBank->amount - $request->amount;
		$amountBank->save();
		$description = " تم دفع مصروف " . $expense->name_ar . " من خزنة " . $bank->name_ar . " بمبلغ " . $request->amount . " " . $currency->name;
		ExpenseDetails::create([
			"description" => $description,
			"amount" => $request->amount,
			"expense_id" => $request->expense_id,
			"bank_id" => $request->bank_id,
			"currency_id" => $request->currency_id,
			"user_id" => $request->user_id,
		]);
		transaction("expenses", $description);
		$request->session()->put("success", trans("global.success_pay_expense"));
		return redirect()->route("expenses");
	}

	public function details(Request $request)
	{
		$this->authorize('details_expenses');
		$expenseDetails = ExpenseDetails::orderBy("id", "DESC")->get();
		$from = $request->from;
		$to = $request->to;
		$user = $request->user_id;
		$currency = $request->currency_id;
		$push = null;
		if ($user != null) {
			if ($currency != null) {
				$expenseDetails = ExpenseDetails::where("user_id", $user)->where("currency_id", $currency)->orderBy("id", "DESC")->get();
				$push = $expenseDetails->sum("amount");
			} else {
				$expenseDetails = ExpenseDetails::where("user_id", $user)->orderBy("id", "DESC")->get();
			}
		}
		if ($from != null && $to != null) {
			if ($user != null) {
				if ($currency != null) {
					$expenseDetails = ExpenseDetails::where("user_id", $user)->where("currency_id", $currency)->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->orderBy("id", "DESC")->get();
					$push = $expenseDetails->sum("amount");
				} else {
					$expenseDetails = ExpenseDetails::where("user_id", $user)->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->orderBy("id", "DESC")->get();
				}
			} else {
				$expenseDetails = ExpenseDetails::orderBy("id", "DESC")->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->get();
			}
		}
		foreach ($expenseDetails as $expenseDetail) {
			$expenseDetail->expense_name = $expenseDetail->expense["name_" . app()->getLocale()];
		}
		if ($request->has("claim_excel")) {
			$this->authorize('export');
			$description = " تم طباعة تقرير كامل Excel لتفاصيل المصروفات ";
			transaction("expense_details", $description);
			$expenseDetails->push = $push;
			return Excel::download(
				new ExpenseDetailsExport([
					"expenseDetails" => $expenseDetails,
				]),
				"expense_details" . Carbon::now() . ".xlsx"
			);
		}
//    dd($request);
		if ($request->has("claim_pdf")) {
			$this->authorize('print');
			$description = " تم طباعة تقرير كامل PDF لتفاصيل المصروفات ";
			transaction("expense_details", $description);
			$expenseDetails->push = $push;
			$pdf = PDF::loadView("pdf.expense_details", [
				"expenseDetails" => $expenseDetails,
			]);
			return $pdf->download("expense_details" . Carbon::now() . ".pdf");
		}
		$users = getUsersRole("partner");
		$currencies = Currency::orderBy('id', 'ASC')->get();
		return view('expenses.details', compact('expenseDetails', "users", "currencies", "push"));
	}


	public function destroy($id)
	{
		$this->authorize('remove_expenses');
		$expense = Expense::findorFail($id);
		$description = " تم حزف مصروف " . $expense->name_ar;
		transaction("expenses", $description);
		$expense->delete();
		session()->put('success', trans("global.success_delete"));
		return redirect()->route("expenses");
	}

	public function destroyAll(Request $request)
	{
		$this->authorize('remove_expenses');
		$expenses = Expense::whereIn('id', json_decode($request->ids))->get();
		$description = " تم حزف مصروفات (";
		foreach ($expenses as $expense) {
			$description .= ", " . $expense->name_ar . " ";
			$expense->delete();
		}
		$description .= " )";
		transaction("expenses", $description);
		session()->put('success', trans("global.success_delete_all"));
		return redirect()->route("expenses");
	}
}
