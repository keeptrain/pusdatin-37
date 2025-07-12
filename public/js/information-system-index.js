// 1. Regular DataTable initialization (works with wire:navigate)
function initializeInformationSystemTable() {
    if (typeof $ === undefined || typeof DataTable === undefined) {
        setTimeout(initializeInformationSystemTable, 100);
        return;
    }

    const dataTable = $("#requestsTable").DataTable({
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
                width: "5%",
            },
            {
                targets: 2,
                width: "80%",
            },
            {
                targets: 3, // Status column
                orderable: false,
                searchable: true,
            },
        ],
        initComplete: function () {
            console.log('DataTable initialized');
        },
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
        { value: "Permohonan Masuk", label: "Permohonan Masuk" },
        { value: "Didisposisikan", label: "Didisposisikan" },
        { value: "Revisi Kasatpel", label: "Revisi Kasatpel" },
        { value: "Revisi Kapusdatin", label: "Revisi Kapusdatin" },
        { value: "Disetujui Kasatpel", label: "Disetujui Kasatpel" },
        { value: "Disetujui Kapusdatin", label: "Disetujui Kapusdatin" },
        { value: "Proses Permohonan", label: "Proses Permohonan" },
        { value: "Permohonan Selesai", label: "Permohonan Selesai" },
        { value: "Ditolak", label: "Ditolak" },
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

        dataTable.column(3).search(selectedStatuses.join("|"), true, false).draw();
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

// 3. Livewire events
document.addEventListener("livewire:navigating", () => {
    $('#requestsTable').DataTable().destroy(true);
});

// 4. Manual initialization if jQuery is already loaded
if (typeof $ !== undefined) {
    $(document).ready(() => initializeInformationSystemTable());
}