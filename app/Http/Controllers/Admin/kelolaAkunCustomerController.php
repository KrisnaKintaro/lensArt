<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class kelolaAkunCustomerController extends Controller
{
    public function index()
    {
        $dataUser = User::latest('created_at')
            ->get();
        return view('admin.pages.kelolaAkunCustomer.kelolaAkunCustomer', compact('dataUser'));
    }

    public function tambahData(Request $request)
    {
        $validator = $request->validate([
            'namaLengkap' => 'required',
            'email'       => 'required|email|unique:user,email',
            'password'    => 'required|min:6',
            'noTelp'      => 'required|numeric',
            'role'        => 'required',
            'fotoProfil'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'namaLengkap.required' => 'Waduh, Nama Lengkap wajib diisi dong cuy!',
            'email.required' => 'Email jangan dikosongin ya.',
            'email.unique'   => 'Yah, email ini udah dipake orang lain. Ganti yang lain ya!',
            'password.required' => 'Password harus diisi biar aman.',
            'noTelp.numeric'  => 'Nomor HP isinya angka doang, jangan pake huruf.',
            'password.min'      => 'Password kependekan cuy! Minimal 6 karakter lah.',
            'fotoProfil.image' => 'File yang diupload harus berupa gambar.',
            'fotoProfil.mimes' => 'Format gambar cuma boleh JPG, JPEG, atau PNG.',
            'fotoProfil.max'   => 'Gambar kegedean! Maksimal ukuran 2MB aja.',
        ]);

        $namaFoto = null;

        if ($request->hasFile('fotoProfil')) {
            $file = $request->fotoProfil;
            $namaFoto = time() . ' _ ' . $file->getClientOriginalName();
            $file->move(public_path('gambarProfilAkun'), $namaFoto);
        }

        User::create([
            'namaLengkap' => $request->namaLengkap,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'noTelp'      => $request->noTelp,
            'role'        => $request->role,
            'fotoProfil'  => $namaFoto
        ]);

        return response()->json(['success' => true]);
    }

    public function ambilDataEdit($idUser)
    {
        $dataUser = user::where('idUser', $idUser)->first();

        return response()->json($dataUser);
    }

    public function editData(Request $request, $idUser)
    {
        $dataUser = User::where('idUser', $idUser)->first();

        $validator = $request->validate([
            'namaLengkap' => 'required',
            // Email unique pengecualian: Boleh sama kalau itu email dia sendiri
            'email'       => 'required|email|unique:user,email,' . $dataUser->idUser . ',idUser',
            'password'    => 'nullable|min:6',
            'noTelp'      => 'required|numeric',
            'role'        => 'required',
            'fotoProfil'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'namaLengkap.required' => 'Waduh, Nama Lengkap wajib diisi dong cuy!',
            'email.required' => 'Email jangan dikosongin ya.',
            'email.unique'   => 'Yah, email ini udah dipake orang lain. Ganti yang lain ya!',
            'noTelp.numeric'  => 'Nomor HP isinya angka doang, jangan pake huruf.',
            'password.min'      => 'Password kependekan cuy! Minimal 6 karakter lah.',
            'fotoProfil.image' => 'File yang diupload harus berupa gambar.',
            'fotoProfil.mimes' => 'Format gambar cuma boleh JPG, JPEG, atau PNG.',
            'fotoProfil.max'   => 'Gambar kegedean! Maksimal ukuran 2MB aja.',
        ]);

        $dataUser->namaLengkap = $request->namaLengkap;
        $dataUser->email = $request->email;
        $dataUser->noTelp = $request->noTelp;
        $dataUser->role = $request->role;

        if ($request->filled('password')) {
            $dataUser->password = Hash::make($request->password);
        }

        if ($request->hasFile('fotoProfil')) {
            if ($dataUser->fotoProfil && file_exists(public_path('gambarProfilAkun/' . $dataUser->fotoProfil))) {
                unlink(public_path('gambarProfilAkun/' . $dataUser->fotoProfil));
            }
            $file = $request->file('fotoProfil');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambarProfilAkun'), $namaFoto);

            $dataUser->fotoProfil = $namaFoto;
        }

        $dataUser->save();
        return response()->json(['success' => true]);
    }

    public function hapusData($idUser)
    {
        $dataUser = USer::where('idUser', $idUser)->first();

        if ($dataUser) {
            if ($dataUser->fotoProfil && file_exists(public_path('gambarProfilAkun/' . $dataUser->fotoProfil))) {
                unlink(public_path('gambarProfilAkun/' . $dataUser->fotoProfil));
            }

            $dataUser->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus bersih!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'User tidak ditemukan'
        ], 404);
    }
}
