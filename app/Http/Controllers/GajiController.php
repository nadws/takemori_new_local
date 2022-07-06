<?php

namespace App\Http\Controllers;

use App\Exports\GajiExport;
use App\Models\Gaji;
use App\Models\Karyawan;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GajiController extends Controller
{
    public function index(Request $request)
    {
        $id_user = Auth::user()->id;
        $id_menu = DB::table('tb_permission')->select('id_menu')->where('id_user', $id_user)
            ->where('id_menu', 22)->first();
        if (empty($id_menu)) {
            return back();
        } else {
            if (Auth::user()->jenis == 'adm') {
                $data = [
                    'title' => 'Gaji',
                    'logout' => $request->session()->get('logout'),
                    'gaji' => DB::select("SELECT a.*, b.*, c.id_gaji, c.rp_e, c.rp_m, c.rp_sp, c.g_bulanan FROM tb_karyawan as a LEFT JOIN tb_posisi as b ON a.id_posisi =  b.id_posisi LEFT JOIN tb_gaji as c ON a.id_karyawan = c.id_karyawan ORDER BY a.tgl_masuk DESC"),
                ];

                return view('gaji.gaji', $data);
            } else {
                return back();
            }
        }
    }

    public function editGaji(Request $request)
    {
        $id_gaji = $request->id_gaji;
        $id_karyawan = $request->id_karyawan;
        if (empty($id_gaji)) {
            $data = [
                'id_karyawan' => $id_karyawan,
                'rp_m' => $request->rp_m,
                'rp_e' => $request->rp_e,
                'rp_sp' => $request->rp_sp,
                'g_bulanan' => $request->g_bulanan,
            ];
            Gaji::create($data);
        } else {
            $data = [
                'rp_m' => $request->rp_m,
                'rp_e' => $request->rp_e,
                'rp_sp' => $request->rp_sp,
                'g_bulanan' => $request->g_bulanan,
            ];
            Gaji::where('id_gaji', $id_gaji)->update($data);
        }
        return redirect()->route('gaji');
    }

    public function gajiExport()
    {
        $gaji = DB::select("SELECT a.*, b.*, c.id_gaji, c.rp_e, c.rp_m, c.rp_sp, c.g_bulanan FROM tb_karyawan as a LEFT JOIN tb_posisi as b ON a.id_posisi =  b.id_posisi LEFT JOIN tb_gaji as c ON a.id_karyawan = c.id_karyawan ORDER BY a.tgl_masuk DESC");


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:D4')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // lebar kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(13);
        // header text
        $sheet
            ->setCellValue('A1', 'ID KARYAWAN')
            ->setCellValue('B1', 'NAMA')
            ->setCellValue('C1', 'POSISI')
            ->setCellValue('D1', 'TANGGAL MASUK')
            ->setCellValue('E1', 'LAMA')
            ->setCellValue('F1', 'RP E')
            ->setCellValue('G1', 'RP M')
            ->setCellValue('H1', 'RP SP')
            ->setCellValue('I1', 'BULANAN');
        $kolom = 2;
        $i = 1;
        foreach ($gaji as $k) {
            $totalKerja = new DateTime($k->tgl_masuk);
            $today = new DateTime();
            $tKerja = $today->diff($totalKerja);
            $sheet->setCellValue('A' . $kolom, $k->id_karyawan);
            $sheet->setCellValue('B' . $kolom, $k->nama);
            $sheet->setCellValue('C' . $kolom, $k->nm_posisi);
            $sheet->setCellValue('D' . $kolom, $k->tgl_masuk);
            $sheet->setCellValue('E' . $kolom, $tKerja->y . ' Tahun');
            $sheet->setCellValue('F' . $kolom, $k->rp_e);
            $sheet->setCellValue('G' . $kolom, $k->rp_m);
            $sheet->setCellValue('H' . $kolom, $k->rp_sp);
            $sheet->setCellValue('I' . $kolom, $k->g_bulanan);

            $kolom++;
        }

        $writer = new Xlsx($spreadsheet);
        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];
        $batas = $gaji;
        $batas = count($batas) + 1;
        $sheet->getStyle('A1:I' . $batas)->applyFromArray($style);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Gaji Karyawan Resto.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function gajiExportTemplate(Request $request)
    {
        $gaji = DB::select("SELECT a.*, b.*, c.id_gaji, c.rp_e, c.rp_m, c.rp_sp, c.g_bulanan FROM tb_karyawan as a LEFT JOIN tb_posisi as b ON a.id_posisi =  b.id_posisi LEFT JOIN tb_gaji as c ON a.id_karyawan = c.id_karyawan ORDER BY a.tgl_masuk DESC");


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getStyle('A1:D4')
            ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        // lebar kolom
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(13);
        $sheet->getColumnDimension('F')->setWidth(13);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(13);
        // header text
        $sheet
            ->setCellValue('A1', 'ID KARYAWAN')
            ->setCellValue('B1', 'NAMA')
            ->setCellValue('C1', 'RP E')
            ->setCellValue('D1', 'RP M')
            ->setCellValue('E1', 'RP SP')
            ->setCellValue('F1', 'BULANAN');
        $kolom = 2;
        $i = 1;
        foreach ($gaji as $k) {
            $totalKerja = new DateTime($k->tgl_masuk);
            $today = new DateTime();
            $tKerja = $today->diff($totalKerja);
            $sheet->setCellValue('A' . $kolom, $k->id_karyawan);
            $sheet->setCellValue('B' . $kolom, $k->nama);
            $sheet->setCellValue('C' . $kolom, $k->rp_e);
            $sheet->setCellValue('D' . $kolom, $k->rp_m);
            $sheet->setCellValue('E' . $kolom, $k->rp_sp);
            $sheet->setCellValue('F' . $kolom, $k->g_bulanan);

            $kolom++;
        }

        $writer = new Xlsx($spreadsheet);
        $style = [
            'borders' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ],
            ],
        ];
        $batas = $gaji;
        $batas = count($batas) + 1;
        $sheet->getStyle('A1:F' . $batas)->applyFromArray($style);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Gaji Karyawan Resto Template.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function gajiImport(Request $request)
    {
        $file = $request->file('file');
        $ext = $file->getClientOriginalExtension();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
        $spreadsheet = $reader->load($file);
        // $loadexcel = $excelreader->load('excel/'.$this->filename.'.xlsx'); // Load file yang telah diupload ke folder excel
        // $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $data = array();

        // lokasi
        $numrow = 1;

        foreach ($sheet as $row) {
            if ($row['A'] == '' && $row['B'] == '' && $row['C'] == '' && $row['D'] == '' && $row['E'] == '' && $row['F'] == '' && $row['G'] == '' && $row['H'] == '' && $row['I']) {
                continue;
            }
            if ($numrow > 1) {
                $data = [
                    'rp_e' => $row['F'],
                    'rp_m' => $row['G'],
                    'rp_sp' => $row['H'],
                    'g_bulanan' => $row['I'],
                ];
                Gaji::where('id_karyawan', $row['A'])->update($data);
                // if ($row['C'] == 0 || $row['D'] == 0 || $row['E'] == 0 || $row['F'] == 0 ) {
                //     $data = [
                //         'rp_e' => $row['C'],
                //         'rp_m' => $row['D'],
                //         'rp_sp' => $row['E'],
                //         'rp_bulanan' => $row['F'],
                //     ];
                //     Gaji::where('id_karyawan', $row['A'])->update($data);
                // } else {
                //     $data = [
                //         'rp_e' => $row['C'],
                //         'rp_m' => $row['D'],
                //         'rp_sp' => $row['E'],
                //         'rp_bulanan' => $row['F'],
                //     ];
                //     Gaji::where('id_karyawan', $row['A'])->update($data);
                // }
            }
            $numrow++;
        }

        return redirect()->route('karyawan')->with('sukses', 'Berhasil Import');
    }

    public function tabelGaji(Request $request)
    {
        $tgl1 = $request->dari;
        $tgl2 = $request->sampai;
        $gaji = DB::select("SELECT a.nama, a.tgl_masuk , b.rp_e, b.rp_m, b.rp_sp, b.g_bulanan, sum(d.qty_m) AS M, sum(d.qty_e) AS E, sum(d.qty_sp) AS Sp,
        
        gagal_masak.point_gagal, berhasil_masak.point_berhasil, cuci.lama_cuci

        FROM tb_karyawan AS a
        LEFT JOIN tb_gaji AS b ON a.id_karyawan = b.id_karyawan
        
        LEFT JOIN (
        SELECT c.id_karyawan,  c.status,
        if(c.status = 'M', COUNT(c.status), 0) AS qty_m,
        if(c.status = 'E', COUNT(c.status), 0) AS qty_e,
        if(c.status = 'SP', COUNT(c.status), 0) AS qty_sp
        FROM tb_absen AS c 
        WHERE c.tgl BETWEEN '$tgl1' AND '$tgl2'
        GROUP BY c.id_karyawan, c.status
        ) AS d ON d.id_karyawan = a.id_karyawan

        LEFT JOIN (
            SELECT koki, SUM(nilai_koki) as point_gagal FROM view_nilai_masak2 
            WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND lama_masak > 30
            GROUP BY koki
        )gagal_masak ON a.id_karyawan = gagal_masak.koki

        LEFT JOIN (
            SELECT koki, SUM(nilai_koki) as point_berhasil FROM view_nilai_masak2 
            WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' AND lama_masak <= 30
            GROUP BY koki
        )berhasil_masak ON a.id_karyawan = berhasil_masak.koki

        LEFT JOIN(
            SELECT nm_karyawan, SUM(lama_cuci) as lama_cuci FROM view_mencuci WHERE tgl >= '$tgl1' AND tgl <= '$tgl2' GROUP BY nm_karyawan
        ) cuci ON a.nama = cuci.nm_karyawan

        GROUP BY a.id_karyawan
        ");

        $data = [
            'dari' => $tgl1,
            'sampai' => $tgl2,
            'gaji' => $gaji,
        ];
        return view('gaji.tabelGaji', $data);
    }

    public function gajiSum(Request $request)
    {
        $dari = $request->dari;
        $sampai = $request->sampai;

        $data = [
            'dari' => $dari,
            'sampai' => $sampai,
            'gaji' => DB::select("SELECT a.nama, a.tgl_masuk ,b.rp_e, b.rp_m, b.rp_sp, b.g_bulanan, sum(d.qty_m) AS M, sum(d.qty_e) AS E, sum(d.qty_sp) AS Sp, d.nm_posisi, gagal_masak.point_gagal, berhasil_masak.point_berhasil, cuci.lama_cuci
            FROM tb_karyawan AS a

            LEFT JOIN tb_gaji AS b ON a.id_karyawan = b.id_karyawan LEFT JOIN tb_posisi as d ON a.id_posisi = d.id_posisi
            
            LEFT JOIN (
            SELECT c.id_karyawan,  c.status,
            if(c.status = 'M', COUNT(c.status), 0) AS qty_m,
            if(c.status = 'E', COUNT(c.status), 0) AS qty_e,
            if(c.status = 'SP', COUNT(c.status), 0) AS qty_sp
            FROM tb_absen AS c 
            WHERE c.tgl BETWEEN '$dari' AND '$sampai'
            GROUP BY c.id_karyawan, c.status
            ) AS d ON d.id_karyawan = a.id_karyawan
            
            LEFT JOIN (
            SELECT koki, SUM(nilai_koki) as point_gagal FROM view_nilai_masak2 
            WHERE tgl >= '$dari' AND tgl <= '$sampai' AND lama_masak > 30
            GROUP BY koki
            )gagal_masak ON a.id_karyawan = gagal_masak.koki

            LEFT JOIN (
                SELECT koki, SUM(nilai_koki) as point_berhasil FROM view_nilai_masak2 
                WHERE tgl >= '$dari' AND tgl <= '$sampai' AND lama_masak <= 30
                GROUP BY koki
            )berhasil_masak ON a.id_karyawan = berhasil_masak.koki

            LEFT JOIN(
            SELECT nm_karyawan, SUM(lama_cuci) as lama_cuci FROM view_mencuci WHERE tgl >= '$dari' AND tgl <= '$sampai' GROUP BY nm_karyawan
        ) cuci ON a.nama = cuci.nm_karyawan

            GROUP BY a.id_karyawan ORDER BY a.id_karyawan desc"),
        ];
        return view('gaji.excel', $data);
    }
}
