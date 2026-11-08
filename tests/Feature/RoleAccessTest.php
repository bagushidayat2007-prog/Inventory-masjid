<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create([
            'nama' => 'User '.$role,
            'username' => 'u_'.$role,
            'password' => Hash::make('password123'),
            'role' => $role,
        ]);
    }

    public function test_bendahara_cannot_access_user_management(): void
    {
        $this->actingAs($this->user('bendahara'))
            ->get('/pengguna')
            ->assertForbidden();
    }

    public function test_petugas_cannot_access_keuangan(): void
    {
        $this->actingAs($this->user('petugas_inventory'))
            ->get('/keuangan')
            ->assertForbidden();
    }

    public function test_bendahara_cannot_access_barang(): void
    {
        $this->actingAs($this->user('bendahara'))
            ->get('/barang')
            ->assertForbidden();
    }

    public function test_admin_can_access_user_management_and_audit_log(): void
    {
        $admin = $this->user('admin');

        $this->actingAs($admin)->get('/pengguna')->assertOk();
        $this->actingAs($admin)->get('/audit-log')->assertOk();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }
}
