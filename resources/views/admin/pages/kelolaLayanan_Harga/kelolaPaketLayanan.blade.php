@extends('admin.masterAdmin')
@section('title', 'Kelola Paket Layanan')

@section('content')

{{-- OVERLAY BLUR --}}
<div class="blur-overlay" id="blurOverlay"></div>

<div class="container-fluid">

    {{-- BUTTON TAMBAH --}}
    <div class="mb-3 mt-3">
        <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#modalPaket"
            onclick="openCreate()">
            <i class="fas fa-plus"></i>  Tambah Paket Layanan
        </button>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Paket Layanan</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Paket</th>
                        <th>Jenis Layanan</th>
                        <th>Deskripsi</th>
                        <th>Durasi</th>
                        <th>File Edit</th>
                        <th>Harga</th>
                        <th>Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($paketLayanan as $item)
                    <tr>
                        <td class="text-center">
                            {{ $paketLayanan->firstItem() + $loop->index }}
                        </td>
                        <td>{{ $item->namaPaket }}</td>
                        <td>{{ $item->jenisLayanan->namaLayanan ?? '-' }}</td>
                        <td>{{ $item->deskripsi ?: '-' }}</td>
                        <td class="text-center">{{ $item->durasiJam }} Jam</td>
                        <td class="text-center">{{ $item->jumlahFileEdit }} File</td>
                        <td>Rp {{ number_format($item->harga,0,',','.') }}</td>
                        <td class="text-center">
                            <span class="badge {{ $item->aktif ? 'badge-success' : 'badge-secondary' }}">
                                {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm"
                                data-toggle="modal"
                                data-target="#modalPaket"
                                data-item='@json($item)'
                                onclick="openEdit(this)">
                                Edit
                            </button>

                            <form action="{{ route('paketLayanan.destroy', $item->idPaketLayanan) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Hapus paket ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Data paket layanan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $paketLayanan->links() }}
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalPaket" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            <form id="formPaket" method="POST">
                @csrf
                <input type="hidden" name="_method" id="method">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Paket Layanan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Jenis Layanan</label>
                        <select name="idJenisLayanan" id="idJenisLayanan" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($jenisLayanan as $jl)
                            <option value="{{ $jl->idJenisLayanan }}">
                                {{ $jl->namaLayanan }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nama Paket</label>
                        <input type="text" name="namaPaket" id="namaPaket" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Durasi (Jam)</label>
                            <input type="number" name="durasiJam" id="durasiJam" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>File Edit</label>
                            <input type="number" name="jumlahFileEdit" id="jumlahFileEdit" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label>Harga</label>
                            <input type="number" name="harga" id="harga" class="form-control">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Status</label>
                        <select name="aktif" id="aktif" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@section('script')
<style>
    /* OVERLAY GELAP + BLUR */
    .blur-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        z-index: 1040;
        display: none;
    }

    /* Pastikan modal di atas overlay */
    .modal {
        z-index: 1050;
    }

    /* Modal aesthetic */
    .modal-content {
        border-radius: 14px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        border: none;
    }
</style>

<script>
    function openCreate() {
        document.getElementById('modalTitle').innerText = 'Tambah Paket Layanan';
        document.getElementById('formPaket').action = "{{ route('paketLayanan.store') }}";
        document.getElementById('method').value = 'POST';
        document.getElementById('formPaket').reset();
    }

    function openEdit(el) {
        const data = JSON.parse(el.dataset.item);

        document.getElementById('modalTitle').innerText = 'Edit Paket Layanan';
        document.getElementById('formPaket').action =
            `/paket-layanan/${data.idPaketLayanan}`;
        document.getElementById('method').value = 'PUT';

        idJenisLayanan.value = data.idJenisLayanan;
        namaPaket.value = data.namaPaket;
        deskripsi.value = data.deskripsi;
        durasiJam.value = data.durasiJam;
        jumlahFileEdit.value = data.jumlahFileEdit;
        harga.value = data.harga;
        aktif.value = data.aktif;
    }

    /* TOGGLE OVERLAY */
    $('#modalPaket').on('show.bs.modal', function() {
        $('#blurOverlay').fadeIn(200);
    });

    $('#modalPaket').on('hidden.bs.modal', function() {
        $('#blurOverlay').fadeOut(200);
    });
</script>
@endsection