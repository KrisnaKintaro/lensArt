<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

class kelolaJenisLayananController extends Controller
{
    /**
     * READ
     */
    public function index()
    {
        $jenisLayanan = JenisLayanan::withCount(['portofolio', 'paket'])
            ->orderBy('idJenisLayanan')
            ->paginate(10);

        return view(
            'admin.pages.kelolaLayanan_Harga.kelolaJenisLayanan',
            compact('jenisLayanan')
        );
    }

    /**
     * CREATE VIEW
     */
    public function create()
    {
        return view('admin.pages.kelolaLayanan_Harga.createJenisLayanan');
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'namaLayanan' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'aktif'       => 'required|boolean',
        ]);

        JenisLayanan::create($request->all());

        return redirect()->route('jenisLayanan.index')
            ->with('success', 'Jenis layanan berhasil ditambahkan');
    }

    /**
     * EDIT VIEW
     */
    public function edit($id)
    {
        $jenis = JenisLayanan::findOrFail($id);

        $jenisLayanan = JenisLayanan::withCount(['portofolio', 'paket'])
            ->orderBy('idJenisLayanan')
            ->paginate(10);

        return view(
            'admin.pages.kelolaLayanan_Harga.kelolaJenisLayanan',
            compact('jenis', 'jenisLayanan')
        );
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'namaLayanan' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'aktif'       => 'required|boolean',
        ]);

        $jenis = JenisLayanan::findOrFail($id);
        $jenis->update($request->all());

        return redirect()->route('jenisLayanan.index')
            ->with('success', 'Jenis layanan berhasil diperbarui');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $jenis = JenisLayanan::findOrFail($id);

        // Cek apakah sudah dipakai
        if (
            $jenis->portofolio()->count() > 0 ||
            $jenis->paket()->count() > 0
        ) {
            return redirect()
                ->route('jenisLayanan.index')
                ->with('error', 'Jenis layanan tidak dapat dihapus karena sudah digunakan pada Portofolio atau Paket');
        }

        $jenis->delete();

        return redirect()
            ->route('jenisLayanan.index')
            ->with('success', 'Jenis layanan berhasil dihapus');
    }
}