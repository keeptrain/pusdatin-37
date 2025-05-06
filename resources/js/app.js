document.addEventListener('alpine:init', () => {
    Alpine.store('notifications', {
      count: 0,
      init() {
        Livewire.on('notification-count-updated', (e) => {
          this.count = e[0]?.count || 0;
        });
      }
    });
  }
);