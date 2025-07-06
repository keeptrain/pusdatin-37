class InformationSystemDataTable {
    constructor() {
        this.dataTable = null;
        this.selectedStatuses = [];
        this.isDeleting = false;
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
                        targets: 1, // Checkbox column
                        orderable: true,
                        searchable: false,
                        className: "text-center",
                    },
                    { targets: 3, className: "judul" },
                    {
                        targets: 4,
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
            this.bindCustomEvents();
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
        $("#globalSearch")
            .off("input.customsearch")
            .on("input.customsearch", (e) => {
                this.dataTable.search(e.target.value).draw();
            });

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

    bindCustomEvents() {
        this.bindLivewireEvents();

        this.bindCheckboxEventsMinimal();
    }

    bindRowNavigation() {
        $("#requestsTable tbody tr")
            .off("click.rownav")
            .on("click.rownav", (e) => {
                const columnIndex = $(e.target).closest("td").index();
                if (columnIndex === 0 || columnIndex === 4) return;

                if (this.isDeleting) return;

                const id = $(e.currentTarget).data("id");
                if (id) {
                    window.location.href = `${window.location.origin}/information-system/${id}`;
                }
            });
    }

    bindCheckboxEventsMinimal() {
        if (!$("#selectAllCheckbox").data("custom-bound")) {
            $("#selectAllCheckbox")
                .on("change.selectallminimal", (e) => {
                    const isChecked = e.target.checked;
                    $(".row-checkbox").prop("checked", isChecked);
                })
                .data("custom-bound", true);
        }

        $(document)
            .off("change.checkboxminimal")
            .on("change.checkboxminimal", ".row-checkbox", (e) => {
                e.stopPropagation();

                const total = $(".row-checkbox").length;
                const checked = $(".row-checkbox:checked").length;
                $("#selectAllCheckbox").prop("checked", checked === total);
            });
    }

    bindLivewireEvents() {
        // Hanya bind sekali
        if (this.livewireEventsBound) return;

        document.addEventListener("livewire:init", () => {
            Livewire.on("delete-error", () => {
                this.isDeleting = false;
                this.hideProcessingMessage();
            });

            Livewire.on("select-all-updated", (event) => {
                // MINIMAL update - hanya checkbox state
                const { selectAll } = event[0];
                $(".row-checkbox").prop("checked", selectAll);
                $("#selectAllCheckbox").prop("checked", selectAll);
            });

            Livewire.on("data-deleted-reinit", () => {
                this.handleDataDeletedReinit();
            });

            Livewire.on("delete-started", () => {
                this.isDeleting = true;
                this.showProcessingMessage();
            });

            Livewire.on("delete-completed", () => {
                this.isDeleting = false;
                this.hideProcessingMessage();
            });
        });

        this.livewireEventsBound = true;
    }

    handleDataDeletedReinit() {
        // Re-initialize setelah data berubah
        setTimeout(() => {
            this.initializeDataTable();

            // Reset filter tanpa mengganggu DataTable
            this.selectedStatuses = [];
            this.updateFilterButtonVisual();
            $("#globalSearch").val("");
        }, 100);
    }

    showProcessingMessage() {
        $("#processing-overlay").remove(); // Remove existing
        const overlay = `
            <div id="processing-overlay" class="fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span class="text-gray-700">Menghapus data...</span>
                </div>
            </div>
        `;
        $("body").append(overlay);
    }

    hideProcessingMessage() {
        $("#processing-overlay").fadeOut(300, function () {
            $(this).remove();
        });
    }

    // Status Filter - ISOLASI COMPLETE dari DataTable events
    initializeStatusFilter() {
        this.populateStatusCheckboxes();
        this.bindStatusFilterEventsIsolated();
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

    bindStatusFilterEventsIsolated() {
        // COMPLETELY ISOLATED - menggunakan namespace yang unik

        // Toggle dropdown
        $(document)
            .off("click.statusfilterunique")
            .on("click.statusfilterunique", "#statusFilterToggle", (e) => {
                e.stopPropagation();
                e.preventDefault();

                const dropdown = $("#statusFilterDropdown");
                dropdown.toggleClass("hidden");
            });

        // Close dropdown
        $(document)
            .off("click.statuscloseunique")
            .on("click.statuscloseunique", "#closeStatusFilter", (e) => {
                e.stopPropagation();
                $("#statusFilterDropdown").addClass("hidden");
            });

        // Status checkbox changes
        $(document)
            .off("change.statuscheckboxunique")
            .on("change.statuscheckboxunique", ".status-checkbox", () => {
                this.updateSelectedStatuses();
                this.applyStatusFilter();
            });

        // Select/Clear all
        $(document)
            .off("click.selectallstatusunique")
            .on("click.selectallstatusunique", "#selectAllStatus", (e) => {
                e.preventDefault();
                $(".status-checkbox").prop("checked", true);
                this.updateSelectedStatuses();
                this.applyStatusFilter();
            });

        $(document)
            .off("click.clearallstatusunique")
            .on("click.clearallstatusunique", "#clearAllStatus", (e) => {
                e.preventDefault();
                $(".status-checkbox").prop("checked", false);
                this.updateSelectedStatuses();
                this.applyStatusFilter();
            });

        // Outside click
        $(document)
            .off("click.outsidestatusunique")
            .on("click.outsidestatusunique", (e) => {
                if (
                    !$(e.target).closest(
                        "#statusFilterToggle, #statusFilterDropdown"
                    ).length
                ) {
                    $("#statusFilterDropdown").addClass("hidden");
                }
            });

        // Prevent close on inside click
        $(document)
            .off("click.insidestatusunique")
            .on("click.insidestatusunique", "#statusFilterDropdown", (e) => {
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

document.addEventListener("livewire:init", () => {
    Livewire.on("data-updated", () => {
        informationSystemTable.initializeDataTable();
    });
});

// jQuery fallback
if (typeof $ !== "undefined") {
    $(document).ready(() => informationSystemTable.init());
}
