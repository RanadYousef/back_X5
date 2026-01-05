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
{   /**
    * Display a listing of the roles with their permissions.
    * * @return \Illuminate\View\View
    */


    public function index()
    {
        $roles = Role::with('permissions:id,name')->get();
        return view('admin.roles.index', compact('roles'));
    }
    /**
     * Show the form for creating a new role.
     * * @return \Illuminate\View\View
     */

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }
    /**
     * Store a newly created role in storage.
     * * @param StoreRoleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(StoreRoleRequest $request)
    {
        // transaction begins
        DB::beginTransaction();
        try {
            $validated = $request->validated();
            // creating role
            $role = Role::create(['name' => $validated['name']]);
            // permission
            if (!empty($validated['permissions'])) {
                $role->givePermissionTo($validated['permissions']);
            }
            // Commit the changes to the database if everything is successful
            DB::commit();
            return redirect()->route('roles.index')->with('success', 'Role and its permissions added successfully');

        } catch (Exception $e) {
            // Rollback the changes if an error occurs to maintain data integrity
            DB::rollBack();
            Log::error("Store Role Error: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while saving.');
        }
    }
    /**
     * Show the form for editing the specified role.
     * * @param Role $role
     * @return \Illuminate\View\View
     */

    public function edit(Role $role)
    {
        // Fetch all available permissions in the system
        $permissions = Permission::all();

        // Retrieve the names of the permissions currently assigned to this role
        $rolePermissions = $role->permissions->pluck('id', 'name')->toArray();

        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role in storage.
     * * @param UpdateRoleRequest $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */

    public function update(UpdateRoleRequest $request, Role $role)
    {
        //   (Transaction)
        DB::beginTransaction();

        try {
            $validated = $request->validated();
            // update role's name
            $role->update(['name' => $validated['name']]);
            // Sync permissions (automatically removes old ones and adds new ones)
            //  syncPermission:The safest method for updating as it prevents duplication and maintains consistency
            $role->syncPermissions($validated['permissions'] ?? []);

            DB::commit();

            return redirect()->route('roles.index')
                ->with('success', 'Update completed successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Update Role Error: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred during the update.');
        }
    }
    /**
     * Summary of show
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(Role $role)
    {
        return redirect()->route('roles.index');
    }

    /**
     * Summary of destroy
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        try {
            // Prevent the deletion of the Admin role
            if (strtolower(trim($role->name)) === 'admin') {
                return redirect()->back()->with('error', 'The Super Admin role cannot be deleted');
            }

            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        } catch (Exception $e) {
            Log::error("Delete Role Error: " . $e->getMessage());
            return redirect()->back()->with('error', 'Delete operation failed.' . $e->getMessage());
        }
    }



}