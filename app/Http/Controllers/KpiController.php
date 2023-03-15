<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class KpiController extends Controller
{
    public function index(Request $r)
    {
        $tgl1 = $r->tgl1 ?? date('Y-m-1');
        $tgl2 = $r->tgl2 ?? date('Y-m-d');
        $client = new Client();

        // tkm
        $response = $client->request('GET', "https://ptagafood.com/api/komisiServer/1/$tgl1/$tgl2");
        $data = json_decode($response->getBody(), true);
        
        $service = json_decode(json_encode($data['service']), false);
        $server = json_decode(json_encode($data['server']), false);
        $jumlah_orang = json_decode(json_encode($data['jumlah_orang']), false);
        $persen = json_decode(json_encode($data['persen']), false);
       
        $l = 1;
        $ttl_kom = 0;

        foreach ($server as $k) {
            $o = $l++;
            $ttl_kom += $k->komisi;
        }
        $service_charge = $service->total * 0.07;
        $orang = $o ?? 0;

        $kom = ((($service_charge / 7) * $persen->jumlah_persen) / $jumlah_orang->jumlah) * $orang;
        // -------------------------------

        // sdb
        $response = $client->request('GET', "https://ptagafood.com/api/komisiServer/2/$tgl1/$tgl2");
        $data2 = json_decode($response->getBody(), true);
        $service2 = json_decode(json_encode($data2['service']), false);
        $server2 = json_decode(json_encode($data2['server']), false);
        $jumlah_orang2 = json_decode(json_encode($data2['jumlah_orang']), false);
        $persen2 = json_decode(json_encode($data2['persen']), false);

        
        $l2 = 1;
        $ttl_kom2 = 0;

        foreach ($server2 as $k) {
            $o2 = $l2++;
            $ttl_kom2 += $k->komisi;
        }


        $service_charge2 = $service2->total * 0.07;
        $orang2 = $o2 ?? 0;

        $kom2 = ((($service_charge2 / 7) * $persen2->jumlah_persen) / $jumlah_orang2->jumlah) * $orang2;
        // -----------------------------------------------
        
        $data = [
            'title' => 'Kpi Server',
            'kom' => $kom,
            'kom2' => $kom2,

            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'logout' => $r->session()->get('logout'),
            'karyawan' => DB::select("SELECT a.nama, b.ttl FROM `tb_karyawan` as a
            LEFT JOIN (
                SELECT a.id_karyawan,count(a.id_karyawan) as ttl FROM tb_denda_kpi as a
                WHERE a.tgl BETWEEN '$tgl1' AND '$tgl2'
                GROUP BY a.id_karyawan
            ) b ON a.id_karyawan = b.id_karyawan
            WHERE a.id_status = 2"),
            'kategori_kpi' => DB::table('tb_kategori_kpi')->orderBy('urutan', 'ASC')->get(),
            'settingOrang' => DB::table('db_denda_kpi')->where('id', 1)->first()->rupiah,
            'rupiah' => DB::table('db_denda_kpi')->where('id', 2)->first()->rupiah,
            'persenBagi' => DB::table('db_denda_kpi')->where('id', 3)->first()->rupiah,
        ];
        return view('kpiServer.kpi', $data);
    }

    public function subKategori(Request $r)
    {
        $sub_kategori = DB::table('tb_sub_kategori_kpi')->where('id_kategori_kpi', $r->kategori_id)->orderBy('urutan', 'ASC')->get();

        $data = [
            'sub_kategori' => $sub_kategori,
            'karyawan' => DB::select("SELECT a.nama, a.id_karyawan FROM tb_karyawan as a WHERE a.id_status = '2'"),
        ];
        return view('kpiServer.loadSubKategori', $data);
    }

    public function saveDendaKpi(Request $r)
    {
        if (!empty($r->id_karyawan)) {
            for ($i = 0; $i < count($r->id_karyawan); $i++) {
                DB::table('tb_denda_kpi')->insert([
                    'id_karyawan' => $r->id_karyawan[$i],
                    'id_sub_kategori_kpi' => $r->sub_kategori_id,
                    'tgl' => date('Y-m-d'),
                    'ket' => empty($r->ket) ? $r->nmSubKategori : $r->ket,
                ]);

                $nama = DB::table('tb_karyawan')->where('id_karyawan', $r->id_karyawan[$i])->first()->nama;
                DB::table('tb_denda')->insert([
                    'nama' => $nama,
                    'alasan' => empty($r->ket) ? $r->nmSubKategori : $r->ket,
                    'nominal' => $r->rupiah,
                    'tgl' => date('Y-m-d'),
                    'id_lokasi' => 1,
                    'admin' => Auth::user()->nama,
                ]);
            }
            return redirect()->route('kpi', ['tgl1' => $r->tgl1, 'tgl2' => $r->tgl2])->with('sukses', 'Berhasil tambah denda');
        }

        return redirect()->route('kpi', ['tgl1' => $r->tgl1, 'tgl2' => $r->tgl2])->with('error', 'Gagal tambah denda');
    }

    public function kategoriKpi(Request $r)
    {
        $urutanKat = DB::table('tb_kategori_kpi')->latest('urutan')->orderBy('urutan', 'asc')->first();
        $urutanSubKat = DB::table('tb_sub_kategori_kpi')->latest('urutan')->orderBy('urutan', 'asc')->first();
        $data = [
            'title' => 'Kategori Kpi Server',
            'logout' => $r->session()->get('logout'),
            'kategori_kpi' => DB::table('tb_kategori_kpi')->orderBy('urutan', 'ASC')->get(),
            'sub_kategori_kpi' => DB::table('tb_sub_kategori_kpi as a')->join('tb_kategori_kpi as b', 'a.id_kategori_kpi', 'b.id_kategori_kpi')->select('a.id_sub_kategori_kpi', 'a.id_kategori_kpi', 'a.urutan', 'a.nm_sub_kategori', 'b.nm_kategori', 'a.icon')->orderBy('a.urutan', 'ASC')->get(),
            'settingOrang' => DB::table('db_denda_kpi')->where('id', 1)->first()->rupiah,
            'rupiah' => DB::table('db_denda_kpi')->where('id', 2)->first()->rupiah,
            'persen' => DB::table('db_denda_kpi')->where('id', 3)->first()->rupiah,
            'urutanKategori' => empty($urutanKat) ? 1 : $urutanKat->urutan + 1,
            'urutanSubKategori' => empty($urutanSubKat) ? 1 : $urutanSubKat->urutan + 1,
        ];
        return view('kpiServer.kategori', $data);
    }

    public function saveKategoriKpi(Request $r)
    {
        DB::table(empty($r->kategori_id) ? 'tb_kategori_kpi' : 'tb_sub_kategori_kpi')->insert([
            'urutan' => $r->urutan,
            'id_kategori_kpi' => $r->kategori_id,
            empty($r->kategori_id) ? 'nm_kategori' : 'nm_sub_kategori' => $r->nm_kategori,
            'icon' => 1,
        ]);

        return redirect()->route('kategoriKpi')->with('sukses', 'Berhasil tambah data');
    }

    public function hapusKategoriKpi($jenis, $id)
    {
        DB::table($jenis == 1 ? 'tb_kategori_kpi' : 'tb_sub_kategori_kpi')->where($jenis == 1 ? 'id_kategori_kpi' : 'id_sub_kategori_kpi', $id)->delete();
        return redirect()->route('kategoriKpi')->with('sukses', 'Berhasil hapus data');
    }

    public function saveSetKpi(Request $r)
    {
        DB::table('db_denda_kpi')->where('id', 1)->update(['rupiah' => $r->orang]);
        DB::table('db_denda_kpi')->where('id', 3)->update(['rupiah' => $r->persen]);

        return redirect()->route('kategoriKpi')->with('sukses', 'Berhasil edit data');
    }

    public function editKategoriKpi(Request $r)
    {
        if ($r->jenis == 1) {
            $tbl = 'tb_kategori_kpi';
            $idWhere = 'id_kategori_kpi';

            for ($i = 0; $i < count($r->urutan); $i++) {
                $data = [
                    'urutan' => $r->urutan[$i],
                    'nm_kategori' => $r->nm_kategori[$i],
                    'icon' => 1,
                ];
                DB::table('tb_kategori_kpi')->where('id_kategori_kpi', $r->id[$i])->update($data);
            }
        } else {
            $tbl = 'tb_sub_kategori_kpi';
            $idWhere = 'id_sub_kategori_kpi';

            $data = [
                'urutan' => $r->urutan,
                'id_kategori_kpi' => $r->kategori_id,
                'nm_sub_kategori' => $r->nm_kategori,
                'icon' => $r->icon,
            ];
            DB::table($tbl)->where($idWhere, $r->id)->update($data);
        }


        return redirect()->route('kategoriKpi')->with('sukses', 'Berhasil edit data');
    }

    public function sub_kategori(Request $r)
    {
        return view('kpiServer.sub_kategori', [
            'subKategori' => DB::table('tb_sub_kategori_kpi')->where('id_kategori_kpi', $r->id_kategori)->orderBy('urutan', 'ASC')->get(),
            'idKategori' => $r->id_kategori
        ]);
    }

    public function save_sub_kategori(Request $r)
    {
        for ($i = 0; $i < count($r->urutan); $i++) {
            DB::table('tb_sub_kategori_kpi')->where('id_sub_kategori_kpi', $r->id_sub_kategori[$i])->update([
                'urutan' => $r->urutan[$i],
                'nm_sub_kategori' => $r->nm_sub_kategori[$i],
                'rupiah' => $r->rupiah[$i],
                'icon' => 1
            ]);
        }
    }

    public function save_tambah_sub_kategori(Request $r)
    {
        DB::table('tb_sub_kategori_kpi')->insert([
            'urutan' => $r->urutan,
            'nm_sub_kategori' => $r->nm_kategori,
            'id_kategori_kpi' => $r->kategori_id,
            'rupiah' => $r->rupiah,
        ]);
    }

    public function delete_subkategori(Request $r)
    {
        DB::table('tb_sub_kategori_kpi')->where('id_sub_kategori_kpi', $r->id_sub_kategori)->delete();
    }
}
