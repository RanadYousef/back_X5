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
    /**
     * index all users
     */
    public function index()
    {
        try {
            $users = User::with('roles')->latest()->get();
            return view('users.index', compact('users'));
        } catch (Exception $e) {
            return back()->with('error', 'user data loading failed');
        }
    }

    /**
     * create user page
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (Exception $e) {
            return back()->with('error', 'failed to load data');
        }
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

            return redirect()
                ->route('users.index')
                ->with('success', 'created user successfully');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'failed to create user');
        }
    }

    /**
     * edit user page
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return back()->with('error','failed to load data');
        }
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

            return redirect()
                ->route('users.index')
                ->with('success', 'updated user successfully');

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'failed to update user');
        }
    }
    /**
     * show user details
     */
     
    public function show(User $user)
    {
        return view('users.show', compact('user'));
        
    }

 
    /**
     *destory user 
     */
   public function destroy(User $user)
{
    try {

       // prevent deletion of yhe last manger
       
        if ($user->hasRole('admin') && User::role('admin')->count() === 1) {
            return back()->with('error', 'لا يمكن حذف المدير الوحيد في النظام');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'deleted user successfully');

    } catch (Exception $e) {
        return back()->with('error', 'failed to delete user');
    }
}

}
