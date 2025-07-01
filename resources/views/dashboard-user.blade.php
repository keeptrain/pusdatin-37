<x-layouts.app :title="__('Dashboard')">
    <!-- Welcome Section -->

    <x-user.main-section />
    <x-user.hero-dashboard />

    <x-user.meeting-list :meetingList="$meetingList" :todayMeetingCount="$todayMeetingCount" />


    <x-user.dashboard.notifications-list />

    <x-user.faq />


    </div>

</x-layouts.app>