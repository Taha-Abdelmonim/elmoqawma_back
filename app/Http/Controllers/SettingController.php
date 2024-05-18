<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
	public function getSettings(Request $request)
	{
		$settings = Setting::orderBy('id', 'DESC')->get();
		return response(['data' => $settings]);
	}

	public function index()
	{
		$this->authorize('show_settings');
		return view('settings.settings');
	}

	public function create()
	{
		$this->authorize('create_settings');
		return view('settings.create');
	}

	public function store(SettingRequest $request)
	{
		$this->authorize('create_settings');
		$path = null;
		if ($request->file('photo')) {
			$namePhoto = $request->file("photo")->getClientOriginalName();
			$path = $request->file('photo')->storeAs('settings', $namePhoto, "public_image");
		}
		$setting = Setting::create([
			'name' => $request->name,
			'key' => $request->key,
			'type' => $request->type,
			'photo' => $path,
			'value_ar' => $request->value_ar,
			'value_en' => $request->value_en,
		]);
		$description = " تم إنشاء إعداد " . $setting->name;
		transaction("settings", $description);
		session()->put('success', trans("global.success_create"));
		return redirect()->route('settings');
	}

	public function show($id)
	{
		$this->authorize('show_settings');
		$value = Setting::findorFail($id)->pluck('value_ar')->first();
		return view('settings.show', compact('value'));
	}

	public function edit($id)
	{
		$this->authorize('edit_settings');
		$setting = Setting::findorFail($id);
		return view('settings.edit', compact('setting'));
	}

	public function update($id, SettingRequest $request)
	{
		$this->authorize('edit_settings');
		$setting = Setting::findorFail($id);
		$path = $setting->photo;
		if ($request->file('photo')) {
			$oldPath = public_path('/images/' . $setting->photo);
			$namePhoto = $request->file("photo")->getClientOriginalName();
			if (is_file($oldPath)) {
				$namePhoto = pathinfo($oldPath)['basename'];
				unlink($oldPath);
			}
			$path = $request->file('photo')->storeAs('settings', $namePhoto, "public_image");
		}
		$setting->update([
			'name' => $request->name,
			'key' => $request->key,
			'type' => $request->type,
			'photo' => $path,
			'value_ar' => $request->value_ar,
			'value_en' => $request->value_en,
		]);
		$description = " تم تعديل إعداد " . $setting->name;
		transaction("settings", $description);
		session()->put('success', trans("global.success_update"));
		return redirect()->route('settings');
	}


	public function destroy($id)
	{
		$this->authorize('remove_settings');
		$setting = Setting::findorFail($id);
		$oldPath = public_path('/images/' . $setting->photo);
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		$description = " تم حزف إعداد " . $setting->name;
		transaction("settings", $description);
		$setting->delete();
		session()->put('success', trans("global.success_delete"));
		return redirect()->route('settings');
	}

	public function backupDatabase()
	{
		$this->authorize('setting_backup_database');
		Artisan::call('db:backup');
		transaction("settings", trans("global.backup_database"));
		return redirect("/")->with("success", trans("global.backup_database"));
	}


	public function clearCash()
	{
		$this->authorize('setting_clear_cash');
		try {
			Artisan::call('optimize:clear');
			Artisan::call('cache:clear');
			Artisan::call('route:clear');
			Artisan::call('view:clear');
			Artisan::call('config:clear');
			// Artisan::call('storage:link');
			transaction("settings", trans("global.clear_cash"));
			return redirect("/")->with("success", trans("global.clear_cash"));
		} catch (\Exception $ex) {
			return redirect('/')->with('error', $ex->getMessage());
		}
	}
}
