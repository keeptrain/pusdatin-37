// 1. DataTable initialization with proper cleanup
function initializePrRequestsTable() {
    // Check if dependencies are loaded
    if (typeof $ === undefined || typeof $.fn.DataTable === undefined) {
        setTimeout(initializePrRequestsTable, 100);
        return;
    }

    // Cleanup any existing DataTable instance
    if ($.fn.dataTable.isDataTable("#prRequestsTable")) {
        // console.log('DataTable masih ada', $.fn.dataTable.isDataTable("#prRequestsTable"));
        // $.fn.dataTable.destroy();
    }

    // Initialize new DataTable instance
    const dataTable = $('#prRequestsTable').DataTable({
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
        initComplete: function () {
            // console.log('DataTable initialized');
        }
    });

    setupGlobalSearch(dataTable);
    setupStatusFilter(dataTable);
}

function setupGlobalSearch(dataTable) {
    // Remove previous event handlers to avoid duplicates
    $("#globalSearch").off("keyup.globalSearch");

    // Setup new handler with namespace
    $("#globalSearch").on("keyup.globalSearch", function () {
        dataTable.search(this.value).draw();
    });
}

function setupStatusFilter(dataTable) {
    statusFilterAction();
    populateStatusCheckboxes();
    bindStasusFilterEvents(dataTable);
    selectAllStatus();
    clearAllStatus();
}

function statusFilterAction() {
    $("#statusFilterToggle").on("click", function () {
        $("#statusFilterDropdown").toggleClass("hidden");
    });

    $("#closeStatusFilter").on("click", function () {
        $("#statusFilterDropdown").addClass("hidden");
    });

    $("#selectAllStatus").on("click", function () {
        $(".statusCheckbox").prop("checked", true);
    });

    $("#clearAllStatus").on("click", function () {
        $(".statusCheckbox").prop("checked", false);
    });
}

function populateStatusCheckboxes() {
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
}

function bindStasusFilterEvents(dataTable) {
    $("#statusCheckboxContainer input[type='checkbox']").change(function () {
        const selectedStatuses = $.makeArray($("#statusCheckboxContainer input[type='checkbox']:checked")).map(function (el) {
            return $(el).val();
        });

        dataTable.column(4).search(selectedStatuses.join("|"), true, false).draw();
    });
}

function selectAllStatus() {
    $("#selectAllStatus").click(function () {
        $("#statusCheckboxContainer input[type='checkbox']").prop("checked", true);
    });
}

function clearAllStatus() {
    $("#clearAllStatus").click(function () {
        $("#statusCheckboxContainer input[type='checkbox']").prop("checked", false);
    });
}

// Livewire navigation events
document.addEventListener("livewire:navigating", () => {
    if ($.fn.dataTable.isDataTable("#prRequestsTable")) {
        $('#prRequestsTable').DataTable().destroy(true);
    }
});

// Manual initialization when jQuery is ready
if (typeof $ !== undefined) {
    $(document).ready(() => {
        initializePrRequestsTable();
    });
}