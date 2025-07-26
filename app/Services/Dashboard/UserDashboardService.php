<?php

namespace App\Services\Dashboard;

use App\Models\InformationSystemRequest;
use App\Models\PublicRelationRequest;
use App\Services\MeetingServices;
use DB;

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
        return InformationSystemRequest::select(
            'id',
            'title as label',
            DB::raw("'Sistem Informasi dan Data' as type"),
            'created_at'
        )
            ->where('user_id', auth()->id())
            ->union(
                PublicRelationRequest::select(
                    'id',
                    'theme as label',
                    DB::raw("'Kehumasan' as type"),
                    'created_at'
                )
                    ->where('user_id', auth()->id())
                    ->getQuery()
            )
            ->orderBy('created_at', 'desc')
            ->get();
    }
}