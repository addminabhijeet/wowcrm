@extends('layout.layout')
@php
    $title = 'Search Candidate';
    $role = auth()->user()->role ?? '';
    if ($role === 'admin') {
        $subTitle = 'Super Admin';
    } elseif ($role === 'operation') {
        $subTitle = 'Operation Manager';
    } elseif ($role === 'senior') {
        $subTitle = 'Senior IT Recruiter';
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
            <div class="table-responsive scroll-sm mb-2" id="top-scroll-wrapper">
                <div id="top-scroll"></div>
            </div>
            <div class="table-responsive scroll-sm" id="bottom-scroll-wrapper">
                @if ($data->isEmpty())
                    <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
                @else
                    <table class="table table-sm bordered-table mb-0">
                        <thead>
                            <tr class="text-nowrap small">
                                <th class="text-center px-1 py-1">Row</th>
                                <th class="text-center px-1 py-1">Name</th>
                                <th class="text-center px-1 py-1">Email</th>
                                <th class="text-center px-1 py-1">Phone</th>
                                <th class="text-center px-1 py-1">Status</th>
                            </tr>
                        </thead>
                        <tbody id="sheet-table-body">
                            @foreach ($data as $row)
                                <!-- MAIN ROW -->
                                <tr class="text-nowrap small">
                                    <td class="px-1 py-1">{{ $row->sheet_row_number }}</td>

                                    <td class="px-1 py-1">
                                        <input type="text" class="form-control form-control-sm p-1 name-input"
                                            data-key="Name" value="{{ $row->Name ?? '' }}">
                                    </td>

                                    <td class="px-1 py-1">
                                        <input type="email" class="form-control form-control-sm p-1 email-input"
                                            data-key="Email Address" value="{{ $row->Email_Address ?? '' }}">
                                    </td>

                                    <td class="px-1 py-1">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text p-1">+1</span>
                                            <input type="tel" class="form-control p-1 phone-input"
                                                data-key="Phone Number" value="{{ $row->Phone_Number ?? '' }}">
                                        </div>
                                    </td>

                                    <td class="px-1 py-1">
                                        <select class="form-select form-select-sm p-1 dynamic-dropdown"
                                            data-key="Exe Remarks">
                                            @foreach (['Called & Mailed', 'Not Interested', 'Interested', 'Others', 'Ready To Pay', 'VM', 'Busy'] as $option)
                                                <option value="{{ $option }}"
                                                    {{ $row->Exe_Remarks === $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <!-- COLLAPSE ROW -->
                                <tr id="collapse-{{ $row->id }}" class="collapse-row d-none">
                                    <td colspan="5" class="p-1">

                                        <div class="p-1 border rounded bg-light small" style="max-width:1000px;">
                                            <div class="row g-1">

                                                <!-- Location -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Location</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm py-0 px-1 location-autocomplete"
                                                        data-key="Location" value="{{ $row->Location ?? '' }}">
                                                </div>

                                                <!-- Date -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Date</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm py-0 px-1 date-picker"
                                                        data-key="Date"
                                                        value="{{ $row->Date ? \Carbon\Carbon::parse($row->Date)->format('m/d/Y') : '' }}">
                                                </div>

                                                <!-- Relocation -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Relocation</label>
                                                    <select class="form-select form-select-sm py-0 px-1 dynamic-dropdown"
                                                        data-key="Relocation">
                                                        @foreach (['YES', 'NO'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Relocation === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Graduation -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Graduation</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm py-0 px-1 date-picker"
                                                        data-key="Graduation Date" value="{{ $row->Graduation_Date }}">
                                                </div>

                                                <!-- Immigration -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Immigration</label>
                                                    <select class="form-select form-select-sm py-0 px-1 dynamic-dropdown"
                                                        data-key="Immigration">
                                                        @foreach (['F1 CPT', 'F1 OPT', 'STEM OPT', 'H1B', 'B2', 'B1', 'H4', 'H4 EAD', 'GC/PR', 'USC', 'L2S'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Immigration === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Course -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Course</label>
                                                    <select class="form-select form-select-sm py-0 px-1 dynamic-dropdown"
                                                        data-key="Course">
                                                        @foreach (['BA', 'DA', 'SAS', 'JAVA', 'QA', 'SQL', 'PYTHON', 'DOT NET'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Course === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Amount -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Amount</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm py-0 px-1 amount-input"
                                                        data-key="Amount" value="{{ $row->Amount }}">
                                                </div>

                                                <!-- Qualification -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Qualification</label>
                                                    <select class="form-select form-select-sm py-0 px-1 dynamic-dropdown"
                                                        data-key="Qualification">
                                                        @foreach (['Masters', 'Bachelors', 'MBA', 'PG Diploma', 'M.Tech', 'B.Tech'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Qualification === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- 1st Follow Up Remarks -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">1st Follow Up</label>
                                                    <select class="form-select form-select-sm py-0 px-1 dynamic-dropdown"
                                                        data-key="1st Follow Up Remarks">
                                                        @foreach (['Interested', 'Doubt need Clarification', 'Money Issue', 'Not Interested', "Don't Call"] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->First_Follow_Up_Remarks === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Time Zone -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Time Zone</label>
                                                    @php $timezoneOptions = ['EST','CST','MST','PST','']; @endphp
                                                    <select class="form-select form-select-sm py-0 px-1 dynamic-dropdown"
                                                        data-key="Time Zone">
                                                        <option value="">-- Select --</option>
                                                        @foreach ($timezoneOptions as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Time_Zone === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                                <!-- Resume Upload -->
                                                <div class="col-md-2 px-1">
                                                    <label class="mb-0 small">Upload</label>

                                                    <input type="file" accept=".pdf,.doc,.docx"
                                                        class="d-none resume-input" data-key="View">

                                                    <button type="button" class="btn btn-sm btn-info upload-btn w-100">
                                                        Upload
                                                    </button>
                                                </div>

                                                <!-- Resume Change -->
                                                <div class="col-md-2 px-1">
                                                    <label class="mb-0 small">Change</label>

                                                    <button type="button"
                                                        class="btn btn-sm btn-warning upload-btn w-100">
                                                        Change
                                                    </button>
                                                </div>

                                                <!-- Resume View -->
                                                <div class="col-md-2 px-1">
                                                    <label class="mb-0 small">View</label>

                                                    @if (!empty($row->resume))
                                                        <a href="{{ url('dashboard/junior/google-sheet/view-resume/' . $row->id) }}"
                                                            target="_blank" class="btn btn-sm btn-primary w-100">
                                                            View
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary w-100" disabled>
                                                            No File
                                                        </button>
                                                    @endif
                                                </div>

                                                <!-- Remark -->
                                                <div class="col-md-12 px-1">
                                                    <label class="mb-0 small">Remark</label>
                                                    <textarea class="form-control form-control-sm py-0 px-1" style="min-height:40px;resize:vertical;" data-key="Remark">{{ $row->Remark ?? '' }}</textarea>
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
            @if (method_exists($data, 'hasPages') && $data->hasPages())
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
                    <div>
                        {{ $data->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- ✅ ENHANCED PROFESSIONAL STYLING: Premium UI/UX Design -->
    <style>
        /* ============================================
           PREMIUM LAYOUT & STRUCTURE
           ============================================ */
        .card {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid rgba(0, 0, 0, 0.06) !important;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        .card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12) !important;
        }

        /* ============================================
           TABLE STYLING - PREMIUM
           ============================================ */
        .table {
            background: #ffffff !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            width: 100% !important;
        }

        #sheet-table-body tr {
            border-spacing: 0 !important;
            transition: all 0.2s ease !important;
        }

        /* Main data rows */
        #sheet-table-body tbody tr:not(.collapse-row) {
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06) !important;
            margin-bottom: 0 !important;
        }

        #sheet-table-body tbody tr:not(.collapse-row):hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.04) 0%, rgba(99, 102, 241, 0.02) 100%) !important;
            box-shadow: inset 0 0 10px rgba(99, 102, 241, 0.05) !important;
        }

        #sheet-table-body tr:not(.collapse-row) td {
            padding: 12px 8px !important;
            vertical-align: middle !important;
        }

        /* ✅ FIX: Ensure proper column widths */
        .table thead th {
            width: 20% !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .table tbody td {
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        /* Header styling */
        .table thead {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 10 !important;
        }

        .table thead th {
            color: #ffffff !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 14px 8px !important;
            border: none !important;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15) !important;
        }

        /* Collapse row */
        #sheet-table-body tr.collapse-row {
            display: none !important;
        }

        #sheet-table-body tr.collapse-row.show {
            display: table-row !important;
            animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #sheet-table-body tr.collapse-row td {
            padding: 0 !important;
            padding-top: 8px !important;
            padding-bottom: 8px !important;
            border-top: none !important;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.02) 0%, rgba(99, 102, 241, 0.01) 100%) !important;
        }

        /* ============================================
           FORM CONTROLS - PREMIUM STYLING
           ============================================ */
        .form-control, .form-select {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%) !important;
            border: 1.5px solid rgba(99, 102, 241, 0.2) !important;
            border-radius: 8px !important;
            padding: 8px 10px !important;
            font-size: 13px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1), 0 2px 8px rgba(99, 102, 241, 0.15) !important;
            background: #ffffff !important;
        }

        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.4) !important;
            font-style: italic !important;
        }

        /* ============================================
           INPUT GROUP STYLING
           ============================================ */
        .input-group-text {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: white !important;
            border: 1.5px solid #6366f1 !important;
            font-weight: 600 !important;
            border-radius: 8px 0 0 8px !important;
        }

        .input-group .form-control {
            border-radius: 0 8px 8px 0 !important;
            border-left: none !important;
        }

        /* ============================================
           BUTTON STYLING - PREMIUM
           ============================================ */
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            border: none !important;
            padding: 8px 16px !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4) !important;
            transform: translateY(-2px) !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white !important;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
            box-shadow: 0 6px 20px rgba(217, 119, 6, 0.4) !important;
            transform: translateY(-2px) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4) !important;
            transform: translateY(-2px) !important;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%) !important;
            color: white !important;
        }

        .btn-secondary:hover:not(:disabled) {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4) !important;
            transform: translateY(-2px) !important;
        }

        /* ============================================
           SEARCH INPUT - PREMIUM
           ============================================ */
        .navbar-search {
            position: relative !important;
        }

        .navbar-search .form-control {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%) !important;
            border: 2px solid rgba(99, 102, 241, 0.2) !important;
            padding: 10px 14px 10px 40px !important;
            border-radius: 12px !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .navbar-search .form-control:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1), 0 4px 12px rgba(99, 102, 241, 0.2) !important;
        }

        .navbar-search .icon {
            position: absolute !important;
            left: 12px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 18px !important;
            color: #6366f1 !important;
        }

        /* ============================================
           COLLAPSE DETAIL SECTION - PREMIUM & FULL WIDTH
           ============================================ */
        .collapse-row .p-1.border.rounded.bg-light {
            background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%) !important;
            border: 1.5px solid rgba(99, 102, 241, 0.15) !important;
            border-radius: 12px !important;
            box-shadow: inset 0 2px 4px rgba(99, 102, 241, 0.05) !important;
            max-width: 100% !important;
            width: 100% !important;
            overflow-x: auto !important;
        }

        .collapse-row label {
            color: #1f2937 !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 0.5px !important;
            margin-bottom: 6px !important;
        }

        .collapse-row textarea {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%) !important;
            border: 1.5px solid rgba(99, 102, 241, 0.2) !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }

        .collapse-row textarea:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }

        /* ============================================
           PAGINATION - PREMIUM
           ============================================ */
        .pagination {
            gap: 4px !important;
        }

        .page-link {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%) !important;
            border: 1.5px solid rgba(99, 102, 241, 0.2) !important;
            color: #6366f1 !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
            font-weight: 600 !important;
        }

        .page-link:hover:not(.disabled) {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-color: #6366f1 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
            transform: translateY(-1px) !important;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-color: #6366f1 !important;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3) !important;
        }

        /* ============================================
           ANIMATIONS & TRANSITIONS
           ============================================ */
        * {
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease !important;
        }

        /* ============================================
           TEXT & TYPOGRAPHY
           ============================================ */
        .text-nowrap.small {
            letter-spacing: 0.3px !important;
        }

        .text-center {
            color: #374151 !important;
        }

        /* ============================================
           SCROLLBAR STYLING - PREMIUM
           ============================================ */
        .scroll-sm::-webkit-scrollbar {
            height: 6px !important;
            width: 6px !important;
        }

        .scroll-sm::-webkit-scrollbar-track {
            background: rgba(99, 102, 241, 0.05) !important;
            border-radius: 10px !important;
        }

        .scroll-sm::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #6366f1 0%, #4f46e5 100%) !important;
            border-radius: 10px !important;
        }

        .scroll-sm::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #4f46e5 0%, #4338ca 100%) !important;
        }

        /* ============================================
           TABLE WRAPPER - FIX LAYOUT
           ============================================ */
        .table-responsive {
            width: 100% !important;
            overflow-x: auto !important;
        }

        #bottom-scroll-wrapper {
            width: 100% !important;
        }

        .table tbody {
            display: table-row-group !important;
        }

        /* ✅ FIX: Ensure collapse row spans properly */
        .collapse-row td {
            display: table-cell !important;
            width: 100% !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

            function fetchTable(search = '', page = 1, junior_user = '', row_id = '', show_all = false) {
                $.ajax({
                    url: "{{ route('google.sheet.seniorsearch') }}",
                    data: {
                        search,
                        page,
                        junior_user,
                        row_id,
                        show_all // ✅ send flag
                    },
                    success: function(res) {
                        $('#senior-table-wrapper').html(res);
                    }
                });
            }
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

                            html += `
                            <a href="#" class="list-group-item list-group-item-primary text-center fw-bold"
                            id="show-all-results">
                            Show All Results
                            </a>`;

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

            $(document).on('click', '#sheet-table-body tr', function() {
                const nextRow = $(this).next('.collapse-row');

                if (nextRow.length) {
                    nextRow.toggleClass('d-none').toggleClass('show');
                }
            });

            $(document).on('click', '#search-suggestions a', function(e) {
                e.preventDefault();

                const rowId = $(this).data('id');
                const junior_user = $('#junior-filter').val();

                $('#search-suggestions').hide().empty();
                fetchTable('', 1, junior_user, rowId);

                setTimeout(() => {
                    const row = $('a[data-id="' + rowId + '"]');

                    // Highlight row
                    $('#sheet-table-body tr').removeClass('table-warning');
                    const targetRow = $('tr').filter(function() {
                        return $(this).find('td:first').text() == rowId;
                    });

                    targetRow.addClass('table-warning');

                    // ✅ Expand collapse row (remove gap)
                    $('#collapse-' + rowId).removeClass('d-none').addClass('show');

                    // Scroll into view
                    targetRow[0]?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                }, 500);
            });

            $(document).on('click', '#show-all-results', function(e) {
                e.preventDefault();

                const query = $('#senior-search').val().trim();
                const junior_user = $('#junior-filter').val();

                $('#search-suggestions').hide().empty();

                fetchTable(query, 1, junior_user, '', true); // ✅ NEW FLAG
            });

            $('#junior-filter').on('change', function() {
                fetchTable($('#senior-search').val(), 1, this.value);
            });

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
