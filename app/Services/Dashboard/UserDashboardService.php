<?php

namespace App\Services\Dashboard;

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

        return view('dashboard-user', compact(
            'meetingList',
            'todayMeetingCount'
        ));
    }
}