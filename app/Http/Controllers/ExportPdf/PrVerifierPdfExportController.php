<?php

namespace App\Http\Controllers\ExportPdf;

use App\Models\PublicRelationRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class PrVerifierPdfExportController extends \App\Http\Controllers\Controller
{
    public function export()
    {
        // Ambil data PublicRelationRequest (tanpa filter dulu)
        $requests = PublicRelationRequest::with('user')->get();

        $data = [
            'requests' => $requests,
        ];

        $pdf = Pdf::loadView('pdf.pr_verifier_report', $data);

        return $pdf->download('pr_verifier_report.pdf');
    }
}
