<div class="flex flex-col items-start">
    @include('partials.letter-heading')

    @if (session('status'))
        @php
            $variant = session('status')['variant'];
            $message = session('status')['message'];
        @endphp
        <flux:notification.toast :variant="$variant" :message="$message" duration="3000" />
    @endif
    <div class="flex justify-center items-center ">
        <div class="flex flex-col gap-6">
            <flux:menu.horizontal icon="upload" heading="Upload" description="Upload files according to the terms."
                :href="route('letter.upload')" />

            <flux:menu.horizontal icon="plus" heading="Create" description="Create letter directly."
                :href="route('letter.form')" />
        </div>
    </div>

</div>
