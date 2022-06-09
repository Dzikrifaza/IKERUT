<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class ImageController extends BaseController
{

    public function getImage(){
        return response()->file(storage_path("app/public/1654176385_barang.png"));
    }

}
