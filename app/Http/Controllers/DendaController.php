<?php

namespace App\Http\Controllers;

use App\Models\Denda;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user',$id_user)
        ->where('id_menu', 7)->first();
        if(empty($id_menu)) {
            return back();
        } else {

            $data = [
                'title' => 'Data Denda',
                'logout' => $request->session()->get('logout'),
                'denda' => Denda::orderBy('id_denda', 'desc')->get(),
                'karyawan' => Karyawan::all()
            ];
    
            return view('denda.denda', $data);
        }
    }

    public function addDenda(Request $request)
    {   
        $data = [
            'nama' => $request->nama,
            'alasan' => $request->alasan,
            'nominal' => $request->nominal,
            'tgl' => $request->tgl,
            'id_lokasi' => $request->session()->get('id_lokasi'),
            'admin' => Auth::user()->nama
        ];

        Denda::create($data);

        return redirect()->route('denda')->with('sukses', 'Berhasil tambah denda');
    }

    public function editDenda(Request $request)
    {
        $data = [
            'nama' => $request->nama,
            'alasan' => $request->alasan,
            'nominal' => $request->nominal,
            'tgl' => $request->tgl,
            'id_lokasi' => $request->session()->get('id_lokasi'),
            'admin' => Auth::user()->nama
        ];

        Denda::where('id_denda',$request->id_denda)->update($data);

      
        return redirect()->route('denda')->with('sukses', 'Berhasil Ubah Data denda');
    }

    public function deleteDenda(Request $request)
    {
        Denda::where('id_denda',$request->id_denda)->delete();
        return redirect()->route('denda')->with('error', 'Berhasil hapus denda');
    }

    public function printDenda(Request $request)
    {
        $tglDari = $request->dari;
        $tglSampai = $request->sampai;
        if (empty($tglDari)) {
            $dari = date('Y-m-1');
            $sampai = date('Y-m-d');
        } else {
            $dari = $tglDari;
            $sampai = $tglSampai;
        }

        $data = [
            'title' => 'Denda print',
            
            'date' => date('m/d/Y'),
            'denda' => DB::select("SELECT *, sum(a.nominal) as total FROM tb_denda as a WHERE `tgl` BETWEEN '$dari' and '$sampai' group by a.nama"),
            'karyawan' => Karyawan::all(),
            'alasan' => Denda::all()
        ];
    
        return view('denda.pdf', $data);
    }
}
