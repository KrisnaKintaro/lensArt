@extends('admin.masterAdmin')
@section('title', 'Kelola Data Portofolio')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4 mt-2">
        {{ isset($portofolio) ? 'Edit Portofolio' : 'Tambah Portofolio' }}
    </h4>

    <div class="card">
        <div class="card-body">

            <form action="{{ isset($portofolio)
                ? route('portofolio.update', $portofolio->idPortofolio)
                : route('portofolio.store') }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @isset($portofolio)
                @method('PUT')
                @endisset

                {{-- Jenis Layanan --}}
                <div class="form-group">
                    <label>Jenis Layanan</label>
                    <select name="idJenisLayanan" class="form-control" required>
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($jenisLayanan as $layanan)
                        <option value="{{ $layanan->idJenisLayanan }}"
                            {{ old('idJenisLayanan', $portofolio->idJenisLayanan ?? '') == $layanan->idJenisLayanan ? 'selected' : '' }}>
                            {{ $layanan->namaLayanan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Nama --}}
                <div class="form-group">
                    <label>Nama Portofolio</label>
                    <input type="text" name="namaPortofolio"
                        class="form-control"
                        value="{{ old('namaPortofolio', $portofolio->namaPortofolio ?? '') }}"
                        required>
                </div>

                {{-- Deskripsi --}}
                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi"
                        class="form-control"
                        rows="3">{{ old('deskripsi', $portofolio->deskripsi ?? '') }}</textarea>
                </div>


                {{-- Jenis Porto --}}
                <div class="form-group">
                    <label>Jenis Portofolio</label>
                    <input type="text" name="jenisPorto"
                        class="form-control"
                        placeholder="foto / video"
                        value="{{ old('jenisPorto', $portofolio->jenisPorto ?? '') }}"
                        required>
                </div>

                {{-- Tanggal --}}
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggalPorto"
                        class="form-control"
                        value="{{ old('tanggalPorto', isset($portofolio) ? $portofolio->tanggalPorto->format('Y-m-d') : '') }}"
                        required>
                </div>

                {{-- Upload Gambar --}}
                <div class="form-group">
                    <label>Gambar Portofolio</label>
                    <input type="file"
                        name="urlPorto"
                        class="form-control-file"
                        accept="image/*"
                        onchange="previewImage(this)">
                </div>

                {{-- Preview --}}
                <div class="form-group">
                    <img id="preview"
                        src="{{ isset($portofolio) ? asset('assetslensart/portofolio/'.$portofolio->urlPorto) : '' }}"
                        class="img-thumbnail {{ isset($portofolio) ? '' : 'd-none' }}"
                        style="max-width: 200px;">
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        {{ isset($portofolio) ? 'Update' : 'Simpan' }}
                    </button>

                    <a href="{{ route('portofolio.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

@section('script')
<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
