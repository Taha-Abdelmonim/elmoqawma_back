<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
  public function getRoles(Request $request)
  {
    $roles = Role::orderBy('id', 'DESC')->get();
    return response(['data' => $roles]);
  }
  public function index(Request $request)
  {
   $this->authorize('show_roles');
    $roles = Role::orderBy('id', 'DESC')->get();
    return view('roles.roles', compact('roles'));
  }

  public function create()
  {
    $this->authorize('create_roles');
    $permissionsGroup = Permission::whereNotNull("permission_name")->get()->groupBy("permission_name");
    $permissions = Permission::whereNull("permission_name")->get();
    return view('roles.create',compact('permissions', "permissionsGroup"));
  }

  public function store(Request $request)
  {
    $this->authorize('create_roles');
    $this->validate($request,
      ['name' => 'required|unique:roles,name', 'permission' => 'required'],
      ['name.required' => trans("validation.name_required"), 'name.unique' => trans("validation.name_unique"),]
    );
    $role = Role::create(['name' => $request->name]);
    $role->syncPermissions($request->permission);
    $description = " تم إنشاء مهام " . $request->name;
    transaction("roles", $description);
    session()->put('success', trans("global.success_create"));
    return redirect()->route('roles');
  }

  public function show($id)
  {
    $this->authorize('show_roles');
  }
  
  public function edit($id)
  {
    $this->authorize('edit_roles');
    $role = Role::findorFail($id);
    $permissionsGroup = Permission::whereNotNull("permission_name")->get()->groupBy("permission_name");
    $permissions = Permission::whereNull("permission_name")->get();
    // return $permissionsGroup;
    $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
      ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
      ->all();
    // return $rolePermissions;
    return view('roles.edit',compact('role','permissions','rolePermissions','permissionsGroup'));
  }
  
  public function update(Request $request, $id)
  {
    $this->authorize('edit_roles');
    $this->validate($request,
      ['name' => 'required|unique:roles,name,'.$id, 'permission' => 'required'],
      ['name.required' => trans("validation.name_required"), 'name.unique' => trans("validation.name_unique"),]
    );
    // return $request;
    $role = Role::findorFail($id);
    $description = " تم تعديل مهام من " . $role->name . " الي " . $request->name;
    $role->name = $request->name;
    $role->save();
    $role->syncPermissions($request->permission);
    transaction("roles", $description);
    return redirect()->route('roles')->with('success',trans("global.success_update"));
  }
  
  public function destroy($id)
  {
    $this->authorize('remove_roles');
    $role = Role::findorFail($id);
    $description = " تم حزف مهام " . $role->name;
    $role->delete();
    transaction("roles", $description);
    return redirect()->route('roles')->with('success',trans("global.success_delete"));
  }
  public function destroyAll(Request $request)
  {
    $this->authorize('remove_roles');
    $roles = Role::whereIn('id', json_decode($request->ids))->get();
    $description = " تم حزف المهامات (";
    foreach ($roles as $role) {
      $description .= ", " . $role->name . " ";
      $role->delete();
    }
    $description .= " )";
    transaction("roles", $description);
    session()->put('success', trans("global.success_delete_all"));
    return redirect()->route("roles");
  }
}
