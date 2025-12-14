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
            'urlPorto'       => 'nullable|url',
            'jenisPorto'     => 'required|string|max:100',
            'tanggalPorto'   => 'required|date',
            'gambar'         => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('gambar');
        $namaFile = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('gambarPortofolio'), $namaFile);

        Portofolio::create([
            'idJenisLayanan' => $request->idJenisLayanan,
            'namaPortofolio' => $request->namaPortofolio,
            'deskripsi'      => $request->deskripsi,
            'urlPorto'       => $request->urlPorto,
            'jenisPorto'     => $request->jenisPorto,
            'tanggalPorto'   => $request->tanggalPorto,
            'gambar'         => $namaFile,
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
            'urlPorto'       => 'nullable|url',
            'jenisPorto'     => 'required|string|max:100',
            'tanggalPorto'   => 'required|date',
            'gambar'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $portofolio = Portofolio::findOrFail($id);
        $namaFile = $portofolio->gambar;

        if ($request->hasFile('gambar')) {
            $path = public_path('gambarPortofolio/' . $portofolio->gambar);
            if ($portofolio->gambar && file_exists($path)) {
                unlink($path);
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('gambarPortofolio'), $namaFile);
        }

        $portofolio->update([
            'idJenisLayanan' => $request->idJenisLayanan,
            'namaPortofolio' => $request->namaPortofolio,
            'deskripsi'      => $request->deskripsi,
            'urlPorto'       => $request->urlPorto,
            'jenisPorto'     => $request->jenisPorto,
            'tanggalPorto'   => $request->tanggalPorto,
            'gambar'         => $namaFile,
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

        $path = public_path('gambarPortofolio/' . $portofolio->gambar);
        if ($portofolio->gambar && file_exists($path)) {
            unlink($path);
        }

        $portofolio->delete();

        return redirect()->route('portofolio.index')
            ->with('success', 'Data portofolio berhasil dihapus');
    }
}