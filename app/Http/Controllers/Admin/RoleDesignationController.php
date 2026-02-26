<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Services\ActivityLogService;

class RoleDesignationController extends Controller
{
    /**
     * Display the management page with all roles, departments, and designations.
     */
    public function index()
    {
        $roles = Role::withCount(['userRoles'])->with('permissions')->get();
        $departments = Department::withCount('users')->get();
        $designations = Designation::withCount('users')->get();
        $permissions = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');

        return view('admin.role-management.index', compact('roles', 'departments', 'designations', 'permissions'));
    }

    // ─── ROLES ───────────────────────────────────────────────

    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name',
            'acronym' => 'required|string|max:10|unique:roles,acronym',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,key',
        ]);

        $validated['name'] = strtolower(trim($validated['name']));
        $validated['acronym'] = strtoupper(trim($validated['acronym']));

        $permissionKeys = $request->input('permissions', []);
        unset($validated['permissions']);

        $role = Role::create($validated);
        $role->syncPermissions($permissionKeys);

        ActivityLogService::log('created', 'Created role: ' . $validated['name'] . ' (' . $validated['acronym'] . ') with ' . count($permissionKeys) . ' permissions');

        return redirect()->route('admin.role-management.index', ['tab' => 'roles'])
            ->with('success', 'Role created successfully.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:roles,name,' . $role->id,
            'acronym' => 'required|string|max:10|unique:roles,acronym,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,key',
        ]);

        $validated['name'] = strtolower(trim($validated['name']));
        $validated['acronym'] = strtoupper(trim($validated['acronym']));

        $permissionKeys = $request->input('permissions', []);
        unset($validated['permissions']);

        // If name changed, update all user_roles entries
        if ($role->name !== $validated['name']) {
            \App\Models\UserRole::where('role', $role->name)->update(['role' => $validated['name']]);
        }

        $role->update($validated);
        $role->syncPermissions($permissionKeys);

        ActivityLogService::log('updated', 'Updated role: ' . $validated['name'] . ' with ' . count($permissionKeys) . ' permissions');

        return redirect()->route('admin.role-management.index', ['tab' => 'roles'])
            ->with('success', 'Role updated successfully.');
    }

    public function destroyRole(Role $role)
    {
        // Prevent deleting roles that are assigned to users
        $userCount = $role->userRoles()->count();
        if ($userCount > 0) {
            return redirect()->route('admin.role-management.index', ['tab' => 'roles'])
                ->with('error', "Cannot delete role \"{$role->name}\" — it is assigned to {$userCount} user(s).");
        }

        $name = $role->name;
        $role->delete();

        ActivityLogService::log('deleted', 'Deleted role: ' . $name);

        return redirect()->route('admin.role-management.index', ['tab' => 'roles'])
            ->with('success', 'Role deleted successfully.');
    }

    // ─── DEPARTMENTS ─────────────────────────────────────────

    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'required|string|max:10|unique:departments,code',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));

        Department::create($validated);

        ActivityLogService::log('created', 'Created department: ' . $validated['name'] . ' (' . $validated['code'] . ')');

        return redirect()->route('admin.role-management.index', ['tab' => 'departments'])
            ->with('success', 'Department created successfully.');
    }

    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'required|string|max:10|unique:departments,code,' . $department->id,
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));

        $department->update($validated);

        ActivityLogService::log('updated', 'Updated department: ' . $validated['name']);

        return redirect()->route('admin.role-management.index', ['tab' => 'departments'])
            ->with('success', 'Department updated successfully.');
    }

    public function destroyDepartment(Department $department)
    {
        $userCount = $department->users()->count();
        if ($userCount > 0) {
            return redirect()->route('admin.role-management.index', ['tab' => 'departments'])
                ->with('error', "Cannot delete department \"{$department->name}\" — it has {$userCount} user(s) assigned.");
        }

        $name = $department->name;
        $department->delete();

        ActivityLogService::log('deleted', 'Deleted department: ' . $name);

        return redirect()->route('admin.role-management.index', ['tab' => 'departments'])
            ->with('success', 'Department deleted successfully.');
    }

    // ─── DESIGNATIONS ────────────────────────────────────────

    public function storeDesignation(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:designations,title',
            'code' => 'required|string|max:20|unique:designations,code',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));

        Designation::create($validated);

        ActivityLogService::log('created', 'Created designation: ' . $validated['title'] . ' (' . $validated['code'] . ')');

        return redirect()->route('admin.role-management.index', ['tab' => 'designations'])
            ->with('success', 'Designation created successfully.');
    }

    public function updateDesignation(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:designations,title,' . $designation->id,
            'code' => 'required|string|max:20|unique:designations,code,' . $designation->id,
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));

        $designation->update($validated);

        ActivityLogService::log('updated', 'Updated designation: ' . $validated['title']);

        return redirect()->route('admin.role-management.index', ['tab' => 'designations'])
            ->with('success', 'Designation updated successfully.');
    }

    public function destroyDesignation(Designation $designation)
    {
        $userCount = $designation->users()->count();
        if ($userCount > 0) {
            return redirect()->route('admin.role-management.index', ['tab' => 'designations'])
                ->with('error', "Cannot delete designation \"{$designation->title}\" — it has {$userCount} user(s) assigned.");
        }

        $title = $designation->title;
        $designation->delete();

        ActivityLogService::log('deleted', 'Deleted designation: ' . $title);

        return redirect()->route('admin.role-management.index', ['tab' => 'designations'])
            ->with('success', 'Designation deleted successfully.');
    }
}
