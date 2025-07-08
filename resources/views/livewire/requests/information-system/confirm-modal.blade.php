<section>
    <x-modal.disposition :systemRequestId="$systemRequestId" />

    <x-modal.verification :systemRequestId="$systemRequestId" :allowedParts="$allowedParts" />

    <x-modal.approved :systemRequestId="$systemRequestId" :allowedParts="$allowedParts" />

    <x-modal.information-system.completed />
</section>