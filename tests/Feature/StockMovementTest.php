<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StockMovementTest extends TestCase
{
    use RefreshDatabase;

    private function petugas(): User
    {
        return User::create([
            'nama' => 'Petugas',
            'username' => 'petugas',
            'password' => Hash::make('password123'),
            'role' => 'petugas_inventory',
        ]);
    }

    private function barang(int $stok = 10): Barang
    {
        $kategori = Kategori::create(['nama_kategori' => 'Umum']);

        return Barang::create([
            'id_kategori' => $kategori->id_kategori,
            'nama_barang' => 'Sajadah',
            'satuan' => 'lembar',
            'stok' => $stok,
            'kondisi' => 'baik',
        ]);
    }

    public function test_barang_masuk_increases_stock(): void
    {
        $user = $this->petugas();
        $barang = $this->barang(10);

        $this->actingAs($user)->post('/barang-masuk', [
            'tanggal' => now()->toDateString(),
            'keterangan' => 'Donasi',
            'detail' => [
                ['id_barang' => $barang->id_barang, 'jumlah' => 5],
            ],
        ])->assertRedirect(route('barang-masuk.index'));

        $this->assertSame(15, $barang->fresh()->stok);
        $this->assertDatabaseHas('barang_masuk', ['id_pengguna' => $user->id_pengguna]);
    }

    public function test_barang_keluar_decreases_stock(): void
    {
        $user = $this->petugas();
        $barang = $this->barang(15);

        $this->actingAs($user)->post('/barang-keluar', [
            'tanggal' => now()->toDateString(),
            'tujuan' => 'Kegiatan',
            'detail' => [
                ['id_barang' => $barang->id_barang, 'jumlah' => 3],
            ],
        ])->assertRedirect(route('barang-keluar.index'));

        $this->assertSame(12, $barang->fresh()->stok);
    }

    public function test_barang_keluar_rejects_quantity_exceeding_stock(): void
    {
        $user = $this->petugas();
        $barang = $this->barang(5);

        $response = $this->actingAs($user)->from('/barang-keluar/create')->post('/barang-keluar', [
            'tanggal' => now()->toDateString(),
            'detail' => [
                ['id_barang' => $barang->id_barang, 'jumlah' => 10],
            ],
        ]);

        $response->assertSessionHasErrors('detail');
        $this->assertSame(5, $barang->fresh()->stok);
        $this->assertSame(0, BarangKeluar::count());
    }

    public function test_deleting_barang_masuk_reverses_stock(): void
    {
        $user = $this->petugas();
        $barang = $this->barang(10);

        $this->actingAs($user)->post('/barang-masuk', [
            'tanggal' => now()->toDateString(),
            'detail' => [['id_barang' => $barang->id_barang, 'jumlah' => 5]],
        ]);

        $masuk = BarangMasuk::first();
        $this->actingAs($user)->delete(route('barang-masuk.destroy', $masuk))
            ->assertRedirect(route('barang-masuk.index'));

        $this->assertSame(10, $barang->fresh()->stok);
        $this->assertSame(0, BarangMasuk::count());
    }
}
