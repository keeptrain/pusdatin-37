<?php

namespace App\Http\Controllers\ExportPdf;

use App\Http\Controllers\Controller;
use App\Models\InformationSystemRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SiVerifierPdfExportController extends Controller
{

    public function export()
    {

        $informationSystemRequests = InformationSystemRequest::with('user')->where('current_division', 3)->get();

        $data = [
            'informationSystemRequests' => $informationSystemRequests,
        ];

        $pdf = Pdf::loadView('pdf.si_verifier_report', $data);

        return $pdf->download('Data Permohonan Sistem Informasi.pdf');
    }
    public function exportFiltered(Request $request)
    {
        $start  = $request->query('start_date');
        $end    = $request->query('end_date');
        $status = $request->query('status');

        $query = InformationSystemRequest::with('user')
            ->where('current_division', 3);

        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }
        if ($status && $status !== 'all') {
            $stateClass = InformationSystemRequest::resolveStatusClassFromString($status);
            $query->whereState('status', $stateClass);
        }

        $informationSystemRequests = $query->get();

        $data = compact('informationSystemRequests', 'start', 'end', 'status');

        $pdf = Pdf::loadView('pdf.si_verifier_filtered', $data);

        $fileName = 'Data Permohonan Sistem Informasi Filter.pdf';
        return $pdf->download($fileName);
    }
}
