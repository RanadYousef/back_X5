<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class RolePermissionController extends Controller
{
     public function index()
    {
        $roles = Role::with('permissions:id,name')->get();
        return view('admin.roles.index', compact('roles'));
    }


    public function create()
    {   $permissions = Permission::all();
        return view('admin.roles.create',compact('permissions'));
    }

   public function store(StoreRoleRequest $request) 
{
    // البيانات هنا تم التحقق منها تلقائياً بفضل الـ Form Request
        // تبدأ العملية (Transaction)

    DB::beginTransaction();
    try {
        $validated = $request->validated();
        //  إنشاء الدور
        $role = Role::create(['name' => $validated['name']]);

        //  ربط الصلاحيات
        if (!empty($validated['permissions'])) {
        $role->givePermissionTo($validated['permissions']);
        }
        //  اعتماد التغييرات في القاعدة
        DB::commit();
        return redirect()->route('roles.index')->with('success', 'تم إضافة الدور وصلاحياته بنجاح');

    }catch (Exception $e) {
        // التراجع عن العملية
            DB::rollBack();
            Log::error("Store Role Error: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء الحفظ');
        }
}
public function edit(Role $role)
{
    // جلب كل الصلاحيات المتاحة في النظام
    $permissions = Permission::all();

    // جلب أسماء الصلاحيات التي يمتلكها هذا الدور حالياً
    $rolePermissions = $role->permissions->pluck('id','name')->toArray();

    return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
}


public function update(UpdateRoleRequest $request, Role $role)
{
    // تبدأ العملية (Transaction)
    DB::beginTransaction();

    try {
        $validated = $request->validated();
        //  تحديث اسم الدور
        $role->update(['name' => $validated['name']]);

        //  مزامنة الصلاحيات (تحذف القديم وتضيف الجديد تلقائياً)
        //  syncPermission:الأكثر أماناً في التحديث
        $role->syncPermissions($validated['permissions'] ?? []);

        //  اعتماد التغييرات في القاعدة
        DB::commit();

        return redirect()->route('roles.index')
                         ->with('success', 'تم تحديث الدور وصلاحياته بنجاح');

    } catch (Exception $e) {
            DB::rollBack();
            Log::error("Update Role Error: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء التحديث');
        }
}
public function show(Role $role)
{
    return redirect()->route('roles.index');
}
public function destroy(Role $role)
{
    try {
        // عدم السماح بحذف دور الادمن
        if (strtolower(trim($role->name)) === 'admin') {
            return redirect()->back()->with('error', 'لا يمكن حذف دور المدير الأساسي');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح');
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'فشل الحذف: ' . $e->getMessage());
    }
}
        
        
    
}
