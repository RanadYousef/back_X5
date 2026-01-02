<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * عرض جميع المستخدمين مع أدوارهم
     */
    public function index()
    {
        return User::with('roles')->get();
    }

    /**
     * إنشاء مستخدم جديد (موظف أو مشترك)
     */
    public function store(Request $request)
    {
        //  Validation 
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role'     => 'required|exists:roles,name',
        ]);

        try {
            //  إنشاء المستخدم
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            //  إسناد الدور
            $user->assignRole($request->role);

            return response()->json([
                'message' => 'User created successfully',
                'user'    => $user
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to create user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث بيانات مستخدم
     */
    public function update(Request $request, User $user)
    {
        // Validation
        $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role'  => 'sometimes|exists:roles,name',
        ]);

        try {
            //  تحديث البيانات الأساسية
            $user->update($request->only(['name', 'email']));

            //  تحديث الدور إن وُجد
            if ($request->has('role')) {
                $user->syncRoles([$request->role]);
            }

            return response()->json([
                'message' => 'User updated successfully',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to update user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف مستخدم
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Failed to delete user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
