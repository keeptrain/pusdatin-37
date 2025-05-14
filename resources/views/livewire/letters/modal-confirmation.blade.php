<section>

    <x-modal.disposition :letterId="$letterId"/>

    <x-modal.verification :letterId="$letterId" :status="$status"/>

    <x-modal.approved :letterId="$letterId" :status="$status"/>

</section>