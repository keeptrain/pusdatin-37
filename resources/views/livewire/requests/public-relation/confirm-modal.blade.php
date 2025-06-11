<section>
    <x-modal.queue-promkes />

    <x-modal.curation :allowedDocument="$this->getAllowedDocument"/>

    <x-modal.queue-pusdatin />

    <x-modal.process />

    <x-modal.completed :allowedDocument="$this->getAllowedDocument"/>

</section>