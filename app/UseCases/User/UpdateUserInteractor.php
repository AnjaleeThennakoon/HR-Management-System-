<?php

namespace App\UseCases\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UpdateUserInteractor
{
    /**
     * @throws Throwable
     */
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::findOrFail($data['id']);

            $updateData = [
                'email' => $data['email'],
            ];

            if (! empty($data['employee_id'])) {
                $updateData['employee_id'] = $data['employee_id'];
            }

            if (! empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            return $user;
        });
    }
}
