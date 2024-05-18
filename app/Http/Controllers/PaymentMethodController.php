<?php
  
  namespace App\Http\Controllers;
  
  use App\Http\Controllers\Controller;
  use App\Http\Requests\PaymentMethodRequest;
  use App\Models\PaymentMethod;
  use Illuminate\Http\Request;
  
  class PaymentMethodController extends Controller
  {
    public function getPaymentMethods(Request $request)
    {
      $payment_methods = PaymentMethod::orderBy('id','DESC')->get();
      return response(["data" => $payment_methods]);
    }
    
    public function index()
    {
      $this->authorize('show_payment_methods');
      return view("payment_methods.payment_method");
    }
    public function create()
    {
      $this->authorize('create_payment_methods');
      return view("payment_methods.create");
    }
    
    public function store(PaymentMethodRequest $request)
    {
      $this->authorize('create_payment_methods');
      $result = assignValue($request, "name_ar", "name_en", trans("global.name"));
      if (gettype($result) != "string" and  $result->getStatusCode() == 302) {
        return $result;
      }
      $paymentMethod = PaymentMethod::create([
        "name_ar" => $request->name_ar,
        "name_en" => $request->name_en,
      ]);
      $description = " تم إنشاء طريقة دفع " . $paymentMethod->name_ar;
      transaction("payment_methods", $description);
      if($request->ajax()){
        return returnData($paymentMethod);
      } else {
        $request->session()->put("success", trans("global.success_create"));
        return redirect()->route("payment_method");
      }
    }
    
    
    public function show()
    {
      //
    }
    
    public function edit($id)
    {
      $this->authorize('edit_payment_methods');
      $payment_method = PaymentMethod::findorFail($id);
      return view("payment_methods.edit", compact("payment_method"));
    }
    
    
    public function update($id, PaymentMethodRequest $request)
    {
      $this->authorize('edit_payment_methods');
      $payment_method = PaymentMethod::findorFail($id);
      $result = assignValue($request, "name_ar", "name_en", trans("global.name"));
      if (gettype($result) != "string" and  $result->getStatusCode() == 302) {
        return $result;
      }
      $payment_method->update([
        "name_ar" => $request->name_ar,
        "name_en" => $request->name_en,
      ]);
      $description = " تم تعديل طريقة دفع " . $payment_method->name_ar;
      transaction("payment_methods", $description);
      $request->session()->put("success",trans("global.success_update"));
      return redirect()->route("payment_method");
    }
    
    
    public function destroy($id)
    {
      $this->authorize('remove_payment_methods');
      $payment_method = PaymentMethod::findorFail($id);
      $description = " تم حزف طريقة دفع " . $payment_method->name_ar;
      transaction("payment_methods", $description);
      $payment_method->delete();
      session()->put('success', trans("global.success_delete"));
      return redirect()->route("payment_method");
    }
    public function destroyAll(Request $request)
    {
      $this->authorize('remove_payment_methods');
      $PaymentMethods = PaymentMethod::whereIn('id', json_decode($request->ids))->get();
      $description = " تم حزف طرق دفع (";
      foreach ($PaymentMethods as $PaymentMethod) {
        $description .= ", " . $PaymentMethod->name_ar . " ";
        $PaymentMethod->delete();
      }
      $description .= " )";
      transaction("payment_methods", $description);
      session()->put('success', trans("global.success_delete_all"));
      return redirect()->route("payment_method");
    }
  }
