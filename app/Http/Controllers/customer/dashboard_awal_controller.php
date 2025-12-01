<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class dashboard_awal_controller extends Controller
{
    public function tampilan_opening(){
        return view('customer.tampilan_opening');
    }
}
