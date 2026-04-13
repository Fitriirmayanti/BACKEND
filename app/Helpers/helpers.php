<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('hasPermission')) {
    function hasPermission(string $menuName, string $permissionName): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return DB::table('user_permission')
            ->join('menu_permission', 'user_permission.menu_permission_id', '=', 'menu_permission.id')
            ->join('menu', 'menu_permission.menu_id', '=', 'menu.id')
            ->join('permission', 'menu_permission.permission_id', '=', 'permission.id')
            ->where('user_permission.user_id', $user->id)
            ->where('menu.nama', $menuName)
            ->where('permission.nama', $permissionName)
            ->exists();
    }
}
