<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use App\Models\Course;
use App\Models\DetailExam;
use App\Models\ExpenseDetails;
use App\Models\Order;
use App\Models\User;
use App\Notifications\MessageNotifications;
use Carbon\Carbon;
use DB;
use App\Traits\GlobalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{
	use GlobalTrait;

	function __construct()
	{
//		12b250c6-645e-4a61-8ee0-e74007af32039a735fc5-dd81-418d-8a07-2450e74a02b5
		//    $this->middleware('permission:dashboard', ['only' => ['index']]);
		//    dd($this->middleware('permission:dashboard', ['only' => ['index']]));
	}


	public function filePut()
	{
		return view("test");
	}

	public function notifications()
	{
		$users = User::orderBy("id", "DESC")->get();
		return view("notifications", compact("users"));
	}

	public function sendNotifications(Request $request)
	{
		if (empty($request->ids)) return back()->with("error", trans("global.users_not_chose"));
		$users = User::whereIn("id", json_decode($request->ids))->get();
		Notification::send($users, new MessageNotifications($request->message));
		return back()->with("success", trans("global.success_create"));
	}

	public function index()
	{
//		dd(Course::with(["comments" => ["user"], "section", "teacher", "subject", "currency", "files", "chapters.lectures"])->find(1));
//		dd(Carbon::today()->addDay());
		$this->authorize("dashboard");

		$supports_done_problem = \App\Models\Support::where("done_problem", 1)->get()->count();
		$supports_done_contact = \App\Models\Support::where("done_contact", 1)->get()->count();
		$supports_uncontact = \App\Models\Support::where("done_contact", 0)->get()->count();
		$supports_deleted = \App\Models\Support::where("deleted_at", "!=", null)->get()->count();
		$supports_unread = \App\Models\Support::where("done_read", 0)->get()->count();
		$supports_count = \App\Models\Support::all()->count();
		$users_count = \App\Models\User::all()->count();
		$this->authorize("dashboard");
		// $supports_unread = \App\Models\Support::where('done_read', 0)->get()->count();
		$settings_count = \App\Models\Setting::all()->count();
		$roles_count = \Spatie\Permission\Models\Role::all()->count();
		$permissions_count = \Spatie\Permission\Models\Permission::all()->count();
		$sections_count = \App\Models\Section::all()->count();
		$sections_count_active = \App\Models\Section::all()->count();
		$review_count = \App\Models\Review::all()->count();
		$coursesIDS = Course::Courses()->get()->pluck("id");
		$comment_count = \App\Models\Comment::whereIn("course_id", $coursesIDS)->get()->count();

		$limit = 6;

		$latest_users = User::get()->take($limit);
		$latest_roles = DB::table("roles")->latest("id")->limit($limit)->get();
		$latest_permissions = DB::table("permissions")->latest("id")->limit($limit)->get();
		$latest_sections = DB::table("sections")->latest("id")->limit($limit)->get();

		$latest_comments = \App\Models\Comment::whereIn("course_id", $coursesIDS)->latest("id")->limit($limit)->get();

		$latest_reviews = \App\Models\Review::latest("id")->limit($limit)->get();
		$latest_settings = DB::table("settings")->latest("id")->limit($limit)->get();
		$latest_supports = DB::table("supports")->latest("id")->limit($limit)->get();
		$latest_expenses = DB::table("expenses")->latest("id")->limit($limit)->get();
		$expenseDetails = ExpenseDetails::whereDate("created_at", Carbon::today())->orderBy("created_at", "DESC")->get();
		$totalExpense = currencyEGP($expenseDetails);

		$bankTransactions = BankTransaction::whereDate("created_at", Carbon::today())->where("bank_id", 1)->orderBy("created_at", "DESC")->get();
		$pullBankTransactions = currencyEGP($bankTransactions->where("type", 1));
		$pushBankTransactions = currencyEGP($bankTransactions->where("type", 2));
		$totalBankTransactions = $pushBankTransactions - $pullBankTransactions;

		$orders = Order::Orders()->whereDate("created_at", Carbon::today())->orderBy("created_at", "DESC")->get();
		$totalOrders = currencyEGP($orders, "price");
		$ordersStatus = Order::Orders()->orderBy("created_at", "DESC")->get();
		$ordersActive = $ordersStatus->where("status", 1)->count();
		$ordersInActive = $ordersStatus->where("status", 0)->count();
		$allOrders_2023 = Order::whereYear("created_at", date("Y"))->get();
		$totalOrdersYear_2023 = currencyEGP($allOrders_2023, "price");
		$allOrders_2024 = Order::whereYear("created_at", "2024")->get();
		$totalOrdersYear_2024 = currencyEGP($allOrders_2024, "price");
		$allOrders_2025 = Order::whereYear("created_at", "2025")->get();
		$totalOrdersYear_2025 = currencyEGP($allOrders_2025, "price");

		$bankTransactions_2023 = BankTransaction::whereYear("created_at", date("Y"))->where("bank_id", 1)->get();
		$bankTransactions_2024 = BankTransaction::whereYear("created_at", "2024")->where("bank_id", 1)->get();
		$bankTransactions_2025 = BankTransaction::whereYear("created_at", "2025")->where("bank_id", 1)->get();
		$pullBankTransactions_2023 = currencyEGP($bankTransactions_2023->where("type", 1));
		$pullBankTransactions_2024 = currencyEGP($bankTransactions_2024->where("type", 1));
		$pullBankTransactions_2025 = currencyEGP($bankTransactions_2025->where("type", 1));
		$pushBankTransactions_2023 = currencyEGP($bankTransactions_2023->where("type", 2));
		$pushBankTransactions_2024 = currencyEGP($bankTransactions_2024->where("type", 2));
		$pushBankTransactions_2025 = currencyEGP($bankTransactions_2025->where("type", 2));

		$expenseDetails_2023 = ExpenseDetails::whereYear("created_at", date("Y"))->get();
		$expenseDetails_2024 = ExpenseDetails::whereYear("created_at", "2024")->get();
		$expenseDetails_2025 = ExpenseDetails::whereYear("created_at", "2025")->get();
		$totalExpense_2023 = currencyEGP($expenseDetails_2023);
		$totalExpense_2024 = currencyEGP($expenseDetails_2024);
		$totalExpense_2025 = currencyEGP($expenseDetails_2025);

		return view(
			"dashboard",
			compact(
				"users_count",
				"supports_count",
				"supports_done_problem",
				"supports_unread",
				"supports_done_contact",
				"supports_uncontact",
				"supports_deleted",
				"settings_count",
				"roles_count",
				"permissions_count",
				"sections_count",
				"sections_count_active",
				"review_count",
				"comment_count",

				"limit",
				"latest_users",
				"latest_roles",
				"latest_permissions",
				"latest_sections",
				"latest_comments",
				"latest_reviews",
				"latest_settings",
				"latest_supports",
				"latest_expenses",

				"expenseDetails",
				"totalExpense",
				"pullBankTransactions",
				"pushBankTransactions",
				"totalBankTransactions",
				"orders",
				"totalOrders",
				"ordersActive",
				"ordersInActive",

				"totalOrdersYear_2023",
				"totalOrdersYear_2024",
				"totalOrdersYear_2025",

				"pullBankTransactions_2023",
				"pullBankTransactions_2024",
				"pullBankTransactions_2025",

				"pushBankTransactions_2023",
				"pushBankTransactions_2024",
				"pushBankTransactions_2025",

				"totalExpense_2023",
				"totalExpense_2024",
				"totalExpense_2025"
			)
		);
	}
}
