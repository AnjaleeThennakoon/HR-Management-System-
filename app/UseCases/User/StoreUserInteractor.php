<?php

namespace App\UseCases\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Throwable;

class StoreUserInteractor
{
    /**
     * @throws Throwable
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'employee_id' => $data['employee_id'],
            ]);

            $role = Role::firstOrCreate([
                'name' => 'employee',
                'guard_name' => 'web',
            ]);

            $user->assignRole($role);

            return $user;
        });
    }
}
