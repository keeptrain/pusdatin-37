/**
 * DataTable Manager for Public Relation Requests
 */
if (typeof window.PRRequestsTableManager === 'undefined') {
    window.PRRequestsTableManager = class PRRequestsTableManager {
    constructor(options = {}) {
        // Set default selectors first
        this.tableSelector = options.tableSelector || '#prRequestsTable';
        this.globalSearchSelector = options.globalSearchSelector || '#globalSearch';
        this.statusFilterToggleSelector = options.statusFilterToggleSelector || '#statusFilterToggle';
        this.statusFilterDropdownSelector = options.statusFilterDropdownSelector || '#statusFilterDropdown';
        this.statusCheckboxContainerSelector = options.statusCheckboxContainerSelector || '#statusCheckboxContainer';
        this.statusBadgeTextSelector = options.statusBadgeTextSelector || '#statusBadgeText';
        
        // Initialize other properties
        this.dataTable = null;
        this.eventHandlers = new Map();
        this.config = this.getDefaultConfig();
        this.defaultStatuses = this.getDefaultStatuses();
        
        // Log initialization
        console.log('PRRequestsTableManager created with selector:', this.tableSelector);
        
        // Initialize
        this.init();
    }

    /**
     * Get default DataTable configuration
     */
    getDefaultConfig() {
        return {
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
            language: {
                sProcessing: "Sedang memproses...",
                sLengthMenu: "Tampilkan _MENU_ data per halaman",
                sZeroRecords: "Tidak ditemukan data yang sesuai",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "",
                sInfoFiltered: "(disaring dari _MAX_ entri keseluruhan)",
                sInfoPostFix: "",
                sSearch: "Cari:",
            },
            columnDefs: [
                {
                    targets: 0, // Checkbox column
                    orderable: false,
                    searchable: false,
                },
                {
                    targets: 2, // Theme column
                    width: "20%",
                },
                {
                    targets: 4, // Status column
                    orderable: false,
                },
            ],
            initComplete: () => {
                console.log('DataTable initialized successfully');
            }
        };
    }

    /**
     * Get default status options
     */
    getDefaultStatuses() {
        return [
            { value: "Usulan Masuk", label: "Usulan Masuk" },
            { value: "Antrean Promkes", label: "Antrean Promkes" },
            { value: "Kurasi Promkes", label: "Kurasi Promkes" },
            { value: "Antrean Pusdatin", label: "Antrean Pusdatin" },
            { value: "Proses Pusdatin", label: "Proses Pusdatin" },
            { value: "Selesai", label: "Selesai" },
        ];
    }

    /**
     * Initialize the DataTable and all related functionality
     */
    init() {
        // Check if we're on the right page before initializing
        if (!this.isOnCorrectPage()) {
            console.log('Not on PR requests page, skipping DataTable initialization');
            return;
        }
        
        // Add a small delay to ensure DOM is ready
        setTimeout(() => {
            this.waitForDependencies(() => {
                this.initializeDataTable();
                this.setupLivewireEventHandlers();
            });
        }, 50);
    }

    /**
     * Check if we're on the correct page that should have the table
     */
    isOnCorrectPage() {
        const selector = this.tableSelector || '#prRequestsTable';
        return $(selector).length > 0;
    }

    /**
     * Wait for jQuery and DataTable dependencies to be loaded
     */
    waitForDependencies(callback, attempts = 0) {
        const maxAttempts = 50; // 5 seconds maximum wait
        
        if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
            if (attempts < maxAttempts) {
                setTimeout(() => this.waitForDependencies(callback, attempts + 1), 100);
            } else {
                console.error('DataTable dependencies not loaded after maximum attempts');
            }
            return;
        }
        
        callback();
    }

    /**
     * Initialize DataTable with proper cleanup
     */
    initializeDataTable() {
        // Ensure tableSelector is set
        const selector = this.tableSelector || '#prRequestsTable';
        
        // Double-check if table element exists
        if ($(selector).length === 0) {
            console.log('Table element not found on current page, skipping initialization');
            return false;
        }

        this.destroyExistingTable();
        
        try {
            console.log('Initializing DataTable on element:', selector);
            this.dataTable = $(selector).DataTable(this.config);
            this.setupTableFeatures();
            console.log('DataTable initialized successfully');
            return true;
        } catch (error) {
            console.error('Error initializing DataTable:', error);
            return false;
        }
    }

    /**
     * Destroy existing DataTable instance if it exists
     */
    destroyExistingTable() {
        const selector = this.tableSelector || '#prRequestsTable';
        if ($.fn.dataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy(true);
            console.log('Existing DataTable destroyed');
        }
    }

    /**
     * Setup all table-related features
     */
    setupTableFeatures() {
        this.setupGlobalSearch();
        this.setupStatusFilter();
    }

    /**
     * Setup global search functionality
     */
    setupGlobalSearch() {
        const selector = this.globalSearchSelector || '#globalSearch';
        this.removeEventHandler('globalSearch');
        
        const handler = (event) => {
            if (this.dataTable) {
                this.dataTable.search(event.target.value).draw();
            }
        };

        $(selector).on('keyup.globalSearch', handler);
        this.eventHandlers.set('globalSearch', {
            element: selector,
            event: 'keyup.globalSearch',
            handler: handler
        });
    }

    /**
     * Setup status filter functionality
     */
    setupStatusFilter() {
        this.setupStatusFilterActions();
        this.populateStatusCheckboxes();
        this.bindStatusFilterEvents();
        this.bindStatusBasedRole();
    }

    /**
     * Setup status filter dropdown actions
     */
    setupStatusFilterActions() {
        const actions = [
            {
                selector: this.statusFilterToggleSelector || '#statusFilterToggle',
                event: 'click',
                handler: () => {
                    $(this.statusFilterDropdownSelector || '#statusFilterDropdown').toggleClass('hidden');
                }
            },
            {
                selector: '#closeStatusFilter',
                event: 'click',
                handler: () => {
                    $(this.statusFilterDropdownSelector || '#statusFilterDropdown').addClass('hidden');
                }
            },
            {
                selector: '#selectAllStatus',
                event: 'click',
                handler: () => {
                    $(`${this.statusCheckboxContainerSelector || '#statusCheckboxContainer'} input[type='checkbox']`).prop('checked', true).trigger('change');
                }
            },
            {
                selector: '#clearAllStatus',
                event: 'click',
                handler: () => {
                    $(`${this.statusCheckboxContainerSelector || '#statusCheckboxContainer'} input[type='checkbox']`).prop('checked', false).trigger('change');
                }
            }
        ];

        actions.forEach(action => {
            this.removeEventHandler(action.selector);
            $(action.selector).on(action.event, action.handler);
            this.eventHandlers.set(action.selector, action);
        });
    }

    /**
     * Populate status checkboxes
     */
    populateStatusCheckboxes() {
        const container = $(this.statusCheckboxContainerSelector);
        container.empty();

        this.defaultStatuses.forEach((status, index) => {
            const checkboxHtml = this.createStatusCheckboxHtml(status, index);
            container.append(checkboxHtml);
        });
    }

    /**
     * Create HTML for status checkbox
     */
    createStatusCheckboxHtml(status, index) {
        return `
            <div class="status-checkbox-item">
                <input type="checkbox" id="status_${index}" class="status-checkbox" value="${status.value}">
                <label for="status_${index}">${status.label}</label>
            </div>
        `;
    }

    /**
     * Bind status filter events
     */
    bindStatusFilterEvents() {
        const handler = () => {
            const selectedStatuses = this.getSelectedStatuses();
            const searchPattern = selectedStatuses.join('|');
            
            if (this.dataTable) {
                this.dataTable.column(4).search(searchPattern, true, false).draw();
            }
        };

        this.removeEventHandler('statusFilter');
        $(`${this.statusCheckboxContainerSelector} input[type='checkbox']`).on('change', handler);
        this.eventHandlers.set('statusFilter', {
            element: `${this.statusCheckboxContainerSelector} input[type='checkbox']`,
            event: 'change',
            handler: handler
        });
    }

    /**
     * Get selected status values
     */
    getSelectedStatuses() {
        return $(`${this.statusCheckboxContainerSelector} input[type='checkbox']:checked`)
            .map(function() { return $(this).val(); })
            .get();
    }

    /**
     * Bind status filter based on user role
     */
    bindStatusBasedRole() {
        try {
            const allowedStatuses = this.getAllowedStatusesFromLivewire();
            
            if (allowedStatuses && allowedStatuses.length > 0) {
                this.setStatusCheckboxes(allowedStatuses);
                this.updateStatusBadge(allowedStatuses.length);
                
                if (this.dataTable) {
                    this.dataTable.draw();
                }
            }
        } catch (error) {
            console.warn('Could not get allowed statuses from Livewire:', error);
        }
    }

    /**
     * Get allowed statuses from Livewire component
     */
    getAllowedStatusesFromLivewire() {
        if (typeof Livewire !== 'undefined' && Livewire.all) {
            const components = Livewire.all();
            if (components && components[1] && components[1].$wire) {
                return components[1].$wire.get('allowedStatuses');
            }
        }
        return null;
    }

    /**
     * Set status checkboxes based on allowed statuses
     */
    setStatusCheckboxes(allowedStatuses) {
        // First uncheck all
        $(`${this.statusCheckboxContainerSelector} input[type='checkbox']`).prop('checked', false);
        
        // Then check only allowed statuses
        allowedStatuses.forEach(status => {
            $(`${this.statusCheckboxContainerSelector} input[type='checkbox'][value='${status}']`)
                .prop('checked', true)
                .trigger('change');
        });
    }

    /**
     * Update status badge text
     */
    updateStatusBadge(count) {
        $(this.statusBadgeTextSelector).text(count);
    }

    /**
     * Setup Livewire event handlers
     */
    setupLivewireEventHandlers() {
        document.addEventListener('livewire:navigating', () => {
            this.cleanup();
        });
        
        // Handle browser back/forward navigation
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                // Page was loaded from cache (back/forward navigation)
                setTimeout(() => {
                    this.handlePageRestore();
                }, 100);
            }
        });
        
        // Handle Livewire navigation completed
        document.addEventListener('livewire:navigated', () => {
            setTimeout(() => {
                this.reinitializeIfNeeded();
            }, 100);
        });
    }

    /**
     * Handle page restore from cache (browser back/forward)
     */
    handlePageRestore() {
        console.log('Page restored from cache, reinitializing DataTable');
        
        // Check if table element exists and DataTable is not initialized
        if ($(this.tableSelector).length > 0 && !$.fn.dataTable.isDataTable(this.tableSelector)) {
            this.initializeDataTable();
        }
    }

    /**
     * Reinitialize DataTable if needed
     */
    reinitializeIfNeeded() {
        // Check if table element exists but DataTable is not initialized
        if ($(this.tableSelector).length > 0 && !$.fn.dataTable.isDataTable(this.tableSelector)) {
            console.log('Table element found but DataTable not initialized, reinitializing...');
            this.initializeDataTable();
        }
    }

    /**
     * Remove specific event handler
     */
    removeEventHandler(key) {
        if (this.eventHandlers.has(key)) {
            const handler = this.eventHandlers.get(key);
            $(handler.element).off(handler.event);
            this.eventHandlers.delete(key);
        }
    }

    /**
     * Clean up all event handlers and DataTable instance
     */
    cleanup() {
        // Remove all event handlers
        this.eventHandlers.forEach((handler, key) => {
            $(handler.element).off(handler.event);
        });
        this.eventHandlers.clear();

        // Destroy DataTable
        this.destroyExistingTable();
        
        console.log('PRRequestsTableManager cleaned up');
    }

    /**
     * Refresh the DataTable
     */
    refresh() {
        if (this.dataTable) {
            this.dataTable.ajax.reload();
        }
    }

    /**
     * Get current DataTable instance
     */
    getDataTable() {
        return this.dataTable;
    }
}
}

