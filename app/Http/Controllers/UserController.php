<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use App\UseCases\User\DeleteUserInteractor;
use App\UseCases\User\ListUserInteractor;
use App\UseCases\User\Request\UserRequest;
use App\UseCases\User\StoreUserInteractor;
use App\UseCases\User\UpdateUserInteractor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Throwable;

class UserController extends Controller
{
    public function index(ListUserInteractor $listUserInteractor): View
    {
        $users = $listUserInteractor->execute(
            request('search'),
            request('per_page')
        );

        $employees = Employee::orderBy('employee_id')->get();
        $assignedEmployeeIds = User::query()
            ->whereNotNull('employee_id')
            ->pluck('employee_id')
            ->all();

        return view('users.UserDashBord', [
            'users' => $users,
            'employees' => $employees,
            'assignedEmployeeIds' => $assignedEmployeeIds,
        ]);
    }
    /**
     * @throws Throwable
     */
    public function store(UserRequest $userRequest, StoreUserInteractor $storeUserInteractor): RedirectResponse
    {
        $storeUserInteractor->execute($userRequest->validated());

        return redirect()->back()->with('success', 'Employee account created successfully.');
    }
    public function update(string $id, UserRequest $userRequest, UpdateUserInteractor $updateUserInteractor): RedirectResponse
    {
        $data = $userRequest->validated();
        $data['id'] = $id;

        $updateUserInteractor->execute($data);

        return redirect()->back()->with('success', 'Employee account updated successfully.');
    }
    /**
     * @throws Throwable
     */
    public function destroy(string $id, DeleteUserInteractor $deleteUserInteractor): RedirectResponse
    {
        $deleteUserInteractor->execute($id);

        return redirect()->back()->with('success', 'Employee account deleted successfully.');
    }
}
