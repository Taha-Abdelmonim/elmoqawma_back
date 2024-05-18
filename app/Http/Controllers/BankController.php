<?php

namespace App\Http\Controllers;

use App\Models\AmountBank;
use App\Models\Bank;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Models\Currency;
use Illuminate\Http\Request;

class BankController extends Controller
{
	public function getBank(Request $request)
	{
		// Expenses
		$bank = Bank::orderBy("id", "ASC")->get();
		return response(["data" => $bank]);
	}

	public function index()
	{
		$this->authorize("show_banks");
		$banks = Bank::orderBy("id", "DESC")->get();
		$currencies = Currency::orderBy("id", "asc")->get();
		$users = getUsersRole("partner");
		return view("bank.index", compact("banks", "currencies", "users"));
	}

	public function create()
	{
		$this->authorize("create_banks");
		$currencies = Currency::orderBy("id", "DESC")->get();

		return view("bank.create", compact("currencies"));
	}

	public function store(BankRequest $request)
	{
		$this->authorize("create_banks");
		$bank = Bank::create([
			"name_ar" => $request->name_ar,
			"name_en" => $request->name_en,
		]);
		if ($request->has('currencies')) {
			foreach ($request->currencies as $currency) {
				AmountBank::create([
					"amount" => $currency["amount"],
					"currency_id" => $currency["id"],
					"bank_id" => $bank->id,
				]);
			}
		} else {
			$currencies = Currency::orderBy('id', 'ASC')->get();
			foreach ($currencies as $currency) {
				AmountBank::create([
					"amount" => 0,
					"currency_id" => $currency->id,
					"bank_id" => $bank->id,
				]);
			}
		}
		$description = " تم إنشاء خزنة " . $bank->name_ar;
		transaction("banks", $description);
		$request->session()->put("success", trans("global.success_create"));
		return redirect()->route("bank");
	}

	public function show(Bank $bank)
	{
		//
	}

	public function edit($id)
	{
		$this->authorize("edit_banks");
		$bank = Bank::findorFail($id);
		$currencies = Currency::orderBy("id", "DESC")->get();
		$amountBanks = AmountBank::where("bank_id", $id)->orderBy("id", "DESC")->get();
		foreach ($amountBanks as $amountBank) {
			$amountBank->currency = $amountBank->currency;
		}
		return view("bank.edit", compact("bank", "currencies", "amountBanks"));
	}

	public function update($id, BankRequest $request)
	{
		$this->authorize("edit_banks");
		$bank = Bank::findorFail($id);
		$bank->update([
			"name_ar" => $request->name_ar,
			"name_en" => $request->name_en,
		]);
		if ($request->has('currencies')) {
			AmountBank::where("bank_id", $id)->delete();
			foreach ($request->currencies as $currency) {
				AmountBank::create([
					"amount" => $currency["amount"],
					"currency_id" => $currency["id"],
					"bank_id" => $bank->id,
				]);
			}
		}
		$description = " تم تعديل خزنة " . $bank->name_ar;
		transaction("banks", $description);
		$request->session()->put("success", trans("global.success_update"));
		return redirect()->route("bank");
	}

	public function destroy($id)
	{
		$this->authorize("remove_banks");
		$bank = Bank::findorFail($id);
		AmountBank::where("bank_id", $bank->id)->delete();
		$description = " تم حزف خزنة " . $bank->name_ar;
		transaction("banks", $description);
		$bank->delete();
		session()->put("success", trans("global.success_delete"));
		return redirect()->route("bank");
	}

	public function destroyAll(Request $request)
	{
		$this->authorize("remove_banks");
		foreach (json_decode($request->ids) as $id) {
			AmountBank::where("bank_id", $id)->delete();
		}
		$banks = Bank::whereIn("id", json_decode($request->ids))->get();
		$description = " تم حزف خزن (";
		foreach ($banks as $bank) {
			$description .= ", " . $bank->name_ar . " ";
			$bank->delete();
		}
		$description .= " )";
		transaction("banks", $description);
		session()->put("success", trans("global.success_delete_all"));
		return redirect()->route("bank");
	}
}
