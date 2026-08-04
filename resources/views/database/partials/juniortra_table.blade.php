@if ($data->isEmpty())
<p class="text-muted">No data found. Fetch a Google Sheet first.</p>
@else
<div class="table-responsive scroll-sm mb-2" id="top-scroll-wrapper"
    style="
        overflow-x: auto;
        overflow-y: hidden;
        scrollbar-gutter: stable;
        height: 20px;
    ">
    <div id="top-scroll" style="height: 1px;"></div>
</div>
<script>
    $(document).ready(function() {
        // Set top-scroll width equal to table width
        function syncTopScroll() {
            var tableWidth = $('#sheet-table')[0].scrollWidth;
            $('#top-scroll').width(tableWidth);
        }

        syncTopScroll(); // initial sync
        $(window).resize(syncTopScroll); // update on window resize

        // Scroll table when top-scroll is moved
        $('#top-scroll-wrapper').on('scroll', function() {
            $('.table-responsive.scroll-sm').scrollLeft($(this).scrollLeft());
        });

        // Scroll top-scroll when table is scrolled
        $('.table-responsive.scroll-sm').on('scroll', function() {
            $('#top-scroll-wrapper').scrollLeft($(this).scrollLeft());
        });
    });
</script>

<div class="table-responsive scroll-sm">

    <table class="table bordered-table sm-table mb-0">
        <thead>
            <tr>
                <th scope="col" class="text-center">Row</th>
                <th scope="col" class="text-center">Date</th>
                <th scope="col" class="text-center">Name</th>
                <th scope="col" class="text-center">Email Address</th>
                <th scope="col" class="text-center">Phone Number</th>
                <th scope="col" class="text-center">Location</th>

                <th scope="col" class="text-center">Relocation</th>
                <th scope="col" class="text-center">Graduation Date</th>
                <th scope="col" class="text-center">Immigration</th>
                <th scope="col" class="text-center">Course</th>
                <th scope="col" class="text-center">Amount</th>
                <th scope="col" class="text-center">Qualification</th>

                <th scope="col" class="text-center">1st Follow Up Remarks</th>
                <th scope="col" class="text-center">Time Zone</th>
                <th scope="col" class="text-center">Resume</th>
                <th scope="col" class="text-center">Remark</th>
                <th scope="col" class="text-center">Status</th>

            </tr>
        </thead>
        <tbody id="sheet-table-body">
            @foreach ($data as $row)
            <tr id="row-{{ $row->id }}" data-id="{{ $row->id }}">

                <td>{{ $row->sheet_row_number }}</td>

                {{-- Date --}}
                <td>
                    <input type="text" class="form-control date-picker" data-key="Date"
                        value="{{ $row->Date ? \Carbon\Carbon::parse($row->Date)->format('m/d/Y') : '' }}"
                        readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                </td>


                {{-- Name --}}
                <td>
                    <input type="text" class="form-control name-input" data-key="Name"
                        value="{{ $row->Name ?? '' }}" placeholder="Name" readonly>
                </td>

                {{-- Email Address --}}
                <td>
                    <input type="email" class="form-control email-input" data-key="Email Address"
                        value="{{ $row->Email_Address ?? '' }}" placeholder="E-mail" readonly>
                </td>

                {{-- Phone Number --}}
                <td>
                    <input type="tel" class="form-control phone-input" data-key="Phone Number"
                        maxlength="14" value="{{ $row->Phone_Number ?? '' }}" placeholder="US number" readonly>
                </td>

                {{-- Location --}}
                <td>
                    <input type="text" class="form-control location-autocomplete" data-key="Location"
                        value="{{ $row->Location ?? '' }}" placeholder="Type location" readonly>
                </td>




                {{-- Relocation --}}
                <td>
                    @php $relOptions = ['YES','NO','']; @endphp
                    <select class="form-select dynamic-dropdown" data-key="Relocation" disabled>
                        <option value="">-- Relocation --</option>
                        @foreach ($relOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->Relocation === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>


                {{-- Graduation Date --}}
                <td>
                    <input type="text" class="form-control date-picker" data-key="Graduation Date"
                        value="{{ $row->Graduation_Date ? \Carbon\Carbon::parse($row->Graduation_Date)->format('m/d/Y') : '' }}"
                        readonly>
                </td>

                {{-- Immigration --}}
                <td>
                    @php $immOptions = ['F1 CPT','F1 OPT','STEM OPT','H1B','B2','B1','H4','H4 EAD', 'GC/PR','USC','L2S','']; @endphp
                    <select class="form-select dynamic-dropdown" data-key="Immigration" disabled>
                        <option value="">-- Immigration --</option>
                        @foreach ($immOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->Immigration === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>

                {{-- Course --}}
                <td>
                    @php $courseOptions = ['BA','DA','SAS','JAVA','QA','SQL','PYTHON','DOT NET','']; @endphp
                    <select class="form-select dynamic-dropdown" data-key="Course" disabled>
                        <option value="">-- Course --</option>
                        @foreach ($courseOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->Course === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>

                {{-- Amount --}}
                <td>
                    <input type="text" class="form-control amount-input" data-key="Amount"
                        value="{{ $row->Amount ? '$' . number_format($row->Amount, 2) : '' }}"
                        placeholder="Amount(469)" readonly>
                </td>

                {{-- Qualification --}}
                <td>
                    @php
                    $qualificationOptions = [
                    'Masters',
                    'Masters of Science',
                    'Bachelors',
                    'PG',
                    'MBA',
                    'PG Diploma',
                    'M.Tech',
                    'B.Tech',
                    'MA',
                    'Associate Degree',
                    'Aerospace Proj. Manag.',
                    '',
                    ];
                    @endphp

                    <select class="form-select dynamic-dropdown" data-key="Qualification" disabled>
                        <option value="">-- Qualification --</option>
                        @foreach ($qualificationOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->Qualification === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>





                {{-- 1st Follow Up Remarks --}}
                <td>
                    @php $followOptions = ['Interested','Doubt need Clarification','Money Issue','Not Interested','Don\'t Call','']; @endphp
                    <select class="form-select dynamic-dropdown" data-key="1st Follow Up Remarks">
                        <option value="">-- 1st Follow Up Remarks --</option>
                        @foreach ($followOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->First_Follow_Up_Remarks === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>

                {{-- Time Zone --}}
                <td>
                    @php $timezoneOptions = ['EST','CST','MST','PST','']; @endphp
                    <select class="form-select dynamic-dropdown" data-key="Time Zone" disabled>
                        <option value="">-- Time Zone --</option>
                        @foreach ($timezoneOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->Time_Zone === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>

                {{-- View (Resume) --}}
                <td>
                    <input type="file" accept=".pdf, .doc, .docx" class="d-none resume-input"
                        data-key="View">

                    <button type="button" class="btn btn-sm btn-info upload-btn">
                        {{ !empty($row->resume) ? 'Change File' : 'Upload' }}
                    </button>

                    @if (!empty($row->resume))
                    <a href="{{ url('dashboard/junior/google-sheet/view-resume/' . $row->id) }}"
                        target="_blank" class="btn btn-sm btn-primary view-btn">View File</a>

                    <a href="{{ url('dashboard/junior/google-sheet/download-resume/' . $row->id) }}"
                        class="btn btn-sm btn-secondary download-btn">Download</a>
                    @else
                    <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View
                        File</a>
                    <a href="#" download
                        class="btn btn-sm btn-secondary download-btn d-none">Download</a>
                    @endif
                </td>

                {{-- Remark --}}
                <td>
                    <!-- OLD REMARK (READONLY) -->
                    <textarea class="form-control remark-autocomplete"
                        data-key="Remark"
                        rows="3"
                        placeholder="Type remark" readonly>{{ $row->Remark ?? '' }}</textarea>

                    <!-- NEW REMARK -->
                    <input type="text"
                        class="form-control new-remark"
                        data-key="Remark"
                        placeholder="Add new remark">
                </td>

                {{-- Status --}}
                <td>
                    @php
                    $exeOptions = [
                    'Called & Mailed',
                    'Not Interested',
                    'Not Connected',
                    'Did Not Pickup',
                    'Others',
                    'Ready To Pay',
                    'VM',
                    'Busy',
                    '',
                    ];
                    @endphp
                    <select class="form-select dynamic-dropdown" data-key="Exe Remarks" disabled>
                        <option value="">-- Status --</option>
                        @foreach ($exeOptions as $option)
                        <option value="{{ $option }}"
                            {{ $row->Exe_Remarks === $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                        @endforeach
                    </select>
                </td>
            </tr>
            @endforeach
        </tbody>
        <script>
            $(document).ready(function() {
                $('.save-btn').click(function() {
                    let rowId = $(this).data('id');
                    let $tr = $('#row-' + rowId);

                    let data = {};

                    // ✅ Collect data from inputs and selects
                    $tr.find('input[data-key], select[data-key]').each(function() {
                        let key = $(this).data('key');
                        data[key] = $(this).val();
                    });

                    // ✅ MERGE OLD + NEW REMARK (IMPORTANT - prevents duplication)
                    const oldRemark = $tr.find('textarea.remark-autocomplete')?.val() || '';
                    const newRemark = $tr.find('.new-remark')?.val() || '';

                    let finalRemark = oldRemark.trim();

                    if (newRemark.trim()) {
                        finalRemark = finalRemark ?
                            finalRemark + "\n" + newRemark.trim() :
                            newRemark.trim();
                    }

                    data['Remark'] = finalRemark;

                    let formData = new FormData();
                    formData.append('id', rowId);
                    formData.append('data', JSON.stringify(data));

                    let fileInput = $tr.find('.resume-input')[0];
                    if (fileInput && fileInput.files.length > 0) {
                        formData.append('resume', fileInput.files[0]);
                    }

                    $.ajax({
                        url: "{{ route('juniorcandmupdate') }}",
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            alert(response.message);
                        },
                        error: function() {
                            alert('AJAX error');
                        }
                    });
                });

                // Show file input when clicking upload
                $('.upload-btn').click(function() {
                    $(this).closest('td').find('input.resume-input').click();
                });
            });
        </script>
        <style>
            textarea.remark-autocomplete,
            .new-remark {
                resize: vertical;
                min-height: 60px;
            }

            textarea.remark-autocomplete[readonly] {
                background-color: #f5f5f5;
                cursor: default;
            }

            .new-remark {
                margin-top: 5px;
            }
        </style>
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