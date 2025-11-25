@extends('admin.masterAdmin')
@section('title', 'Kelola Data Customer')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Customer Terdaftar</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm btn-add">
                                    <i class="fas fa-plus"></i> Tambah Customer
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="tabelCustomer" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Foto</th>
                                        <th>Nama Lengkap</th>
                                        <th>Kontak</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataUser as $key => $user)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td class="text-center">
                                                @if ($user->fotoProfil)
                                                    <img src="{{ asset('gambarProfilAkun/' . $user->fotoProfil) }}"
                                                        class="img-circle img-size-50"
                                                        style="object-fit: cover; width: 50px; height: 50px;">
                                                @else
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->namaLengkap) }}"
                                                        class="img-circle img-size-50">
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $user->namaLengkap }}</strong><br>
                                                <small class="text-muted">ID: {{ $user->idUser }}</small>
                                            </td>
                                            <td>{{ $user->email }}<br>{{ $user->noTelp }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $user->role == 'admin' ? 'badge-danger' : 'badge-success' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm btn-edit"
                                                    data-id="{{ $user->idUser }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-delete"
                                                    data-id="{{ $user->idUser }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal-form-customer">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Form Customer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form-customer" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="_method" name="_method" value="POST">
                    <input type="hidden" id="idUserHidden" name="idUser">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" id="group-id-user" style="display: none;">
                                    <label>ID User</label>
                                    <input type="text" class="form-control" id="idUserDisplay" disabled>
                                </div>

                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="namaLengkap" id="namaLengkap" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required
                                        placeholder="Email Harus Unik">
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Minimal 6 karakter">
                                    <small class="text-muted" id="password-hint">Kosongkan jika tidak
                                        ingin mengganti password</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. Telepon</label>
                                    <input type="number" name="noTelp" id="noTelp" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label>Role</label>
                                    <select name="role" id="role" class="form-control">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Foto Profil</label>
                                    <input type="file" name="fotoProfil" id="fotoProfil" class="form-control-file">
                                    <small class="text-muted">Format: jpg, png, jpeg, ukuran maksimal 2Mb</small><br>
                                    <small class="text-muted" id="fotoProfil-hint" style="display: none;">Kosongkan jika tidak ingin dirubah</small>
                                    <div class="mt-2">
                                        <img id="preview-foto" src="" alt="Preview Image" class="img-circle"
                                            style="display: none; width: 100px; height: 100px; object-fit: cover; border: 2px solid #ccc;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-type-submit">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });

            $('#fotoProfil').on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview-foto').attr('src', e.target.result);
                        $('#preview-foto').show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $('#preview-foto').hide();
                }
            });

            $("#tabelCustomer").DataTable({
                "responsive": true,
                "lengthChange": false,
                "ordering": false,
                "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tabelCustomer_wrapper .col-md-6:eq(0)');

            // Alur tambah data baru
            $('.btn-add').on('click', function() {
                $('#form-customer')[0].reset();

                $('#preview-foto').attr('src', '').hide();

                $('.modal-header').removeClass('bg-warning bg-danger');
                $('.modal-header').addClass('bg-primary');
                $('.modal-title').css('color', '#fff');
                $('.close').css('color', '#fff');
                $('.btn-type-submit').removeClass('bg-warning bg-danger');
                $('.btn-type-submit').addClass('bg-primary');
                $('.btn-type-submit').css('color', '#fff');
                $('.btn-type-submit').text('Tambah');

                $('#modal-title').html('<i class="fas fa-plus"></i> Tambah Customer Baru');

                $('#_method').val('POST')
                $('#password').attr('required', true);
                $('#password-hint').hide();

                $('#modal-form-customer').modal('show');
            });

            $('#form-customer').on('submit', function(e) {
                e.preventDefault();

                let data = new FormData(this);
                for (var pair of data.entries()) {
                    console.log(pair[0] + ', ' + pair[1]);
                }
                $.ajax({
                    url: "{{ route('kelolaAkunCustomer.tambahData') }}",
                    type: "POST",
                    data: data,
                    contentType: false, // Wajib buat upload file
                    processData: false, // Wajib buat upload file
                    success: function(response) {
                        $('#modal-form-customer').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: 'Berhasil menyimpan data!'
                        });
                        // Reload untuk update data di table
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let pesanError =
                                '<ul style="text-align: left; margin-left: 20px;">'; // Bikin list HTML
                            $.each(errors, function(key, value) {
                                // value[0] itu isinya pesan custom
                                pesanError += '<li>' + value[0] + '</li>';
                            });

                            pesanError += '</ul>';
                            Swal.fire({
                                icon: 'error',
                                title: 'Eits, ada yang kurang pas!',
                                html: pesanError,
                                confirmButtonText: 'Oke, Saya Perbaiki',
                                confirmButtonColor: '#d33',
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan Server',
                                text: 'Silakan hubungi admin atau coba lagi nanti.'
                            });
                            console.log(xhr.responseText);
                        }
                    }
                });
            });

            // Alur edit data
            // pake 'body' on click biar aman kalau datatable di-page 2 dst
            $('body').on('click', '.btn-edit', function() {
                $('#form-customer')[0].reset();

                $('.modal-header').removeClass('bg-primary bg-danger');
                $('.modal-header').addClass('bg-warning');
                $('.modal-title').css('color', '#000');
                $('.close').css('color', '#000');
                $('.btn-type-submit').removeClass('bg-primary bg-danger');
                $('.btn-type-submit').addClass('bg-warning');
                $('.btn-type-submit').css('color', '#000');
                $('.btn-type-submit').text('Edit');

                $('.modal-title').html('<i class="fas fa-edit"></i> Edit Data Customer');
                Swal.showLoading();
                let idUser = $(this).data('id');

                // Suruh laravel buat link pake id palsu, kemuddian rubah isinya
                let url = "{{ route('kelolaAkunCustomer.ambilDataEdit', 'idUser') }}";
                url = url.replace('idUser', idUser);

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        Swal.close();

                        let urlFoto = "";
                        if(response.fotoProfil){
                            urlFoto = "{{ asset('gambarProfilAkun') }}/" + response.fotoProfil;
                        }else{
                            urlFoto = "https://ui-avatars.com/api/?name=" + encodeURIComponent(response.namaLengkap);
                        };
                        $('#preview-foto').attr('src', urlFoto).show();

                        $('#idUserDisplay').val(response.idUser);
                        $('#idUserHidden').val(response.idUser);
                        $('#namaLengkap').val(response.namaLengkap);
                        $('#email').val(response.email);
                        $('#noTelp').val(response.noTelp);
                        $('#role').val(response.role);
                        $('#password').attr('required', false);
                        $('#password-hint').show();
                        $('#fotoProfil-hint').show();
                        $('#group-id-user').show();
                    },
                    error: function(xhr) {
                        Swal.close();
                        $('#modal-form-customer').modal('hide');
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal mengambil data',
                            text: 'User tidak ditemukan atau terjadi kesalahan server.'
                        });
                    },
                });

                $('#modal-form-customer').modal('show');
            });

            // ALur hapus data
            $('.btn-delete').on('click', function() {
                $('#form-customer')[0].reset();

                $('.modal-header').removeClass('bg-primary bg-warning');
                $('.modal-header').addClass('bg-danger');
                $('.modal-title').css('color', '#fff');
                $('.close').css('color', '#fff');
                $('.btn-type-submit').removeClass('bg-primary bg-warning');
                $('.btn-type-submit').addClass('bg-danger');
                $('.btn-type-submit').css('color', '#fff');
                $('.btn-type-submit').text('Hapus');

                $('.modal-title').html('<i class="fas fa-trash"></i> Hapus Data Customer');
                $('#modal-form-customer').modal('show');
            })
        });
    </script>
@endsection
