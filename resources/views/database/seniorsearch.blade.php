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

        <div class="card-body p-2" id="senior-table-wrapper">

            <div class="table-responsive mb-1" id="top-scroll-wrapper" style="overflow-x: hidden;">
                <div id="top-scroll"></div>
            </div>

            <div class="table-responsive" id="bottom-scroll-wrapper" style="overflow-x: hidden;">
                @if ($data->isEmpty())
                    <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
                @else
                    <table class="table table-sm bordered-table mb-0 w-100" style="table-layout: fixed; font-size:11px;">
                        <thead>
                            <tr class="text-wrap">
                                <th class="text-center px-1 py-0" style="width:4%;">#</th>
                                <th class="text-center px-1 py-0" style="width:18%;">Name</th>
                                <th class="text-center px-1 py-0" style="width:22%;">Email</th>
                                <th class="text-center px-1 py-0" style="width:18%;">Phone</th>
                                <th class="text-center px-1 py-0" style="width:18%;">Status</th>
                            </tr>
                        </thead>

                        <tbody id="sheet-table-body">
                            @foreach ($data as $row)
                                <!-- MAIN ROW -->
                                <tr class="text-wrap">
                                    <td class="px-1 py-0 text-center">
                                        {{ $row->sheet_row_number }}
                                    </td>

                                    <td class="px-1 py-0">
                                        <input type="text" class="form-control form-control-sm p-0 w-100"
                                            style="height:22px; font-size:11px;" data-key="Name"
                                            value="{{ $row->Name ?? '' }}">
                                    </td>

                                    <td class="px-1 py-0">
                                        <input type="email" class="form-control form-control-sm p-0 w-100"
                                            style="height:22px; font-size:11px;" data-key="Email Address"
                                            value="{{ $row->Email_Address ?? '' }}">
                                    </td>

                                    <td class="px-1 py-0">
                                        <input type="tel" class="form-control form-control-sm p-0 w-100"
                                            style="height:22px; font-size:11px;" data-key="Phone Number"
                                            value="{{ $row->Phone_Number ?? '' }}">
                                    </td>

                                    <td class="px-1 py-0">
                                        <select class="form-select form-select-sm p-0 w-100"
                                            style="height:22px; font-size:11px;" data-key="Exe Remarks">
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
                                    <td colspan="7" class="p-1">
                                        <div class="p-1 border rounded bg-light small">

                                            <div class="row g-1">

                                                <div class="px-1 py-0">
                                                    <label>Location</label>
                                                    <input type="text" class="form-control form-control-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Location"
                                                        value="{{ $row->Location ?? '' }}">
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Date</label>
                                                    <input type="text" class="form-control form-control-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Date"
                                                        value="{{ $row->Date ? \Carbon\Carbon::parse($row->Date)->format('m/d/Y') : '' }}">
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Relocation</label>
                                                    <select class="form-select form-select-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Relocation">
                                                        @foreach (['YES', 'NO'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Relocation === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Graduation</label>
                                                    <input type="text" class="form-control form-control-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Graduation Date"
                                                        value="{{ $row->Graduation_Date }}">
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Immigration</label>
                                                    <select class="form-select form-select-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Immigration">
                                                        @foreach (['F1 CPT', 'F1 OPT', 'STEM OPT', 'H1B', 'B2', 'B1', 'H4', 'H4 EAD', 'GC/PR', 'USC', 'L2S'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Immigration === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Course</label>
                                                    <select class="form-select form-select-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Course">
                                                        @foreach (['BA', 'DA', 'SAS', 'JAVA', 'QA', 'SQL', 'PYTHON', 'DOT NET'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Course === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Amount</label>
                                                    <input type="text" class="form-control form-control-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Amount"
                                                        value="{{ $row->Amount }}">
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Qualification</label>
                                                    <select class="form-select form-select-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Qualification">
                                                        @foreach (['Masters', 'Bachelors', 'MBA', 'PG Diploma', 'M.Tech', 'B.Tech'] as $option)
                                                            <option value="{{ $option }}"
                                                                {{ $row->Qualification === $option ? 'selected' : '' }}>
                                                                {{ $option }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="px-1 py-0">
                                                    <label>Remark</label>
                                                    <input type="text" class="form-control form-control-sm p-0 w-100"
                                                        style="height:22px; font-size:11px;" data-key="Remark"
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
                <div class="d-flex justify-content-between mt-2">
                    {{ $data->links('pagination::bootstrap-5') }}
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
