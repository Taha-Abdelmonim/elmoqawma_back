<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\BankTransactionExport;
use App\Models\AmountBank;
use App\Models\Bank;
use App\Models\BankTransaction;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class TransactionController extends Controller
{
	public function index(Request $request)
	{
		$this->authorize("transactions");
		$from = $request->from;
		$to = $request->to;
		$user = $request->user_id;
		$transactions = Transaction::orderBy("id", "DESC")->get();
		$users = User::whereNotNull("roles_name")->orderBy("id", "DESC")->get();
		if ($user != null) {
			$transactions = Transaction::where("user_id", $user)->orderBy("id", "DESC")->get();
		}
		if ($from != null and $to != null) {
			if ($user != null) {
				$transactions = Transaction::where("user_id", $user)->whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->orderBy("id", "DESC")->get();
			} else {
				$transactions = Transaction::whereDate("created_at", ">=", $from)->whereDate("created_at", "<=", $to)->orderBy("id", "DESC")->get();
			}
		}
		return view("transactions", compact("transactions", "users"));
	}
}
