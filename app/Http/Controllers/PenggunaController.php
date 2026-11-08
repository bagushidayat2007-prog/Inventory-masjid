<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $pengguna = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        return view('pengguna.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:pengguna,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'bendahara', 'petugas_inventory'])],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ], $this->messages());

        $data['password'] = Hash::make($data['password']);

        $pengguna = User::create($data);

        AuditLogger::log('Menambahkan data pengguna: '.$pengguna->nama);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('pengguna', 'username')->ignore($pengguna->id_pengguna, 'id_pengguna')],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', Rule::in(['admin', 'bendahara', 'petugas_inventory'])],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ], $this->messages());

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $pengguna->update($data);

        AuditLogger::log('Mengubah data pengguna: '.$pengguna->nama);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna)
    {
        if ($pengguna->id_pengguna === $request->user()->id_pengguna) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $nama = $pengguna->nama;
        $pengguna->delete();

        AuditLogger::log('Menghapus data pengguna: '.$nama);

        return redirect()->route('pengguna.index')->with('success', 'Data pengguna berhasil dihapus.');
    }

    private function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
        ];
    }
}
