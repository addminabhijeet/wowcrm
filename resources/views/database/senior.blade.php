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

                    <!-- Fetch Google Sheet Form -->
                    <!-- <form method="POST" action="" class="d-flex flex-wrap gap-3 mb-4">
                        @csrf
                        <input type="url" name="sheet_link" id="sheet_link"
                            class="form-control flex-grow-1 h-40-px"
                            placeholder="Paste Google Sheet URL" required>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="fas fa-sync"></i> Fetch
                        </button>
                    </form> -->

                    <!-- Data Table -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
/* small input styling for visual consistency */
.input-hint { font-size: .85rem; color: #6c757d; }
select.dynamic-dropdown { min-width: 160px; }
input.valid { background-color: #d4edda; }      /* green */
input.invalid { background-color: #f8d7da; }    /* red */
input.neutral { background-color: #ffffff; }
select.neutral { background-color: #ffffff; }
select.valid { background-color: #d4edda; }
.phone-hint { font-size: .8rem; color:#6c757d; margin-top:3px; display:block; }
.small-hint { font-size:.8rem; color:#6c757d; display:block; margin-top:2px; }


</style>

<div class="table-responsive scroll-sm">
    @if($data->isEmpty())
        <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
    @else
        <table class="table bordered-table sm-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Row #</th>
                    @foreach(array_keys(json_decode($data->first()->data, true)) as $col)
                        <th>{{ $col }}</th>
                    @endforeach
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="sheet-table-body">
                @foreach($data as $row)
                    @php $decoded = json_decode($row->data, true); @endphp
                    <tr id="row-{{ $row->id }}" data-id="{{ $row->id }}">
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->sheet_row_number }}</td>

                        @foreach($decoded as $key => $val)
                            @php
                                $exeOptions = ['Called & Mailed','Not Interested','Others','N/A','VM','Busy'];
                                $immOptions = ['Dependent Visa','Global Visa','Graduate Visa','Student Visa','Citizen','Permanent Residence(ILR)'];
                                $relOptions = ['YES','NO'];
                                $followOptions = ['Interested','Doubt need Clarification','Money Issue','Not Interested','Don\'t Call'];
                                $courseOptions = ['BA','SAS','JAVA','QA','SQL','PYTHON','DOT NET'];
                                $timezoneOptions = ['EST','CST','MST','PST'];
                            @endphp

                            {{-- Dropdown columns --}}
                            @if($key === 'Exe Remarks')
                                <td>
                                    <select class="form-select dynamic-dropdown" data-key="{{ $key }}">
                                        @foreach($exeOptions as $option)
                                            <option value="{{ $option }}" {{ $val === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            @elseif($key === 'Immigration')
                                <td>
                                    <select class="form-select dynamic-dropdown" data-key="{{ $key }}">
                                        @foreach($immOptions as $option)
                                            <option value="{{ $option }}" {{ $val === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            @elseif($key === 'Relocation')
                                <td>
                                    <select class="form-select dynamic-dropdown" data-key="{{ $key }}">
                                        @foreach($relOptions as $option)
                                            <option value="{{ $option }}" {{ $val === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            @elseif($key === '1st Follow Up Remarks')
                                <td>
                                    <select class="form-select dynamic-dropdown" data-key="{{ $key }}">
                                        @foreach($followOptions as $option)
                                            <option value="{{ $option }}" {{ $val === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            @elseif($key === 'Course')
                                <td>
                                    <select class="form-select dynamic-dropdown" data-key="{{ $key }}">
                                        @foreach($courseOptions as $option)
                                            <option value="{{ $option }}" {{ $val === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            @elseif($key === 'Time Zone')
                                <td>
                                    <select class="form-select dynamic-dropdown" data-key="{{ $key }}">
                                        @foreach($timezoneOptions as $option)
                                            <option value="{{ $option }}" {{ $val === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            
                            @elseif($key === 'View')
                            <td>
                                <input type="file" accept="application/pdf" class="d-none resume-input" data-key="{{ $key }}">
                                <button type="button" class="btn btn-sm btn-info upload-btn">Upload</button>

                                @if(!empty($row->resume))
                                    <a href="{{ asset('storage/resumes/' . $row->resume) }}" target="_blank" class="btn btn-sm btn-primary view-btn">View PDF</a>
                                    <a href="{{ asset('storage/resumes/' . $row->resume) }}" download class="btn btn-sm btn-secondary download-btn">Download</a>
                                @else
                                    <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View PDF</a>
                                    <a href="#" download class="btn btn-sm btn-secondary download-btn d-none">Download</a>
                                @endif
                            </td>


                            @elseif($key === 'Amount')
                                <td>
                                    <input type="text" class="form-control amount-input" data-key="{{ $key }}" 
                                        value="{{ $val ?? '' }}" placeholder="$100">
                                </td>

                            @elseif($key === 'Location')
                                <td>
                                    <input type="text" class="form-control location-autocomplete" data-key="{{ $key }}" 
                                        value="{{ $val ?? '' }}" placeholder="Type location (e.g. Aus or Delaw)">
                                </td>


                            {{-- Date fields --}}
                            @elseif($key === 'Date' || $key === 'Graduation Date')
                                <td>
                                    <input type="text" class="form-control date-picker" data-key="{{ $key }}" value="{{ $val ?? '' }}">
                                </td>

                            {{-- Phone --}}
                            @elseif($key === 'Phone Number')
                                <td>
                                    <input type="tel" class="form-control phone-input" data-key="{{ $key }}" maxlength="12" value="{{ $val ?? '' }}" placeholder="US number">
                                    
                                </td>

                            {{-- Email --}}
                            @elseif($key === 'Email Address')
                                <td>
                                    <input type="email" class="form-control email-input" data-key="{{ $key }}" value="{{ $val ?? '' }}" placeholder="E-mail">
                                    
                                </td>

                            {{-- Name --}}
                            @elseif($key === 'Name')
                                <td>
                                    <input type="text" class="form-control name-input" data-key="{{ $key }}" value="{{ $val ?? '' }}" placeholder="Name">
                                    
                                </td>

                            {{-- Qualification	 --}}
                            @elseif($key === 'Qualification')
                                <td>
                                    <input type="text" class="form-control qualification-input" data-key="{{ $key }}" value="{{ $val ?? '' }}" placeholder="Qualification">
                                    
                                </td>

                            {{-- default editable --}}
                            @else
                                <td contenteditable="true" data-key="{{ $key }}">{{ $val }}</td>
                            @endif

                        @endforeach

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

@endsection

<!-- JS libs -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("sheet-table-body");

    /* Color maps */
    const exeColors = { 'Called & Mailed': '#d4edda', 'Not Interested': '#f8d7da', 'Others': '#d1ecf1', 'N/A': '#e2e3e5', 'VM': '#fff3cd', 'Busy': '#cce5ff' };
    const immColors = { 'Dependent Visa': '#d1ecf1', 'Global Visa': '#cce5ff', 'Graduate Visa': '#d4edda', 'Student Visa': '#fff3cd', 'Citizen': '#e2e3e5', 'Permanent Residence(ILR)': '#f8d7da' };
    const relColors = { 'YES': '#d4edda', 'NO': '#f8d7da' };
    const followColors = { 'Interested': '#d4edda','Doubt need Clarification': '#fff3cd','Money Issue': '#f8d7da','Not Interested': '#f8d7da',"Don't Call": '#e2e3e5' };
    const courseColors = { 'BA':'#e2f0d9','SAS':'#d1ecf1','JAVA':'#cce5ff','QA':'#fff3cd','SQL':'#fbe7d0','PYTHON':'#d4edda','DOT NET':'#f8d7da' };
    const timezoneColors = { 'EST':'#e2f0d9','CST':'#d1ecf1','MST':'#cce5ff','PST':'#fff3cd' };

    // Date highlight color
    const dateColor = "#e0f7fa"; // light blue
    const amountColors = "#e0f7fa"; // light blue

    /* Helper to update select background */
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
        else if (key === 'Amount') color = amountColors[val] || color;
        select.style.backgroundColor = color;
    }

    /* Helper to format & validate phone (US 123-456-7890) */
    function formatPhoneNumber(value) {
        const digits = value.replace(/\D/g, "").slice(0, 10); // only 10 digits max
        const part1 = digits.slice(0, 3);
        const part2 = digits.slice(3, 6);
        const part3 = digits.slice(6, 10);
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


    /* Helper to validate email (lowercase only) */
    function validateEmailInput(inp) {
        const v = inp.value;
        const lower = v === v.toLowerCase();
        const ok = /^[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$/.test(v) && lower;
        if (ok) { inp.classList.remove('invalid'); inp.classList.add('valid'); }
        else if (v.length === 0) { inp.classList.remove('invalid'); inp.classList.remove('valid'); inp.classList.add('neutral'); }
        else { inp.classList.add('invalid'); inp.classList.remove('valid'); }
    }

    /* Helper to validate name (lowercase letters + spaces) */
    function validateNameInput(inp) {
        const v = inp.value;
        const ok = /^[a-zA-Z\s]+$/.test(v) && v.length>0;
        if (ok) { inp.classList.remove('invalid'); inp.classList.add('valid'); }
        else if (v.length === 0) { inp.classList.remove('invalid'); inp.classList.remove('valid'); inp.classList.add('neutral'); }
        else { inp.classList.add('invalid'); inp.classList.remove('valid'); }
    }

    /* Helper to validate Qualification	 (uppercase letters + spaces) */
    function validateQualificationInput(inp) {
        const v = inp.value;
        const ok = /^[A-Z\s]+$/.test(v) && v.length>0;
        if (ok) { inp.classList.remove('invalid'); inp.classList.add('valid'); }
        else if (v.length === 0) { inp.classList.remove('invalid'); inp.classList.remove('valid'); inp.classList.add('neutral'); }
        else { inp.classList.add('invalid'); inp.classList.remove('valid'); }
    }

    /* Helper to validate Amount: must start with $ and only numbers after */
    function validateAmountInput(inp) {
        let v = inp.value.trim();

        // force $ prefix if user starts typing numbers
        if (v !== "" && !v.startsWith("$")) {
            v = "$" + v.replace(/[^0-9]/g, "");
        }

        // allow only digits after $
        v = "$" + v.slice(1).replace(/[^0-9]/g, "");

        inp.value = v;

        // mark validity
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


    /* Initialize Flatpickr with restrictions */
    function initDatePickers(context=document) {
        context.querySelectorAll('input.date-picker').forEach(input => {
            const key = input.dataset.key;
            const opts = {
                dateFormat: "m/d/Y",
                allowInput: true,
                onChange: function(selectedDates, dateStr) {
                    if (dateStr) input.style.backgroundColor = dateColor;
                    else input.style.backgroundColor = '#fff';
                },
                onReady: function(selectedDates, dateStr) {
                    if (input.value) input.style.backgroundColor = dateColor;
                }
            };
            if (key === "Graduation Date") opts.maxDate = "today";
            if (key === "Date") opts.minDate = "today";
            flatpickr(input, opts);
            // attach manual-typing validation: when blurred, try to parse; if invalid clear color
            input.addEventListener('blur', function(){
                if (input.value && !/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(input.value)) {
                    // invalid format: clear highlight
                    input.style.backgroundColor = '#fff';
                }
            });
        });
    }

    /* Initialize location autocomplete using LocationIQ (jQuery) */
    function initLocationAutocomplete(context=document) {
        $(context).find('input.location-autocomplete').each(function(){
            const $input = $(this);
            $input.on('input', function(){
                const q = $(this).val().trim();
                if (q.length < 2) { $('#loc-suggestions').remove(); return; }
                // call LocationIQ (replace YOUR_LOCATIONIQ_KEY)
                const key = 'pk.e91481c6e5f0a93703159ae988e641a0';
                $.getJSON(`https://us1.locationiq.com/v1/autocomplete.php?key=${key}&q=${encodeURIComponent(q)}&limit=5&dedupe=1&normalizecity=1&accept-language=en`)
                .done(function(results){
                    // remove old suggestions
                    $('#loc-suggestions').remove();
                    const $list = $('<div id="loc-suggestions" class="list-group" style="position:absolute; z-index:9999; max-height:200px; overflow:auto;"></div>');
                    results.forEach(r => {
                        const addr = r.address || {};
                        const city = addr.city || addr.town || addr.village || '';
                        const state = addr.state || addr.region || '';
                        const country = addr.country || '';
                        const display = [city, state, country].filter(Boolean).join(', ');
                        const item = $('<a href="#" class="list-group-item list-group-item-action"></a>').text(display || r.display_name);
                        item.on('click', function(e){
                            e.preventDefault();
                            $input.val(display || r.display_name);
                            $input.css('background-color', '#d4edda'); 
                            $('#loc-suggestions').remove();
                        });
                        $list.append(item);
                    });
                    // attach below input
                    $('body').append($list);
                    const offset = $input.offset();
                    $list.css({ top: offset.top + $input.outerHeight(), left: offset.left, width: $input.outerWidth() });
                })
                .fail(function(){
                    $('#loc-suggestions').remove();
                });

            });

            // remove suggestions on blur
            $input.on('blur', function(){
                setTimeout(()=>$('#loc-suggestions').remove(), 200);
            });
        });
    }

    /* Apply initial colors and validations for existing fields */
    function applyInitialState(context=document) {
        // selects
        context.querySelectorAll('select.dynamic-dropdown').forEach(s => updateSelectColor(s));
        // date pickers
        initDatePickers(context);
        // location autocomplete
        initLocationAutocomplete(context);
        // phone/email/name/qualification validation
        context.querySelectorAll('input.amount-input').forEach(i => { validateAmountInput(i); i.addEventListener('input', () => validateAmountInput(i));});
        context.querySelectorAll("input.phone-input").forEach(i => { i.value = formatPhoneNumber(i.value); validatePhoneInput(i); i.addEventListener("input", () => { i.value = formatPhoneNumber(i.value); validatePhoneInput(i);});});
        context.querySelectorAll('input.email-input').forEach(i => { validateEmailInput(i); i.addEventListener('input', ()=>{ i.value = i.value.toLowerCase(); validateEmailInput(i); }); });
        context.querySelectorAll('input.name-input').forEach(i => { validateNameInput(i); i.addEventListener('input', ()=>{ i.value = i.value.toLowerCase().replace(/[^a-zA-Z\s]/g,''); validateNameInput(i); }); });
        context.querySelectorAll('input.qualification-input').forEach(i => { validateQualificationInput(i); i.addEventListener('input', () => { i.value = i.value.toUpperCase().replace(/[^A-Z\s]/g, ''); validateQualificationInput(i);
        context.querySelectorAll('input.resume-input').forEach(input => { input.addEventListener('change', function() { const file = this.files[0]; if (!file) return; if (file.type !== "application/pdf") { alert("Only PDF files are allowed!"); this.value = ""; return; } });}); 
            });
        });

    }

    /* Add blank new row similar to earlier behavior */
    function addBlankRow() {
        let colKeys = [];
        let firstRow = tableBody.querySelector("tr");
        if (firstRow) {
            firstRow.querySelectorAll("td[contenteditable=true], select[data-key], input[data-key]").forEach(cell => {
                colKeys.push(cell.dataset.key);
            });
        }

        let newRow = document.createElement("tr");
        newRow.setAttribute("data-id", "new");

        let cells = `<td>New</td><td>—</td>`;
        colKeys.forEach(k => {
            // same option sets as blade above (kept in sync)
            if (k === 'Exe Remarks') {
                const opts = ['Called & Mailed','Not Interested','Others','N/A','VM','Busy'];
                cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}">
                    <option value="" disabled selected>-- Select Exe Remarks --</option>
                    ${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}
                </select></td>`;
            } else if (k === 'Immigration') {
                const opts = ['Dependent Visa','Global Visa','Graduate Visa','Student Visa','Citizen','Permanent Residence(ILR)'];
                cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}">
                    <option value="" disabled selected>-- Select Immigration --</option>
                    ${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}
                </select></td>`;
            } else if (k === 'Relocation') {
                const opts = ['YES','NO'];
                cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}">
                    <option value="" disabled selected>-- Select Relocation --</option>
                    ${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}
                </select></td>`;
            } else if (k === '1st Follow Up Remarks') {
                const opts = ['Interested','Doubt need Clarification','Money Issue','Not Interested','Don\'t Call'];
                cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}">
                    <option value="" disabled selected>-- Select Follow Up --</option>
                    ${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}
                </select></td>`;
            } else if (k === 'Course') {
                const opts = ['BA','SAS','JAVA','QA','SQL','PYTHON','DOT NET'];
                cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}">
                    <option value="" disabled selected>-- Select Course --</option>
                    ${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}
                </select></td>`;
            } else if (k === 'Time Zone') {
                const opts = ['EST','CST','MST','PST'];
                cells += `<td><select class="form-select dynamic-dropdown" data-key="${k}">
                    <option value="" disabled selected>-- Select Time Zone --</option>
                    ${opts.map(o=>`<option value="${o}">${o}</option>`).join('')}
                </select></td>`;
            } else if (k === 'Amount') {
                cells += `<td><input type="text" class="form-control amount-input" data-key="${k}" placeholder="$100"></td>`;
            } else if (k === 'Location') {
                cells += `<td><input type="text" class="form-control location-autocomplete" data-key="${k}" placeholder="Location"><span class="small-hint"></span></td>`;
            } else if (k === 'Date' || k === 'Graduation Date') {
                const placeholder = (k === 'Graduation Date') ? 'Graduation Date (MM/DD/YYYY)' : 'Date (MM/DD/YYYY)';
                cells += `<td><input type="text" class="form-control date-picker" data-key="${k}" placeholder="${placeholder}"><span class="small-hint"></span></td>`;
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
                            <input type="file" accept="application/pdf" class="d-none resume-input" data-key="resume">
                            <button type="button" class="btn btn-sm btn-info upload-btn">Upload</button>
                            <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View PDF</a>
                            <a href="#" download class="btn btn-sm btn-secondary download-btn d-none">Download</a>
                        </td>`;
            } else {
                cells += `<td contenteditable="true" data-key="${k}"></td>`;
            }
        });

        cells += `<td><button class="btn btn-sm btn-success save-btn" data-id="new"><i class="fas fa-save"></i> Save</button></td>`;
        newRow.innerHTML = cells;
        tableBody.appendChild(newRow);

        applyInitialState(newRow);
        attachSaveHandler(newRow.querySelector(".save-btn"));
    }

    if (!tableBody.querySelector('tr[data-id="new"]')) addBlankRow();

    /* change handler for selects to recolor */
    tableBody.addEventListener('change', function(e){
        if (e.target.matches('select.dynamic-dropdown')) updateSelectColor(e.target);
    });

    /* attach handlers & validations */
        function attachSaveHandler(btn) {
        btn.addEventListener("click", function () {
            let saveBtn = this;
            let id = saveBtn.dataset.id;
            let row = saveBtn.closest("tr");

            saveBtn.disabled = true;
            let originalHTML = saveBtn.innerHTML;
            saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;

            // collect data from row
            let updatedData = {};
            row.querySelectorAll("td[contenteditable=true]").forEach(cell => updatedData[cell.dataset.key] = cell.innerText.trim());
            row.querySelectorAll("select[data-key]").forEach(sel => updatedData[sel.dataset.key] = sel.value);
            row.querySelectorAll("input.date-picker").forEach(inp => updatedData[inp.dataset.key] = inp.value);
            row.querySelectorAll("input.location-autocomplete").forEach(inp => updatedData[inp.dataset.key] = inp.value);
            row.querySelectorAll("input.phone-input").forEach(inp => updatedData[inp.dataset.key] = inp.value);
            row.querySelectorAll("input.email-input").forEach(inp => updatedData[inp.dataset.key] = inp.value);
            row.querySelectorAll("input.name-input").forEach(inp => updatedData[inp.dataset.key] = inp.value);
            row.querySelectorAll("input.qualification-input").forEach(inp => updatedData[inp.dataset.key] = inp.value);

            const url = id === 'new' ? '/dashboard/junior/google-sheet/store' : `/dashboard/junior/google-sheet/update/${id}`;

            // create FormData for file upload
            let formData = new FormData();
            if (id === 'new') {
                formData.append('rows[0]', JSON.stringify(updatedData));
            } else {
                formData.append('data', JSON.stringify(updatedData));
                formData.append('_method', 'PATCH');
            }

            // attach resume file if present
            const resumeInput = row.querySelector('input.resume-input');
            if (resumeInput && resumeInput.files[0]) {
                formData.append('resume', resumeInput.files[0]);
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" // DO NOT set Content-Type for FormData
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (id === 'new') {
                        // handle new row rendering
                        let rowData = data.rows[0];
                        let html = `<td>${rowData.id}</td><td>${rowData.sheet_row_number}</td>`;
                        Object.entries(rowData.data).forEach(([key,val]) => {
                            if (['Exe Remarks','Immigration','Relocation','1st Follow Up Remarks','Course','Time Zone','Amount'].includes(key)) {
                                let opts = [];
                                if (key==='Exe Remarks') opts = ['Called & Mailed','Not Interested','Others','N/A','VM','Busy'];
                                else if (key==='Immigration') opts = ['Dependent Visa','Global Visa','Graduate Visa','Student Visa','Citizen','Permanent Residence(ILR)'];
                                else if (key==='Relocation') opts = ['YES','NO'];
                                else if (key==='1st Follow Up Remarks') opts = ['Interested','Doubt need Clarification','Money Issue','Not Interested','Don\'t Call'];
                                else if (key==='Course') opts = ['BA','SAS','JAVA','QA','SQL','PYTHON','DOT NET'];
                                else if (key==='Time Zone') opts = ['EST','CST','MST','PST'];
                                else if (key==='Amount') opts = Array.from({length:10},(_,i)=>`$${100+i}`).concat(Array.from({length:10},(_,i)=>`$${1000+i}`));
                                let optionsHTML = opts.map(o => `<option value="${o}" ${o===val ? 'selected' : '' }>${o}</option>`).join('');
                                html += `<td><select class="form-select dynamic-dropdown" data-key="${key}">${optionsHTML}</select></td>`;
                            } else if (key==='Location') {
                                html += `<td><input type="text" class="form-control location-autocomplete" data-key="${key}" value="${val ?? ''}"><span class="small-hint"></span></td>`;
                            } else if (key==='Date' || key==='Graduation Date') {
                                html += `<td><input type="text" class="form-control date-picker" data-key="${key}" value="${val ?? ''}"></td>`;
                            } else if (key==='Phone Number') {
                                html += `<td><input type="tel" class="form-control phone-input" data-key="${key}" value="${val ?? ''}" maxlength="12"><span class="phone-hint"></span></td>`;
                            } else if (key==='Email Address') {
                                html += `<td><input type="email" class="form-control email-input" data-key="${key}" value="${val ?? ''}"><span class="small-hint"></span></td>`;
                            } else if (key==='Name') {
                                html += `<td><input type="text" class="form-control name-input" data-key="${key}" value="${val ?? ''}"><span class="small-hint"></span></td>`;
                            } else if (key==='Qualification') {
                                html += `<td><input type="text" class="form-control qualification-input" data-key="${key}" value="${val ?? ''}"><span class="small-hint"></span></td>`;
                            } else {
                                html += `<td contenteditable="true" data-key="${key}">${val ?? ''}</td>`;
                            }
                        });
                        html += `<td><button class="btn btn-sm btn-success save-btn" data-id="${rowData.id}"><i class="fas fa-save"></i> Save</button></td>`;
                        row.setAttribute("id", "row-" + rowData.id);
                        row.setAttribute("data-id", rowData.id);
                        row.innerHTML = html;
                        applyInitialState(row);
                        attachSaveHandler(row.querySelector(".save-btn"));
                        showMessage("✅ New row saved!", "success");
                        addBlankRow();
                    } else {
                        // updated existing row - recolor inputs/selects
                        row.querySelectorAll("select.dynamic-dropdown").forEach(s => updateSelectColor(s));
                        row.querySelectorAll("input.phone-input").forEach(i=>validatePhoneInput(i));
                        row.querySelectorAll("input.email-input").forEach(i=>validateEmailInput(i));
                        row.querySelectorAll("input.name-input").forEach(i=>validateNameInput(i));
                        row.querySelectorAll("input.qualification-input").forEach(i=>validateQualificationInput(i));
                        showMessage("✓ Row updated!", "success");
                    }
                    if (data.row && data.row.resume_url) {
                        const resumeTd = row.querySelector("td input.resume-input")?.closest("td");
                        if (resumeTd) {
                            const viewBtn = resumeTd.querySelector(".view-btn");
                            const downloadBtn = resumeTd.querySelector(".download-btn");

                            viewBtn.classList.remove("d-none");
                            downloadBtn.classList.remove("d-none");

                            viewBtn.href = data.row.resume_url;
                            downloadBtn.href = data.row.resume_url;
                        }
                    }
                } else {
                    showMessage("❌ Failed to save row", "danger");
                }
            })
            .catch(()=>showMessage("⚠️ Server error!", "danger"))
            .finally(()=>{
                saveBtn.disabled=false;
                saveBtn.innerHTML = originalHTML;
            });
        });
    }


    // initial attach for existing buttons
    document.querySelectorAll(".save-btn").forEach(btn => attachSaveHandler(btn));
    // apply initial state across page
    applyInitialState(document);

    // remove global suggestions placeholder if user clicks elsewhere
    document.addEventListener('click', function(e){
        if (!$(e.target).closest('#loc-suggestions, .location-autocomplete').length) $('#loc-suggestions').remove();
    });

    // ensure dynamic behavior for newly created selects: recolor on change delegated
    tableBody.addEventListener('input', function(e){
        if (e.target.matches('input.phone-input')) validatePhoneInput(e.target);
        if (e.target.matches('input.email-input')) { e.target.value = e.target.value.toLowerCase(); validateEmailInput(e.target); }
        if (e.target.matches('input.name-input')) { let v = e.target.value.replace(/[^a-zA-Z\s]/g, ''); v = v.toLowerCase() .replace(/\b\w/g, c => c.toUpperCase()); e.target.value = v;validateNameInput(e.target); }
        if (e.target.matches('input.qualification-input')) { e.target.value = e.target.value.toUpperCase().replace(/[^A-Z\s]/g,''); validateQualificationInput(e.target); }
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("sheet-table-body");
    if (!tableBody) return console.error("❌ Table body not found");

    // CSRF token from meta
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
    if (!csrfToken) console.warn("⚠️ CSRF token not found in meta tag");

    tableBody.addEventListener("click", function (e) {
        const button = e.target.closest(".upload-btn");
        if (!button) return;

        const td = button.closest("td");
        if (!td) return console.error("❌ Could not find <td> for Upload button");

        const fileInput = td.querySelector(".resume-input");
        if (!fileInput) return console.error("❌ No file input found in this cell");

        fileInput.onchange = null;
        fileInput.click();

        fileInput.onchange = function () {
            const file = fileInput.files[0];
            if (!file) return console.warn("⚠️ No file selected.");
            if (file.type !== "application/pdf") {
                alert("Only PDF files are allowed!");
                fileInput.value = "";
                return;
            }

            uploadResume(td, file, button);
        };
    });

    async function uploadResume(td, file, button) {
    const row = td.closest("tr");
    if (!row) return console.error("❌ <tr> not found for this cell");

    const recordId = row.getAttribute("data-id") || "new";

    const formData = new FormData();
    formData.append("resume", file);

    let url = `/dashboard/junior/google-sheet/pdfstore`;
    let method = "POST";

    if (recordId !== "new") {
        url = `/dashboard/junior/google-sheet/pdfupdate/${recordId}`;
        formData.append("_method", "PATCH"); // Laravel method override
        method = "POST"; // Always POST; Laravel treats _method=PATCH
        console.log("🔄 Updating resume for record ID:", recordId);
    } else {
        console.log("➕ Creating new resume entry");
    }

    // Show loading state
    button.innerHTML = "Uploading...";
    button.disabled = true;

    try {
        const response = await fetch(url, {
            method: method,
            headers: { "X-CSRF-TOKEN": csrfToken },
            body: formData,
            credentials: "same-origin"
        });

        if (response.status === 419) throw new Error("Session expired or CSRF token mismatch");

        const resp = await response.json();

        button.innerHTML = "Upload";
        button.disabled = false;

        if (resp.success && resp.resume_url) {
            if (resp.id) row.setAttribute("data-id", resp.id);

        const viewBtn = td.querySelector(".view-btn");
        const downloadBtn = td.querySelector(".download-btn");

        const resumePath = resp.resume_url;
        const fullUrl = resumePath.startsWith('http') ? resumePath : window.location.origin + '/' + resumePath;

        const filename = resumePath.split('/').pop(); // get actual file name from path

        if (viewBtn) {
            viewBtn.classList.remove("d-none");
            viewBtn.href = fullUrl;
        }
        if (downloadBtn) {
            downloadBtn.classList.remove("d-none");
            downloadBtn.href = fullUrl;
            downloadBtn.setAttribute('download', filename); // specify correct file name
        }
        } else {
            console.error("⚠️ Upload failed. Response:", resp);
            alert("Upload failed.");
        }

    } catch (err) {
        console.error("❌ Upload error:", err);
        button.innerHTML = "Upload";
        button.disabled = false;
        alert(err.message || "Error uploading resume!");
    }
}

});
</script>


