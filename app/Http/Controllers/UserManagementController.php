<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;

class UserManagementController extends Controller
{
    /*
     * index all users
     */
    public function index()
    {
        try {
            $users = User::withTrashed()->with('roles')->latest()->get();
            return view('users.index', compact('users'));
        } catch (Exception $e) {
            return back()->with('error', 'Failed to load users');
        }
    }

    /**
     * create user page
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * 
     * store new user
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();

            // hash password
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

           // assign role
            $user->assignRole($data['role']);

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to create user');
        }
    }

    /**
     * edit user page
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     *  update user data
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            // update user info
            $user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            // 
            $user->syncRoles([$data['role']]);

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to update user');
        }
    }

    /** soft delet*/
    public function destroy(User $user)
    {
        try {
            if ($user->hasRole('admin') && User::role('admin')->count() === 1) {
                return back()->with('error', 'لا يمكن حذف المدير الوحيد');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User soft deleted');

        } catch (Exception $e) {
            return back()->with('error', 'Delete failed');
        }
    }

    /** restore*/
    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->route('users.index')
                ->with('success', 'User restored');

        } catch (Exception $e) {
            return back()->with('error', 'Restore failed');
        }
    }

    /** delet final*/
    public function forceDelete($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);

            if ($user->hasRole('admin') && User::role('admin')->count() === 1) {
                return back()->with('error', 'لا يمكن حذف المدير الوحيد نهائياً');
            }

            $user->forceDelete();

            return redirect()->route('users.index')
                ->with('success', 'User permanently deleted');

        } catch (Exception $e) {
            return back()->with('error', 'Force delete failed');
        }
    }
}