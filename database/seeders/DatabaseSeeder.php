<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Pengguna default
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'nama' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'no_telp' => '081234567890',
            ]
        );

        User::firstOrCreate(
            ['username' => 'bendahara'],
            [
                'nama' => 'Bendahara Masjid',
                'password' => Hash::make('bendahara123'),
                'role' => 'bendahara',
                'no_telp' => '081234567891',
            ]
        );

        User::firstOrCreate(
            ['username' => 'petugas'],
            [
                'nama' => 'Petugas Inventory',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas_inventory',
                'no_telp' => '081234567892',
            ]
        );

        // Kategori contoh
        $kategoris = ['Perlengkapan Ibadah', 'Elektronik', 'Perabotan', 'Kebersihan'];
        foreach ($kategoris as $nama) {
            Kategori::firstOrCreate(['nama_kategori' => $nama]);
        }

        // Supplier contoh
        $supplier = Supplier::firstOrCreate(
            ['nama_supplier' => 'Toko Perlengkapan Masjid'],
            ['alamat' => 'Jl. Merdeka No. 10', 'no_telp' => '0217654321']
        );

        // Barang contoh
        $kategoriIbadah = Kategori::where('nama_kategori', 'Perlengkapan Ibadah')->first();
        Barang::firstOrCreate(
            ['nama_barang' => 'Sajadah'],
            [
                'id_kategori' => $kategoriIbadah?->id_kategori,
                'id_supplier' => $supplier->id_supplier,
                'satuan' => 'lembar',
                'stok' => 50,
                'kondisi' => 'baik',
            ]
        );

        Barang::firstOrCreate(
            ['nama_barang' => 'Al-Quran'],
            [
                'id_kategori' => $kategoriIbadah?->id_kategori,
                'id_supplier' => $supplier->id_supplier,
                'satuan' => 'buah',
                'stok' => 30,
                'kondisi' => 'baik',
            ]
        );
    }
}
