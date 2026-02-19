<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['role', 'department']); // Eager load relationships

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        // Department filter
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->latest()->paginate(10);

        // Get roles and departments for filters
        $roles = Role::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('admin.users.index', compact('users', 'roles', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('admin.users.create', compact('roles', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'required|exists:roles,id',  // Changed from roles[] to role_id
            'is_active' => 'boolean',
            'email_verified' => 'boolean',
            'avatar' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,  // Single role ID
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'position' => $request->position,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle email verification
        if ($request->boolean('email_verified')) {
            $data['email_verified_at'] = now();
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['role', 'department']);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();

        return view('admin.users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'role_id' => 'required|exists:roles,id',  // Changed from roles[] to role_id
            'is_active' => 'boolean',
            'email_verified' => 'boolean',
            'avatar' => 'nullable|image|max:2048' // 2MB max
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,  // Single role ID
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'position' => $request->position,
            'is_active' => $request->boolean('is_active', false),
        ];

        // Handle email verification
        if ($request->boolean('email_verified')) {
            $data['email_verified_at'] = $user->email_verified_at ?? now();
        } else {
            $data['email_verified_at'] = null;
        }

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status (activate/deactivate).
     */
    public function toggleStatus(User $user)
    {
        // Prevent deactivating your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.users.index')
            ->with('success', "User {$status} successfully.");
    }
}