let dataTable;
let selectedStatuses = [];
let isDeleting = false;

function initDataTable() {
    // Check if dependencies are loaded
    if (typeof $ === "undefined" || typeof DataTable === "undefined") {
        setTimeout(initDataTable, 100);
        return;
    }

    // Destroy existing table
    if (DataTable.isDataTable("#prRequestsTable")) {
        new DataTable("#prRequestsTable").destroy();
    }

    // Initialize DataTable v2 with custom layout
    dataTable = new DataTable("#prRequestsTable", {
        layout: {
            topStart: null,
            topEnd: null,
            bottomStart: "info",
            bottomEnd: "paging",
        },
        pageLength: 10,
        responsive: true,
        ordering: true,
        searching: true,
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
                className: "text-center",
            },
            {
                targets: 4, // Status column
                orderable: false,
            },
            {
                targets: -1, // Last column
                orderable: false,
                searchable: false,
            },
        ],
        drawCallback: function () {
            $("#prRequestsTable tbody tr").addClass("hover:bg-gray-50");

            // Re-bind checkbox events after table redraw
            bindCheckboxEvents();

            // Update custom info text
            updateCustomInfo();

            // Re-apply checkbox states after redraw
            reapplyCheckboxStates();
        },
    });

    // Global search functionality - IMPORTANT: Setup search AFTER table initialization
    setupGlobalSearch();

    // Initialize status filter in header
    initHeaderStatusFilter();

    // Initial checkbox binding
    bindCheckboxEvents();

    // Add custom entries control
    addCustomEntriesControl();
}

function setupGlobalSearch() {
    // Remove any existing event handlers first
    $("#globalSearch").off("keyup");

    // Setup new handler
    $("#globalSearch").on("keyup", function () {
        if (dataTable) {
            dataTable.search(this.value).draw();
        }
    });
}

function addCustomEntriesControl() {
    // Check if control already exists
    if ($(".custom-entries-control").length > 0) {
        return;
    }

    // Add custom entries control before the table
    const entriesControl = `
        <div class="custom-entries-control">
            <label for="customEntriesSelect">Show</label>
            <select id="customEntriesSelect">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label>entries per page</label>
        </div>
    `;

    // Insert before the table
    $("#prRequestsTable").before(entriesControl);

    // Handle entries per page change
    $("#customEntriesSelect").on("change", function () {
        const length = parseInt($(this).val());
        dataTable.page.len(length).draw();
    });
}

function updateCustomInfo() {
    // This function can be used to customize the info display if needed
    // DataTables will handle the default info display
}

function reapplyCheckboxStates() {
    // Reapply select all checkbox state
    const selectAllChecked = $("#selectAllCheckbox").prop("checked");
    if (selectAllChecked) {
        $(".row-checkbox")
            .prop("checked", true)
            .prop("disabled", true)
            .addClass("checkbox-disabled");
    }
}

function bindCheckboxEvents() {
    // Remove existing handlers to prevent duplicates
    $("#prRequestsTable tbody tr").off("click");
    $("#selectAllCheckbox").off("change");
    $(".row-checkbox").off("change");

    // Row click handler
    $("#prRequestsTable tbody tr").on("click", function (e) {
        if (isDeleting) {
            return;
        }

        const clickedColumnIndex = $(e.target).closest("td").index();
        if (
            clickedColumnIndex === 0 ||
            clickedColumnIndex === 4 ||
            clickedColumnIndex === 8
        ) {
            return;
        }

        const id = $(this).data("id");
        if (id) {
            window.location.href = "{{ url('public-relation') }}" + "/" + id;
        }
    });

    // Select all checkbox handler
    $("#selectAllCheckbox").on("change", function () {
        const isChecked = $(this).prop("checked");

        if (isChecked) {
            // Check all checkboxes and disable them
            $(".row-checkbox")
                .prop("checked", true)
                .prop("disabled", true)
                .addClass("checkbox-disabled");
        } else {
            // Uncheck all checkboxes and enable them
            $(".row-checkbox")
                .prop("checked", false)
                .prop("disabled", false)
                .removeClass("checkbox-disabled");
        }
    });

    // Individual checkbox handler
    $(".row-checkbox").on("change", function () {
        const totalCheckboxes = $(".row-checkbox").length;
        const checkedCheckboxes = $(".row-checkbox:checked").length;

        if (checkedCheckboxes === totalCheckboxes && totalCheckboxes > 0) {
            $("#selectAllCheckbox").prop("checked", true);
        } else {
            $("#selectAllCheckbox").prop("checked", false);
        }
    });
}

