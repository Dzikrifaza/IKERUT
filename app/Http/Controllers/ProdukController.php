<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Signin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ValidateRequests;
class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = [
            'count_user' => Produk::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_product',
            'title'    => 'Table Produk'
        ];

        if ($request->ajax()) {
            $q_user = Produk::select('*');

            return Datatables::of($q_user)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                        $btn = '<div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
                        $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';

                         return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        // return $data;
        return view('layouts.v_template',$data);
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'thumbnail' => 'required|image'
        ]);

        if ($request->hasFile('thumbnail') == true) {
            $file_name = $request->file('thumbnail')->store('gambar/produk');

        }
                // return response()->json(['code'=>1,'msg'=>'Updated']);
                Produk::updateOrCreate(['id' => $request->user_id],
                [
                 'idkategori' => $request->idkategori,
                 'idsubkategori' => $request->idsubkategori,
                 'kategori' => $request->kategori,
                 'subkategori' => $request->subkategori,
                 'judul' => $request->judul,
                 'deskripsi' => $request->deskripsi,
                 'harga' => $request->harga,
                 'thumbnail' => $file_name,
                //  'thumbnail' => 'dist/images/'.$file_name,
                 'st' => $request->st,
                 'satuan' => $request->satuan,
                ]);
            return response()->json(['success'=>'Produk saved successfully!']);
    }

    public function edit($id)
    {
        $User = Produk::find($id);
        return response()->json($User);

    }

    public function destroy($id)
    {
        Produk::find($id)->delete();

        return response()->json(['success'=>'Produk deleted!']);
    }
}
