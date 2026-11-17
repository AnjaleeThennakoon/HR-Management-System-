@props([
    'permission' => null,
    'adminOnly' => false,
])

@php
    $user = auth()->user();
    $isAdmin = $user && (
        (method_exists($user, 'hasApplicationRole') && $user->hasApplicationRole('admin'))
        || ($user->role ?? null) === 'admin'
    );

    $hasPermission = false;
    if ($user && $permission) {
        try {
            $hasPermission = (bool) (method_exists($user, 'checkPermissionTo') && $user->checkPermissionTo($permission));
        } catch (\Throwable $e) {
            $hasPermission = false;
        }

        if (! $hasPermission && method_exists($user, 'hasApplicationRole') && $user->hasApplicationRole('employee') && in_array($permission, ['attendance.view-own'])) {
            $hasPermission = true;
        }
    }

    $isAuthorized = $user && (
        $isAdmin
        || (
            ! $adminOnly
            && (
                ! $permission
                || $hasPermission
            )
        )
    );
@endphp

@if ($isAuthorized)
    {{ $slot }}
@endif
