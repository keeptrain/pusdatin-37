document.addEventListener('alpine:init', () => {
    Alpine.store('notifications', {
        hasUnread: false,
        init() {
            Livewire.on('notification-count-updated', (e) => {
                this.hasUnread = e.hasUnread;
            });
        }
    });
});