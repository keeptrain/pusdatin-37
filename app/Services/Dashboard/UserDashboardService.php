<?php

namespace App\Services\Dashboard;

use App\Services\MeetingServices;
use App\Services\ZipServices;
use Illuminate\Support\Collection;

class UserDashboardService
{
    public function __construct(public MeetingServices $meetingService)
    {
    }

    public function render()
    {
        $user = auth()->user();
        $meetings = $this->meetingService->getUpcomingMeetingsForUser($user);
        $emptySlots = $this->meetingService->getEmptyDateSlots();
        $meetingList = $this->mergeMeetingsWithEmptySlots($meetings, $emptySlots);
        $todayMeetingCount = $this->calculateTodayMeetings($meetings);

        return view('dashboard-user', compact(
            'meetingList',
            'todayMeetingCount'
        ));
    }

    protected function mergeMeetingsWithEmptySlots(Collection $meetings, Collection $slots): Collection
    {
        return $slots->map(function ($slot) use ($meetings) {
            $meetingDate = $slot['date_day'];
            return $meetings->firstWhere('date_day', $meetingDate) ?? $slot;
        });
    }

    public function getMeetingList()
    {
        return $this->meetingService->getUpcomingMeetingsForUser(auth()->user());
    }

    private function calculateTodayMeetings($meetings): int
    {
        return $meetings->where('is_today', true)
            ->sum(fn($dateGroup) => count($dateGroup['meetings']));
    }

    public function downloadSopAndTemplates()
    {
        try {
            return new ZipServices()->downloadSopAndTemplates();
        } catch (\Exception $e) {
            abort(500, 'Error: ' . $e->getMessage());
        }
    }
}