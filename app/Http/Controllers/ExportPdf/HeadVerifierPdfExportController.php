<?php

namespace App\Http\Controllers\ExportPdf;

use App\Http\Controllers\Controller;
use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class HeadVerifierPdfExportController extends Controller
{
    public function export()
    {
        $informationSystemRequests = InformationSystemRequest::with('user')->where('current_division', 3)->get();
        $prRequests = PublicRelationRequest::with('user')->get();

        $data = [
            'informationSystemRequests' => $informationSystemRequests,
            'prRequests' => $prRequests,
        ];

        $pdf = Pdf::loadView('pdf.head_verifier_report', $data)->setPaper('a4', 'landscape');

        return $pdf->download('List Data Permohonan.pdf');
    }

    public function exportFiltered(Request $request)
    {

        $start = $request->query('start_date');
        $end   = $request->query('end_date');
        $status    = $request->query('status');
        $source    = $request->query('source'); // 'letter' atau 'pr'


        if ($source === 'information_system_request') {
            $query = InformationSystemRequest::with('user');
        } else if ($source === 'public_relation_request') {
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
            if ($source === 'information_system_request') {
                $stateClass = InformationSystemRequest::resolveStatusClassFromString($status);
                $query->whereState('status', $stateClass);
            } else if ($source === 'public_relation_request') {
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


        $pdf = Pdf::loadView('pdf.head_verifier_filtered', $data)->setPaper('a4', 'landscape');


        $fileName = 'List Data Permohonan(Filter).pdf';


        return $pdf->download($fileName);
    }
}
