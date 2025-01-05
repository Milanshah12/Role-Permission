<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:View user', ['only' => ['index','show']]);
        $this->middleware('permission:Create user', ['only' => ['create', 'store']]);
        $this->middleware('permission:Restore user', ['only' => ['restore']]);
        $this->middleware('permission:Permanent-delete user', ['only' => ['forceDelete']]);

        $this->middleware('permission:Edit user', ['only' => ['update', 'edit']]);
        $this->middleware('permission:Delete user', ['only' => ['destroy']]);

    }


    public function index(Request $request)
    {

        if ($request->ajax()) {
            if ($request->type === 'soft_deleted') {
                $users = User::onlyTrashed() ->with('profile','roles')->get();
            } else {
                $users = User::where('id', '!=', Auth::id())
                ->with('roles')->get();
            }

            return DataTables::of($users)
                ->addColumn('role', function ($user) {
                    return optional($user->getRoleNames())->map(function ($role) {
                        return '<label class="badge bg-primary">' . $role . '</label>';
                    })->implode(' ');
                })
                ->addColumn('action', function ($user) {
                    $actions = '';
                    $editButton = '';
                    $deleteForm = '';
                    $accessUser='';

                    if ($user->trashed()) {
                        $restoreForm = '';
                        if (Gate::allows('Restore user')) {
                            $restoreForm = '<form action="' . route('users.restore', $user->id) . '" method="POST" style="display: inline;">
                                                ' . csrf_field() . '
                                                <button type="submit" class="btn btn-warning">Restore</button>
                                            </form>';
                        }

                        $forceDeleteForm = '';
                        if (Gate::allows('Permanent-delete user')) {
                            $forceDeleteForm = '<button type="button" class="btn btn-danger"
                                            onclick="confirmDelete(' . $user->id . ', \'' . route('users.forceDelete', $user->id) . '\')">
                                            Permanent Delete
                                        </button>';
                        }

                        $actions = $restoreForm . ' ' . $forceDeleteForm;
                    } else {

                        $editUrl = url('users/' . $user->id . '/edit');
                        if (Gate::allows('Edit user')) {
                            $editButton = '<button class="btn btn-success" data-bs-target="#editUser" data-bs-toggle="modal" data-user-id="'.$user->id.'">Edit</button>';

                        }

                        if (Gate::allows('Delete user')) {
                            $deleteForm = '<button type="button" class="btn btn-danger"
                                               onclick="confirmDelete(' . $user->id . ', \'' . route('users.destroy', $user->id) . '\')">
                                               Delete
                                           </button>';

                        }
                        if (auth()->user()->canImpersonate()) {
                            $accessUser = '<button type="button" class="btn btn-warning"
                                            onclick="confirmAccess(' . $user->id . ', \'' . route('impersonate', $user->id) . '\')">
                                            Access User
                                        </button>';
                        }
                        $actions = $editButton . ' ' . $deleteForm.''.$accessUser;
                    }

                    return $actions;
                })


                ->rawColumns(['role', 'action'])
                ->make(true);
        }
        $roles = Role::pluck('name', 'name')->all();
        return view('role-permission.user.index', ['roles' => $roles]);
    }

    public function show( string $id)

    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name'),
        ]);


    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
            'roles' => 'required|array',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $roles = is_array($request->roles) ? $request->roles : [$request->roles];


        foreach ($roles as $roleName) {

            $role = Role::where('name', $roleName)->first();

            if ($role) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $role->id,
                    'model_id' => $user->id,
                    'model_type' => User::class,
                ]);
            }
        }


        return response()->json(['success' => true, 'message' => 'User added successfully!']);    }



    // public function show(string $id)
    // {
    //     //
    // }

    public function edit(User $user)
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('role-permission.user.edit', ['user' => $user, 'roles' => $roles]);
    }



    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|max:255',
            'roles' => 'required|array',
        ]);


        $user->update([
            'name' => $request->name,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        DB::table('model_has_roles')
            ->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->delete();


        $roles = is_array($request->roles) ? $request->roles : [$request->roles];


        foreach ($roles as $roleName) {
            $role = Role::where('name', $roleName)->first();

             if ($role) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $role->id,
                    'model_id' => $user->id,
                    'model_type' => User::class,
                ]);
            }
        }

return response()->json(['success' => true, 'message' => 'User updated successfully']);
    }




    public function destroy(User $user)
    {
        $user->syncRoles([]);

        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully']);

    }
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->back()->with('status', 'User restored successfully.');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->back()->with('status', 'User permanently deleted.');
    }


}
