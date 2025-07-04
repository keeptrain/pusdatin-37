<?php

namespace App\Http\Controllers\ExportPdf;

use App\Http\Controllers\Controller;
use App\Models\Letters\Letter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DataVerifierPdfExportController extends Controller
{
    public function export()
    {

        $letters = Letter::with('user')->where('current_division', 4)->get();

        $data = [
            'letters' => $letters,
        ];

        $pdf = Pdf::loadView('pdf.data_verifier_report', $data);

        return $pdf->download('data_verifier_report.pdf');
    }
    public function exportFiltered(Request $request)
    {
        $start  = $request->query('start_date');
        $end    = $request->query('end_date');
        $status = $request->query('status');

        $query = Letter::with('user')
            ->where('current_division', 4);

        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }
        if ($status && $status !== 'all') {
            $stateClass = Letter::resolveStatusClassFromString($status);
            $query->whereState('status', $stateClass);
        }

        $letters = $query->get();

        $data = compact('letters', 'start', 'end', 'status');

        $pdf = Pdf::loadView('pdf.data_verifier_filtered', $data);

        $fileName = 'data_verifier_report_filtered.pdf';
        return $pdf->download($fileName);
    }
}
