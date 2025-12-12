<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan;
use App\Models\Portofolio;
use Illuminate\Http\Request;

class dashboardAwalController extends Controller
{
    /**
     * Method yang dipanggil oleh route '/' untuk tampilan landing/opening.
     * MEMANGGIL VIEW YANG BENAR: customer.tampilan_opening
     */
    public function tampilan_opening()
    {
        // Ganti 'customer.tampilanCustomer' dengan nama view yang benar
        return view('customer.tampilan_opening'); 
    }

    /**
     * Method index() untuk route('/dashboard') setelah login.
     */
    public function index()
    {
        // View ini berisi Auth::user() dan hanya diakses setelah login
        return view('customer.tampilanCustomer'); 
    }

    // ... (metode tampilPortofolio, filter, dst. tetap sama) ...
    public function tampilPortofolio()
    {
        $portofolios = Portofolio::with('jenisLayanan')
            ->orderBy('tanggalPorto', 'desc')
            ->get();
        $groupedPortofolios = $portofolios->groupBy('idJenisLayanan');
        $jenisLayanans = JenisLayanan::all();

        return view('customer.portofolio', [
            'groupedPortofolios' => $groupedPortofolios,
            'jenisLayanans' => $jenisLayanans
        ]);
    }

    public function filter($id)
    {
        if ($id === "all") {
            $data = Portofolio::orderBy('tanggalPorto', 'desc')->get();
        } else {
            $data = Portofolio::where('idJenisLayanan', $id)
                ->orderBy('tanggalPorto', 'desc')
                ->get();
        }

        return response()->json($data);
    }
}