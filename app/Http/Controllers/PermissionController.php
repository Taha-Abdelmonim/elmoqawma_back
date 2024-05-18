<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
  public function getPermissions(Request $request)
  {
    $permission = Permission::orderBy('id', 'DESC')->get();
    return response(['data' => $permission]);
  }
  public function index()
  {
    $this->authorize('show_permissions');
    $permissions = Permission::orderBy('id', 'DESC')->get();
    return view('permissions.permissions', compact('permissions'));
  }

  public function create()
  {
    $this->authorize('create_permissions');
    $permissions = Permission::all();
    return view('permissions.create', compact('permissions'));
  }

  public function store(Request $request)
  {
    $this->authorize('create_permissions');
    $this->validate(
      $request,
      ['name' => 'required|unique:permissions,name'],
      ['name.required' => trans("validation.name_required"), 'name.unique' => trans("validation.name_unique"),]
    );
    Permission::create(['name' => $request->name]);
    $description = " تم إنشاء صلاحية " . $request->name;
    transaction("permissions", $description);
    session()->put('success', trans("global.success_create"));
    return redirect()->route('permissions');
  }

  public function show($id)
  {
    $this->authorize('show_permissions');
  }

  public function edit($id, Request $request)
  {
    $this->authorize('edit_permissions');
    $permission = Permission::findorFail($id);
    return view('permissions.edit', compact('permission'));
  }

  public function update(Request $request, $id)
  {
    $this->authorize('edit_permissions');
    $permission = Permission::findorFail($id);
    $this->validate(
      $request,
      ['name' => 'required|unique:permissions,name,' . $id],
      ['name.required' => trans("validation.name_required"), 'name.unique' => trans("validation.name_unique"),]
    );
    $description = " تم تعديل صلاحية من " . $permission->name . " الي " . $request->name;
    $permission->update(['name' => $request->name]);
    transaction("permissions", $description);
    session()->put('success', trans("global.success_update"));
    return redirect()->route('permissions');
  }

  public function destroy($id)
  {
    $this->authorize('remove_permissions');
    $permission = Permission::findorFail($id);
    $description = " تم حزف صلاحية " . $permission->name;
    transaction("permissions", $description);
    $permission->delete();
    session()->put('success', trans("global.success_delete"));
    return redirect()->route('permissions');
  }
}
