<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Ctt_driver;
use App\Models\Denda;
use App\Models\Kasbon;
use App\Models\Mencuci;
use App\Models\Order2;
use App\Models\Orderan;
use App\Models\Tips;
use App\Models\Jurnal;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Import_allController extends Controller
{
    public function index(Request $request)
    {

        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 7)->first();
        if (empty($id_menu)) {
            return back();
        } else {
            $date = date('Y-m-d');
            $data = [
                'title' => 'Import',
                'logout' => $request->session()->get('logout'),
                'tb_order' => Orderan::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_order2' => Order2::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_transaksi' => Transaksi::where('import', 'T')->where('tgl_transaksi', '<=', $date)->first(),
                'tb_absen' => Absen::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_mencuci' => Mencuci::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_driver' => Ctt_driver::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_tips' => Tips::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_denda' => Denda::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_kasbon' => Kasbon::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_jurnal' => Jurnal::where('import', 'T')->where('tgl', '<=', $date)->first(),
                'tb_pembelian' => DB::table('tb_pembelian')->where('import', 'T')->where('tanggal', '<=', $date)->first(),
                'komisi' => DB::table('komisi')->where('import', 'T')->where('tgl', '<=', $date)->first()
            ];
            return view('import_all.index', $data);
        }
    }
}
