<section x-data="{ 
    openModal: false,
    hasReadSOP: false,
    sopConfirmed: false,
    
    init() {
        // Cek apakah user sudah pernah membaca SOP
        this.hasReadSOP = sessionStorage.getItem('read_sop') === 'true';
    },
    
    handleSIDataRequest() {
        if (this.hasReadSOP) {
           
            window.location.href = '{{ route('si-data.form') }}';
        } else {
           
            this.openModal = true;
        }
    },
    
    confirmReadSOP() {
        // Simpan ke sessionStorage
        sessionStorage.setItem('read_sop', 'true');
        this.hasReadSOP = true;
        
        // Redirect ke form
        window.location.href = '{{ route('si-data.form') }}';
    },
    
    closeModal() {
        this.openModal = false;
        this.sopConfirmed = false;
    }
}" x-cloak>

    <x-user.dashboard.service-cards />

    <x-user.dashboard.warning-modal-sop />

</section>