<?php

namespace App\Http\Controllers\Customer; // Namespace sudah otomatis terisi

use App\Http\Controllers\Controller;
use App\Models\JenisLayanan; 
use Illuminate\Http\Request;

class JenisLayananController extends Controller
{
    public function index()
    {
        $jenisLayanan = JenisLayanan::with('paket')->get(); 

        return view('customer.layanan', compact('jenisLayanan')); 
    }
}