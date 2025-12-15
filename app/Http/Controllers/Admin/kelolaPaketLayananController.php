<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketLayanan;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

class kelolaPaketLayananController extends Controller
{
    /**
     * READ
     */
    public function index()
    {
        $jenisLayanan = JenisLayanan::where('aktif', 1)->get();

        $paketLayanan = PaketLayanan::with('jenisLayanan')
            ->withCount('slotJadwal')
            ->orderBy('idPaketLayanan')
            ->paginate(10);

        return view(
            'admin.pages.kelolaLayanan_Harga.kelolaPaketLayanan',
            compact('paketLayanan', 'jenisLayanan')
        );
    }

    /**
     * EDIT (1 VIEW)
     */
    public function edit($id)
    {
        $jenis = JenisLayanan::where('aktif', 1)->get();

        $paket = PaketLayanan::findOrFail($id);

        $paketLayanan = PaketLayanan::with(['jenisLayanan'])
            ->withCount('slotJadwal')
            ->orderBy('idPaketLayanan')
            ->paginate(10);

        return view(
            'admin.pages.kelolaLayanan_Harga.kelolaPaketLayanan',
            [
                'paketEdit'    => $paket,
                'paketLayanan' => $paketLayanan,
                'jenisLayanan' => $jenis
            ]
        );
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $request->validate([
            'idJenisLayanan' => 'required|integer',
            'namaPaket'      => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'jumlahFileEdit' => 'required|integer|min:0',
            'durasiJam'      => 'required|integer|min:1',
            'harga'          => 'required|numeric|min:0',
            'aktif'          => 'required|boolean',
        ]);

        PaketLayanan::create($request->all());

        return redirect()
            ->route('paketLayanan.index')
            ->with('success', 'Paket layanan berhasil ditambahkan');
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'idJenisLayanan' => 'required|integer',
            'namaPaket'      => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'jumlahFileEdit' => 'required|integer|min:0',
            'durasiJam'      => 'required|integer|min:1',
            'harga'          => 'required|numeric|min:0',
            'aktif'          => 'required|boolean',
        ]);

        $paket = PaketLayanan::findOrFail($id);
        $paket->update($request->all());

        return redirect()
            ->route('paketLayanan.index')
            ->with('success', 'Paket layanan berhasil diperbarui');
    }

    /**
     * DELETE (PROTECTED)
     */
    public function destroy($id)
    {
        $paket = PaketLayanan::findOrFail($id);

        if ($paket->slotJadwal()->count() > 0) {
            return redirect()
                ->route('paketLayanan.index')
                ->with('error', 'Paket tidak dapat dihapus karena sudah digunakan pada jadwal');
        }

        $paket->delete();

        return redirect()
            ->route('paketLayanan.index')
            ->with('success', 'Paket layanan berhasil dihapus');
    }
}