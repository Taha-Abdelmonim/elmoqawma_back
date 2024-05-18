<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyRequest;
use App\Models\AmountBank;
use App\Models\Bank;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
  public function getCurrencies(Request $request)
  {
    $currencies = Currency::orderBy('id','DESC')->get();
    return response(["data" => $currencies]);
  }
  
    public function index()
    {
      $this->authorize('show_currencies');
      return view("currencies.currency");
    }
    
    public function create()
    {
      $this->authorize('create_currencies');
      return view("currencies.create");
    }
    
    public function store(CurrencyRequest $request)
    {
      $this->authorize('create_currencies');
      $currency = Currency::create([
        "name" => $request->name,
        "currency_symbol" => $request->currency_symbol,
        "currency_rate" => $request->currency_rate,
      ]);
      $banks = Bank::orderBy("id", "ASC")->get();
      foreach ($banks as $bank) {
        AmountBank::create([
          "amount" => 0,
          "currency_id" => $currency->id,
          "bank_id" => $bank->id,
        ]);
      }
      $description = " تم إنشاء عملة " . $currency->name;
      transaction("currencies", $description);
      if($request->ajax()){
        return returnData($currency);
      } else {
        $request->session()->put("success", trans("global.success_create"));
        return redirect()->route("currency");
      }
    }
   
    public function show()
    {
        //
    }

    
    public function edit($id)
    {
      $this->authorize('edit_currencies');
      $currency = Currency::findorFail($id);
      return view("currencies.edit", compact("currency"));
    }

    
    public function update($id, CurrencyRequest $request)
    {
      $this->authorize('edit_currencies');
      $currency = Currency::findorFail($id);
      $currency->update([
        "name" => $request->name,
        "currency_symbol" => $request->currency_symbol,
        "currency_rate" => $request->currency_rate,
      ]);
      $description = " تم تعديل عملة " . $currency->name;
      transaction("currencies", $description);
      $request->session()->put("success", trans("global.success_update"));
      return redirect()->route("currency");
    }

    
    public function destroy($id)
    {
      $this->authorize('remove_currencies');
      $currency = Currency::findorFail($id);
      $description = " تم حزف عملة " . $currency->name;
      transaction("currencies", $description);
      $currency->delete();
      session()->put('success', trans("global.success_delete"));
      return redirect()->route("currency");
    }
    public function destroyAll(Request $request)
    {
      $this->authorize('remove_currencies');
      $currencies = Currency::whereIn('id', json_decode($request->ids))->get();
      $description = " تم حزف عملات (";
      foreach ($currencies as $currency) {
        $description .= ", " . $currency->name . " ";
        $currency->delete();
      }
      $description .= " )";
      transaction("currencies", $description);
      session()->put('success', trans("global.success_delete_all"));
      return redirect()->route("currency");
    }
}