// Check if we're on the correct page before initializing
function shouldInitializeTable() {
    // Check if table element exists
    const tableExists = $('#prRequestsTable').length > 0;
    
    // Additional checks - you can add more specific page identifiers
    const isCorrectPage = tableExists && (
        // Check URL contains specific path
        window.location.pathname.includes('/public-relation') ||
        // Check for specific page elements
        $('.pr-requests-page').length > 0 ||
        // Check for specific body class
        $('body').hasClass('pr-requests-page') ||
        // Fallback - just check if table exists
        tableExists
    );
    
    return isCorrectPage;
}

// Simple initialization function
function initializePrRequestsTable() {
    // Only initialize if we should
    if (!shouldInitializeTable()) {
        console.log('Not on PR requests page, skipping table initialization');
        return;
    }
    
    // Clean up existing instance if it exists
    if (window.prTableManager) {
        window.prTableManager.cleanup();
        window.prTableManager = null;
    }
    
    console.log('Initializing PR requests table...');
    window.prTableManager = new window.PRRequestsTableManager();
}

// Primary initialization - when DOM is ready
$(document).ready(function() {
    if (typeof $ !== 'undefined') {
        initializePrRequestsTable();
    }
});

// Handle browser back/forward navigation
window.addEventListener('pageshow', function(event) {
    if (event.persisted) {
        // Page was loaded from cache
        console.log('Page restored from cache');
        setTimeout(function() {
            if (shouldInitializeTable() && !window.prTableManager) {
                console.log('Reinitializing after browser back navigation');
                initializePrRequestsTable();
            }
        }, 100);
    }
});

// Handle Livewire navigation
document.addEventListener('livewire:navigated', function() {
    setTimeout(function() {
        if (shouldInitializeTable() && !window.prTableManager) {
            console.log('Reinitializing after Livewire navigation');
            initializePrRequestsTable();
        }
    }, 100);
});