<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Coupon;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Traits\GlobalTrait;

//      $this->authorize('users');
//    $pageConfigs = ['myLayout' => 'blank']; , ['pageConfigs' => $pageConfigs]
class UserController extends Controller
{
	use GlobalTrait;

	public function wallet($id, Request $request)
	{
		$this->authorize("show_users");
		$user = User::findorFail($id);
		$coupon = Coupon::where("code", $request->code)
			->where("type", "wallet")
			->first();
		$user->balance += $coupon->discount;
		$user->save();
		$description = " تم شحن رصيد لمستخدم " . $user->name() . " بقيمة " . $coupon->discount . " جنية ";
		$coupon->user_id = $id;
		$coupon->save();
		$coupon->delete();
		transaction("users", $description);
		return redirect()
			->route("users")
			->with("success", trans("global.success_wallet"));
	}

	public function getUsers(Request $request)
	{
		$users = User::with("teacher")->where("id", "!=", auth()->user()->id)
			->orderBy("id", "DESC")
			->get();
		return response(["data" => $users]);
	}

	public function index(Request $request)
	{
		$this->authorize("show_users");
		return view("users.users");
	}

	public function show($id)
	{
		$this->authorize("show_users");
		$user = User::findorFail($id);
		$ordersCourses = $user->courses($id);
		$ordersLectures = $user->lectures($id);
//		dd($ordersLectures);
		return view("users.show", compact("user", "ordersCourses", "ordersLectures"));
	}

	public function create(Request $request)
	{
		$this->authorize("create_users");
		$roles = Role::pluck("name", "name")->all();
		$teachers = Teacher::all();
		return view("users.create", compact("roles", "teachers"));
	}

	public function store(UserRequest $request)
	{
		$this->authorize("create_users");
		$this->validate($request, ["password" => "required"], ["password.required" => trans("validation.password_required")]);
		$path = "";
		if ($request->file("photo")) {
			$path = $request->file("photo")->store("users", "public_image");
		}
		$data = $request->all();
		$data["photo"] = $path;
		$data["password"] = Hash::make($request->password);
		$user = User::create($data);

		$user->assignRole($request->roles_name);
		$description = " تم إنشاء مستخدم " . $user->name();
		transaction("users", $description);
		return redirect()
			->route("users")
			->with("success", trans("global.success_create"));
	}

	public function edit($id, Request $request)
	{
		$this->authorize("edit_users");
		$user = User::findorFail($id);
		$roles = Role::pluck("name", "name")->all();
		$teachers = Teacher::all();
		return view("users.edit", compact("roles", "user", "teachers"));
	}

	public function update($id, UserRequest $request)
	{
		//		dd($request);
		$this->authorize("edit_users");
		$user = User::findorFail($id);
		$path = $user->photo;
		if ($request->file("photo")) {
			$oldPath = public_path("images/" . $user->photo);
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$path = $request->file("photo")->store("users", "public_image");
		}
		$password = $user->password;
		if ($request->password) {
			$password = Hash::make($request->password);
		}
		$data = $request->all();
		$data["photo"] = $path;
		$data["password"] = $password;
		$data["teacher_id"] = isset($request->teacher_id) ? $request->teacher_id : null;
		$user->update($data);
		DB::table("model_has_roles")
			->where("model_id", $id)
			->delete();
		$user->assignRole($request->roles_name);
		$description = " تم تعديل مستخدم " . $user->name();
		transaction("users", $description);
		$request->session()->put("success", trans("global.success_update"));
		return redirect()->route("users");
	}

	public function destroy($id)
	{
		$this->authorize("remove_users");
		$user = User::findorFail($id);
		$oldPath = public_path("images/" . $user->photo);
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		$user->delete();
		$description = " تم حزف مستخدم " . $user->name();
		transaction("users", $description);
		return redirect()
			->route("users")
			->with("success", trans("global.success_delete"));
	}

	public function destroyAll(Request $request)
	{
		$this->authorize("remove_users");
		$users = User::whereIn("id", json_decode($request->ids))->get();
		$description = " تم حزف مستخدمين (";
		foreach ($users as $user) {
			$description .= ", " . $user->name() . " ";
			$oldPath = public_path("images/" . $user->photo);
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$user->delete();
		}
		$description .= " )";
		transaction("users", $description);
		session()->put("success", trans("global.success_delete_all"));
		return redirect()->route("users");
	}
}
