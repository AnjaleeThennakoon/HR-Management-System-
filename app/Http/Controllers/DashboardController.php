<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        abort_unless($user instanceof User, 403, 'Unauthorized access');

        if ($user->hasApplicationRole('admin') || $user->hasApplicationRole('employee')) {
            return view('AdminDashboard', ['employees' => Employee::all()]);
        }

        abort(403, 'Unauthorized access');
    }
}
