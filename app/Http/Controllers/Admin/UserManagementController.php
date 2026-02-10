<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use App\Services\PhotoService;
use App\Services\EmployeeIdService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserManagementController extends Controller
{
    protected $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('department', 'designation', 'userRoles')->paginate(10);
        $departments = Department::all();
        return view('admin.users.index', compact('users', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = ['admin', 'hr', 'director', 'dean', 'faculty'];
        $departments = Department::all();
        $designations = Designation::all();
        return view('admin.users.create', compact('roles', 'departments', 'designations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,hr,director,dean,faculty',
            'is_active' => 'boolean',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        // Generate unique employee ID
        $roles = $validated['roles'];
        $isHrOrDirector = in_array('hr', $roles) || in_array('director', $roles);
        
        if ($isHrOrDirector) {
            // HR and Director get unique IDs without department codes
            $validated['employee_id'] = EmployeeIdService::generateForHrOrDirector($roles);
        } elseif (!empty($validated['department_id'])) {
            // Other roles get department-based IDs
            $validated['employee_id'] = EmployeeIdService::generate($validated['department_id']);
        }

        // Store roles separately
        $roles = $validated['roles'];
        unset($validated['roles']);
        
        // Clear department and designation for HR and Director
        $isHrOrDirector = in_array('hr', $roles) || in_array('director', $roles);
        if ($isHrOrDirector) {
            $validated['department_id'] = null;
            $validated['designation_id'] = null;
        }

        // Create user
        $user = User::create($validated);

        // Assign roles
        foreach ($roles as $role) {
            $user->assignRole($role);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('userRoles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Prevent editing the main admin account
        if ($user->employee_id === 'URS26-ADM00001') {
            return redirect()->route('admin.users.index')->with('error', 'The administrator account cannot be edited');
        }

        $roles = ['admin', 'hr', 'director', 'dean', 'faculty'];
        $departments = Department::all();
        $designations = Designation::all();
        $user->load('userRoles');
        return view('admin.users.edit', compact('user', 'roles', 'departments', 'designations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Prevent updating the main admin account
        if ($user->employee_id === 'URS26-ADM00001') {
            return redirect()->route('admin.users.index')->with('error', 'The administrator account cannot be modified');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:admin,hr,director,dean,faculty',
            'is_active' => 'boolean',
            'department_id' => 'nullable|exists:departments,id',
            'designation_id' => 'nullable|exists:designations,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Handle roles separately
        $newRoles = $validated['roles'];
        unset($validated['roles']);
        
        // Check if user has HR or Director role
        $isHrOrDirector = in_array('hr', $newRoles) || in_array('director', $newRoles);
        
        // Handle employee ID and department logic
        if ($isHrOrDirector) {
            // HR and Director don't need department or designation
            $validated['department_id'] = null;
            $validated['designation_id'] = null;
            
            // Generate unique employee ID if they don't have one or need role-based update
            if (!$user->employee_id) {
                $validated['employee_id'] = EmployeeIdService::generateForHrOrDirector($newRoles);
            }
        } else {
            // For other roles, handle department changes
            if (isset($validated['department_id']) && $validated['department_id'] != $user->department_id) {
                // Department changed - update employee ID with new department code
                if ($user->employee_id) {
                    $validated['employee_id'] = EmployeeIdService::updateDepartmentCode(
                        $user->employee_id, 
                        $validated['department_id']
                    );
                } else {
                    // User doesn't have employee ID yet, generate new one
                    $validated['employee_id'] = EmployeeIdService::generate($validated['department_id']);
                }
            } elseif (isset($validated['department_id']) && !$user->employee_id) {
                // Department exists but user has no employee ID, generate one
                $validated['employee_id'] = EmployeeIdService::generate($validated['department_id']);
            }
        }

        // Update user data
        $user->update($validated);

        // Update roles
        $currentRoles = $user->roles();
        
        // Remove roles that are no longer selected
        foreach ($currentRoles as $role) {
            if (!in_array($role, $newRoles)) {
                $user->removeRole($role);
            }
        }

        // Add new roles
        foreach ($newRoles as $role) {
            if (!in_array($role, $currentRoles)) {
                $user->assignRole($role);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting the main admin account
        if ($user->employee_id === 'URS26-ADM00001') {
            return redirect()->route('admin.users.index')->with('error', 'The administrator account cannot be deleted');
        }

        // Prevent deleting self
        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot delete your own account');
        }

        // Delete photos
        $this->photoService->deleteAllUserPhotos($user);

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        // Prevent toggling the main admin account
        if ($user->employee_id === 'URS26-ADM00001') {
            return redirect()->route('admin.users.index')->with('error', 'The administrator account status cannot be changed');
        }

        if (auth()->user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot toggle your own status');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')->with('success', "User {$status} successfully");
    }
}