<?php

namespace App\Services\Dashboard;

use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use App\Services\MeetingServices;

class UserDashboardService
{
    public function __construct(public MeetingServices $meetingService)
    {
    }

    public function render()
    {
        $user = auth()->user();

        $meetingList = $this->meetingService->getUserMeetings($user);
        $todayMeetingCount = $this->meetingService->getTodayMeetingsCount($meetingList);
        $requests = $this->getListRequests();

        return view('dashboard-user', compact(
            'meetingList',
            'todayMeetingCount',
            'requests'
        ));
    }

    public function getListRequests()
    {
        // Get data from InformationSystemRequest
        $systemRequests = InformationSystemRequest::select('id', 'title as label', 'created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'Sistem Informasi dan Data',
                    'label' => $item->label,
                    'created_at' => $item->created_at,
                ];
            });

        // Get data from PublicRelationRequest
        $prRequests = PublicRelationRequest::select('id', 'theme as label', 'created_at')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'Kehumasan',
                    'label' => $item->label,
                    'created_at' => $item->created_at,
                ];
            });

        // Combine the collections
        $combinedRequests = $systemRequests->concat($prRequests);

        // Sort by created_at (optional)
        $combinedRequests = $combinedRequests->sortByDesc('created_at')->values();

        return $combinedRequests;
    }
}