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
        
        $headers = $rows[0] ?? [];
        $body = array_slice($rows, 1);

        // filter baris kosong
        $body = array_values(array_filter($body, function($row) {
            return isset($row[0]) && trim($row[0]) !== '';
        }));

        //sort data berdasarkan kolom 'Total Aktivitas'
        usort($body, function ($a, $b) {
            return ($b[13] ?? 0) <=> ($a[13] ?? 0);
        });

        // ambil data kolom
        $md_code = array_column($body, 0);
        $md_name = array_column($body, 1);
        $npsn = array_column($body, 2);
        $name = array_column($body, 3);
        $total_activity_update_data = array_column($body, 4);
        $total_activity_download_document = array_column($body, 5);
        $total_activity_verify_data = array_column($body, 6);
        $total_activity_view_vacancy = array_column($body, 7);
        $total_activity_apply_vacancy = array_column($body, 8);
        $total_activity_view_article = array_column($body, 9);
        $total_activity_create_ki = array_column($body, 10);
        $total_activity_update_ki = array_column($body, 11);
        $total_activity_view_ki = array_column($body, 12);
        $total_activity = array_column($body, 13);

        $mdActivity = [];
        foreach ($body as $row) {
            $md = $row[1] ?? '—';  
            $total = (float) ($row[13] ?? 0);

            if (!isset($mdActivity[$md])) {
                $mdActivity[$md] = 0;
            }
            $mdActivity[$md] += $total;
        }


        // Urutkan dari yang tertinggi
        arsort($mdActivity);
        $topmdLabels = array_keys(array_slice($mdActivity, 0, 3, true));
        $topmdData   = array_values(array_slice($mdActivity, 0, 3, true));

        // Hitung jumlah sekolah per MD
        $schoolPerMd = [];
        foreach ($body as $row) {
            $md = $row[1] ?? '—'; 
            if (!isset($schoolPerMd[$md])) {
                $schoolPerMd[$md] = 0;
            }
            $schoolPerMd[$md]++;
        }

        $mdLabels = array_keys($schoolPerMd);
        $mdSchoolCount = array_values($schoolPerMd);

        return view('dashboard', [
            'fileName' => $request->file('file')->getClientOriginalName(),
            'headers' => $headers,
            'body' => $body,
            'md_code' => $md_code,
            'md_name' => $md_name,
            'npsn' => $npsn,
            'name' => $name,
            'total_activity_update_data' => $total_activity_update_data,
            'total_activity_download_document' => $total_activity_download_document,
            'total_activity_verify_data' => $total_activity_verify_data,
            'total_activity_view_vacancy' => $total_activity_view_vacancy,
            'total_activity_apply_vacancy' => $total_activity_apply_vacancy,
            'total_activity_view_article' => $total_activity_view_article,
            'total_activity_create_ki' => $total_activity_create_ki,
            'total_activity_update_ki' => $total_activity_update_ki,
            'total_activity_view_ki' => $total_activity_view_ki,
            'total_activity' => $total_activity,
            'topmdLabels' => $topmdLabels,
            'topmdData'   => $topmdData,
            'mdLabels' => $mdLabels,
            'mdSchoolCount' => $mdSchoolCount,  
        ]);
    }
}