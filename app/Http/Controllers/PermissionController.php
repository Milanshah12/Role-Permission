<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{

    public function __construct(){
        $this->middleware('permission:View permission',['only'=>['index']]);
        $this->middleware('permission:Create permission',['only'=>['create','store']]);

        $this->middleware('permission:Edit permission',['only'=>['update','edit']]);
        $this->middleware('permission:Delete permissoin',['only'=>['destroy']]);

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permission = Permission::select(['id', 'name']);
            return DataTables::of($permission)
                ->addColumn('action', function ($permission) {
                    // Check permissions using Gate
                    $editButton = Gate::allows('Edit permission') ? '
                        <button type="button" class="btn btn-success"
                            data-coreui-toggle="modal"
                            data-coreui-target="#editPermission"
                            data-permission-id="' . $permission->id . '">
                            Edit
                        </button>' : '';

                    $deleteButton = Gate::allows('Delete permissoin') ? "
                        <button class='btn btn-danger' onclick='deletePermission({$permission->id})'>
                            Delete
                        </button>
                    " : '';
                    // Combine buttons
                    return $editButton . ' ' . $deleteButton;
                })
                ->rawColumns(['action']) // Make the action column render raw HTML
                ->make(true);
        }

        return view('role-permission.permission.index');
    }



    // $permission=Permission::get();
    // $per=compact('permission');
    // return view('role-permission.permission.index',$per);
    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return response()->json([
    //         'success'=>true,
    //         'message'=>'Permission Added successfully'
    //     ]);
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'permission'=>[
                'required',
                'string',
                'unique:permissions,name'
            ]
            ]);
            Permission::create([
                'name'=>$request->permission
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'Permission Added successfully'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $permission=Permission::findOrFail($id);
        return response()->json([
            // 'success'=>true,
            // 'message'=>'Permission Added successfully'
            'name'=>$permission->name
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return view('role-permission.permission.edit',['permission'=>$permission]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
{
    // Validate the incoming request data
    $request->validate([
       'permission' => 'required|string|max:255|unique:permissions,name',
    ]);

    // Update the permission
    $permission->update([
        'name' => $request->permission
    ]);

    // Return a success response
    return response()->json([
        'success' => true,
        'message' => 'Permission updated successfully.'
    ]);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($permissionid)
    {
        $permission=Permission::find($permissionid);
        $permission->delete();

        return response()->json(['success'=>true, 'message' => 'Permission deleted successfully!']);    }
}
