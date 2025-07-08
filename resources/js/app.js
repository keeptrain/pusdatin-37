import header from "./components/header.js";

document.addEventListener('alpine:init', () => {
    Alpine.store('notifications', {
        hasUnread: false,
        init() {
            Livewire.on('notification-count-updated', (e) => {
                this.hasUnread = e.hasUnread;
            });
        }
    });

    if (document.querySelector('[x-data="header"]')) {
        Alpine.data('header', header);
    }
});