<?php

namespace App\Http\Controllers;


use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\Whatsapp;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\BankTransaction;
use App\Models\ExpenseDetails;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use DB;
use App\Traits\GlobalTrait;
use Mpdf\Http\Request;

class WhatsappController extends Controller
{
	use GlobalTrait;

	function __construct()
	{
		//    $this->middleware('permission:dashboard', ['only' => ['index']]);
		//    dd($this->middleware('permission:dashboard', ['only' => ['index']]));
	}

	public function index()
	{
		$this->authorize('whatsapp');
		$users = Whatsapp::orderBy('id', 'DESC')->get();
		return view("whatsapp", compact("users"));
	}

	public function send_whatsapp(Request $request)
	{
		return response()->json(["data" => $this->sendMessageWhatsapp()], 200);
	}

	public function import()
	{
		Excel::import(new UsersImport, request()->file('file'));
		$users = Whatsapp::orderBy('id', 'DESC')->get();
		return view("whatsapp_users", compact("users"));
	}

	public function export()
	{
		return Excel::download(new UsersExport, 'users.xlsx');
	}

	public function delete($id)
	{
		Whatsapp::find($id)->delete();
		return back()->with("success", trans("global.success_delete"));
	}


}
