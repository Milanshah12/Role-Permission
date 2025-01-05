<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller

{

    public function __construct(){
        $this->middleware('permission:View role',['only'=>['index']]);
        $this->middleware('permission:Create role',['only'=>['create','store']]);
        $this->middleware('permission:AssignPermissionToRole',['only'=>['addPermissionToRole','givePermissionToRole']]);

        $this->middleware('permission:Edit role',['only'=>['update','edit']]);
        $this->middleware('permission:Delete role',['only'=>['destroy']]);

    }
    public function index()
    {
        $roles=Role::get();
        $per=compact('roles');
        return view('role-permission.roles.index',$per);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('role-permission.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role'=>[
                'required',
                'string',
                'unique:roles,name'
            ]
            ]);
            Role::create([
                'name'=>$request->role
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'New Role added'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role=Role::findOrFail($id);
        return response()->json([
            'name'=>$role->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('role-permission.roles.edit',['roles'=>$role]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {

        $request->validate([
            'role'=>[
                'required',
                'string',
                'unique:roles,name'
            ]
            ]);

            $role->Update([
                'name'=>$request->role
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'update successfully'
            ]);
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($roleid)
    {
        $roles=Role::find($roleid);
        $roles->delete();
        return response()->json(['success'=>true, 'message' => 'Role deleted successfully!']);    }

    public function addPermissionToRole($roleId){

        $permission=Permission::get();
        $role=Role::findOrFail($roleId);
        $rolePermission=DB::table('role_has_permissions')
                            ->where('role_id',$roleId)
                            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                            ->all();
        return view('role-permission.roles.role-permission',[
            'role'=>$role,
            'permission'=>$permission,
            'rolepermission'=>$rolePermission
        ]);
    }

    public function givePermissionToRole(Request $request, $roleId){
        $request->validate([
            'permission'=>'required'
        ]);
        $role=Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);
        return redirect()->back()->with('status','permission Added to role');

    }
}
