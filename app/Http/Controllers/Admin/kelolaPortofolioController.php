<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use App\Models\JenisLayanan;
use Illuminate\Http\Request;

class kelolaPortofolioController extends Controller
{
    /**
     * READ + FILTER + PAGINATION
     */
    public function index(Request $request)
    {
        $jenisLayanan = JenisLayanan::all();

        $query = Portofolio::with('jenisLayanan');

        if ($request->idJenisLayanan) {
            $query->where('idJenisLayanan', $request->idJenisLayanan);
        }

        $portofolios = $query->orderByDesc('idPortofolio')
                            ->paginate(10);

        $portofolios->appends($request->all());

        return view(
            'admin.pages.kelolaPortofolio.indexPortofolio',
            compact('portofolios', 'jenisLayanan')
        );
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        $jenisLayanan = JenisLayanan::all();

        return view(
            'admin.pages.kelolaPortofolio.createPortofolio',
            compact('jenisLayanan')
        );
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {

        $request->validate([
            'idJenisLayanan' => 'required|integer',
            'namaPortofolio' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'urlPorto'       => 'required|image|mimes:jpg,jpeg,png',
            'jenisPorto'     => 'required|string|max:100',
            'tanggalPorto'   => 'required|date',
        ]);

        $file = $request->file('urlPorto');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('assetslensart/portofolio/'), $namaFile);

        Portofolio::create([
            'idJenisLayanan' => $request->idJenisLayanan,
            'namaPortofolio' => $request->namaPortofolio,
            'deskripsi'      => $request->deskripsi,
            'urlPorto'       => $namaFile,
            'jenisPorto'     => $request->jenisPorto,
            'tanggalPorto'   => $request->tanggalPorto,
        ]);

        return redirect()->route('portofolio.index')
            ->with('success', 'Data portofolio berhasil ditambahkan');
    }

    /**
     * FORM EDIT
     */
    public function edit($id)
    {
        $portofolio = Portofolio::findOrFail($id);
        $jenisLayanan = JenisLayanan::all();

        return view(
            'admin.pages.kelolaPortofolio.createPortofolio',
            compact('portofolio', 'jenisLayanan')
        );
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'idJenisLayanan' => 'required|integer',
            'namaPortofolio' => 'required|string|max:255',
            'deskripsi'      => 'nullable|string',
            'urlPorto'       => 'nullable|image|mimes:jpg,jpeg,png',
            'jenisPorto'     => 'required|string|max:100',
            'tanggalPorto'   => 'required|date',
        ]);

        $portofolio = Portofolio::findOrFail($id);
        $namaFile = $portofolio->urlPorto;

        if ($request->hasFile('urlPorto')) {
            $path = public_path('assetslensart/portofolio/' . $portofolio->gambar);
            if ($portofolio->gambar && file_exists($path)) {
                unlink($path);
            }

            $file = $request->file('urlPorto');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assetslensart.portofolio'), $namaFile);
        }

        $portofolio->update([
            'idJenisLayanan' => $request->idJenisLayanan,
            'namaPortofolio' => $request->namaPortofolio,
            'deskripsi'      => $request->deskripsi,
            'urlPorto'       => $namaFile,
            'jenisPorto'     => $request->jenisPorto,
            'tanggalPorto'   => $request->tanggalPorto,
        ]);

        return redirect()->route('portofolio.index')
            ->with('success', 'Data portofolio berhasil diperbarui');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $portofolio = Portofolio::findOrFail($id);

        $path = public_path('assetslensart/portofolio/' . $portofolio->gambar);
        if ($portofolio->urlPorto && file_exists($path)) {
            unlink($path);
        }

        $portofolio->delete();

        return redirect()->route('portofolio.index')
            ->with('success', 'Data portofolio berhasil dihapus');
    }
}
