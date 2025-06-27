let dataTable;
let selectedStatuses = [];

function initDataTable() {
    // Check if dependencies are loaded
    if (typeof $ === "undefined" || typeof DataTable === "undefined") {
        setTimeout(initDataTable, 100);
        return;
    }

    // Destroy existing table
    if (DataTable.isDataTable("#requestsTable")) {
        new DataTable("#requestsTable").destroy();
    }

    // Initialize DataTable v2
    dataTable = new DataTable("#requestsTable", {
        layout: {
            topEnd: {
                search: {
                    placeholder: "Search...",
                },
            },
        },
        pageLength: 10,
        responsive: true,
        ordering: true,
        searching: true,
        language: {
            sProcessing: "Sedang memproses...",
            sLengthMenu: "Tampilkan _MENU_ entri per halaman",
            sZeroRecords: "Tidak ditemukan data yang sesuai",
            sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
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
                targets: 3, // Judul column
                className: "judul",
            },
            {
                targets: 4, // Status column - disable sorting to prevent conflicts
                orderable: false,
            },
        ],
        drawCallback: function () {
            $("#requestsTable tbody tr").addClass("hover:bg-gray-50");

            // Re-bind checkbox events after table redraw
            bindCheckboxEvents();
        },
    });

    // Global search functionality
    $("#globalSearch").on("keyup", function () {
        dataTable.search(this.value).draw();
    });

    // Initialize status filter in header
    initHeaderStatusFilter();

    // Initial checkbox binding
    bindCheckboxEvents();
}

function bindCheckboxEvents() {
    // Handle row clicks (exclude checkbox column and status column)
    $("#requestsTable tbody tr")
        .off("click")
        .on("click", function (e) {
            // Don't navigate if clicking on checkbox column, status column, or action column
            const clickedColumnIndex = $(e.target).closest("td").index();
            if (
                clickedColumnIndex === 0 ||
                clickedColumnIndex === 4 ||
                clickedColumnIndex === 7
            ) {
                return;
            }

            const id = $(this).data("id");
            if (id) {
                // Using dynamic URL construction
                const baseUrl = window.location.origin;
                window.location.href = baseUrl + "/information-system/" + id;
            }
        });
}

// Function to show flash messages
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

    // Hide cancel selection button
    $("button[wire\\:click=\"$set('selectedRequests', [])\"]")
        .closest("div")
        .hide();
}

const defaultStatusOptions = [
    {
        value: "Permohonan Masuk",
        label: "Permohonan Masuk",
    },
    {
        value: "Didisposisikan",
        label: "Didisposisikan",
    },
    {
        value: "Revisi Kasatpel",
        label: "Revisi Kasatpel",
    },
    {
        value: "Revisi Kapusdatin",
        label: "Revisi Kapusdatin",
    },
    {
        value: "Disetujui Kasatpel",
        label: "Disetujui Kasatpel",
    },
    {
        value: "Disetujui Kapusdatin",
        label: "Disetujui Kapusdatin",
    },
    {
        value: "Proses Permohonan",
        label: "Proses Permohonan",
    },
    {
        value: "Permohonan Selesai",
        label: "Permohonan Selesai",
    },
    {
        value: "Ditolak",
        label: "Ditolak",
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
    dataTable.draw();
}

// Initialize on various events
document.addEventListener("DOMContentLoaded", initDataTable);
document.addEventListener("livewire:navigated", initDataTable);
document.addEventListener("livewire:load", initDataTable);

// jQuery ready fallback
if (typeof $ !== "undefined") {
    $(document).ready(initDataTable);
}
