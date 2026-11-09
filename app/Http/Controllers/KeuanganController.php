<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\PengajuanPengeluaran;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Keuangan::with('pengguna');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sumber', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($jenis = $request->input('jenis')) {
            $query->where('jenis', $jenis);
        }

        if ($dari = $request->input('dari')) {
            $query->whereDate('tanggal', '>=', $dari);
        }
        if ($sampai = $request->input('sampai')) {
            $query->whereDate('tanggal', '<=', $sampai);
        }

        // Total mengikuti filter yang aktif
        $totalPemasukan = (clone $query)->where('jenis', 'pemasukan')->sum('jumlah');
        $totalPengeluaran = (clone $query)->where('jenis', 'pengeluaran')->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $keuangan = $query->orderByDesc('tanggal')->orderByDesc('id_keuangan')->paginate(10)->withQueryString();

        return view('keuangan.index', compact('keuangan', 'totalPemasukan', 'totalPengeluaran', 'saldo'));
    }

    public function create(Request $request)
    {
        $jenis = $request->input('jenis', 'pemasukan');
        if (! in_array($jenis, ['pemasukan', 'pengeluaran'], true)) {
            $jenis = 'pemasukan';
        }

        return view('keuangan.create', compact('jenis'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['id_pengguna'] = $request->user()->id_pengguna;

        // Jika bendahara menginput pengeluaran
        if (
            $request->user()->role === 'bendahara' &&
            $data['jenis'] === 'pengeluaran'
        ) {
            PengajuanPengeluaran::create([
                'id_pengguna' => $data['id_pengguna'],
                'tanggal' => $data['tanggal'],
                'jumlah' => $data['jumlah'],
                'tujuan' => $data['tujuan'] ?? null,
                'keterangan' => $data['keterangan'] ?? null,
                'status' => 'menunggu',
            ]);

            AuditLogger::log(
                'Mengajukan pengeluaran sebesar Rp ' .
                number_format($data['jumlah'], 0, ',', '.')
            );

            return redirect()->route('keuangan.index')
                ->with('success', 'Pengajuan pengeluaran berhasil dikirim dan menunggu persetujuan admin.');
        }

        // Transaksi lainnya tetap disimpan langsung
        Keuangan::create($data);

        AuditLogger::log(
            'Menambahkan ' . $data['jenis'] .
            ' keuangan sebesar Rp ' .
            number_format($data['jumlah'], 0, ',', '.')
        );

        return redirect()->route('keuangan.index')
            ->with('success', 'Transaksi keuangan berhasil ditambahkan.');
    }

    public function edit(Keuangan $keuangan)
    {
        return view('keuangan.edit', compact('keuangan'));
    }

    public function update(Request $request, Keuangan $keuangan)
    {
        $data = $this->validateData($request);

        $keuangan->update($data);

        AuditLogger::log('Mengubah transaksi keuangan #'.$keuangan->id_keuangan);

        return redirect()->route('keuangan.index')->with('success', 'Transaksi keuangan berhasil diperbarui.');
    }

    public function destroy(Keuangan $keuangan)
    {
        $id = $keuangan->id_keuangan;
        $keuangan->delete();

        AuditLogger::log('Menghapus transaksi keuangan #'.$id);

        return redirect()->route('keuangan.index')->with('success', 'Transaksi keuangan berhasil dihapus.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'tanggal' => ['required', 'date'],
            'jenis' => ['required', Rule::in(['pemasukan', 'pengeluaran'])],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'sumber' => ['nullable', 'string', 'max:150'],
            'tujuan' => ['nullable', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jenis.required' => 'Jenis transaksi wajib dipilih.',
            'jenis.in' => 'Jenis hanya boleh pemasukan atau pengeluaran.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah keuangan harus lebih dari 0.',
        ]);
    }
}
