<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('rupiah')) {
    /**
     * Format angka menjadi format Rupiah: Rp 1.000.000
     */
    function rupiah($value): string
    {
        return 'Rp '.number_format((float) $value, 0, ',', '.');
    }
}

if (! function_exists('has_role')) {
    /**
     * Cek apakah pengguna yang login memiliki salah satu role.
     */
    function has_role(string ...$roles): bool
    {
        $user = Auth::user();

        return $user && in_array($user->role, $roles, true);
    }
}
