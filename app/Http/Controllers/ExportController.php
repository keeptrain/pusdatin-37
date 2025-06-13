<?php

namespace App\Http\Controllers;

use App\Exports\DataVerifierExport;
use App\Exports\HeadVerifierExport;
use App\Exports\LetterExport;
use App\Exports\PrExport;
use App\Exports\SiVerifierExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportHeadVerifier()
    {
        return Excel::download(new HeadVerifierExport, 'List Data Permohonan.xlsx');
    }
    public function exportHeadVerifierFilteredExcel(Request $request)
    {
        $start  = $request->query('start_date');
        $end    = $request->query('end_date');
        $status = $request->query('status');
        $source = $request->query('source'); // 'letter' atau 'pr'

        if ($source === 'letter') {
            $export = new LetterExport($start, $end, $status);
            $file   = 'List Data Permohonan Sistem Informasi dan Permintaan Data.xlsx';
        } elseif ($source === 'pr') {
            $export = new PrExport($start, $end, $status);
            $file   = 'List Data Kehumasan.xlsx';
        } else {
            abort(404);
        }

        return Excel::download($export, $file);
    }
    public function exportSiVerifier()
    {
        return Excel::download(new SiVerifierExport, 'List Data Permohonan Sistem Informasi.xlsx');
    }
    public function exportSiVerifierWithFilter(Request $request)
    {
        // Ambil parameter, bisa null jika tidak dikirim
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status = $request->query('status');


        return Excel::download(
            new SiVerifierExport($start, $end, $status),
            'List Data Permohonan Sistem Informasi(Filtered).xlsx'
        );
    }
    public function exportDataVerifier()
    {
        return Excel::download(new DataVerifierExport, 'List Data Permohonan Data.xlsx');
    }
    public function exportDataVerifierWithFilter(Request $request)
    {
        // Ambil parameter, bisa null jika tidak dikirim
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status = $request->query('status');


        return Excel::download(
            new DataVerifierExport($start, $end, $status),
            'List Data Permohonan Data(Filtered).xlsx'
        );
    }
    public function exportPrVerifier()
    {
        return Excel::download(new PrExport, 'List Data Kehumasan.xlsx');
    }
    public function exportPrVerifierWithFilter(Request $request)
    {
        // Ambil parameter, bisa null jika tidak dikirim
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status = $request->query('status');


        return Excel::download(
            new PrExport($start, $end, $status),
            'List Data Kehumasan(Filter).xlsx'
        );
    }
}
