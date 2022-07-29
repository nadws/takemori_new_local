<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Ctt_driver;
use App\Models\Denda;
use App\Models\Discount;
use App\Models\Harga;
use App\Models\Karyawan;
use App\Models\Jumlah_orang;
use App\Models\Kasbon;
use App\Models\Mencuci;
use App\Models\Handicap;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Order2;
use App\Models\Orderan;
use App\Models\Permission;
use App\Models\Tips;
use App\Models\Transaksi;
use App\Models\Users;
use App\Models\Voucher;
use App\Models\Gaji;
use App\Models\Voucher_hapus;
use App\Models\Persentase_kom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 7)->first();
        $voucher = Http::get("https://ptagafood.com/api/voucher");
        $dt_voucher = json_decode($voucher, TRUE);


        $date = date('Y-m-d');
        $data = [
            'title' => 'Import',
            'voucher' => $dt_voucher,
            'logout' => $request->session()->get('logout'),

        ];
        return view('download.index', $data);
    }

    public function voucher(Request $request)
    {
        $voucher = Http::get("https://ptagafood.com/api/voucher_tkmr");
        $dt_voucher = json_decode($voucher, TRUE);
        foreach ($dt_voucher['voucher'] as $v) {
            $v_lokal = Voucher::where('kode', $v['kode'])->first();

            if (!$v_lokal) {
                $data = [
                    'jumlah' => $v['jumlah'],
                    'ket' => $v['ket'],
                    'expired' => $v['expired'],
                    'status' => $v['status'],
                    'lokasi' => $v['lokasi'],
                    'kode' => $v['kode'],
                    'terpakai' => $v['terpakai'],
                    'admin' => $v['admin'],
                ];
                Voucher::create($data);
            } else {
            }
        }
        return redirect()->route('sukses2')->with('sukses', 'Sukses');
    }
    public function user(Request $request)
    {
        $user = Http::get("https://ptagafood.com/api/users");
        $dt_user = json_decode($user, TRUE);
        Users::truncate();

        foreach ($dt_user['users'] as $v) {
            $data = [
                'id' => $v['id'],
                'nama' => $v['nama'],
                'username' => $v['username'],
                'password' => $v['password'],
                'id_posisi' => $v['id_posisi'],
                'jenis' => $v['jenis'],
            ];
            Users::create($data);
        }

        $permision = Http::get("https://ptagafood.com/api/tb_permission");
        $dt_permision = json_decode($permision, TRUE);
        Permission::truncate();
        foreach ($dt_permision['tb_permission'] as $v) {
            $data = [
                'id_user' => $v['id_user'],
                'id_menu' => $v['id_menu'],
                'lokasi' => $v['lokasi'],
            ];
            Permission::create($data);
        }

        return redirect()->route('sukses2')->with('sukses', 'Sukses');
    }

    public function discount(Request $request)
    {
        $discount = Http::get("https://ptagafood.com/api/diskon_tkm");
        $dt_discount = json_decode($discount, TRUE);
        Discount::truncate();
        foreach ($dt_discount['diskon'] as $v) {
            $data = [
                'id_discount' => $v['id_discount'],
                'disc' => $v['disc'],
                'ket' => $v['ket'],
                'jenis' => $v['jenis'],
                'dari' => $v['dari'],
                'expired' => $v['expired'],
                'status' => $v['status'],
                'lokasi' => $v['lokasi'],
            ];
            Discount::create($data);
        }
        return redirect()->route('sukses2')->with('sukses', 'Sukses');
    }
    public function karyawan(Request $request)
    {
        $karyawan = Http::get("https://ptagafood.com/api/karyawan_tb");
        $dt_karyawan = json_decode($karyawan, TRUE);
        Karyawan::truncate();
        foreach ($dt_karyawan['karyawan'] as $v) {
            $data = [
                'id_karyawan' => $v['id_karyawan'],
                'nama' => $v['nama'],
                'id_status' => $v['id_status'],
                'tgl_masuk' => $v['tgl_masuk'],
                'id_posisi' => $v['id_posisi'],
                'posisi' => $v['posisi'],
                'point' => $v['point'],
            ];
            Karyawan::create($data);
        }

        $gaji = Http::get("https://ptagafood.com/api/gaji");
        $dt_gaji = json_decode($gaji, TRUE);
        Gaji::truncate();
        foreach ($dt_gaji['gaji'] as $v) {
            $data = [
                'id_gaji' => $v['id_gaji'],
                'id_karyawan' => $v['id_karyawan'],
                'rp_e' => $v['rp_e'],
                'rp_m' => $v['rp_m'],
                'rp_sp' => $v['rp_sp'],
                'g_bulanan' => $v['g_bulanan'],
            ];
            Gaji::create($data);
        }

        $tb_jumlah_orang = Http::get("https://ptagafood.com/api/tb_jumlah_orang");
        $dt_tb_jumlah_orang = json_decode($tb_jumlah_orang, TRUE);
        Jumlah_orang::truncate();
        foreach ($dt_tb_jumlah_orang['tb_jumlah_orang'] as $v) {
            $data = [
                'id_orang' => $v['id_orang'],
                'ket_karyawan' => $v['ket_karyawan'],
                'jumlah' => $v['jumlah'],
                'id_lokasi' => $v['id_lokasi']
            ];
            Jumlah_orang::create($data);
        }

        $tb_persentase = Http::get("https://ptagafood.com/api/persentase_komisi");
        $dt_tb_persentase = json_decode($tb_persentase, TRUE);
        Persentase_kom::truncate();
        foreach ($dt_tb_persentase['persentase_komisi'] as $v) {
            $data = [
                'id_pesentase' => $v['id_persentase'],
                'nama_persentase' => $v['nama_persentase'],
                'jumlah_persen' => $v['jumlah_persen'],
                'id_lokasi' => $v['id_lokasi']
            ];
            Persentase_kom::create($data);
        }

        $tb_menit = Http::get("https://ptagafood.com/api/tb_menit");
        $dt_menit = json_decode($tb_menit, TRUE);
        DB::table('tb_menit')->truncate();
        foreach ($dt_menit['tb_menit'] as $v) {
            $data = [
                'id_menit' => $v['id_menit'],
                'nm_menit' => $v['nm_menit'],
                'menit' => $v['menit'],
                'id_lokasi' => $v['id_lokasi'],
            ];
            DB::table('tb_menit')->insert($data);
        }

        return redirect()->route('sukses2')->with('sukses', 'Sukses');
    }

    public function menu(Request $request)
    {
        $menu = Http::get("https://ptagafood.com/api/menu_tb");
        $dt_menu = json_decode($menu, TRUE);
        Menu::truncate();
        foreach ($dt_menu['menu'] as $v) {
            $kode_server = $v['id_menu'];
            $v_local = DB::selectOne("SELECT * from tb_menu as a where a.id_menu = '$kode_server'");
            $data = [
                'id_menu' => $v['id_menu'],
                'id_kategori' => $v['id_kategori'],
                'id_handicap' => $v['id_handicap'],
                'kd_menu' => $v['kd_menu'],
                'nm_menu' => $v['nm_menu'],
                'tipe' => $v['tipe'],
                'image' => $v['image'],
                'jenis' => $v['jenis'],
                'lokasi' => $v['lokasi'],
                'aktif' => $v['aktif'],
                'tgl_sold' => $v['tgl_sold'],
                'id_handicap' => $v['id_handicap']
            ];
            Menu::create($data);
        }
        $harga = Http::get("https://ptagafood.com/api/harga_tb");
        $dt_harga = json_decode($harga, TRUE);
        Harga::truncate();
        foreach ($dt_harga['harga'] as $v) {
            $data = [
                'id_harga' => $v['id_harga'],
                'id_menu' => $v['id_menu'],
                'id_distribusi' => $v['id_distribusi'],
                'harga' => $v['harga'],
            ];
            Harga::create($data);
        }

        $handicap = Http::get("https://ptagafood.com/api/handicap");
        $dt_handicap = json_decode($handicap, TRUE);
        Handicap::truncate();
        foreach ($dt_handicap['handicap'] as $v) {
            $data = [
                'id_handicap' => $v['id_handicap'],
                'handicap' => $v['handicap'],
                'point' => $v['point'],
                'ket' => $v['ket'],
                'id_lokasi' => $v['id_lokasi'],
            ];
            Handicap::create($data);
        }
        $kategori = Http::get("https://ptagafood.com/api/kategori_menu");
        $dt_kategori = json_decode($kategori, TRUE);
        Kategori::truncate();
        foreach ($dt_kategori['kategori_menu'] as $v) {
            $data = [
                'kd_kategori' => $v['kd_kategori'],
                'kategori' => $v['kategori'],
                'lokasi' => $v['lokasi'],
            ];
            Kategori::create($data);
        }

        

        return redirect()->route('sukses2')->with('sukses', 'Sukses');
    }

    public function tb_voucher_hapus()
    {
        $tb_voucher_hapus = Http::get("https://ptagafood.com/api/tb_voucher_hapus");
        $dt_voucher_hapus = json_decode($tb_voucher_hapus, TRUE);

        foreach ($dt_voucher_hapus['tb_voucher_hapus'] as $v) {
            $v_lokal = Voucher_hapus::where('id_voucher', $v['id_voucher'])->first();
            if (!$v_lokal) {
                $data = [
                    'id_voucher' => $v['id_voucher'],
                    'kode_voucher' => $v['kode_voucher'],
                    'status' => $v['status'],
                ];
                Voucher_hapus::create($data);
            } else {
            }
        }
        return redirect()->route('sukses2')->with('sukses', 'Sukses');
    }


    public function sukses(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 7)->first();
        if (empty($id_menu)) {
            return back();
        } else {

            $data = [
                'title' => 'Import',
                'logout' => $request->session()->get('logout'),
            ];
            return view('api_import.index2', $data);
        }
    }
}
