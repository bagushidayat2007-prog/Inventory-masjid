<?php

namespace App\Http\Controllers;

use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $pengguna = $request->user();

        return view('profile.edit', compact('pengguna'));
    }

    public function update(Request $request)
    {
        $pengguna = $request->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],

            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pengguna', 'username')
                    ->ignore($pengguna->id_pengguna, 'id_pengguna')
            ],

            'no_telp' => ['nullable', 'string', 'max:20'],

            'password' => [
                'nullable',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            'password_lama' => [
                'nullable',
                'required_with:password',
                'string'
            ],

        ], [
            'nama.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',

            'password.min' => 'Password minimal 8 karakter.',
            'password.mixed' => 'Password harus mengandung huruf besar dan huruf kecil.',
            'password.numbers' => 'Password harus mengandung angka.',
            'password.symbols' => 'Password harus mengandung simbol.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',

            'password_lama.required_with' =>
                'Password lama wajib diisi untuk mengganti password.',
        ]);

        if (!empty($data['password'])) {

            if (!Hash::check($data['password_lama'], $pengguna->password)) {
                return back()->withErrors([
                    'password_lama' => 'Password lama tidak sesuai.'
                ]);
            }

            $data['password'] = Hash::make($data['password']);

        } else {
            unset($data['password']);
        }

        unset($data['password_lama']);

        $pengguna->update($data);

        AuditLogger::log('Mengubah profil pengguna');

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
