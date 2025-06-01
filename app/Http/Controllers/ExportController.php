<?php

namespace App\Http\Controllers;

use App\Exports\DataVerifierExport;
use App\Exports\HeadVerifierExport;
use App\Exports\PrExport;
use App\Exports\SiVerifierExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportHeadVerifier()
    {
        return Excel::download(new HeadVerifierExport, 'head_verifier_data.xlsx');
    }
    public function exportSiVerifier()
    {
        return Excel::download(new SiVerifierExport, 'si_verifier_data.xlsx');
    }
    public function exportSiVerifierWithFilter(Request $request)
    {
        // Ambil parameter, bisa null jika tidak dikirim
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status = $request->query('status');


        return Excel::download(
            new SiVerifierExport($start, $end, $status),
            'Data Filter Sistem Informasi.xlsx'
        );
    }
    public function exportDataVerifier()
    {
        return Excel::download(new DataVerifierExport, 'data_verifier_data.xlsx');
    }
    public function exportDataVerifierWithFilter(Request $request)
    {
        // Ambil parameter, bisa null jika tidak dikirim
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status = $request->query('status');


        return Excel::download(
            new DataVerifierExport($start, $end, $status),
            'Data Filter Divsi Data.xlsx'
        );
    }
    public function exportPrVerifier()
    {
        return Excel::download(new PrExport, 'pr_verifier_data.xlsx');
    }
    public function exportPrVerifierWithFilter(Request $request)
    {
        // Ambil parameter, bisa null jika tidak dikirim
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status = $request->query('status');


        return Excel::download(
            new DataVerifierExport($start, $end, $status),
            'Data Filter Public Relation.xlsx'
        );
    }
}
