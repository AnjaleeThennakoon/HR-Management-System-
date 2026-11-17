<?php

namespace App\UseCases\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

class DeleteUserInteractor
{
    /**
     * @throws Throwable
     */
    public function execute(string $id): void
    {
        DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);
            $user->delete();
        });
    }
}
