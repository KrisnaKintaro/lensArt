@extends('admin.masterAdmin')
@section('title', 'Kelola Data Portofolio')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3 mt-2">
        <h4>Data Portofolio</h4>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="idJenisLayanan" class="form-control">
                    <option value="">-- Semua Layanan --</option>
                    @foreach($jenisLayanan as $layanan)
                        <option value="{{ $layanan->idJenisLayanan }}"
                            {{ request('idJenisLayanan') == $layanan->idJenisLayanan ? 'selected' : '' }}>
                            {{ $layanan->namaLayanan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="card-header">
            
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered text-nowrap">
                <thead class="bg-light">
                    <tr class="text-center">
                        <th width="5%">No</th>
                        <th width="10%">Gambar</th>
                        <th>Nama Portofolio</th>
                        <th>Layanan</th>
                        <th>Jenis</th>
                        <th width="12%">Tanggal</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($portofolios as $item)
                        <tr>
                            <td class="text-center">
                                {{ $portofolios->firstItem() + $loop->index }}
                            </td>

                            <td class="text-center">
                                <img src="{{ asset('gambarPortofolio/'.$item->gambar) }}"
                                    class="img-thumbnail"
                                    style="max-height:80px">
                            </td>

                            <td>{{ $item->namaPortofolio }}</td>

                            <td>
                                {{ $item->jenisLayanan->namaLayanan ?? '-' }}
                            </td>

                            <td>
                                <span class="badge badge-info">
                                    {{ $item->jenisPorto }}
                                </span>
                            </td>

                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($item->tanggalPorto)->format('d-m-Y') }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('portofolio.edit', $item->idPortofolio) }}"
                                class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('portofolio.destroy', $item->idPortofolio) }}"
                                    method="POST"
                                    class="d-inline form-delete">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button"
                                            class="btn btn-danger btn-sm btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Data portofolio belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer clearfix">
            {{ $portofolios->links() }}
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function () {
        const form = this.closest('form');
        Swal.fire({
            title: 'Hapus data?',
            text: 'Data yang dihapus tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection