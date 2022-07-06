<?php

namespace App\Http\Controllers;

use App\Models\Aktiva;
use App\Models\Akun;
use App\Models\Atk;
use App\Models\Jurnal;
use App\Models\Karyawan;
use App\Models\KelPeralatan;
use App\Models\Peralatan;
use App\Models\PostCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Illuminate\Support\Str;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $data = [
            'title' => 'Akun',
            'logout' => $request->session()->get('logout'),
            'akun' => Akun::join('tb_kategori_akun', 'tb_kategori_akun.id_kategori', '=', 'tb_akun.id_kategori')->get()
        ];
        return view('accounting.home', $data);
    }

    public function dashboard(Request $request)
    {
        $data = [
            'title' => 'Accounting Takemori',
            'logout' => $request->session()->get('logout'),
            'akun' => Akun::join('tb_kategori_akun', 'tb_kategori_akun.id_kategori', '=', 'tb_akun.id_kategori')->get()
        ];
        return view('accounting.dashboard', $data);
    }

    public function akun(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 21)->first();
        if (empty($id_menu)) {
            return back();
        } else {
            if (Auth::user()->jenis == 'adm') {
                $data = [
                    'title' => 'Akun',
                    'logout' => $request->session()->get('logout'),
                    'akun' => Akun::join('tb_kategori_akun', 'tb_kategori_akun.id_kategori', '=', 'tb_akun.id_kategori')->where('id_lokasi', $request->acc)->orderBy('no_akun', 'asc')->get(),
                    
                ];

                return view('accounting.accounting', $data);
            } else {
                return back();
            }
        }
    }

    public function addPostCenter(Request $request)
    {
        $id_akun = $request->id_akun;
        $nm_post = $request->nm_post;
        $id_lokasi = $request->id_lokasi;

        $data = [
            'id_akun' => $id_akun,
            'nm_post' => $nm_post,
            'id_lokasi' => $id_lokasi,
        ];
        PostCenter::create($data);
        return redirect()->route('akun', ['acc' => $id_lokasi])->with('sukses', 'Berhasil tambah post center');
    }

    public function get_data_post_center(Request $request)
    {
        $id_akun = $request->id_akun;
        $id_lokasi = $request->id_lokasi;

        $data = [
            'post_center' => PostCenter::where('id_akun', $id_akun)->get()
            
        ];

        return view('accounting.postCenter',$data);
    }

    public function getProjek(Request $request)
    {
        $proyek = $request->id_projek;
        $aktiva = DB::select("SELECT j.id_proyek, sum(j.debit) as kredit1 from tb_jurnal as j where j.id_proyek = '$proyek' group by j.id_proyek");
        $output = [];
        foreach ($aktiva as $k) {
            if ($k->id_proyek == "PR022") {
                $output['b_kredit'] = '0';
            } else {
                $output['b_kredit'] = $k->kredit1;
            }
        }
        echo json_encode($output);
    }

    public function getPost(Request $request)
    {
        $id_pilih = $request->id_pilih;
        $post = PostCenter::where('id_akun', $id_pilih)->get();
        // dd($post);
        echo "<option value=''>Pilih post center</option>";
        foreach ($post as $k) {
            echo "<option value='" . $k->id_post . "'>" . $k->nm_post . "</option>";
        }
    }

    public function getPost2(Request $request)
    {
        $id_pilih = $request->id_pilih;
        $post = DB::select("SELECT a.*
        FROM tb_post_center AS a 
        WHERE a.id_akun = '$id_pilih'  AND a.nm_post NOT IN(SELECT b.barang FROM aktiva AS b)");
        echo "<option value=''>Pilih post center</option>";
        foreach ($post as $k) {
            echo "<option value='" . $k->id_post . "'>" . $k->nm_post . "</option>";
        }
    }

    public function addAkun(Request $request)
    {

        $data = [
            'kd_akun' => $request->kd_akun,
            'no_akun' => $request->no_akun,
            'nm_akun' => $request->nm_akun,
            'id_kategori' => $request->id_kategori,
            'id_lokasi' => $request->id_lokasi
        ];

        Akun::create($data);

        return redirect()->route('akun', ['acc' => $request->id_lokasi])->with('sukses', 'Data berhasil Ditambah');
    }

    public function editAkun(Request $request)
    {

        $data = [
            'kd_akun' => $request->kd_akun,
            'no_akun' => $request->no_akun,
            'nm_akun' => $request->nm_akun,
            'id_kategori' => $request->id_kategori,
        ];

        Akun::where('id_akun', $request->id_akun)->update($data);

        return redirect()->route('akun', ['acc' => $request->id_lokasi])->with('sukses', 'Data berhasil Diubah');
    }

    public function exportAkun(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:D4')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // lebar kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        $sheet
            ->setCellValue('A1', 'NO AKUN')
            ->setCellValue('B1', 'AKUN ' . $request->id_lokasi == 1 ? 'TAKEMORI' : 'SOONDOBU')
            ->setCellValue('C1', 'KODE AKUN')
            ->setCellValue('D1', 'ID KATEGORI')
            ->setCellValue('E1', 'ID LOKASI');
        $no = 2;
        $data = Akun::join('tb_kategori_akun', 'tb_kategori_akun.id_kategori', '=', 'tb_akun.id_kategori')->where('id_lokasi', $request->id_lokasi)->orderBy('no_akun', 'asc')->get();
        foreach ($data as $k) {
            $sheet
                ->setCellValue('A' . $no, $k->no_akun)
                ->setCellValue('B' . $no, $k->nm_akun)
                ->setCellValue('C' . $no, $k->kd_akun)
                ->setCellValue('D' . $no, $k->id_kategori)
                ->setCellValue('E' . $no, $request->id_lokasi);
            $no++;
        }
        $writer = new Xlsx($spreadsheet);
        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];

        // tambah style
        $batas = count($data) + 1;
        $sheet->getStyle('A1:E' . $batas)->applyFromArray($style);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Data Akun.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function importAkun(Request $request)
    {
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);
        // $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
        // $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $lokasi = $request->id_lokasi;
        $numrow = 1;
        foreach ($sheet as $row) {
            if ($numrow > 1) {
                $data = [
                    'id_lokasi' => $row['E'],
                    'kd_akun' => $row['C'],
                    'no_akun' => $row['A'],
                    'nm_akun' => $row['B'],
                    'id_kategori' => $row['D'],
                ];
                Akun::create($data);
            }
            $numrow++;
        }
        return redirect()->route('akun', ['acc' => $lokasi])->with('sukses', 'Data berhasil Diimport');
    }

    public function jPemasukan(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 32)->first();
        if (empty($id_menu)) {
            return back();
        } else {
            if (Auth::user()->jenis == 'adm') {
                $tglDari = $request->dari;
                $tglSampai = $request->sampai;
                if (empty($tglDari)) {
                    $dari = date('Y-m-1');
                    $sampai = date('Y-m-d');
                } else {
                    $dari = $tglDari;
                    $sampai = $tglSampai;
                }
                $id_lokasi = $request->acc;
                $data = [
                    'title' => 'Jurnal Pemasukan',
                    'logout' => $request->session()->get('logout'),
                    'jurnal' => Jurnal::join('tb_akun', 'tb_akun.id_akun', '=', 'tb_jurnal.id_akun')->where([
                        ['id_buku', 1],
                        ['tb_jurnal.id_lokasi', $id_lokasi]
                    ])->whereBetween('tgl', [$dari, $sampai])->get(),
                ];

                return view('accounting.jPemasukan', $data);
            } else {
                return back();
            }
        }
    }

    public function jPengeluaran(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 33)->first();
        if (empty($id_menu)) {
            return back();
        } else {
            if (Auth::user()->jenis == 'adm') {
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
                    'title' => 'Jurnal Pengeluaran',
                    'logout' => $request->session()->get('logout'),
                    'akun' => Akun::where('id_lokasi', $request->acc)->get(),
                    'jurnal' => Jurnal::join('tb_akun', 'tb_akun.id_akun', '=', 'tb_jurnal.id_akun')->where([['tb_jurnal.id_lokasi', $request->get('acc')], ['tb_jurnal.id_buku', 3]])->whereBetween('tgl', [$dari, $sampai])->get(),
                    'satuan' => DB::table('tb_satuan')->get(),
                    'peralatan' => KelPeralatan::all(),
                    'nm_penanggung' => DB::table('tb_penanggung_jawab')->get(),
                    'lokasi' => DB::table('tb_lokasi')->get(),
                    'aktiva' => DB::table('tb_kelompok_aktiva')->get(),
                ];

                return view('accounting.jPengeluaran', $data);
            } else {
                return back();
            }
        }
    }

    public function addjPengeluaran(Request $request)
    {

        $kd_gabungan = 'RST' . date('dmy') . strtoupper(Str::random(3));
        // dd($kd_gabungan);
        $tgl = $request->tgl;
        $id_akun = $request->id_akun;
        $metode = $request->metode;
        $ket = $request->keterangan;
        $ket2 = $request->ket2;
        $ttl_rp = $request->ttl_rp;
        $id_proyek = $request->id_proyek;
        $admin = Auth::user()->nama;
        $id_satuan = $request->id_satuan;
        $qty = $request->qty;
        $id_post = $request->id_post_center;
        $no_id = $request->no_id;
        $id_lokasi = $request->id_lokasi;

        $month = date('m', strtotime($tgl));
        $year = date('Y', strtotime($tgl));

        $get_kode_akun = Akun::where('id_akun', $id_akun)->get()[0];
        $kode_akun = Jurnal::where('id_akun', $id_akun)->whereMonth('tgl', $month)->whereYear('tgl', $year)->count();

        if ($kode_akun == 0) {
            $kode_akun = 1;
        } else {
            $kode_akun += 1;
        }

        $get_kd_metode = Akun::where('id_akun', $metode)->get()[0];
        $kode_metode = Jurnal::where('id_akun', $metode)->whereMonth('tgl', $month)->whereYear('tgl', $year)->count();

        if ($kode_metode == 0) {
            $kode_metode = 1;
        } else {
            $kode_metode += 1;
        }

        $total = 0;

        for ($count = 0; $count < count($ttl_rp); $count++) {
            $total += $ttl_rp[$count];
        }

        $data_metode = [
            'id_buku' => 3,
            'id_akun' => $metode,
            'id_customer' => '3',
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_metode->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_metode,
            'kredit' => $total,
            'tgl' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            'jenis' => 'biaya',
            'id_lokasi' => $id_lokasi,
            // 'id_post' => $id_post

        ];

        Jurnal::create($data_metode);

        for ($i = 0; $i < count($ttl_rp); $i++) {
            // $total += $ttl_rp[$i];
            $data_jurnal = [
                'id_buku' => 3,
                'id_akun' => $id_akun,
                'id_customer' => '3',
                'kd_gabungan' => $kd_gabungan,
                'id_proyek' => $id_proyek,
                'no_nota' => $get_kode_akun->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_akun,
                'debit' => $ttl_rp[$i],
                'ket' => $ket[$i],
                'ket2' => $ket2[$i],
                'tgl' => $tgl,
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
                'qty' => $qty[$i],
                'no_urutan' => $no_id[$i],
                'id_satuan' => $id_satuan[$i],
                // 'rp_beli' => $rp_beli[$i],
                'ttl_rp' => $ttl_rp[$i],
                'jenis' => 'biaya',
                'id_lokasi' => $id_lokasi,
            ];

            Jurnal::create($data_jurnal);
            // $kode_akun++;
        }

        return redirect()->route('jPengeluaran', ['acc' => $id_lokasi])->with('sukses', 'Data berhasil Ditambah');
    }

    public function addjAtk(Request $request)
    {
        $kd_gabungan = 'RST' . date('dmy') . strtoupper(Str::random(3));
        // dd($kd_gabungan);
        $tgl = $request->tgl;
        $id_akun = $request->id_akun;
        $metode = $request->metode;
        $ket = $request->keterangan;
        $ket2 = $request->ket2;
        $ttl_rp = $request->ttl_rp;
        $id_proyek = $request->id_proyek;
        $admin = Auth::user()->nama;
        $id_satuan = $request->id_satuan;
        $qty = $request->qty;
        $id_post = $request->id_post_center;
        $no_id = $request->no_id;
 
        $id_lokasi = $request->id_lokasi;

        $month = date('m', strtotime($tgl));
        $year = date('Y', strtotime($tgl));

        $get_kode_akun = Akun::where('id_akun', $id_akun)->get()[0];
        $kode_akun = Jurnal::where('id_akun', $id_akun)->whereMonth('tgl', $month)->whereYear('tgl', $year)->count();

        if ($kode_akun == 0) {
            $kode_akun = 1;
        } else {
            $kode_akun += 1;
        }

        $get_kd_metode = Akun::where('id_akun', $metode)->get()[0];
        $kode_metode = Jurnal::where('id_akun', $metode)->whereMonth('tgl', $month)->whereYear('tgl', $year)->count();

        if ($kode_metode == 0) {
            $kode_metode = 1;
        } else {
            $kode_metode += 1;
        }

        $total = 0;
        for ($i = 0; $i < count($ttl_rp); $i++) {
            $total += $ttl_rp[$i];
        }

        $data_metode = [
            'id_buku' => 3,
            'id_akun' => $metode,
            'id_customer' => '3',
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_metode->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_metode,
            'kredit' => $total,
            'tgl' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            'jenis' => 'atk',
            'id_lokasi' => $id_lokasi,
            // 'id_post' => $id_post

        ];

        Jurnal::create($data_metode);

        for ($i = 0; $i < count($ttl_rp); $i++) {
            // $total += $ttl_rp[$i];
            $data_jurnal = [
                'id_buku' => 3,
                'id_akun' => $id_akun,
                'id_customer' => '3',
                'kd_gabungan' => $kd_gabungan,
                'id_proyek' => $id_proyek,
                'no_nota' => $get_kode_akun->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_akun,
                'debit' => $ttl_rp[$i],
                'ket' => $ket[$i],
                'ket2' => $ket2[$i],
                'tgl' => $tgl,
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
                'qty' => $qty[$i],
                'no_urutan' => $no_id[$i],
                'id_satuan' => $id_satuan[$i],
                // 'rp_beli' => $rp_beli[$i],
                'ttl_rp' => $ttl_rp[$i],
                'jenis' => 'atk',
                'id_lokasi' => $id_lokasi,
            ];

            Jurnal::create($data_jurnal);
            // $kode_akun++;
        }

        for ($i = 0; $i < count($ket); $i++) {

            $nota = Atk::select("SELECT MAX(a.no_nota) as nota
            FROM tb_atk AS a");

            if (empty($nota->nota)) {
                $no = '1001';
            } else {
                $no = $nota->nota + 1;
            }
            $data_atk = [
                'no_nota' => $no,
                'tgl' => $tgl,
                'nm_barang' => $ket2[$i],
                'debit_atk' => $ttl_rp[$i],
                'qty_debit' => $qty[$i],
                'id_satuan' => $id_satuan[$i],
                'id_lokasi' => $id_lokasi

            ];
            Atk::create($data_atk);
        }

        return redirect()->route('jPengeluaran', ['acc' => $id_lokasi])->with('sukses', 'Data berhasil Ditambah');
    }

    public function addjPeralatan(Request $request)
    {
        $kd_gabungan = 'RST' . date('dmy') . strtoupper(Str::random(3));
        // dd($kd_gabungan);
        $tgl = $request->tgl;
        $id_akun = $request->id_akun;
        $metode = $request->metode;
        $nm_barang = $request->nm_barang;
        $ttl_rp = $request->ttl_rp;
        $total3 = $request->total;
        $admin = Auth::user()->nama;
        $id_satuan = $request->id_satuan;
        $qty = $request->qty;
        $no_urutan = $request->no_id;
        $id_lokasi = $request->id_lokasi;
        $id_penanggung = $request->id_penanggung;
        $id_kelompok = $request->id_kelompok;

        $month = date('m', strtotime($tgl));
        $year = date('Y', strtotime($tgl));

        $get_kode_akun = Akun::where('id_akun', $id_akun)->get()[0];
        $kode1 = DB::selectOne("SELECT a.id_jurnal,a.no_nota, SUBSTRING(a.no_nota,-1) AS nota2
        FROM tb_jurnal AS a
        WHERE a.id_akun = '$id_akun' AND MONTH(a.tgl) = '$month' AND YEAR(a.tgl) = '$year'
        GROUP BY a.no_nota
        ORDER BY SUBSTRING(a.no_nota,-1) DESC");

        if (empty($kode1)) {
            $kode_akun = 1;
        } else {
            $kode_akun = $kode1->nota2 + 1;
        }

        $get_kd_metode = Akun::where('id_akun', $metode)->get()[0];
        $kode = DB::selectOne("SELECT a.id_jurnal,a.no_nota, SUBSTRING(a.no_nota,-1) AS nota2
        FROM tb_jurnal AS a
        WHERE a.id_akun = '$metode' AND MONTH(a.tgl) = '$month' AND YEAR(a.tgl) = '$year'
        GROUP BY a.no_nota
        ORDER BY SUBSTRING(a.no_nota,-1) DESC
        ");

        if (empty($kode)) {
            $kode_metode = 1;
        } else {
            $kode_metode = $kode->nota2 + 1;
        }

        $total = 0;
        for ($count = 0; $count < count($ttl_rp); $count++) {
            $total += $ttl_rp[$count];
        }

        $data_metode = [
            'id_buku' => 3,
            'id_akun' => $metode,
            'id_customer' => '3',
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_metode->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_metode,
            'kredit' => $total3,
            'tgl' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            'jenis' => 'peralatan',
            'id_lokasi' => $id_lokasi,
            // 'id_post' => $id_post

        ];

        Jurnal::create($data_metode);

        for ($i = 0; $i < count($ttl_rp); $i++) {
            $data_jurnal = [
                'id_buku' => 3,
                'id_customer' => '3',
                'id_akun' => $id_akun,
                'kd_gabungan' => $kd_gabungan,
                'no_nota' => $get_kode_akun->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_akun,
                'debit' => $ttl_rp[$i],
                'ket' => 'pembelian ' . ' ' . $nm_barang[$i],
                'tgl' => $tgl,
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
                'qty' => $qty[$i],
                'id_satuan' => $id_satuan[$i],
                'ttl_rp' => $ttl_rp[$i],
                'no_urutan' => $no_urutan[$i],
                'jenis' => 'peralatan',
                'id_lokasi' => $id_lokasi
            ];
            $id_kredit = Jurnal::create($data_jurnal);
            $id_kredit = $id_kredit->id;
        }

        for ($x = 0; $x < sizeof($nm_barang); $x++) {
          
            $kelompok = KelPeralatan::where('id_kelompok', $id_kelompok[$x])->first();

            if ($kelompok->satuan == 'Bulan') {
                $susut = $kelompok->umur;
            } else {
                $susut = $kelompok->umur * 12;
            }
            $b_penyusutan = $ttl_rp[$x] / $susut;

            $data_barang = [
                'tgl' => $tgl,
                'id_kelompok_peralatan' =>  $id_kelompok[$x],
                'barang' => $nm_barang[$x],
                'qty' => $qty[$x],
                'debit' => $ttl_rp[$x],
                'lokasi' => $id_lokasi[$x],
                'id_satuan' => $id_satuan[$x],
                'penanggung_jawab' => $id_penanggung[$x],
                'id_peralatan_kredit' => $id_kredit,
                'b_penyusutan' =>  $b_penyusutan,

            ];
            Peralatan::create($data_barang);
        }
        return redirect()->route('jPengeluaran', ['acc' => $id_lokasi])->with('sukses', 'Data berhasil Ditambah');
    }

    public function addjAktiva(Request $request)
    {
        $kd_gabungan = 'RST' . date('dmy') . strtoupper(Str::random(3));
        // dd($kd_gabungan);
        $tgl = $request->tgl;
        $id_akun = $request->id_akun;
        $metode = $request->metode;
        $ttl_rp = $request->ttl_rp;
        $total = $request->total;
        $admin = Auth::user()->nama;
        $rp_satuan = $request->rp_satuan;
        $qty = $request->qty;
        $ppn = $request->ppn;
        $id_lokasi = $request->id_lokasi;
        $id_post = $request->id_post;
        $ket2 = $request->ket;
        $no_urutan = $request->no_id;
        $id_satuan = $request->id_satuan;

        $post_center = DB::table('tb_post_center')->where('id_post')->first();

        $ket3 = empty($post_center->nm_post) ? $ket2 : $post_center->nm_post;

        $month = date('m', strtotime($tgl));
        $year = date('Y', strtotime($tgl));

        $get_kode_akun = Akun::where('id_akun', $id_akun)->get()[0];
        $kode1 = DB::selectOne("SELECT a.id_jurnal,a.no_nota, SUBSTRING(a.no_nota,-1) AS nota2
        FROM tb_jurnal AS a
        WHERE a.id_akun = '$id_akun' AND MONTH(a.tgl) = '$month' AND YEAR(a.tgl) = '$year'
        GROUP BY a.no_nota
        ORDER BY SUBSTRING(a.no_nota,-1) DESC");

        if (empty($kode1)) {
            $kode_akun = 1;
        } else {
            $kode_akun = $kode1->nota2 + 1;
        }

        $get_kd_metode = Akun::where('id_akun', $metode)->get()[0];
        $kode = DB::selectOne("SELECT a.id_jurnal,a.no_nota, SUBSTRING(a.no_nota,-1) AS nota2
        FROM tb_jurnal AS a
        WHERE a.id_akun = '$metode' AND MONTH(a.tgl) = '$month' AND YEAR(a.tgl) = '$year'
        GROUP BY a.no_nota
        ORDER BY SUBSTRING(a.no_nota,-1) DESC
        ");

        if (empty($kode)) {
            $kode_metode = 1;
        } else {
            $kode_metode = $kode->nota2 + 1;
        }

        $total = $ttl_rp;

        $get_kode_akun = Akun::where('id_akun', $id_lokasi == 1 ? 100 : 141)->get()[0];
        $kode_ppn = Jurnal::where('id_akun', $id_lokasi == 1 ? 100 : 141)->whereMonth('tgl', $month)->whereYear('tgl', $year)->count();

        if ($kode_ppn == 0) {
            $kode_ppn = 1;
        } else {
            $kode_ppn += 1;
        }

        $data_metode = [
            'id_buku' => 3,
            'id_akun' => $metode,
            'id_customer' => '3',
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kd_metode->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_metode,
            'kredit' => $total,
            'tgl' => $tgl,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            'jenis' => 'aktiva',
            'id_lokasi' => $id_lokasi,
            'no_urutan' => $no_urutan,
            // 'id_post' => $id_post

        ];

        Jurnal::create($data_metode);

        if(empty($ppn)) {

        } else {
            $data_jurnal = [
                'id_buku' => 3,
                'id_akun' => $id_lokasi == 1 ? 100 : 141,
                'id_customer' => '3',
                'kd_gabungan' => $kd_gabungan,
                'no_nota' => $get_kode_akun->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_akun,
                'debit' => $ppn,
                'tgl' => $tgl,
                'ttl_rp' => $total,
                'tgl_input' => date('Y-m-d H:i:s'),
                'admin' => $admin,
                'no_urutan' => $no_urutan,
                // 'rp_beli' => $rp_beli[$i],
                'jenis' => 'aktiva',
                'id_lokasi' => $id_lokasi,
            ];

            Jurnal::create($data_jurnal);
            $kode_akun++;
        }

        $data_jurnal = [
            'id_buku' => 3,
            'id_akun' => $id_akun,
            'id_customer' => '3',
            'kd_gabungan' => $kd_gabungan,
            'no_nota' => $get_kode_akun->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_akun,
            'debit' => $rp_satuan * $qty,
            'tgl' => $tgl,
            'ket' => $ket3,
            'ttl_rp' => $ttl_rp,
            'tgl_input' => date('Y-m-d H:i:s'),
            'admin' => $admin,
            'qty' => $qty,
            'no_urutan' => $no_urutan,
            // 'rp_beli' => $rp_beli[$i],
            'jenis' => 'aktiva',
            'id_lokasi' => $id_lokasi,
        ];

        Jurnal::create($data_jurnal);

        $id_kelompok = $request->id_kelompok;
        $id = $id_kelompok;
        $kelompok = DB::table('tb_kelompok_aktiva')->where('id_kelompok', $id)->first();
        $susut = $kelompok->tarif;
        $satuan = DB::table('tb_satuan')->where('id', $id_satuan)->first();
        $aktiva = [
            'id_lokasi' => $id_lokasi,
            'barang' => $ket3,
            'debit_aktiva' => $rp_satuan * $qty,
            'tgl' => $tgl,
            'qty' => $qty,
            'satuan' => $satuan->n,
            'nota' => $get_kode_akun->kd_akun . date('my', strtotime($tgl)) . '-' . $kode_akun,
            'b_penyusutan' =>(($rp_satuan * $qty) * $susut) / 12,
        ];

        Aktiva::create($aktiva);

        return redirect()->route('jPengeluaran', ['acc' => $id_lokasi])->with('sukses', 'Data berhasil Ditambah');

    }

    public function deletejPengeluaran(Request $request)
    {
        Jurnal::where('kd_gabungan', $request->kd_gabungan)->delete();
        return redirect()->route('jPengeluaran', ['acc' => $request->id_lokasi])->with('error', 'Data berhasil dihapus');
    }
}