function enableTableInteractions() {
    $("#prRequestsTable").removeClass("table-disabled");

    if (!$("#selectAllCheckbox").prop("checked")) {
        $(".row-checkbox")
            .prop("disabled", false)
            .removeClass("checkbox-disabled");
    }

    $("#selectAllCheckbox").prop("disabled", false);
    $("#globalSearch").prop("disabled", false);
    $("#statusFilterToggle").prop("disabled", false);
}

function showFlashMessage(type, message) {
    const flashContainer = $("#flash-messages");
    const alertClass =
        type === "success"
            ? "bg-green-100 border-green-400 text-green-700"
            : "bg-red-100 border-red-400 text-red-700";

    const flashHtml = ` 
        <div class="mb-4 p-4 ${alertClass} border rounded flash-message">
            ${message}
        </div>
    `;

    flashContainer.html(flashHtml);

    // Auto hide after 5 seconds
    setTimeout(() => {
        flashContainer.find(".flash-message").fadeOut();
    }, 5000);
}

// Function to remove rows from DataTable
function removeRowsFromDataTable(deletedIds) {
    deletedIds.forEach(function (id) {
        // Find and remove row with the specific data-id
        const row = dataTable.row($(`tr[data-id="${id}"]`));
        if (row.length) {
            row.remove();
        }
    });

    // Redraw table
    dataTable.draw();
}

// Listen for Livewire events
document.addEventListener("livewire:init", () => {
    // Handle confirm delete button click
    Livewire.on("confirm-delete", (event) => {
        const deleteButton = $('button[wire\\:click="confirmDelete"]');
        const originalText = deleteButton.html();

        // Disable button and show loading state
        deleteButton
            .prop("disabled", true)
            .html(
                '<span class="inline-flex items-center"><svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...</span>'
            );

        // Re-enable button after modal shows (typically after a short delay)
        setTimeout(() => {
            deleteButton.prop("disabled", false).html(originalText);
        }, 500);
    });

    Livewire.on("data-deleted", (event) => {
        const data = event[0];
        const deletedIds = data.deletedIds;
        const deletedCount = data.deletedCount;

        // Remove rows from DataTable
        removeRowsFromDataTable(deletedIds);

        // Show success message
        showFlashMessage(
            "success",
            `Data berhasil dihapus sebanyak ${deletedCount} item.`
        );

        // Update button states and selections
        updateButtonStates();
    });

    Livewire.on("select-all-updated", (event) => {
        const data = event[0];
        const selectAll = data.selectAll;
        const selectedIds = data.selectedIds;

        if (selectAll) {
            // Check and disable all individual checkboxes when select all is checked
            $(".row-checkbox")
                .prop("checked", true)
                .prop("disabled", true)
                .addClass("checkbox-disabled");
        } else {
            // Uncheck and enable all individual checkboxes when select all is unchecked
            $(".row-checkbox")
                .prop("checked", false)
                .prop("disabled", false)
                .removeClass("checkbox-disabled");
        }
    });

    Livewire.on("delete-started", () => {
        isDeleting = true;
    });

    Livewire.on("delete-completed", () => {
        isDeleting = false;
        enableTableInteractions();
    });
});

// Function to update button states after deletion
function updateButtonStates() {
    // Reset checkboxes and selections
    $(".row-checkbox").prop("checked", false);
    $("#selectAllCheckbox").prop("checked", false);

    // Update button text to show 0 selected
    $('button[wire\\:click="confirmDelete"] span').text("0");

    // Disable delete button
    $('button[wire\\:click="confirmDelete"]')
        .prop("disabled", true)
        .addClass("opacity-50 cursor-not-allowed");

    // Enable all checkboxes again
    $(".row-checkbox").prop("disabled", false).removeClass("checkbox-disabled");
}

const defaultStatusOptions = [
    {
        value: "Usulan Masuk",
        label: "Usulan Masuk",
    },
    {
        value: "Antrean Promkes",
        label: "Antrean Promkes",
    },
    {
        value: "Kurasi Promkes",
        label: "Kurasi Promkes",
    },
    {
        value: "Antrean Pusdatin",
        label: "Antrean Pusdatin",
    },
    {
        value: "Proses Pusdatin",
        label: "Proses Pusdatin",
    },
    {
        value: "Selesai",
        label: "Selesai",
    },
];

