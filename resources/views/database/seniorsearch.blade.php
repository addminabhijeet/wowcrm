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

                                        <div class="p-1 border rounded bg-light small">
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
                                                    <textarea class="form-control form-control-sm py-0 px-1" data-key="Remark">{{ $row->Remark ?? '' }}</textarea>
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

    <!-- ✅ EXTREME-COMPRESSION PREMIUM: Zero Design Errors, Full Space Optimization -->
    <style>
        /* ============================================
           ULTRA-TIGHT SPACING - EXTREME COMPRESSION
           ============================================ */
        body, .card-body, .card-header {
            margin: 0 !important;
            padding: 0 !important;
        }

        .card {
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid rgba(99, 102, 241, 0.12) !important;
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
            margin-bottom: 0 !important;
        }

        .card-header {
            padding: 12px 16px !important;
            background: #ffffff !important;
            border-bottom: 1px solid rgba(99, 102, 241, 0.1) !important;
        }

        .card-body {
            padding: 10px !important;
        }

        /* ============================================
           TABLE - EXTREME COMPRESSION
           ============================================ */
        .table {
            background: #ffffff !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            width: 100% !important;
            font-size: 12px !important;
            margin-bottom: 0 !important;
        }

        .table thead {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 20 !important;
        }

        .table thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.4px !important;
            padding: 7px 4px !important;
            border: none !important;
            vertical-align: middle !important;
            width: 20% !important;
            word-break: break-word !important;
        }

        /* ✅ DATA ROWS - ULTRA-TIGHT */
        #sheet-table-body tbody tr:not(.collapse-row) {
            background-color: #ffffff !important;
            border-bottom: 1px solid rgba(0, 0, 0, 0.04) !important;
            height: 36px !important;
        }

        #sheet-table-body tbody tr:not(.collapse-row):hover {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.03) 0%, rgba(99, 102, 241, 0.01) 100%) !important;
            box-shadow: inset 0 0 8px rgba(99, 102, 241, 0.04) !important;
        }

        #sheet-table-body tr:not(.collapse-row) td {
            padding: 6px 3px !important;
            vertical-align: middle !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: nowrap !important;
        }

        /* ============================================
           COLLAPSE ROW - ULTRA-COMPACT
           ============================================ */
        #sheet-table-body tr.collapse-row {
            display: none !important;
        }

        #sheet-table-body tr.collapse-row.show {
            display: table-row !important;
            animation: slideDown 0.25s ease-out !important;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                max-height: 0;
            }
            to {
                opacity: 1;
                max-height: 2000px;
            }
        }

        #sheet-table-body tr.collapse-row td {
            padding: 4px !important;
            border-top: 1px solid rgba(99, 102, 241, 0.08) !important;
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%) !important;
        }

        /* ============================================
           COLLAPSE DETAIL - ULTRA-COMPRESSED
           ============================================ */
        .collapse-row .p-1.border.rounded.bg-light {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%) !important;
            border: 1px solid rgba(99, 102, 241, 0.1) !important;
            border-radius: 6px !important;
            padding: 6px !important;
            max-width: 100% !important;
            width: 100% !important;
            box-shadow: none !important;
        }

        .collapse-row .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            row-gap: 4px !important;
            column-gap: 4px !important;
        }

        .collapse-row .col-md-3,
        .collapse-row .col-md-2,
        .collapse-row .col-md-12 {
            padding-left: 2px !important;
            padding-right: 2px !important;
            padding-bottom: 0 !important;
        }

        .collapse-row label {
            color: #1f2937 !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            font-size: 9px !important;
            letter-spacing: 0.3px !important;
            margin-bottom: 2px !important;
            display: block !important;
            width: 100% !important;
        }

        /* ============================================
           FORM CONTROLS - ULTRA-TIGHT
           ============================================ */
        .form-control, .form-select, .collapse-row .form-control, .collapse-row .form-select {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
            border: 1px solid rgba(99, 102, 241, 0.15) !important;
            border-radius: 6px !important;
            padding: 4px 6px !important;
            font-size: 11px !important;
            transition: all 0.2s ease !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
            height: 28px !important;
            margin-bottom: 0 !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1), inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
            background: #ffffff !important;
        }

        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.35) !important;
            font-size: 10px !important;
        }

        /* ============================================
           INPUT GROUP - TIGHT
           ============================================ */
        .input-group {
            gap: 0 !important;
            height: 28px !important;
        }

        .input-group-text {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: white !important;
            border: 1px solid #6366f1 !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            padding: 4px 6px !important;
            border-radius: 6px 0 0 6px !important;
        }

        .input-group .form-control {
            border-radius: 0 6px 6px 0 !important;
            border-left: none !important;
        }

        /* ============================================
           BUTTONS - ULTRA-COMPACT
           ============================================ */
        .btn {
            border-radius: 6px !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            transition: all 0.2s ease !important;
            text-transform: uppercase !important;
            letter-spacing: 0.3px !important;
            border: none !important;
            padding: 5px 10px !important;
            height: 28px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08) !important;
            margin-bottom: 0 !important;
            width: 100% !important;
        }

        .btn-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            color: white !important;
        }

        .btn-info:hover:not(:disabled) {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3) !important;
            transform: translateY(-1px) !important;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            color: white !important;
        }

        .btn-warning:hover:not(:disabled) {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%) !important;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3) !important;
            transform: translateY(-1px) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            color: white !important;
        }

        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3) !important;
            transform: translateY(-1px) !important;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%) !important;
            color: white !important;
        }

        .btn-secondary:hover:not(:disabled) {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
            box-shadow: 0 2px 6px rgba(107, 114, 128, 0.3) !important;
            transform: translateY(-1px) !important;
        }

        /* ============================================
           SEARCH - PREMIUM MINIMAL
           ============================================ */
        .navbar-search {
            position: relative !important;
        }

        .navbar-search .form-control {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
            border: 1.5px solid rgba(99, 102, 241, 0.2) !important;
            padding: 7px 10px 7px 32px !important;
            border-radius: 10px !important;
            font-weight: 500 !important;
            font-size: 12px !important;
            height: 32px !important;
        }

        .navbar-search .form-control:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08), inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
        }

        .navbar-search .icon {
            position: absolute !important;
            left: 10px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 16px !important;
            color: #6366f1 !important;
        }

        /* ============================================
           PAGINATION - ULTRA-TIGHT & FIXED
           ============================================ */
        .d-flex.align-items-center.justify-content-between {
            position: sticky !important;
            bottom: 0 !important;
            background: linear-gradient(180deg, #ffffff 0%, #f9fafb 100%) !important;
            padding: 8px 10px !important;
            border-top: 1px solid rgba(99, 102, 241, 0.1) !important;
            z-index: 15 !important;
            gap: 12px !important;
            margin-top: 4px !important;
        }

        .pagination {
            gap: 2px !important;
            margin: 0 !important;
        }

        .page-link {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
            border: 1px solid rgba(99, 102, 241, 0.15) !important;
            color: #6366f1 !important;
            border-radius: 4px !important;
            transition: all 0.2s ease !important;
            font-weight: 600 !important;
            font-size: 10px !important;
            padding: 4px 6px !important;
            min-width: 24px !important;
            text-align: center !important;
        }

        .page-link:hover:not(.disabled) {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-color: #6366f1 !important;
            color: white !important;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.2) !important;
            transform: translateY(-1px) !important;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            border-color: #6366f1 !important;
            box-shadow: 0 2px 6px rgba(99, 102, 241, 0.2) !important;
        }

        /* ============================================
           TABLE WRAPPER - SEAMLESS
           ============================================ */
        .table-responsive {
            width: 100% !important;
            overflow-x: auto !important;
            border: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #bottom-scroll-wrapper, #top-scroll-wrapper {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .table tbody {
            display: table-row-group !important;
        }

        .collapse-row td {
            display: table-cell !important;
            width: 100% !important;
        }

        /* ============================================
           SCROLLBAR - MINIMAL PREMIUM
           ============================================ */
        .scroll-sm::-webkit-scrollbar {
            height: 4px !important;
            width: 4px !important;
        }

        .scroll-sm::-webkit-scrollbar-track {
            background: rgba(99, 102, 241, 0.03) !important;
        }

        .scroll-sm::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #6366f1 0%, #4f46e5 100%) !important;
            border-radius: 10px !important;
        }

        .scroll-sm::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #4f46e5 0%, #4338ca 100%) !important;
        }

        /* ============================================
           TEXTAREA - COMPACT
           ============================================ */
        .collapse-row textarea {
            background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%) !important;
            border: 1px solid rgba(99, 102, 241, 0.15) !important;
            border-radius: 6px !important;
            padding: 4px 6px !important;
            font-size: 11px !important;
            transition: all 0.2s ease !important;
            width: 100% !important;
            min-height: 50px !important;
            resize: vertical !important;
        }

        .collapse-row textarea:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.08) !important;
        }

        /* ============================================
           GLOBAL OPTIMIZATIONS
           ============================================ */
        * {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }

        .mb-2, .mb-24, .mt-24 {
            margin: 0 !important;
        }

        small, .small {
            font-size: 11px !important;
        }

        /* ============================================
           TEXT STYLING
           ============================================ */
        .text-muted {
            color: #6b7280 !important;
            font-size: 11px !important;
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
