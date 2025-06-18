<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions',
            'description' => 'nullable|string',
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    public function show(Permission $permission)
    {
        return view('permissions.show', compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'description' => 'nullable|string',
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.show', $permission)->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }

    public function userPermissions(User $user)
    {
        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('id')->toArray();
        return view('permissions.user', compact('user', 'permissions', 'userPermissions'));
    }

    public function updateUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $user->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('users.show', $user)->with('success', 'User permissions updated successfully.');
    }
}