class InformationSystemDataTable {
    constructor() {
        this.dataTable = null;
        this.selectedStatuses = [];
        this.config = {
            defaultStatusOptions: [
                { value: "Permohonan Masuk", label: "Permohonan Masuk" },
                { value: "Didisposisikan", label: "Didisposisikan" },
                { value: "Revisi Kasatpel", label: "Revisi Kasatpel" },
                { value: "Revisi Kapusdatin", label: "Revisi Kapusdatin" },
                { value: "Disetujui Kasatpel", label: "Disetujui Kasatpel" },
                {
                    value: "Disetujui Kapusdatin",
                    label: "Disetujui Kapusdatin",
                },
                { value: "Proses Permohonan", label: "Proses Permohonan" },
                { value: "Permohonan Selesai", label: "Permohonan Selesai" },
                { value: "Ditolak", label: "Ditolak" },
            ],
            tableConfig: {
                layout: {
                    topEnd: {
                        search: { placeholder: "Search..." },
                    },
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
                        targets: 1, // No column
                        orderable: true,
                        searchable: false,
                        className: "text-center",
                    },
                    { targets: 3, className: "judul" },
                    {
                        targets: 4, // Status column
                        orderable: false,
                        searchable: false,
                    },
                ],
                drawCallback: () => this.onTableDraw(),
            },
        };
    }

    init() {
        this.waitForDependencies(() => {
            this.initializeDataTable();
            this.bindBasicEvents();
            this.initializeStatusFilter();
        });
    }

    waitForDependencies(callback) {
        if (typeof $ === "undefined" || typeof DataTable === "undefined") {
            setTimeout(() => this.waitForDependencies(callback), 100);
            return;
        }
        callback();
    }

    initializeDataTable() {
        if (DataTable.isDataTable("#requestsTable")) {
            new DataTable("#requestsTable").destroy();
        }

        this.dataTable = new DataTable(
            "#requestsTable",
            this.config.tableConfig
        );
        this.setupCustomSearch();
    }

    setupCustomSearch() {
        // Global search
        $("#globalSearch")
            .off("input.customsearch")
            .on("input.customsearch", (e) => {
                this.dataTable.search(e.target.value).draw();
            });

        // Status filtering
        $.fn.dataTable.ext.search.push((settings, data, dataIndex) => {
            if (this.selectedStatuses.length === 0) return true;
            const row = settings.aoData[dataIndex].nTr;
            const rowStatus = $(row).data("status");
            return this.selectedStatuses.includes(rowStatus);
        });
    }

    onTableDraw() {
        this.bindRowNavigation();
    }

    bindBasicEvents() {
        this.bindCheckboxEvents();
        this.bindLivewireEvents();
    }

    bindRowNavigation() {
        // Row click untuk navigasi
        $("#requestsTable tbody tr")
            .off("click.rownav")
            .on("click.rownav", (e) => {
                const columnIndex = $(e.target).closest("td").index();
                if (columnIndex === 0 || columnIndex === 4) return; // Skip checkbox & status column

                const id = $(e.currentTarget).data("id");
                if (id) {
                    window.location.href = `${window.location.origin}/information-system/${id}`;
                }
            });
    }

    bindCheckboxEvents() {
        if (!$("#selectAllCheckbox").data("custom-bound")) {
            $("#selectAllCheckbox")
                .on("change.selectall", (e) => {
                    const isChecked = e.target.checked;
                    $(".row-checkbox").prop("checked", isChecked);
                })
                .data("custom-bound", true);
        }

        $(document)
            .off("change.checkbox")
            .on("change.checkbox", ".row-checkbox", (e) => {
                e.stopPropagation();

                const total = $(".row-checkbox").length;
                const checked = $(".row-checkbox:checked").length;
                $("#selectAllCheckbox").prop("checked", checked === total);
            });
    }

    bindLivewireEvents() {
        // Minimal Livewire events - hanya yang essential
        if (this.livewireEventsBound) return;

        document.addEventListener("livewire:init", () => {
            // Update checkbox state saat Livewire update
            Livewire.on("select-all-updated", (event) => {
                const { selectAll } = event[0];
                $(".row-checkbox").prop("checked", selectAll);
                $("#selectAllCheckbox").prop("checked", selectAll);
            });
        });

        this.livewireEventsBound = true;
    }

    initializeStatusFilter() {
        this.populateStatusCheckboxes();
        this.bindStatusFilterEvents();
    }

    populateStatusCheckboxes() {
        const container = $("#statusCheckboxContainer");
        container.empty();

        this.config.defaultStatusOptions.forEach((opt, idx) => {
            container.append(`
                <div class="status-checkbox-item">
                    <input type="checkbox" id="status_${idx}" class="status-checkbox" value="${opt.value}">
                    <label for="status_${idx}">${opt.label}</label>
                </div>
            `);
        });
    }

    bindStatusFilterEvents() {
        // Toggle dropdown
        $(document)
            .off("click.statusfilter")
            .on("click.statusfilter", "#statusFilterToggle", (e) => {
                e.stopPropagation();
                e.preventDefault();
                const dropdown = $("#statusFilterDropdown");
                dropdown.toggleClass("hidden");
            });

        // Close dropdown
        $(document)
            .off("click.statusclose")
            .on("click.statusclose", "#closeStatusFilter", (e) => {
                e.stopPropagation();
                $("#statusFilterDropdown").addClass("hidden");
            });

        // Status checkbox
        $(document)
            .off("change.statuscheckbox")
            .on("change.statuscheckbox", ".status-checkbox", () => {
                this.updateSelectedStatuses();
                this.applyStatusFilter();
            });

        // Select all statuses
        $(document)
            .off("click.selectallstatus")
            .on("click.selectallstatus", "#selectAllStatus", (e) => {
                e.preventDefault();
                $(".status-checkbox").prop("checked", true);
                this.updateSelectedStatuses();
                this.applyStatusFilter();
            });

        // Clear all statuses
        $(document)
            .off("click.clearallstatus")
            .on("click.clearallstatus", "#clearAllStatus", (e) => {
                e.preventDefault();
                $(".status-checkbox").prop("checked", false);
                this.updateSelectedStatuses();
                this.applyStatusFilter();
            });

        // Close dropdown jika klik diluar areanya
        $(document)
            .off("click.outsidestatus")
            .on("click.outsidestatus", (e) => {
                if (
                    !$(e.target).closest(
                        "#statusFilterToggle, #statusFilterDropdown"
                    ).length
                ) {
                    $("#statusFilterDropdown").addClass("hidden");
                }
            });

        $(document)
            .off("click.insidestatus")
            .on("click.insidestatus", "#statusFilterDropdown", (e) => {
                e.stopPropagation();
            });
    }

    updateSelectedStatuses() {
        this.selectedStatuses = [];
        $(".status-checkbox:checked").each((_, checkbox) => {
            this.selectedStatuses.push($(checkbox).val());
        });
        this.updateFilterButtonVisual();
    }

    updateFilterButtonVisual() {
        const filterButton = $("#statusFilterToggle");
        const count = this.selectedStatuses.length;

        filterButton.find(".filter-indicator").remove();

        if (count > 0) {
            filterButton.addClass("filter-active");
            filterButton.append(
                `<span class="filter-indicator absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">${count}</span>`
            );
        } else {
            filterButton.removeClass("filter-active");
        }
    }

    applyStatusFilter() {
        this.dataTable.draw();
    }
}

const informationSystemTable = new InformationSystemDataTable();

const initEvents = ["DOMContentLoaded", "livewire:navigated", "livewire:load"];
initEvents.forEach((event) => {
    document.addEventListener(event, () => informationSystemTable.init());
});

if (typeof $ !== "undefined") {
    $(document).ready(() => informationSystemTable.init());
}
