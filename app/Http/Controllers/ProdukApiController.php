<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ProdukApiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index(){
        $produk = Produk::all();
        return response()->json([
            'message' =>'success',
            'data' => $produk
    ]);
    }
}
