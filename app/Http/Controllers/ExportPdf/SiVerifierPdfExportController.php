<?php

namespace App\Http\Controllers\ExportPdf;

use App\Models\Letters\Letter;
use Barryvdh\DomPDF\Facade\Pdf;

class SiVerifierPdfExportController extends \App\Http\Controllers\Controller
{
    public function export()
    {

        $letters = Letter::with('user')->where('current_division', 3)->get();


        $data = [
            'letters' => $letters,
        ];


        $pdf = Pdf::loadView('pdf.si_verifier_report', $data);


        return $pdf->download('si_verifier_report.pdf');
    }
}
