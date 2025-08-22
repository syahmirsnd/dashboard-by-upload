<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        $rows = [];
        foreach ($sheet->getRowIterator() as $row) {
            $cells = [];
            foreach ($row->getCellIterator() as $cell) {
                $cells[] = $cell->getCalculatedValue();
            }
            $rows[] = $cells;
        }

        // row pertama = header
        $headers = $rows[0] ?? [];
        $body = array_slice($rows, 1);

        //sort data berdasarkan kolom 'Total Aktivitas'
        usort($body, function ($a, $b) {
            return ($b[4] ?? 0) <=> ($a[4] ?? 0);
        });

        // ambil data kolom
        $namaAnak = array_column($body, 0);  // kolom A
        $namaWali = array_column($body, 1);  // kolom B
        $jumlahKegiatan = array_column($body, 2);
        $jumlahKunjungan = array_column($body, 3);
        $totalAktivitas = array_column($body, 4);

        $waliAktivitas = [];
        foreach ($body as $row) {
            $wali = $row[1] ?? '—';         // kolom B
            $total = (float) ($row[4] ?? 0); // kolom E

            if (!isset($waliAktivitas[$wali])) {
                $waliAktivitas[$wali] = 0;
            }
            $waliAktivitas[$wali] += $total;
        }

        // Urutkan dari yang tertinggi
        arsort($waliAktivitas);
        $topWaliLabels = array_keys(array_slice($waliAktivitas, 0, 3, true));
        $topWaliData   = array_values(array_slice($waliAktivitas, 0, 3, true));


        return view('dashboard', [
            'fileName' => $request->file('file')->getClientOriginalName(),
            'headers' => $headers,
            'body' => $body,
            'namaAnak' => $namaAnak,
            'namaWali' => $namaWali,
            'jumlahKegiatan' => $jumlahKegiatan,
            'jumlahKunjungan' => $jumlahKunjungan,
            'totalAktivitas' => $totalAktivitas,
            'topWaliLabels' => $topWaliLabels,
            'topWaliData' => $topWaliData,
        ]);
    }
}