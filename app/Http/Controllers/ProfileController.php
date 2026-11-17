<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function admin(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return view('profile.admin', ['user' => $user]);
    }

    public function employee(Request $request): View
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return view('profile.employee', ['user' => $user]);
    }
}
