<?php

namespace App\Services;

use App\Models\User;
use App\Models\Department;
use App\Models\Role;

class EmployeeIdService
{
    /**
     * Generate a unique employee ID
     * Format: URS<YY>-<DEPT_CODE><5_DIGIT_RANDOM>
     * Example: URS26-CCS12345
     */
    public static function generate(int $departmentId): string
    {
        $department = Department::find($departmentId);
        
        if (!$department) {
            throw new \Exception('Department not found');
        }

        $year = date('y'); // Last 2 digits of current year
        $deptCode = strtoupper($department->code); // Department code (e.g., CCS)
        
        // Generate a unique 5-digit random number
        $maxAttempts = 100;
        $attempt = 0;
        
        do {
            $randomDigits = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $employeeId = "URS{$year}-{$deptCode}{$randomDigits}";
            $attempt++;
            
            // Check if this employee ID already exists
            $exists = User::where('employee_id', $employeeId)->exists();
            
            if (!$exists) {
                return $employeeId;
            }
            
        } while ($attempt < $maxAttempts);
        
        throw new \Exception('Could not generate unique employee ID after ' . $maxAttempts . ' attempts');
    }

    /**
     * Generate a unique employee ID for HR or Director roles (no department code)
     * Format: URS<YY>-<ROLE_ACRONYM><5_DIGIT_RANDOM>
     * Example: URS26-HRD12345 (HR), URS26-DIR12345 (Director)
     */
    public static function generateForHrOrDirector(array $roles): string
    {
        // Determine role code from DB, with priority: Director > HR > first role
        $roleCode = null;
        $priorityRoles = ['director', 'hr'];
        
        foreach ($priorityRoles as $pRole) {
            if (in_array($pRole, $roles)) {
                $roleCode = Role::getAcronym($pRole);
                break;
            }
        }
        
        // Fallback: use the acronym of the first role
        if (!$roleCode && !empty($roles)) {
            $roleCode = Role::getAcronym($roles[0]);
        }
        
        // Final fallback if role not found in DB
        if (!$roleCode) {
            $roleCode = 'USR';
        }
        
        $year = date('y'); // Last 2 digits of current year
        
        // Generate a unique 5-digit random number
        $maxAttempts = 100;
        $attempt = 0;
        
        do {
            $randomDigits = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $employeeId = "URS{$year}-{$roleCode}{$randomDigits}";
            $attempt++;
            
            // Check if this employee ID already exists
            $exists = User::where('employee_id', $employeeId)->exists();
            
            if (!$exists) {
                return $employeeId;
            }
            
        } while ($attempt < $maxAttempts);
        
        throw new \Exception('Could not generate unique employee ID after ' . $maxAttempts . ' attempts');
    }

    /**
     * Update employee ID department code when department changes
     * Keeps the same random digits, just updates the department code
     * Format: URS<YY>-<NEW_DEPT_CODE><EXISTING_DIGITS>
     */
    public static function updateDepartmentCode(string $currentEmployeeId, int $newDepartmentId): string
    {
        $department = Department::find($newDepartmentId);
        
        if (!$department) {
            throw new \Exception('Department not found');
        }

        // Extract the random digits from current employee ID
        // Pattern: URS26-CCS12345 -> extract "12345"
        if (preg_match('/^URS\d{2}-[A-Z]+(\d{5})$/', $currentEmployeeId, $matches)) {
            $randomDigits = $matches[1];
            $year = date('y');
            $deptCode = strtoupper($department->code);
            
            return "URS{$year}-{$deptCode}{$randomDigits}";
        }
        
        // If pattern doesn't match or no current ID, generate new one
        return self::generate($newDepartmentId);
    }
}
