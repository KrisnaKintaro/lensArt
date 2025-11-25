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
        $dataUser = User::where('role', 'customer')
            ->latest('created_at')
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

    public function ambilDataEdit($idUser){
        $dataUser = user::where('idUser', $idUser)->first();

        return response()->json($dataUser);
    }

    public function editData(){

    }

    public function hapusData(){

    }
}
