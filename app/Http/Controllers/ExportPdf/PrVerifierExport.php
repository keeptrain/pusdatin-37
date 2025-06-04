<?php

namespace App\Http\Controllers\ExportPdf;

use App\Models\PublicRelationRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class PrVerifierPdfExportController extends \App\Http\Controllers\Controller
{
    public function export()
    {

        $pr = PublicRelationRequest::with('user')->where('current_division', 4)->get();


        $data = [
            'letters' => $pr,
        ];


        $pdf = Pdf::loadView('pdf.pr_verifier_report', $data);


        return $pdf->download('pr_verifier_report.pdf');
    }
}
