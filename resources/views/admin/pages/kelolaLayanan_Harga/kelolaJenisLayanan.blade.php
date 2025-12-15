@extends('admin.masterAdmin')
@section('title', 'Kelola Jenis Layanan')

@section('content')

{{-- OVERLAY BLUR --}}
<div class="blur-overlay" id="blurOverlay"></div>

<div class="container-fluid">

    {{-- BUTTON TAMBAH --}}
    <div class="mb-3 mt-3">
        <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#modalJenis"
            onclick="openCreate()">
            <i class="fas fa-plus"></i> Tambah Jenis Layanan
        </button>
    </div>

    {{-- TABLE --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Jenis Layanan</h3>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jenisLayanan as $item)
                    <tr>
                        <td class="text-center">
                            {{ $jenisLayanan->firstItem() + $loop->index }}
                        </td>
                        <td>{{ $item->namaLayanan }}</td>
                        <td>{{ $item->deskripsi ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $item->aktif ? 'badge-success' : 'badge-secondary' }}">
                                {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="text-center">

                            <button class="btn btn-warning btn-sm"
                                data-toggle="modal"
                                data-target="#modalJenis"
                                data-item='@json($item)'
                                onclick="openEdit(this)">
                                Edit
                            </button>

                            @if($item->portofolio_count > 0 || $item->paket_count > 0)
                            <button class="btn btn-danger btn-sm" disabled
                                title="Tidak dapat dihapus karena sudah digunakan">
                                Hapus
                            </button>
                            @else
                            <form action="{{ route('jenisLayanan.destroy', $item->idJenisLayanan) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Data jenis layanan belum tersedia
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $jenisLayanan->links() }}
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalJenis" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form id="formJenis" method="POST">
                @csrf
                <input type="hidden" name="_method" id="method">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Jenis Layanan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label>Nama Layanan</label>
                        <input type="text"
                            name="namaLayanan"
                            id="namaLayanan"
                            class="form-control"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="deskripsi"
                            id="deskripsi"
                            class="form-control"
                            rows="5"></textarea>
                    </div>

                    <div class="form-group">
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

    .modal {
        z-index: 1050;
    }

    .modal-content {
        border-radius: 14px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        border: none;
    }
</style>

<script>
    function openCreate() {
        modalTitle.innerText = 'Tambah Jenis Layanan';
        formJenis.action = "{{ route('jenisLayanan.store') }}";
        method.value = 'POST';
        formJenis.reset();
    }

    function openEdit(el) {
        const data = JSON.parse(el.dataset.item);

        modalTitle.innerText = 'Edit Jenis Layanan';
        formJenis.action = `/jenis-layanan/${data.idJenisLayanan}`;
        method.value = 'PUT';

        namaLayanan.value = data.namaLayanan;
        deskripsi.value = data.deskripsi;
        aktif.value = data.aktif;
    }

    /* TOGGLE OVERLAY */
    $('#modalJenis').on('show.bs.modal', function() {
        $('#blurOverlay').fadeIn(200);
    });

    $('#modalJenis').on('hidden.bs.modal', function() {
        $('#blurOverlay').fadeOut(200);
    });
</script>
@endsection