<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserManagementController extends Controller
{
    /**
     * عرض جميع المستخدمين
     */
    public function index()
    {
        try {
            $users = User::with('roles')->get();
            return view('users.index', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل المستخدمين');
        }
    }

    /**
     * عرض صفحة إنشاء مستخدم
     */
    public function create()
    {
        try {
            $roles = Role::all();
            return view('users.create', compact('roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل الصفحة');
        }
    }

    /**
     * حفظ مستخدم جديد
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($request->role);

            return redirect()
                ->route('users.index')
                ->with('success', 'تم إنشاء المستخدم بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'فشل إنشاء المستخدم');
        }
    }

    /**
     * عرض صفحة تعديل مستخدم
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::all();
            return view('users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل البيانات');
        }
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            $user->syncRoles([$request->role]);

            return redirect()
                ->route('users.index')
                ->with('success', 'تم تحديث المستخدم بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'فشل تحديث المستخدم');
        }
    }

    /**
     * حذف مستخدم
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return redirect()
                ->route('users.index')
                ->with('success', 'تم حذف المستخدم بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'فشل حذف المستخدم');
        }
    }
}

