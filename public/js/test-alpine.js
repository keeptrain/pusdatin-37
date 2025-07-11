document.addEventListener('livewire:init', () => {
    Alpine.data('prRequestsTable', () => ({
        selectedDataId: [],
        component: null,

        init() {
            // console.log(typeof Alpine !== undefined);
            this.$nextTick(() => {
                this.component = Livewire.all();
                this.selectedDataId = [];
            });
        },

        test() {
            this.component[1].$wire.set('selectedDataId', this.selectedDataId, false);
        }
    }))

});