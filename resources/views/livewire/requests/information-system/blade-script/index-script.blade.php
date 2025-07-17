<script>
    let dataTable = null;

    // Cache DOM elements and constants
    const DOM_CACHE = {
        globalSearch: null,
        statusFilterToggle: null,
        statusFilterDropdown: null,
        closeStatusFilter: null,
        selectAllStatus: null,
        clearAllStatus: null,
        statusCheckboxContainer: null,
        requestsTable: null
    };

    const DEFAULT_STATUS_OPTIONS = [
        { value: "Permohonan Masuk", label: "Permohonan Masuk" },
        { value: "Didisposisikan", label: "Didisposisikan" },
        { value: "Revisi Kasatpel", label: "Revisi Kasatpel" },
        { value: "Revisi Kapusdatin", label: "Revisi Kapusdatin" },
        { value: "Disetujui Kasatpel", label: "Disetujui Kasatpel" },
        { value: "Disetujui Kapusdatin", label: "Disetujui Kapusdatin" },
        { value: "Proses", label: "Proses Permohonan" },
        { value: "Selesai", label: "Selesai" },
        { value: "Ditolak", label: "Ditolak" },
    ];

    // Cache DOM elements on first access
    function getDOMElement(key, selector) {
        if (!DOM_CACHE[key]) {
            DOM_CACHE[key] = $(selector);
        }
        return DOM_CACHE[key];
    }

    // Debounce function for search
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    Alpine.data('requestsTable', () => ({
        selectedId: [],
        statuses: @js($allowedStatuses),
        selectedStatuses: [],

        init() {
            this.$nextTick(() => {
                dataTable = this.initializeDataTable();
                this.setupGlobalSearch(dataTable);
                this.setupStatusFilter(dataTable);
            });
        },

        initializeDataTable() {
            const table = getDOMElement('requestsTable', '#requestsTable');

            const dataTable = table.DataTable({
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: "info",
                    bottomEnd: "paging",
                },
                responsive: true,
                paging: true,
                searching: true,
                pageLength: 10,
                destroy: true,
                deferRender: true, // Render rows only when needed
                language: {
                    sProcessing: "Sedang memproses...",
                    sLengthMenu: "Tampilkan _MENU_ data per halaman",
                    sZeroRecords: this.getNoRecordsTemplate(),
                    sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    sInfoEmpty: "Tidak ada data tersedia",
                    sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                    sInfoPostFix: "",
                },
                columnDefs: [
                    {
                        targets: 0,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        targets: 1,
                        width: "16%",
                    },
                    {
                        targets: 3,
                        orderable: false,
                        width: "16%",
                    },
                ],
                initComplete: function () {
                    // console.log('DataTable initialized');
                }
            });
            return dataTable;
        },

        sendSelectedId() {
            $wire.set('selectedSystemId', this.selectedId);
        },

        setupGlobalSearch(dataTable) {
            const searchInput = getDOMElement('globalSearch', '#globalSearch');

            // Remove previous event handlers
            searchInput.off("keyup.globalSearch");

            // Debounced search function
            const debouncedSearch = debounce((value) => {
                dataTable.search(value).draw();
            }, 300);

            // Setup new handler with debouncing
            searchInput.on("keyup.globalSearch", function () {
                debouncedSearch(this.value);
            });
        },

        getNoRecordsTemplate(message = "Tidak ada data yang sesuai dengan pencarian Anda") {
            return `
            <div class="text-center py-4">
                <div class="mb-2">
                    <x-lucide-search-x class="w-12 h-12 text-gray-600 mx-auto" />
                </div>
                <p class="text-sm text-gray-600">${message}</p>
            </div>
            `;
        },

        setupStatusFilter(dataTable) {
            this.setupStatusFilterActions();
            this.populateStatusCheckboxes();
            this.bindStatusFilterEvents(dataTable);
        },

        setupStatusFilterActions() {
            // Cache DOM elements
            const filterToggle = getDOMElement('statusFilterToggle', '#statusFilterToggle');
            const filterDropdown = getDOMElement('statusFilterDropdown', '#statusFilterDropdown');
            const closeFilter = getDOMElement('closeStatusFilter', '#closeStatusFilter');
            const selectAll = getDOMElement('selectAllStatus', '#selectAllStatus');
            const clearAll = getDOMElement('clearAllStatus', '#clearAllStatus');

            // Remove existing handlers to prevent duplicates
            filterToggle.off('click.statusFilter');
            closeFilter.off('click.statusFilter');
            selectAll.off('click.statusFilter');
            clearAll.off('click.statusFilter');

            // Bind events with namespacing
            filterToggle.on('click.statusFilter', () => {
                filterDropdown.toggleClass('hidden');
            });

            closeFilter.on('click.statusFilter', () => {
                filterDropdown.addClass('hidden');
            });

            selectAll.on('click.statusFilter', () => {
                this.selectAllStatuses();
            });

            clearAll.on('click.statusFilter', () => {
                this.clearAllStatuses();
            });
        },

        populateStatusCheckboxes() {
            const container = getDOMElement('statusCheckboxContainer', '#statusCheckboxContainer');

            // Build HTML in memory first, then append once
            const checkboxHTML = DEFAULT_STATUS_OPTIONS.map((opt, idx) => {
                const isChecked = this.statuses.includes(opt.value);
                return `
                    <div class="status-checkbox-item">
                        <input type="checkbox" id="status_${idx}" class="status-checkbox" value="${opt.value}" ${isChecked ? "checked" : ""}>
                        <label for="status_${idx}">${opt.label}</label>
                    </div>
                `;
            }).join('');

            container.html(checkboxHTML);
        },

        bindStatusFilterEvents(dataTable) {
            const container = getDOMElement('statusCheckboxContainer', '#statusCheckboxContainer');

            // Remove existing handlers
            container.off('change.statusFilter');

            // Use event delegation for better performance
            container.on('change.statusFilter', 'input[type="checkbox"]', () => {
                this.updateSelectedStatuses();
                this.applyStatusFilter(dataTable);
            });

            // Initialize selected statuses
            this.selectedStatuses = [...this.statuses];
            this.applyStatusFilter(dataTable);
        },

        updateSelectedStatuses() {
            const container = getDOMElement('statusCheckboxContainer', '#statusCheckboxContainer');
            this.selectedStatuses = container.find('input[type="checkbox"]:checked')
                .map((_, el) => el.value)
                .get();
        },

        applyStatusFilter(dataTable) {
            const searchPattern = this.selectedStatuses.length > 0
                ? this.selectedStatuses.join('|')
                : '';
            dataTable.column(3).search(searchPattern, true, false).draw();
        },

        selectAllStatuses() {
            const container = getDOMElement('statusCheckboxContainer', '#statusCheckboxContainer');
            const checkboxes = container.find('input[type="checkbox"]');

            checkboxes.prop('checked', true);
            this.updateSelectedStatuses();
            this.applyStatusFilter(dataTable);
        },

        clearAllStatuses() {
            const container = getDOMElement('statusCheckboxContainer', '#statusCheckboxContainer');
            const checkboxes = container.find('input[type="checkbox"]');

            checkboxes.prop('checked', false);
            this.updateSelectedStatuses();
            this.applyStatusFilter(dataTable);
        }
    }))



</script>