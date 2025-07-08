export default () => ({
    openDropdown: false,
    openModal: false,
    hasReadSOP: false,
    sopConfirmed: false,

    init() {
        this.hasReadSOP = sessionStorage.getItem('read_sop') === 'true';
    },

    handleSIDataRequest() {
        this.hasReadSOP ?
            Livewire.navigate(this.route('si-data.form')) :
            this.openModal = true;
    },

    handlePRRequest() {
        Livewire.navigate(this.route('pr.form'));
    },

    confirmReadSOP() {
        sessionStorage.setItem('read_sop', 'true');
        this.hasReadSOP = true;
        Livewire.navigate(this.route('si-data.form'));
    },

    closeModal() {
        this.openModal = false;
        this.sopConfirmed = false;
    },

    // Helper function to resolve routes dynamically
    route(name) {
        return window.routes[name] || '#';
    }
});