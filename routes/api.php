<?php

use App\Models\Absen;
use App\Models\Ctt_driver;
use App\Models\Discount;
use App\Models\Dp;
use App\Models\Harga;
use App\Models\Karyawan;
use App\Models\Mencuci;
use App\Models\Menu;
use App\Models\Orderan;
use App\Models\Tips;
use App\Models\Transaksi;
use App\Models\Voucher;
use Illuminate\Http\Request as r;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (r $request) {
    return $request->user();
});

Route::get('voucher', function () {
    $data = [
        'voucher' => Voucher::all(),
    ];
    return response()->json($data, HttpFoundationResponse::HTTP_OK);
});

Route::get('discount', function () {
    $data = [
        'disount' => Discount::all(),
    ];
    return response()->json($data, HttpFoundationResponse::HTTP_OK);
});


Route::post('tb_order', function (r $t) {

    $data = array(
        'no_order' => $t->no_order,
        'id_harga' => $t->id_harga,
        'qty' => $t->qty,
        'harga' => $t->harga,
        'tambahan' => $t->tambahan,
        'page' => $t->page,
        'id_meja' => $t->id_meja,
        'selesai' => $t->selesai,
        'id_lokasi' => $t->id_lokasi,
        'pengantar' => $t->pengantar,
        'tgl' => $t->tgl,
        'void' => $t->void,
        'round' => $t->round,
        'alasan' => $t->alasan,
        'nm_void' => $t->nm_void,
        'j_mulai' => $t->j_mulai,
        'j_selesai' => $t->j_selesai,
        'admin' => $t->admin,
        'diskon' => $t->diskon,
        'wait' => $t->wait,
        'aktif' => $t->aktif,
        'id_kok1' => $t->id_kok1,
        'id_kok2' => $t->id_kok2,
        'ongkir' => $t->ongkir,
        'id_distribusi' => $t->id_distribusi,
        'orang' => $t->orang,
        'no_checker' => $t->no_checker,
        'print ' => $t->print,
        'copy_print ' => $t->copy_print,
        'request' => $t->request2,
    );
    $testing = Orderan::create($data);
    if (!$testing) {
        return 'gagal';
    } else {
        return 'sukses';
    }
});

Route::post('tb_transaki', function (r $t) {
    $data = array(
        'tgl_transaksi' => $t->tgl_transaksi,
        'no_order' => $t->no_order,
        'total_orderan' => $t->total_orderan,
        'discount' => $t->discount,
        'voucher' => $t->voucher,
        'dp' => $t->dp,
        'gosen' => $t->gosen,
        'total_bayar' => $t->total_bayar,
        'admin' => $t->admin,
        'round' => $t->round,
        'id_lokasi' => $t->id_lokasi,
        'cash' => $t->cash,
        'd_bca' => $t->d_bca,
        'k_bca' => $t->k_bca,
        'd_mandiri' => $t->d_mandiri,
        'k_mandiri' => $t->k_mandiri,
        'ongkir' => $t->ongkir,
        'service' => $t->service,
        'tax' => $t->tax,
    );
    $testing = Transaksi::create($data);
    if (!$testing) {
        return 'gagal';
    } else {
        return 'sukses';
    }
});
Route::post('tb_absen', function (r $t) {
    $data = array(
        'id_karyawan' => $t->id_karyawan,
        'status' => $t->status,
        'tgl' => $t->tgl,
        'id_lokasi' => $t->id_lokasi,
    );
    $testing = Absen::create($data);
    if (!$testing) {
        return 'gagal';
    } else {
        return 'sukses';
    }
});

Route::post('tb_mencuci', function (r $t) {
    $data = array(
        'nm_karyawan' => $t->nm_karyawan,
        'id_ket' => $t->id_ket,
        'j_awal' => $t->j_awal,
        'j_akhir' => $t->j_akhir,
        'tgl' => $t->tgl,
        'admin' => $t->admin,
    );
    $testing = Mencuci::create($data);
    if (!$testing) {
        return 'gagal';
    } else {
        return 'sukses';
    }
});
Route::post('tb_driver', function (r $t) {
    $data = array(
        'no_order' => $t->no_order,
        'nm_driver' => $t->nm_driver,
        'nominal' => $t->nominal,
        'tgl' => $t->tgl,
        'admin' => $t->admin,
    );
    $testing = Ctt_driver::create($data);
    if (!$testing) {
        return 'gagal';
    } else {
        return 'sukses';
    }
});


Route::post('tb_tips', function (r $t) {
    $data = array(
        'tgl' => $t->tgl,
        'nominal' => $t->nominal,
        'admin' => $t->admin,
    );
    $testing = Tips::create($data);
    if (!$testing) {
        return 'gagal';
    } else {
        return 'sukses';
    }
});


Route::get('menu_tb', function () {
    $data = [
        'menu' => Menu::all(),
    ];
    return response()->json($data, HttpFoundationResponse::HTTP_OK);
});
Route::get('harga_tb', function () {
    $data = [
        'harga' => Harga::all(),
    ];
    return response()->json($data, HttpFoundationResponse::HTTP_OK);
});
Route::get('karyawan_tb', function () {
    $data = [
        'karyawan' => Karyawan::all(),
    ];
    return response()->json($data, HttpFoundationResponse::HTTP_OK);
});
