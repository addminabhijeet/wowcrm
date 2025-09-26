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

<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
            <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                <option>10</option>
            </select>
            <form class="navbar-search">
                <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search">
                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
            </form>
            <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                <option>Status</option>
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>
    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            @if($data->isEmpty())
            <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
            @else
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th scope="col">Row #</th>
                        <th scope="col">Date</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email Address</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Location</th>
                        <th scope="col">Relocation</th>
                        <th scope="col">Graduation Date</th>
                        <th scope="col">Immigration</th>
                        <th scope="col">Course</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Qualification</th>
                        <th scope="col">Exe Remarks</th>
                        <th scope="col">1st Follow Up Remarks</th>
                        <th scope="col">Time Zone</th>
                        <th scope="col">View</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="sheet-table-body">
                    @foreach($data as $row)
                    <tr id="row-{{ $row->id }}" data-id="{{ $row->id }}">

                        <td>{{ $row->sheet_row_number }}</td>

                        {{-- Date --}}
                        <td>
                            <input type="text" class="form-control date-picker" data-key="Date"
                                value="{{ $row->Date ? \Carbon\Carbon::parse($row->Date)->format('m/d/Y') : '' }}">
                        </td>

                        {{-- Name --}}
                        <td>
                            <input type="text" class="form-control name-input" data-key="Name"
                                value="{{ $row->Name ?? '' }}" placeholder="Name">
                        </td>

                        {{-- Email Address --}}
                        <td>
                            <input type="email" class="form-control email-input" data-key="Email Address"
                                value="{{ $row->Email_Address ?? '' }}" placeholder="E-mail">
                        </td>

                        {{-- Phone Number --}}
                        <td>
                            <input type="tel" class="form-control phone-input" data-key="Phone Number"
                                maxlength="12" value="{{ $row->Phone_Number ?? '' }}" placeholder="US number">
                        </td>

                        {{-- Location --}}
                        <td>
                            <input type="text" class="form-control location-autocomplete" data-key="Location"
                                value="{{ $row->Location ?? '' }}" placeholder="Type location">
                        </td>

                        {{-- Relocation --}}
                        <td>
                            @php $relOptions = ['YES','NO']; @endphp
                            <select class="form-select dynamic-dropdown" data-key="Relocation">
                                <option value="">-- Select --</option>
                                @foreach($relOptions as $option)
                                <option value="{{ $option }}" {{ $row->Relocation === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        {{-- Graduation Date --}}
                        <td>
                            <input type="text" class="form-control date-picker" data-key="Graduation Date"
                                value="{{ $row->Graduation_Date ? \Carbon\Carbon::parse($row->Graduation_Date)->format('m/d/Y') : '' }}">
                        </td>

                        {{-- Immigration --}}
                        <td>
                            @php $immOptions = ['Dependent Visa','Global Visa','Graduate Visa','Student Visa','Citizen','Permanent Residence(ILR)']; @endphp
                            <select class="form-select dynamic-dropdown" data-key="Immigration">
                                <option value="">-- Select --</option>
                                @foreach($immOptions as $option)
                                <option value="{{ $option }}" {{ $row->Immigration === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        {{-- Course --}}
                        <td>
                            @php $courseOptions = ['BA','SAS','JAVA','QA','SQL','PYTHON','DOT NET']; @endphp
                            <select class="form-select dynamic-dropdown" data-key="Course">
                                <option value="">-- Select --</option>
                                @foreach($courseOptions as $option)
                                <option value="{{ $option }}" {{ $row->Course === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        {{-- Amount --}}
                        <td>
                            <input type="text" class="form-control amount-input" data-key="Amount"
                                value="{{ $row->Amount ? '$' . number_format($row->Amount, 2) : '' }}" placeholder="$100">
                        </td>

                        {{-- Qualification --}}
                        <td>
                            <input type="text" class="form-control qualification-input" data-key="Qualification"
                                value="{{ $row->Qualification ?? '' }}" placeholder="Qualification">
                        </td>

                        {{-- Exe Remarks --}}
                        <td>
                            @php $exeOptions = ['Called & Mailed','Not Interested','Others','N/A','VM','Busy']; @endphp
                            <select class="form-select dynamic-dropdown" data-key="Exe Remarks">
                                <option value="">-- Select --</option>
                                @foreach($exeOptions as $option)
                                <option value="{{ $option }}" {{ $row->Exe_Remarks === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        {{-- 1st Follow Up Remarks --}}
                        <td>
                            @php $followOptions = ['Interested','Doubt need Clarification','Money Issue','Not Interested','Don\'t Call']; @endphp
                            <select class="form-select dynamic-dropdown" data-key="1st Follow Up Remarks">
                                <option value="">-- Select --</option>
                                @foreach($followOptions as $option)
                                <option value="{{ $option }}" {{ $row->First_Follow_Up_Remarks === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        {{-- Time Zone --}}
                        <td>
                            @php $timezoneOptions = ['EST','CST','MST','PST']; @endphp
                            <select class="form-select dynamic-dropdown" data-key="Time Zone">
                                <option value="">-- Select --</option>
                                @foreach($timezoneOptions as $option)
                                <option value="{{ $option }}" {{ $row->Time_Zone === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </td>

                        {{-- View (Resume) --}}
                        <td>
                            <input type="file" accept="application/pdf" class="d-none resume-input" data-key="View">
                            <button type="button" class="btn btn-sm btn-info upload-btn">
                                {{ !empty($row->resume) ? 'Change File' : 'Upload' }}
                            </button>

                            @if(!empty($row->resume))
                            <a href="{{ asset('storage/resumes/' . $row->resume) }}" target="_blank" class="btn btn-sm btn-primary view-btn">View PDF</a>
                            <a href="{{ asset('storage/resumes/' . $row->resume) }}" download class="btn btn-sm btn-secondary download-btn">Download</a>
                            @else
                            <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View PDF</a>
                            <a href="#" download class="btn btn-sm btn-secondary download-btn d-none">Download</a>
                            @endif
                        </td>

                        <td class="text-center">
                            <button class="btn btn-sm btn-success save-btn" data-id="{{ $row->id }}">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        {{-- Pagination --}}
        @if($data->hasPages())
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <div>
                {{ $data->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .input-hint {
        font-size: .85rem;
        color: #6c757d;
    }

    select.dynamic-dropdown {
        min-width: 160px;
    }

    input.valid {
        background-color: #d4edda;
    }

    input.invalid {
        background-color: #f8d7da;
    }

    input.neutral {
        background-color: #ffffff;
    }

    select.neutral {
        background-color: #ffffff;
    }

    select.valid {
        background-color: #d4edda;
    }

    .phone-hint {
        font-size: .8rem;
        color: #6c757d;
        margin-top: 3px;
        display: block;
    }

    .small-hint {
        font-size: .8rem;
        color: #6c757d;
        display: block;
        margin-top: 2px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tableBody = document.getElementById("sheet-table-body");

        const exeColors = {
            'Called & Mailed': '#d4edda',
            'Not Interested': '#f8d7da',
            'Others': '#d1ecf1',
            'N/A': '#e2e3e5',
            'VM': '#fff3cd',
            'Busy': '#cce5ff'
        };
        const immColors = {
            'Dependent Visa': '#d1ecf1',
            'Global Visa': '#cce5ff',
            'Graduate Visa': '#d4edda',
            'Student Visa': '#fff3cd',
            'Citizen': '#e2e3e5',
            'Permanent Residence(ILR)': '#f8d7da'
        };
        const relColors = {
            'YES': '#d4edda',
            'NO': '#f8d7da'
        };
        const followColors = {
            'Interested': '#d4edda',
            'Doubt need Clarification': '#fff3cd',
            'Money Issue': '#f8d7da',
            'Not Interested': '#f8d7da',
            "Don't Call": '#e2e3e5'
        };
        const courseColors = {
            'BA': '#e2f0d9',
            'SAS': '#d1ecf1',
            'JAVA': '#cce5ff',
            'QA': '#fff3cd',
            'SQL': '#fbe7d0',
            'PYTHON': '#d4edda',
            'DOT NET': '#f8d7da'
        };
        const timezoneColors = {
            'EST': '#e2f0d9',
            'CST': '#d1ecf1',
            'MST': '#cce5ff',
            'PST': '#fff3cd'
        };
        const dateColor = "#e0f7fa";
        const amountColors = "#e0f7fa";

        function updateSelectColor(select) {
            const val = select.value;
            const key = select.dataset.key;
            let color = '#ffffff';
            if (key === 'Exe Remarks') color = exeColors[val] || color;
            else if (key === 'Immigration') color = immColors[val] || color;
            else if (key === 'Relocation') color = relColors[val] || color;
            else if (key === '1st Follow Up Remarks') color = followColors[val] || color;
            else if (key === 'Course') color = courseColors[val] || color;
            else if (key === 'Time Zone') color = timezoneColors[val] || color;
            select.style.backgroundColor = color;
        }

        function formatPhoneNumber(value) {
            const digits = value.replace(/\D/g, "").slice(0, 10);
            const part1 = digits.slice(0, 3),
                part2 = digits.slice(3, 6),
                part3 = digits.slice(6, 10);
            if (digits.length > 6) return `${part1}-${part2}-${part3}`;
            if (digits.length > 3) return `${part1}-${part2}`;
            if (digits.length > 0) return part1;
            return "";
        }

        function validatePhoneInput(inp) {
            const v = inp.value.replace(/\D/g, "");
            if (v.length === 10) {
                inp.classList.remove("invalid");
                inp.classList.add("valid");
            } else if (v.length === 0) {
                inp.classList.remove("invalid");
                inp.classList.remove("valid");
                inp.classList.add("neutral");
            } else {
                inp.classList.add("invalid");
                inp.classList.remove("valid");
            }
        }

        function validateEmailInput(inp) {
            const v = inp.value;
            const lower = v === v.toLowerCase();
            const ok = /^[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/.test(v) && lower;
            if (ok) {
                inp.classList.remove('invalid');
                inp.classList.add('valid');
            } else if (v.length === 0) {
                inp.classList.remove('invalid');
                inp.classList.remove('valid');
                inp.classList.add('neutral');
            } else {
                inp.classList.add('invalid');
                inp.classList.remove('valid');
            }
        }

        function validateNameInput(inp) {
            const v = inp.value;
            const ok = /^[a-zA-Z\s]+$/.test(v) && v.length > 0;
            if (ok) {
                inp.classList.remove('invalid');
                inp.classList.add('valid');
            } else if (v.length === 0) {
                inp.classList.remove('invalid');
                inp.classList.remove('valid');
                inp.classList.add('neutral');
            } else {
                inp.classList.add('invalid');
                inp.classList.remove('valid');
            }
        }

        function validateQualificationInput(inp) {
            const v = inp.value;
            const ok = /^[A-Z\s]+$/.test(v) && v.length > 0;
            if (ok) {
                inp.classList.remove('invalid');
                inp.classList.add('valid');
            } else if (v.length === 0) {
                inp.classList.remove('invalid');
                inp.classList.remove('valid');
                inp.classList.add('neutral');
            } else {
                inp.classList.add('invalid');
                inp.classList.remove('valid');
            }
        }

        function validateAmountInput(inp) {
            let v = inp.value.trim();
            if (v !== "" && !v.startsWith("$")) {
                v = "$" + v.replace(/[^0-9]/g, "");
            }
            v = "$" + v.slice(1).replace(/[^0-9]/g, "");
            inp.value = v;
            if (/^\$\d+$/.test(v)) {
                inp.classList.remove("invalid");
                inp.classList.add("valid");
            } else if (v === "$") {
                inp.classList.remove("invalid");
                inp.classList.remove("valid");
                inp.classList.add("neutral");
            } else {
                inp.classList.add("invalid");
                inp.classList.remove("valid");
            }
        }

        function initDatePickers(context = document) {
            context.querySelectorAll('input.date-picker').forEach(input => {
                const key = input.dataset.key;
                const opts = {
                    dateFormat: "m/d/Y",
                    allowInput: true,
                    onChange: function(selectedDates, dateStr) {
                        input.style.backgroundColor = dateStr ? dateColor : '#fff';
                    },
                    onReady: function(selectedDates, dateStr) {
                        if (input.value) input.style.backgroundColor = dateColor;
                    }
                };
                if (key === "Graduation Date") opts.maxDate = "today";
                if (key === "Date") opts.minDate = "today";
                flatpickr(input, opts);
                input.addEventListener('blur', function() {
                    if (input.value && !/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(input.value)) {
                        input.style.backgroundColor = '#fff';
                    }
                });
            });
        }

        function initLocationAutocomplete(context = document) {
            $(context).find('input.location-autocomplete').each(function() {
                const $input = $(this);
                $input.on('input', function() {
                    const q = $(this).val().trim();
                    if (q.length < 2) {
                        $('#loc-suggestions').remove();
                        return;
                    }
                    const key = 'pk.e91481c6e5f0a93703159ae988e641a0';
                    $.getJSON(`https://us1.locationiq.com/v1/autocomplete.php?key=${key}&q=${encodeURIComponent(q)}&limit=5&dedupe=1&normalizecity=1&accept-language=en`)
                        .done(function(results) {
                            $('#loc-suggestions').remove();
                            const $list = $('<div id="loc-suggestions" class="list-group" style="position:absolute; z-index:9999; max-height:200px; overflow:auto;"></div>');
                            results.forEach(r => {
                                const addr = r.address || {};
                                const city = addr.city || addr.town || addr.village || '';
                                const state = addr.state || addr.region || '';
                                const country = addr.country || '';
                                const display = [city, state, country].filter(Boolean).join(', ');
                                const item = $('<a href="#" class="list-group-item list-group-item-action"></a>').text(display || r.display_name);
                                item.on('click', function(e) {
                                    e.preventDefault();
                                    $input.val(display || r.display_name);
                                    $input.css('background-color', '#d4edda');
                                    $('#loc-suggestions').remove();
                                });
                                $list.append(item);
                            });
                            $('body').append($list);
                            const offset = $input.offset();
                            $list.css({
                                top: offset.top + $input.outerHeight(),
                                left: offset.left,
                                width: $input.outerWidth()
                            });
                        })
                        .fail(function() {
                            $('#loc-suggestions').remove();
                        });
                });
                $input.on('blur', function() {
                    setTimeout(() => $('#loc-suggestions').remove(), 200);
                });
            });
        }

        function applyInitialState(context = document) {
            context.querySelectorAll('select.dynamic-dropdown').forEach(s => updateSelectColor(s));
            initDatePickers(context);
            initLocationAutocomplete(context);
            context.querySelectorAll('input.amount-input').forEach(i => {
                validateAmountInput(i);
                i.addEventListener('input', () => validateAmountInput(i));
            });
            context.querySelectorAll("input.phone-input").forEach(i => {
                i.value = formatPhoneNumber(i.value);
                validatePhoneInput(i);
                i.addEventListener("input", () => {
                    i.value = formatPhoneNumber(i.value);
                    validatePhoneInput(i);
                });
            });
            context.querySelectorAll('input.email-input').forEach(i => {
                validateEmailInput(i);
                i.addEventListener('input', () => {
                    i.value = i.value.toLowerCase();
                    validateEmailInput(i);
                });
            });
            context.querySelectorAll('input.name-input').forEach(i => {
                validateNameInput(i);
                i.addEventListener('input', () => {
                    i.value = i.value.toLowerCase().replace(/[^a-zA-Z\s]/g, '');
                    validateNameInput(i);
                });
            });
            context.querySelectorAll('input.qualification-input').forEach(i => {
                validateQualificationInput(i);
                i.addEventListener('input', () => {
                    i.value = i.value.toUpperCase().replace(/[^A-Z\s]/g, '');
                    validateQualificationInput(i);
                });
            });
        }

        function addBlankRow() {
            let colKeys = [];
            let firstRow = tableBody.querySelector("tr");
            if (firstRow) {
                firstRow.querySelectorAll("input[data-key], select[data-key]").forEach(cell => colKeys.push(cell.dataset.key));
            }

            let newRow = document.createElement("tr");
            newRow.setAttribute("data-id", "new");
            let cells = `<td>—</td>`;

            colKeys.forEach(k => {
                if (['Exe Remarks', 'Immigration', 'Relocation', '1st Follow Up Remarks', 'Course', 'Time Zone'].includes(k)) {
                    let opts = [];
                    if (k === 'Exe Remarks') opts = ['Called & Mailed', 'Not Interested', 'Others', 'N/A', 'VM', 'Busy'];
                    if (k === 'Immigration') opts = ['Dependent Visa', 'Global Visa', 'Graduate Visa', 'Student Visa', 'Citizen', 'Permanent Residence(ILR)'];
                    if (k === 'Relocation') opts = ['YES', 'NO'];
                    if (k === '1st Follow Up Remarks') opts = ['Interested', 'Doubt need Clarification', 'Money Issue', 'Not Interested', "Don't Call"];
                    if (k === 'Course') opts = ['BA', 'SAS', 'JAVA', 'QA', 'SQL', 'PYTHON', 'DOT NET'];
                    if (k === 'Time Zone') opts = ['EST', 'CST', 'MST', 'PST'];
                    cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}"><option value="" disabled selected>-- Select ${k} --</option>${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}</select></td>`;
                } else if (k === 'Amount') {
                    cells += `<td><input type="text" class="form-control amount-input" data-key="${k}" placeholder="$100"></td>`;
                } else if (k === 'Location') {
                    cells += `<td><input type="text" class="form-control location-autocomplete" data-key="${k}" placeholder="Location"><span class="small-hint"></span></td>`;
                } else if (k === 'Date' || k === 'Graduation Date') {
                    cells += `<td><input type="text" class="form-control date-picker" data-key="${k}" placeholder="${k} (MM/DD/YYYY)"><span class="small-hint"></span></td>`;
                } else if (k === 'Phone Number') {
                    cells += `<td><input type="tel" class="form-control phone-input" data-key="${k}" maxlength="12" placeholder="US number"><span class="phone-hint"></span></td>`;
                } else if (k === 'Email Address') {
                    cells += `<td><input type="email" class="form-control email-input" data-key="${k}" placeholder="Email"><span class="small-hint"></span></td>`;
                } else if (k === 'Name') {
                    cells += `<td><input type="text" class="form-control name-input" data-key="${k}" placeholder="Name"><span class="small-hint"></span></td>`;
                } else if (k === 'Qualification') {
                    cells += `<td><input type="text" class="form-control qualification-input" data-key="${k}" placeholder="Qualification"><span class="small-hint"></span></td>`;
                } else if (k === 'View') {
                    cells += `<td>
                    <input type="file" accept="application/pdf" class="d-none resume-input" data-key="View">
                    <button type="button" class="btn btn-sm btn-info upload-btn">Upload</button>
                    <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View PDF</a>
                    <a href="#" download class="btn btn-sm btn-secondary download-btn d-none">Download</a>
                </td>`;
                }
            });

            cells += `<td><button class="btn btn-sm btn-success save-btn" data-id="new"><i class="fas fa-save"></i> Save</button></td>`;
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
                    url = "{{ route('juniorstore') }}";
                    method = "POST";
                } else {
                    url = "{{ route('juniorupdate') }}";
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
                                // Update the row with the new ID
                                row.dataset.id = data.id;
                                saveBtn.dataset.id = data.id;
                                row.querySelector("td:first-child").innerText = data.sheet_row_number;

                                // Update resume buttons with correct URLs
                                const viewBtn = row.querySelector('.view-btn');
                                const downloadBtn = row.querySelector('.download-btn');

                                if (viewBtn && data.resume_path) {
                                    viewBtn.href = `/dashboard/junior/google-sheet/view-resume/${data.id}`;
                                    viewBtn.classList.remove('d-none');
                                }

                                if (downloadBtn && data.resume_path) {
                                    downloadBtn.href = `/dashboard/junior/google-sheet/download-resume/${data.id}`;
                                    downloadBtn.classList.remove('d-none');
                                }

                                // Only add a new blank row if this was a new row being saved
                                const existingNewRows = tableBody.querySelectorAll('tr[data-id="new"]');
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

        // Handle file upload button clicks
        tableBody.addEventListener('click', function(e) {
            if (e.target.matches('.upload-btn')) {
                const row = e.target.closest('tr');
                const fileInput = row.querySelector('.resume-input');
                fileInput.click();
            }

            // Handle view and download buttons for unsaved rows
            if (e.target.matches('.view-btn') || e.target.matches('.download-btn')) {
                const row = e.target.closest('tr');
                const id = row.dataset.id;

                if (id === "new") {
                    e.preventDefault();
                    alert("Please save the row first before viewing/downloading the resume.");
                    return;
                }
            }
        });

        // Handle file selection
        tableBody.addEventListener('change', function(e) {
            if (e.target.matches('.resume-input')) {
                const row = e.target.closest('tr');
                const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';

                // Show view and download buttons temporarily
                const viewBtn = row.querySelector('.view-btn');
                const downloadBtn = row.querySelector('.download-btn');

                if (viewBtn) viewBtn.classList.remove('d-none');
                if (downloadBtn) downloadBtn.classList.remove('d-none');

                // Update button text
                const uploadBtn = row.querySelector('.upload-btn');
                if (uploadBtn) uploadBtn.textContent = 'Change File';

                console.log('File selected:', fileName);
            }
        });

        // Apply initial state to all existing rows
        applyInitialState(document);

        // Cleanup location suggestions
        document.addEventListener('click', function(e) {
            if (!$(e.target).closest('#loc-suggestions, .location-autocomplete').length)
                $('#loc-suggestions').remove();
        });

        // Real-time validation
        tableBody.addEventListener('input', function(e) {
            if (e.target.matches('input.phone-input')) validatePhoneInput(e.target);
            if (e.target.matches('input.email-input')) {
                e.target.value = e.target.value.toLowerCase();
                validateEmailInput(e.target);
            }
            if (e.target.matches('input.name-input')) {
                let v = e.target.value.replace(/[^a-zA-Z\s]/g, '');
                v = v.toLowerCase().replace(/\b\w/g, c => c.toUpperCase());
                e.target.value = v;
                validateNameInput(e.target);
            }
            if (e.target.matches('input.qualification-input')) {
                e.target.value = e.target.value.toUpperCase().replace(/[^A-Z\s]/g, '');
                validateQualificationInput(e.target);
            }
        });
    });
</script>
@endsection