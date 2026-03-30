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
                                        <input type="tel" class="form-control form-control-sm p-1 phone-input"
                                            data-key="Phone Number" value="{{ $row->Phone_Number ?? '' }}">
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

                                                <!-- Resume -->
                                                <div class="col-md-3 px-1">
                                                    <label class="mb-0 small">Resume</label>

                                                    <input type="file" accept=".pdf,.doc,.docx"
                                                        class="d-none resume-input" data-key="View">

                                                    <button type="button" class="btn btn-sm btn-info upload-btn w-100">
                                                        {{ !empty($row->resume) ? 'Change' : 'Upload' }}
                                                    </button>

                                                    @if (!empty($row->resume))
                                                        <a href="{{ url('dashboard/junior/google-sheet/view-resume/' . $row->id) }}"
                                                            target="_blank"
                                                            class="btn btn-sm btn-primary w-100 mt-1">View</a>
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

            $(document).on('click', '#sheet-table-body tr', function() {
                const nextRow = $(this).next('.collapse-row');

                if (nextRow.length) {
                    nextRow.toggleClass('d-none');
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

                    // Expand collapse row
                    $('#collapse-' + rowId).removeClass('d-none');

                    // Scroll into view
                    targetRow[0]?.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                }, 500);
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
