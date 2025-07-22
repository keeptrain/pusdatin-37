<div>
    @unlessrole('user')
    <div class="flex items-center space-x-2">
        <flux:heading size="xl" class="">Forum Diskusi</flux:heading>
    </div>
    <flux:heading size="lg" level="2" class="mb-6">{{ __('Daftar diskusi dari para pemohon.') }}</flux:heading>
    @endunlessrole

    @role('user')
    <!-- Discussion Form -->
    <x-user.dashboard.discussion-form :requests="$requests" :form="$form" :perPage="3" />
    @endrole

    <div class="space-y-4 mt-6">
        <x-discussions.filter-area :sort="$sort" />

        @if($discussions->isEmpty())
            <!-- Empty State -->
            <div class="px-8 py-8 text-center" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95">

                <div class="max-w-md mx-auto">
                    @if($hasActiveFilters)
                        <x-discussions.empty-filter :search="$search" />
                    @else
                        <x-discussions.empty-discussion />
                    @endif
                </div>
            </div>
        @else
            <!-- Discussions List -->
            @foreach ($discussions as $discussion)
                <x-discussions.list :discussion="$discussion" />
            @endforeach
        @endif

        {{-- Pagination --}}
        @if($discussions->hasPages())
            <div class="mt-4">
                {{ $discussions->links(data: ['scrollTo' => false]) }}
            </div>
        @endif
    </div>
</div>