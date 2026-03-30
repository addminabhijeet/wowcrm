@extends('layout.layout')
@php
    $title = 'Search Candidate';
    $role = auth()->user()->role ?? '';
    if ($role === 'admin') {
        $subTitle = 'Super Admin';
    } elseif ($role === 'operation') {
        $subTitle = 'Operation Manager';
    } else {
        $subTitle = 'role';
    }
    $script = '<script>
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });
    </script>';
@endphp

@section('content')

    <div class="card h-100 p-0 radius-12">
        <div
            class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
            <div class="d-flex align-items-center flex-wrap gap-3">

                <!-- SEARCH -->
                <form class="navbar-search position-relative" autocomplete="off">
                    <input type="text" id="senior-search" class="bg-base h-40-px w-auto form-control"
                        placeholder="Search Name, Email, Phone">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                    <div id="search-suggestions" class="list-group position-absolute w-100" style="z-index:1000;"></div>
                </form>

            </div>
        </div>

        <div class="card-body p-24" id="senior-table-wrapper">
            <!-- Extra Scroll Bar Above -->
            <!-- Extra Scroll Bar Above -->
            <div class="table-responsive scroll-sm mb-2" id="top-scroll-wrapper">
                <div id="top-scroll"></div>
            </div>

            <!-- Main Table Scroll -->
            <div class="table-responsive scroll-sm" id="bottom-scroll-wrapper">


                @if ($data->isEmpty())
                    <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
                @else
                    <table class="table bordered-table sm-table mb-0">
                        <thead>
                            <tr class="text-nowrap">
                                <th class="w-auto"></th>
                                <th class="text-center w-auto">Row</th>
                                <th class="text-center w-auto">Name</th>
                                <th class="text-center w-auto">Email Address</th>
                                <th class="text-center w-auto">Phone Number</th>
                                <th class="text-center w-auto">Status</th>
                            </tr>
                        </thead>
                        <tbody id="sheet-table-body">
                            @foreach ($data as $row)
                                <tr class="text-nowrap">
                                    <td class="text-center w-auto px-2">
                                        <button class="btn btn-sm btn-primary toggle-row p-1 px-2"
                                            data-id="{{ $row->id }}">
                                            +
                                        </button>
                                    </td>

                                    <td class="w-auto px-2">{{ $row->sheet_row_number }}</td>

                                    <td class="w-auto px-2">
                                        <input type="text" class="form-control form-control-sm name-input"
                                            data-key="Name" value="{{ $row->Name ?? '' }}">
                                    </td>

                                    <td class="w-auto px-2">
                                        <input type="email" class="form-control form-control-sm email-input"
                                            data-key="Email Address" value="{{ $row->Email_Address ?? '' }}">
                                    </td>

                                    <td class="w-auto px-2">
                                        <input type="tel" class="form-control form-control-sm phone-input"
                                            data-key="Phone Number" value="{{ $row->Phone_Number ?? '' }}">
                                    </td>

                                    <td class="w-auto px-2">
                                        <select class="form-select form-select-sm dynamic-dropdown" data-key="Exe Remarks">
                                            @foreach (['Called & Mailed', 'Not Interested', 'Interested', 'Others', 'Ready To Pay', 'VM', 'Busy'] as $option)
                                                <option value="{{ $option }}"
                                                    {{ $row->Exe_Remarks === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>


                                <tr id="collapse-{{ $row->id }}" class="collapse-row d-none">
                                    <td colspan="7">
                                        <div class="p-3 border rounded bg-light">

                                            <div class="row g-3">

                                                <!-- Location -->
                                                <div class="col-md-3">
                                                    <label>Location</label>
                                                    <input type="text" class="form-control location-autocomplete"
                                                        data-key="Location" value="{{ $row->Location ?? '' }}">
                                                </div>


                                                <div class="col-md-3">
                                                    <label>Date</label>
                                                    <input type="text" class="form-control date-picker" data-key="Date"
                                                        value="{{ $row->Date ? \Carbon\Carbon::parse($row->Date)->format('m/d/Y') : '' }}">
                                                </div>


                                                <!-- Relocation -->
                                                <div class="col-md-3">
                                                    <label>Relocation</label>
                                                    <select class="form-select dynamic-dropdown" data-key="Relocation">
                                                        @foreach (['YES', 'NO'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Relocation === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Graduation -->
                                                <div class="col-md-3">
                                                    <label>Graduation</label>
                                                    <input type="text" class="form-control date-picker"
                                                        data-key="Graduation Date" value="{{ $row->Graduation_Date }}">
                                                </div>

                                                <!-- Immigration -->
                                                <div class="col-md-3">
                                                    <label>Immigration</label>
                                                    <select class="form-select dynamic-dropdown" data-key="Immigration">
                                                        @foreach (['F1 CPT', 'F1 OPT', 'STEM OPT', 'H1B', 'B2', 'B1', 'H4', 'H4 EAD', 'GC/PR', 'USC', 'L2S'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Immigration === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Course -->
                                                <div class="col-md-3">
                                                    <label>Course</label>
                                                    <select class="form-select dynamic-dropdown" data-key="Course">
                                                        @foreach (['BA', 'DA', 'SAS', 'JAVA', 'QA', 'SQL', 'PYTHON', 'DOT NET'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Course === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Amount -->
                                                <div class="col-md-3">
                                                    <label>Amount</label>
                                                    <input type="text" class="form-control amount-input"
                                                        data-key="Amount" value="{{ $row->Amount }}">
                                                </div>

                                                <!-- Qualification -->
                                                <div class="col-md-3">
                                                    <label>Qualification</label>
                                                    <select class="form-select dynamic-dropdown" data-key="Qualification">
                                                        @foreach (['Masters', 'Bachelors', 'MBA', 'PG Diploma', 'M.Tech', 'B.Tech'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Qualification === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Remark -->
                                                <div class="col-md-3">
                                                    <label>Remark</label>
                                                    <input type="text" class="form-control" data-key="Remark"
                                                        value="{{ $row->Remark ?? '' }}">
                                                </div>

                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            {{-- Pagination --}}
            @if ($data->hasPages())
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div>
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function addBlankRow() {
                let colKeys = [];
                let firstRow = tableBody.querySelector("tr");
                if (firstRow) {
                    firstRow.querySelectorAll("input[data-key], select[data-key]").forEach(cell => colKeys.push(cell
                        .dataset.key));
                }

                let newRow = document.createElement("tr");
                newRow.setAttribute("data-id", "new");
                let cells = `<td>—</td>`;

                colKeys.forEach(k => {
                    if (['Exe Remarks', 'Immigration', 'Relocation', '1st Follow Up Remarks', 'Course',
                            'Time Zone', 'Qualification'
                        ].includes(k)) {
                        let opts = [];
                        if (k === 'Qualification') opts = ['Masters', 'Masters of Science', 'Bachelors',
                            'PG', 'MBA', 'PG Diploma', 'M.Tech', 'B.Tech', 'MA', 'Associate Degree',
                            'Aerospace Proj. Manag.'
                        ];
                        if (k === 'Exe Remarks') opts = ['Called & Mailed', 'Not Interested',
                            'Not Connected', 'Did Not Connect', 'Others', 'N/A', 'VM', 'Busy'
                        ];
                        if (k === 'Immigration') opts = ['F1 CPT', 'F1 OPT', 'STEM OPT', 'H1B', 'B2', 'B1',
                            'H4', 'H4 EAD', 'GC/PR', 'GC EAD', 'USC', 'L2S'
                        ];
                        if (k === 'Relocation') opts = ['YES', 'NO'];
                        if (k === '1st Follow Up Remarks') opts = ['Interested', 'Doubt need Clarification',
                            'Money Issue', 'Not Interested', "Don't Call"
                        ];
                        if (k === 'Course') opts = ['BA', 'DA', 'SAS', 'JAVA', 'QA', 'SQL', 'PYTHON',
                            'DOT NET'
                        ];
                        if (k === 'Time Zone') opts = ['EST', 'CST', 'MST', 'PST'];
                        cells +=
                            `<td><select class="form-select dynamic-dropdown" data-key="${k}"><option value="" disabled selected>-- Select ${k} --</option>${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}</select></td>`;
                    } else if (k === 'Amount') {
                        cells +=
                            `<td><input type="text" class="form-control amount-input" data-key="${k}" placeholder="Amount(469)"></td>`;
                    } else if (k === 'Location') {
                        cells +=
                            `<td><input type="text" class="form-control location-autocomplete" data-key="${k}" placeholder="Location"><span class="small-hint"></span></td>`;
                    } else if (k === 'Remark') {
                        cells +=
                            `<td><input type="text" class="form-control Remark-autocomplete" data-key="${k}" placeholder="Remark"><span class="small-hint"></span></td>`;
                    } else if (k === 'Date' || k === 'Graduation Date') {
                        cells +=
                            `<td><input type="text" class="form-control date-picker" data-key="${k}" placeholder="${k} (MM/DD/YYYY)"><span class="small-hint"></span></td>`;
                    } else if (k === 'Phone Number') {
                        cells +=
                            `<td><input type="tel" class="form-control phone-input" data-key="${k}" maxlength="12" placeholder="US number"><span class="phone-hint"></span></td>`;
                    } else if (k === 'Email Address') {
                        cells +=
                            `<td><input type="email" class="form-control email-input" data-key="${k}" placeholder="Email"><span class="small-hint"></span></td>`;
                    } else if (k === 'Name') {
                        cells +=
                            `<td><input type="text" class="form-control name-input" data-key="${k}" placeholder="Name"><span class="small-hint"></span></td>`;
                    } else if (k === 'View') {
                        cells += `<td>
            <input type="file" accept=".pdf, .doc, .docx" class="d-none resume-input" data-key="View">
            <button type="button" class="btn btn-sm btn-info upload-btn">Upload</button>
            <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View File</a>
            <a href="#" download class="btn btn-sm btn-secondary download-btn d-none">Download</a>
        </td>`;
                    }

                });

                cells +=
                    `<td><button class="btn btn-sm btn-success save-btn" data-id="new"><i class="fas fa-save"></i> Save</button></td>`;
                newRow.innerHTML = cells;
                tableBody.appendChild(newRow);
                applyInitialState(newRow);
            }

            // Check if we need to add a blank row on page load
            // Only add if there are no existing "new" rows
            const hasNewRow = tableBody.querySelector('tr[data-id="new"]');
            const hasAnyRows = tableBody.querySelector('tr');

            if (!hasNewRow && !hasAnyRows) {
                // No rows at all - add one blank row
                addBlankRow();
            } else if (!hasNewRow && hasAnyRows) {
                // Has existing rows but no "new" row - add blank row at the end
                addBlankRow();
            }
            // If hasNewRow exists, we don't need to add another one

            // Handle select color changes
            tableBody.addEventListener('change', function(e) {
                if (e.target.matches('select.dynamic-dropdown')) updateSelectColor(e.target);
            });

            // Event delegation for save buttons (handles both existing and dynamically added buttons)
            tableBody.addEventListener('click', function(e) {
                if (e.target.matches('.save-btn') || e.target.closest('.save-btn')) {
                    e.preventDefault();
                    let saveBtn = e.target.matches('.save-btn') ? e.target : e.target.closest('.save-btn');
                    let id = saveBtn.dataset.id;
                    let row = saveBtn.closest("tr");
                    console.log("Saving row with id:", id);

                    // Collect all data from the row
                    let rowData = {};
                    row.querySelectorAll("input[data-key], select[data-key]").forEach(cell => {
                        let key = cell.dataset.key;
                        let value = cell.value;
                        rowData[key] = value;
                    });
                    console.log("Row data:", rowData);

                    // Create FormData object
                    let formData = new FormData();
                    formData.append("data", JSON.stringify(rowData));
                    formData.append("_token", "{{ csrf_token() }}");

                    // Handle resume file upload
                    let resumeInput = row.querySelector("input.resume-input");
                    if (resumeInput && resumeInput.files.length > 0) {
                        formData.append("resume", resumeInput.files[0]);
                    }

                    // Determine URL and method
                    let url, method;
                    if (id === "new") {
                        url = "";
                        method = "POST";
                    } else {
                        url = "";
                        method = "POST";
                        formData.append("id", id);
                    }

                    console.log("Sending to:", url, "Method:", method);

                    // Send the request
                    fetch(url, {
                            method: method,
                            body: formData
                        })
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`HTTP error! status: ${res.status}`);
                            }
                            return res.json();
                        })
                        // In the save button click event handler, update the success callback:
                        .then(data => {
                            console.log("Response from server:", data);
                            if (data.success) {
                                alert("Saved successfully");
                                if (id === "new") {
                                    // Update row with new ID
                                    row.dataset.id = data.id;
                                    saveBtn.dataset.id = data.id;
                                    row.querySelector("td:first-child").innerText = data
                                        .sheet_row_number;

                                    const viewBtn = row.querySelector('.view-btn');
                                    const downloadBtn = row.querySelector('.download-btn');

                                    if (viewBtn && data.resume_path) {
                                        viewBtn.href =
                                            `/dashboard/junior/google-sheet/view-resume/${data.id}`;
                                        viewBtn.classList.remove('d-none');
                                    }

                                    if (downloadBtn && data.resume_path) {
                                        downloadBtn.href =
                                            `/dashboard/junior/google-sheet/download-resume/${data.id}`;
                                        downloadBtn.classList.remove('d-none');
                                    }

                                    // Only add new blank row if none exists
                                    const existingNewRows = tableBody.querySelectorAll(
                                        'tr[data-id="new"]');
                                    if (existingNewRows.length === 0) {
                                        addBlankRow();
                                    }
                                }

                            } else {
                                console.error("Server error:", data.message);
                                alert("Error: " + (data.message || "Unknown error"));
                            }
                        })
                        .catch(err => {
                            console.error("Fetch error:", err);
                            alert("Save failed. Check console for details.");
                        });
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <script>
        $(document).ready(function() {

            function debounce(fn, delay) {
                let timer;
                return function() {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, arguments), delay);
                };
            }

            function fetchTable(search = '', page = 1, junior_user = '', row_id = '') {
                $.ajax({
                    url: "{{ route('google.sheet.seniorsearch') }}",
                    data: {
                        search,
                        page,
                        junior_user,
                        row_id
                    },
                    success: function(res) {
                        $('#senior-table-wrapper').html(res);
                    }
                });
            }

            // 🔍 LIVE SEARCH SUGGESTIONS
            const showSuggestions = debounce(function() {

                const query = $('#senior-search').val().trim();
                const junior_user = $('#junior-filter').val();

                if (query.length < 3) {
                    $('#search-suggestions').hide().empty();
                    fetchTable('', 1, junior_user);
                    return;
                }

                $.ajax({
                    url: "{{ route('seniorsearch.suggestions') }}",
                    data: {
                        query,
                        junior_user
                    },
                    success: function(res) {

                        let html = '';

                        if (res.length) {
                            res.forEach(item => {
                                html += `
                        <a href="#" class="list-group-item list-group-item-action"
                           data-id="${item.id}">
                            ${item.sheet_row_number} | ${item.Name} | ${item.Email_Address} |
                            ${item.Phone_Number} | ${item.forwarded_by}
                        </a>`;
                            });
                        } else {
                            html = '<span class="list-group-item">No results found</span>';
                        }

                        $('#search-suggestions').html(html).show();
                    }
                });

            }, 300);

            $('#senior-search').on('input', showSuggestions);

            // 📌 CLICK SUGGESTION
            $(document).on('click', '#search-suggestions a', function(e) {
                e.preventDefault();

                const rowId = $(this).data('id');
                const junior_user = $('#junior-filter').val();

                $('#search-suggestions').hide().empty();
                fetchTable('', 1, junior_user, rowId);
            });

            // 👤 JUNIOR FILTER
            $('#junior-filter').on('change', function() {
                fetchTable($('#senior-search').val(), 1, this.value);
            });

            // ❌ HIDE SUGGESTIONS
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#senior-search, #search-suggestions').length) {
                    $('#search-suggestions').hide().empty();
                }
            });

        });
    </script>

    <script>
        document.getElementById('junior-filter').addEventListener('change', function() {
            let juniorId = this.value;
            let search = document.getElementById('senior-search').value;

            fetch("{{ route('google.sheet.seniorsearch') }}?junior_user=" + juniorId + "&search=" + search, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('senior-table-wrapper').innerHTML = html;
                });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection
