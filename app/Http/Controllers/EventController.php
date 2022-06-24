<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    } 

    public function index(Request $request)
    {
        $data = [
            'count_user' => Event::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_event',
            'title'    => 'Table Event'
        ];

        if ($request->ajax()) {
            $q_user = Event::select('*'); 
            return Datatables::of($q_user)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
     
                        $btn = '<div data-toggle="tooltip"  data-id="'.$row->id_event.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
                        $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id_event.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';
 
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
            $file_name = $request->file('thumbnail')->store('gambar/event');
        }
        Event::updateOrCreate(['id_event' => $request->id_event],
                [
                 'nama_event' => $request->nama_event,
                 'tanggal' => $request->tanggal,
                 'htm' => $request->htm,
                 'thumbnail' => $file_name,
                ]);        

        return response()->json(['success'=>'User saved successfully!']);
    }

    public function edit($id_event)
    {
        $User = Event::find($id_event);
        return response()->json($User);
    }

    public function destroy($id)
    {
        Event::find($id)->delete();

        return response()->json(['success'=>'Customer deleted!']);
    }
}
