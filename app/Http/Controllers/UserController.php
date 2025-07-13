<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        // Pass to Blade
        return view('users.view', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        // Pass to Blade
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,name',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');
            $customName = $originalName . '_' . $timestamp . '.' . $extension;

            $avatarPath = $file->storeAs('avatar', $customName, 'public');
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatarPath,
        ]);

        // Assign role
        if ($request->role) {
            $user->syncRoles($request->role);
        }

        return response()->json([
            'status' => true,
            'message' => 'User added successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($user)
    {
        $user = User::findOrFail($user);
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $user)
    {
        $user = User::findOrFail($user);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

        // Check what changed
        $nameChanged = $user->name !== $request->name;
        $emailChanged = $user->email !== $request->email;
        $roleChanged = !$user->roles->contains('name', $request->role);
        $passwordChanged = $request->filled('password') && !Hash::check($request->password, $user->password);
        $avatarChanged = $request->hasFile('avatar');

        if (
            !$nameChanged &&
            !$emailChanged &&
            !$roleChanged &&
            !$passwordChanged &&
            !$avatarChanged
        ) {
            return response()->json([
                'status' => false,
                'message' => 'Nothing to update.',
            ]);
        }

        // Handle avatar upload
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('storage/' . $user->avatar))) {
                @unlink(public_path('storage/' . $user->avatar));
            }

            $file = $request->file('avatar');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $timestamp = now()->format('Ymd_His');
            $customName = $originalName . '_' . $timestamp . '.' . $extension;

            $avatarPath = $file->storeAs('avatar', $customName, 'public');
        }

        // Only update fields that were changed
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }
        if ($avatarPath) {
            $updateData['avatar'] = $avatarPath;
        }

        $user->update($updateData);

        // Assign role
        if ($request->role) {
            $user->syncRoles($request->role);
        }

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully.',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully.'
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
