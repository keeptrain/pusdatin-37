<section x-data="{ 
    openModal: false,
    hasReadSOP: false,
    sopConfirmed: false,
    sopPdfUrl: '{{ asset('pdf/sop-sistem-informasi-data.pdf') }}',
    
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
    
    downloadSOP() {
        // Download PDF dari folder public
        const link = document.createElement('a');
        link.href = this.sopPdfUrl;  
        link.download = 'sop-sistem-informasi.pdf';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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