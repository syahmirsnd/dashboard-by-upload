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

        // Load Excel
        $spreadsheet = IOFactory::load($request->file('file')->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        // Ambil semua data
        $rows = $sheet->toArray(null, true, true, true);

        // Buang header (row pertama)
        $body = array_slice($rows, 1);

        // Kolom A = nama_anak, kolom E = total_aktivitas
        $labels = array_column($body, 'A');  
        $data   = array_column($body, 'E');  

        // Kirim ke view
        return view('dashboard', compact('rows', 'labels', 'data'));
    }
}

