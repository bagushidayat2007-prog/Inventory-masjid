@extends('layouts.app')

@section('title', 'Edit Barang Keluar')

@section('content')
    <form action="{{ route('barang-keluar.update', $barangKeluar) }}" method="POST" data-loading id="formKeluar">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h2>Edit Barang Keluar #{{ $barangKeluar->id_keluar }}</h2>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', $barangKeluar->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Tujuan</label>
                        <input type="text" id="tujuan" name="tujuan"
                               class="form-control @error('tujuan') is-invalid @enderror"
                               value="{{ old('tujuan', $barangKeluar->tujuan) }}">
                        @error('tujuan')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan"
                               class="form-control @error('keterangan') is-invalid @enderror"
                               value="{{ old('keterangan', $barangKeluar->keterangan) }}">
                        @error('keterangan')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>Detail Barang</h2>
                <button type="button" class="btn btn-primary btn-sm" id="addRow">+ Tambah Baris</button>
            </div>
            <div class="table-responsive">
                <table class="data">
                    <thead>
                        <tr>
                            <th style="width:60%;">Barang</th>
                            <th style="width:25%;">Jumlah</th>
                            <th style="width:15%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody"></tbody>
                </table>
            </div>
            @error('detail')<div style="padding:10px 16px;"><span class="error-text">{{ $message }}</span></div>@enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Perbarui</button>
            <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    <template id="rowTemplate">
        <tr>
            <td>
                <select name="detail[__INDEX__][id_barang]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangList as $b)
                        <option value="{{ $b->id_barang }}">{{ $b->nama_barang }} (stok: {{ $b->stok }} {{ $b->satuan }})</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="number" name="detail[__INDEX__][jumlah]" class="form-control" min="1" value="1" required>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm removeRow">Hapus</button>
            </td>
        </tr>
    </template>

    @push('scripts')
    <script>
        (function () {
            const body = document.getElementById('detailBody');
            const template = document.getElementById('rowTemplate').innerHTML;
            let index = 0;

            function addRow(barangId, jumlah) {
                const html = template.replace(/__INDEX__/g, index++);
                const wrapper = document.createElement('tbody');
                wrapper.innerHTML = html;
                const tr = wrapper.firstElementChild;
                if (barangId) tr.querySelector('select').value = barangId;
                if (jumlah) tr.querySelector('input').value = jumlah;
                body.appendChild(tr);
            }

            document.getElementById('addRow').addEventListener('click', function () { addRow(); });
            body.addEventListener('click', function (e) {
                if (e.target.classList.contains('removeRow') && body.children.length > 1) {
                    e.target.closest('tr').remove();
                }
            });

            @if (old('detail'))
                @foreach (old('detail') as $row)
                    addRow(@json($row['id_barang'] ?? null), @json($row['jumlah'] ?? null));
                @endforeach
            @else
                @foreach ($barangKeluar->detail as $detail)
                    addRow(@json($detail->id_barang), @json($detail->jumlah));
                @endforeach
            @endif
        })();
    </script>
    @endpush
@endsection
