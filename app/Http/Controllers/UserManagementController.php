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
     * 
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $users = User::with('roles')->latest()->get();
            $deletedUsers = User::onlyTrashed()->with('roles')->get(); // ✅ المحذوفون
            return view('users.index', compact('users', 'deletedUsers'));
        } catch (Exception $e) {
            return back()->with('error', 'فشل تحميل بيانات المستخدمين');
        }
    }

    /**
     * 
     * 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (Exception $e) {
            return back()->with('error', 'فشل تحميل البيانات');
        }
    }

    /**
     * 
     * 
     * @param StoreUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
            return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'فشل إنشاء المستخدم');
        }
    }

    /**
     * 
     * 
     * @param User $user
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        } catch (Exception $e) {
            return back()->with('error','فشل تحميل البيانات');
        }
    }

    /**
     * 
     * 
     * @param UpdateUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
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
            // $user->syncRoles([$data['role']]); // إذا أردت تحديث الدور
            return redirect()->route('users.index')->with('success', 'تم تحديث المستخدم بنجاح');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'فشل تحديث المستخدم');
        }
    }

    /**
     * 
     * 
     * @param User $user
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 
     * 
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            if ($user->hasRole('admin') && User::role('admin')->count() === 1) {
                return back()->with('error', 'لا يمكن حذف المدير الوحيد في النظام');
            }

            $user->delete(); // ✅ حذف ناعم
            return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
        } catch (Exception $e) {
            return back()->with('error', 'فشل حذف المستخدم');
        }
    }

    /**
     * 
     * 
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();
            return redirect()->route('users.index')->with('success', 'تم استرجاع المستخدم بنجاح');
        } catch (Exception $e) {
            return back()->with('error', 'فشل استرجاع المستخدم');
        }
    }

    /**
     * 
     * 
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDelete($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);

            if ($user->hasRole('admin') && User::role('admin')->count() === 1) {
                return back()->with('error', 'لا يمكن حذف المدير الوحيد في النظام');
            }$user->forceDelete();
            return redirect()->route('users.index')->with('success', 'تم حذف المستخدم نهائياً');
        } catch (Exception $e) {
            return back()->with('error', 'فشل حذف المستخدم نهائياً');
        }
    }
}