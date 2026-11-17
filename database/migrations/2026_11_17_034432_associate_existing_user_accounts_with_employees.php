<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $employeesByName = DB::table('employees')
            ->get(['employee_id', 'first_name', 'last_name'])
            ->groupBy(fn (object $employee): string => mb_strtolower(trim($employee->first_name.' '.$employee->last_name)));

        $usersByName = DB::table('users')
            ->whereNull('employee_id')
            ->where('role', '!=', 'admin')
            ->whereNotExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('model_has_roles')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->whereColumn('model_has_roles.model_id', 'users.id')
                    ->where('model_has_roles.model_type', 'App\\Models\\User')
                    ->where('roles.name', 'admin');
            })
            ->get(['id', 'name'])
            ->groupBy(fn (object $user): string => mb_strtolower(trim($user->name)));

        foreach ($employeesByName as $normalizedName => $employees) {
            $users = $usersByName->get($normalizedName, collect());

            if ($employees->count() !== 1 || $users->count() !== 1) {
                continue;
            }

            DB::table('users')
                ->where('id', $users->first()->id)
                ->whereNull('employee_id')
                ->update(['employee_id' => $employees->first()->employee_id]);
        }
    }

    public function down(): void
    {
        // Keep account-to-employee links on rollback to avoid erasing account data.
    }
};
