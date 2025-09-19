@extends('layout.layout')
@php
    $title='Users Grid';
    $subTitle = 'Database';
    $script ='<script>
                        $(".remove-item-btn").on("click", function() {
                            $(this).closest("tr").addClass("d-none")
                        });
            </script>';
@endphp

@section('content')

            <div class="card h-100 p-0 radius-12 mt-4">
                <!-- Header -->
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                    <h5 class="text-primary m-0 d-flex align-items-center gap-2">
                        <i class="fab fa-google"></i>
                    </h5>
                    <!-- <a href="" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Fetch New Sheet
                    </a> -->
                </div>

                <div class="card-body p-24">
                    <!-- Flash & Validation Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li><i class="fas fa-times-circle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Data Table -->
                    <div class="table-responsive scroll-sm">
                        @if($data->isEmpty())
                            <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
                        @else
                            <table class="table bordered-table sm-table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Row #</th>
                                        @foreach(array_keys($data->first()['data'] ?? []) as $col)
                                            <th>{{ $col }}</th>
                                        @endforeach
                                        <th>Forwarded By</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sheet-table-body">
                                    @foreach($data as $row)
                                        @php
                                            $decoded = $row['data'];
                                        @endphp
                                        <tr id="row-{{ $row['id'] }}" data-id="{{ $row['id'] }}">
                                            <td>{{ $row['id'] }}</td>
                                            <td>{{ $row['sheet_row_number'] }}</td>
                                            @foreach($decoded as $key => $val)
                                                <td contenteditable="true" data-key="{{ $key }}">{{ $val }}</td>
                                            @endforeach
                                            <td>{{ $row['forwarded_by'] }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-success save-btn" data-id="{{ $row['id'] }}">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>


@endsection

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("sheet-table-body");

    // Auto-add row function
    function addBlankRow() {
        let colKeys = [];
        let firstRow = tableBody.querySelector("tr");
        if (firstRow) {
            firstRow.querySelectorAll("td[contenteditable=true]").forEach(cell => {
                colKeys.push(cell.dataset.key);
            });
        }

        let newRow = document.createElement("tr");
        newRow.setAttribute("data-id", "new");

        let cells = `<td>New</td><td>—</td>`;
        colKeys.forEach(k => {
            cells += `<td contenteditable="true" data-key="${k}"></td>`;
        });
        cells += `
            <td>SELF ({{ Auth::id() }}) ({{ Auth::user()->role }})</td>
            <td>
                <button class="btn btn-sm btn-success save-btn" data-id="new">
                    <i class="fas fa-save"></i> Save
                </button>
            </td>
        `;

        newRow.innerHTML = cells;
        tableBody.appendChild(newRow);

        attachSaveHandler(newRow.querySelector(".save-btn"));
    }

    if (!tableBody.querySelector('tr[data-id="new"]')) {
        addBlankRow();
    }

    function attachSaveHandler(btn) {
        btn.addEventListener("click", function () {
            let saveBtn = this;
            let id = saveBtn.dataset.id;
            let row = saveBtn.closest("tr");

            // Disable button + show spinner
            saveBtn.disabled = true;
            let originalHTML = saveBtn.innerHTML;
            saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;

            let updatedData = {};
            row.querySelectorAll("td[contenteditable=true]").forEach(cell => {
                updatedData[cell.dataset.key] = cell.innerText.trim();
            });

            if (id === "new") {
                fetch(`/dashboard/senior/google-sheet/store`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ rows: [updatedData] }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        let rowData = data.rows[0];

                        // Replace row content
                        let html = `
                            <td>${rowData.id}</td>
                            <td>${rowData.sheet_row_number}</td>
                        `;
                        Object.entries(rowData.data).forEach(([key, val]) => {
                            html += `<td contenteditable="true" data-key="${key}">${val}</td>`;
                        });
                        html += `
                            <td>${rowData.created_by === "{{ Auth::id() }}|{{ Auth::user()->role }}" 
                                ? `SELF (${rowData.created_by.replace('|', ') (')})`
                                : rowData.forwarded_by}</td>
                            <td>
                                <button class="btn btn-sm btn-success save-btn" data-id="${rowData.id}">
                                    <i class="fas fa-save"></i> Save
                                </button>
                            </td>
                        `;

                        row.setAttribute("id", "row-" + rowData.id);
                        row.setAttribute("data-id", rowData.id);
                        row.innerHTML = html;

                        attachSaveHandler(row.querySelector(".save-btn"));
                        showMessage("✅ New row saved!", "success");

                        addBlankRow();
                    } else {
                        showMessage("❌ Failed to insert row", "danger");
                    }
                })
                .catch(() => showMessage("⚠️ Server error!", "danger"))
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHTML;
                });

            } else {
                fetch(`/dashboard/senior/google-sheet/update/${id}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ data: updatedData, _method: "PATCH" }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage("✅ Row updated!", "success");
                    } else {
                        showMessage("❌ Update failed: " + (data.message || ""), "danger");
                    }
                })
                .catch(() => showMessage("⚠️ Server error!", "danger"))
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = originalHTML;
                });
            }
        });
    }

    document.querySelectorAll(".save-btn").forEach(btn => attachSaveHandler(btn));
});

// Flash message
function showMessage(message, type) {
    let alertBox = document.createElement("div");
    alertBox.className = `alert alert-${type} alert-dismissible fade show mt-2`;
    alertBox.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector(".container-fluid").prepend(alertBox);
}
</script>