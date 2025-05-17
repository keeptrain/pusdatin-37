<div>
    <div class="bg-white border-b border-gray-200 px-4 py-6">
        <div class="max-w-screen-xl mx-auto">
            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Detail History Permohonan</h2>
                    <p class="mt-1 text-sm text-gray-500">View and manage all your application requests in one place</p>
                </div>
            </div>
        </div>
    </div>
    <div class=" py-6">
        <x-user.card-basic-info
            :request-id="$track->letter->request_id"
            :created-at="$track->created_at->format('M d, Y')"
            :status="$track->letter->status"
            :title="$track->letter->title"
            :person="$track->letter->responsible_person" />
    </div>
    <x-user.tracking-progres />




    <liviwire:data.edit />
</div>