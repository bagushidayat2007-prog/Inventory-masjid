@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')

@section('content')
    <form action="{{ route('barang-keluar.store') }}" method="POST" data-loading id="formKeluar">
        @csrf
        <div class="card">
            <div class="card-header">
                <h2>Tambah Barang Keluar</h2>
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="required" for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="tujuan">Tujuan</label>
                        <input type="text" id="tujuan" name="tujuan"
                               class="form-control @error('tujuan') is-invalid @enderror"
                               value="{{ old('tujuan') }}" placeholder="Contoh: Kegiatan pengajian">
                        @error('tujuan')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" id="keterangan" name="keterangan"
                               class="form-control @error('keterangan') is-invalid @enderror"
                               value="{{ old('keterangan') }}" placeholder="Opsional">
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
            @error('detail.*.id_barang')<div style="padding:0 16px;"><span class="error-text">{{ $message }}</span></div>@enderror
            @error('detail.*.jumlah')<div style="padding:0 16px;"><span class="error-text">{{ $message }}</span></div>@enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('barang-keluar.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>

    <template id="rowTemplate">
        <tr>
            <td>
                <select name="detail[__INDEX__][id_barang]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangList as $b)
                        <option value="{{ $b->id_barang }}" data-stok="{{ $b->stok }}">
                            {{ $b->nama_barang }} (stok: {{ $b->stok }} {{ $b->satuan }})
                        </option>
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

            function addRow() {
                const html = template.replace(/__INDEX__/g, index++);
                const wrapper = document.createElement('tbody');
                wrapper.innerHTML = html;
                body.appendChild(wrapper.firstElementChild);
            }

            document.getElementById('addRow').addEventListener('click', addRow);
            body.addEventListener('click', function (e) {
                if (e.target.classList.contains('removeRow') && body.children.length > 1) {
                    e.target.closest('tr').remove();
                }
            });

            addRow();
        })();
    </script>
    @endpush
@endsection