function initHeaderStatusFilter() {
    // Populate checkbox options
    const container = $("#statusCheckboxContainer");
    container.empty();

    defaultStatusOptions.forEach((opt, idx) => {
        container.append(`
            <div class="status-checkbox-item">
                <input type="checkbox" id="status_${idx}" class="status-checkbox" value="${opt.value}">
                <label for="status_${idx}">${opt.label}</label>
            </div>
        `);
    });

    // Add custom search function for status filtering
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        // If no status filter selected, show all rows
        if (selectedStatuses.length === 0) {
            return true;
        }

        // Get the status from the row data (assuming status is in column index 4)
        const row = settings.aoData[dataIndex].nTr;
        const rowStatus = $(row).data("status");

        // Return true if the row status matches any of the selected statuses
        return selectedStatuses.includes(rowStatus);
    });

    // Toggle dropdown visibility when clicking the filter icon
    $(document).on("click", "#statusFilterToggle", function (e) {
        e.stopPropagation();
        e.preventDefault();

        const dropdown = $("#statusFilterDropdown");
        const isVisible = !dropdown.hasClass("hidden");

        // Close all other dropdowns first
        $(".dropdown-menu").addClass("hidden");

        if (isVisible) {
            dropdown.addClass("hidden");
        } else {
            dropdown.removeClass("hidden");

            // Position the dropdown correctly
            positionDropdown();
        }
    });

    // Close dropdown when clicking the close button
    $(document).on("click", "#closeStatusFilter", function (e) {
        e.stopPropagation();
        $("#statusFilterDropdown").addClass("hidden");
    });

    // Close dropdown when clicking outside
    $(document).on("click", function (e) {
        if (
            !$(e.target).closest("#statusFilterToggle, #statusFilterDropdown")
                .length
        ) {
            $("#statusFilterDropdown").addClass("hidden");
        }
    });

    // Prevent dropdown from closing when clicking inside it
    $(document).on("click", "#statusFilterDropdown", function (e) {
        e.stopPropagation();
    });

    // Handle checkbox changes
    $(document).on("change", ".status-checkbox", function () {
        updateSelectedStatuses();
        applyStatusFilter();
    });

    // Select All functionality
    $(document).on("click", "#selectAllStatus", function (e) {
        e.preventDefault();
        $(".status-checkbox").prop("checked", true);
        updateSelectedStatuses();
        applyStatusFilter();
    });

    // Clear All functionality
    $(document).on("click", "#clearAllStatus", function (e) {
        e.preventDefault();
        $(".status-checkbox").prop("checked", false);
        updateSelectedStatuses();
        applyStatusFilter();
    });
}

function positionDropdown() {
    const toggle = $("#statusFilterToggle");
    const dropdown = $("#statusFilterDropdown");

    if (toggle.length && dropdown.length) {
        const toggleOffset = toggle.offset();
        const toggleHeight = toggle.outerHeight();
        const toggleWidth = toggle.outerWidth();

        // Position dropdown relative to the toggle button
        dropdown.css({
            position: "absolute",
            top: "100%",
            right: "0",
            left: "auto",
        });
    }
}

function updateSelectedStatuses() {
    selectedStatuses = [];
    $(".status-checkbox:checked").each(function () {
        selectedStatuses.push($(this).val());
    });

    updateFilterButtonVisual();
}

function updateFilterButtonVisual() {
    const filterButton = $("#statusFilterToggle");
    const count = selectedStatuses.length;

    if (count > 0) {
        filterButton.addClass("filter-active");
        // Optionally add a badge or indicator
        if (!filterButton.find(".filter-indicator").length) {
            filterButton.append(
                '<span class="filter-indicator absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">' +
                    count +
                    "</span>"
            );
        } else {
            filterButton.find(".filter-indicator").text(count);
        }
    } else {
        filterButton.removeClass("filter-active");
        filterButton.find(".filter-indicator").remove();
    }
}

function applyStatusFilter() {
    // Simply redraw the table - the custom search function will handle the filtering
    if (dataTable) {
        dataTable.draw();
    }
}

// Initialize on various events
document.addEventListener("DOMContentLoaded", initDataTable);
document.addEventListener("livewire:navigated", initDataTable);
document.addEventListener("livewire:load", initDataTable);

// jQuery ready fallback
if (typeof $ !== "undefined") {
    $(document).ready(initDataTable);
}
