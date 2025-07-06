 document.addEventListener('livewire:init', () => {
            Alpine.data('dashboard', () => ({
                openDropdown: false,
                openModal: false,
                hasReadSOP: false,
                sopConfirmed: false,

                init() {
                    this.hasReadSOP = sessionStorage.getItem('read_sop') === 'true';
                },

                handleSIDataRequest() {
                    this.hasReadSOP ?
                        Livewire.navigate('{{ route('si-data.form ') }}') :
                        this.openModal = true;
                },

                handlePRRequest() {
                    Livewire.navigate('{{ route('pr.form ') }}');
                },

                confirmReadSOP() {
                    sessionStorage.setItem('read_sop', 'true');
                    this.hasReadSOP = true;
                    Livewire.navigate('{{ route('si-data.form') }}');
                },

                closeModal() {
                    this.openModal = false;
                    this.sopConfirmed = false;
                },
            }));
        });