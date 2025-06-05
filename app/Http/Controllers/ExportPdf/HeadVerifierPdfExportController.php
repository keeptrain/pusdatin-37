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
    public function exportFiltered(Request $request)
    {

        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status    = $request->query('status');
        $source    = $request->query('source'); // 'letter' atau 'pr'


        if ($source === 'letter') {
            $query = Letter::with('user');
        } else if ($source === 'pr') {
            $query = PublicRelationRequest::with('user');
        } else {
            return abort(404);
        }


        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }

        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }

        // Filter berdasarkan status (jika ada)
        if ($status && $status !== 'all') {
            if ($source === 'letter') {
                $stateClass = Letter::resolveStatusClassFromString($status);
                $query->whereState('status', $stateClass);
            } else if ($source === 'pr') {
                $stateClassPr = PublicRelationRequest::resolveStatusClassFromString($status);
                $query->whereState('status', $stateClassPr);
            }
        }


        $data = $query->get();


        $data = [
            'data' => $data,
            'start_date' => $start,
            'end_date' => $end,
            'status' => $status,
        ];


        $pdf = Pdf::loadView('pdf.head_verifier_filtered', $data);


        $fileName = 'head_verifier_report_filtered.pdf';


        return $pdf->download($fileName);
    }
}
