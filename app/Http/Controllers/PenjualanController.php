<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ValidateRequests;
class PenjualanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = [
            'count_user' => Penjualan::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_penjualan',
            'title'    => 'Table Penjualan'
        ];

        if ($request->ajax()) {
            $q_user = Penjualan::select('*');
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

    public function edit($id)
    {
        $User = Penjualan::find($id);
        return response()->json($User);
    }

    public function destroy($id)
    {
        Penjualan::find($id)->delete();

        return response()->json(['success'=>'Produk deleted!']);
    }
}
