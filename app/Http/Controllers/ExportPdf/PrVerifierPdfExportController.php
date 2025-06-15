<?php

namespace App\Http\Controllers\ExportPdf;

use App\Http\Controllers\Controller;
use App\Models\PublicRelationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrVerifierPdfExportController extends Controller
{
    public function export()
    {
        // Ambil data PublicRelationRequest (tanpa filter dulu)
        $requests = PublicRelationRequest::with('user')->get();

        $data = [
            'requests' => $requests,
        ];

        $pdf = Pdf::loadView('pdf.pr_verifier_report', $data)->setPaper('a4', 'landscape');

        return $pdf->download('Data Permohonan Kehumasan.pdf');
    }
    public function exportFiltered(Request $request)
    {
        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status    = $request->query('status');

        $query = PublicRelationRequest::with('user');

        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }
        if ($status && $status !== 'all') {
            // Pastikan ada method resolveStatusClassFromString di model
            $stateClass = PublicRelationRequest::resolveStatusClassFromString($status);
            $query->whereState('status', $stateClass);
        }

        $requests = $query->get();

        $data = [
            'requests' => $requests,
            'start' => $start,
            'end' => $end,
            'status' => $status,
        ];

        $pdf = Pdf::loadView('pdf.pr_verifier_filtered', $data)->setPaper('a4', 'landscape');

        $fileName = 'Data Permohonan Kehumasan (Filter).pdf';

        return $pdf->download($fileName);
    }
}
