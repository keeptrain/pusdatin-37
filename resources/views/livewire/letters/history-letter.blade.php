<div>
    <x-user.header-history />
    <div class=" py-6">
        @foreach($tracks as $track)
        <x-user.card-history
            :requestId="$track->id"
            :title="$track->letter->title" />
        @endforeach

    </div>

</div>