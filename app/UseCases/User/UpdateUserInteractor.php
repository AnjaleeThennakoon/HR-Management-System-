<?php

namespace App\UseCases\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateUserInteractor
{
    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::findOrFail($data['id']);

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (! empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            return $user;
        });
    }
}
