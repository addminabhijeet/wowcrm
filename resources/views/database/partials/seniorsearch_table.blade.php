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
                <input type="text" class="form-control form-control-sm p-1 name-input" data-key="Name"
                    value="{{ $row->Name ?? '' }}">
            </td>

            <td class="px-1 py-1">
                <input type="email" class="form-control form-control-sm p-1 email-input"
                    data-key="Email Address" value="{{ $row->Email_Address ?? '' }}">
            </td>

            <td class="px-1 py-1">
                <div class="input-group input-group-sm">
                    <span class="input-group-text p-1">+1</span>
                    <input type="tel" class="form-control p-1 phone-input" data-key="Phone Number"
                        value="{{ $row->Phone_Number ?? '' }}">
                </div>
            </td>

            <td class="px-1 py-1">
                <select class="form-select form-select-sm p-1 dynamic-dropdown" data-key="Exe Remarks">
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
                            <input type="text" class="form-control form-control-sm py-0 px-1 date-picker"
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
                            <input type="text" class="form-control form-control-sm py-0 px-1 date-picker"
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
                            <input type="text" class="form-control form-control-sm py-0 px-1 amount-input"
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

                            <input type="file" accept=".pdf,.doc,.docx" class="d-none resume-input"
                                data-key="View">

                            <button type="button" class="btn btn-sm btn-info upload-btn w-100">
                                Upload
                            </button>
                        </div>

                        <!-- Resume Change -->
                        <div class="col-md-2 px-1">
                            <label class="mb-0 small">Change</label>

                            <button type="button" class="btn btn-sm btn-warning upload-btn w-100">
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