<?php

namespace Tests\Feature;

use App\Models\Keuangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KeuanganTest extends TestCase
{
    use RefreshDatabase;

    private function bendahara(): User
    {
        return User::create([
            'nama' => 'Bendahara',
            'username' => 'bendahara',
            'password' => Hash::make('password123'),
            'role' => 'bendahara',
        ]);
    }

    public function test_bendahara_can_create_income_and_expense(): void
    {
        $user = $this->bendahara();

        $this->actingAs($user)->post('/keuangan', [
            'tanggal' => now()->toDateString(),
            'jenis' => 'pemasukan',
            'jumlah' => 1000000,
            'sumber' => 'Kotak amal',
        ])->assertRedirect(route('keuangan.index'));

        $this->actingAs($user)->post('/keuangan', [
            'tanggal' => now()->toDateString(),
            'jenis' => 'pengeluaran',
            'jumlah' => 250000,
            'tujuan' => 'Listrik',
        ])->assertRedirect(route('keuangan.index'));

        $this->assertSame(2, Keuangan::count());
    }

    public function test_jumlah_must_be_positive(): void
    {
        $this->actingAs($this->bendahara())
            ->from('/keuangan/create')
            ->post('/keuangan', [
                'tanggal' => now()->toDateString(),
                'jenis' => 'pemasukan',
                'jumlah' => 0,
            ])
            ->assertSessionHasErrors('jumlah');

        $this->assertSame(0, Keuangan::count());
    }

    public function test_jenis_must_be_valid(): void
    {
        $this->actingAs($this->bendahara())
            ->from('/keuangan/create')
            ->post('/keuangan', [
                'tanggal' => now()->toDateString(),
                'jenis' => 'transfer',
                'jumlah' => 1000,
            ])
            ->assertSessionHasErrors('jenis');
    }
}
