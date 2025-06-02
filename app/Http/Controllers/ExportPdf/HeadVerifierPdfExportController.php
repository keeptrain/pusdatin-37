<?php

namespace App\Http\Controllers\ExportPdf;

use App\Http\Controllers\Controller;
use App\Models\Letters\Letter;
use App\Models\PublicRelationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HeadVerifierPdfExportController extends Controller
{
    public function export()
    {
        $letters = Letter::with('user')->get();
        $prRequests = PublicRelationRequest::with('user')->get();

        $data = [
            'letters' => $letters,
            'prRequests' => $prRequests,
        ];

        $pdf = Pdf::loadView('pdf.head_verifier_report', $data);

        return $pdf->download('head_verifier_report.pdf');
    }
}
