<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager-load permissions for efficiency
        $roles = Role::with('permissions')->get();

        // Pass to Blade
        return view('roles.view', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Permission check (redundant if using middleware, but safe for API/AJAX)
        // if (!Auth::check() || !Auth::user()->can('create roles')) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Unauthorized.'
        //     ], 403);
        // }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role name already exists.',
            'permissions.required' => 'Please select at least one permission.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'status' => true,
            'message' => 'Role added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ], [
            'name.required' => 'The role name is required.',
            'name.unique' => 'This role name already exists.',
            'permissions.required' => 'Please select at least one permission.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Check if anything has actually changed
        $nameChanged = $role->name !== $request->name;
        $oldPermissions = $role->permissions->pluck('name')->sort()->values()->toArray();
        $newPermissions = collect($request->permissions)->sort()->values()->toArray();
        $permissionsChanged = $oldPermissions !== $newPermissions;

        if (!$nameChanged && !$permissionsChanged) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Only update if there are changes
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'status' => true,
            'message' => 'Role updated successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findOrFail($id);
        // Prevent deleting special roles if needed, e.g. 'admin'
        // if (in_array($role->name, ['admin'])) {
        //     return response()->json(['status' => false, 'message' => 'Cannot delete this role.'], 403);
        // }
        $role->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Role deleted successfully.'
            ]);
        }
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
