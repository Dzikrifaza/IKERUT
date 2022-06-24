<?php

namespace App\Http\Controllers;

use App\Models\Signin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;

class SigninController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    } 

    public function index(Request $request)
    {
        $data = [
            'count_user' => Signin::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_pengguna',
            'title'    => 'Table Pengguna'
        ];

        if ($request->ajax()) {
            $q_user = Signin::select('*'); 
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
            'foto' => 'required|image'
        ]);

        if ($request->hasFile('foto') == true) {
            $file_name = $request->file('foto')->store('gambar/pengguna');
            // dd($file_name);
        }
        Signin::updateOrCreate(['id' => $request->id],
                [
                 'userid' => $request->userid,
                 'pass' => Hash::make($request->pass),
                 'nama' => $request->nama,
                 'level' => $request->level,
                 'email' => $request->email,
                 'foto' => $file_name,
                ]);        

        return response()->json(['success'=>'User saved successfully!']);
    }

    public function edit($id)
    {
        $User = Signin::find($id);
        return response()->json($User);
    }

    public function destroy($id)
    {
        Signin::find($id)->delete();

        return response()->json(['success'=>'Customer deleted!']);
    }
}
