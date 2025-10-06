@if($data->isEmpty())
<p class="text-muted">No data found. Fetch a Google Sheet first.</p>
@else
<div class="table-responsive scroll-sm">
<table class="table bordered-table sm-table mb-0">
    <thead>
        <tr>
            <th>Row #</th>
            <th>Date</th>
            <th>Name</th>
            <th>Email Address</th>
            <th>Phone Number</th>
            <th>Location</th>
            <th>Relocation</th>
            <th>Graduation Date</th>
            <th>Immigration</th>
            <th>Course</th>
            <th>Amount</th>
            <th>Qualification</th>
            <th>Exe Remarks</th>
            <th>1st Follow Up Remarks</th>
            <th>Time Zone</th>
            <th>Forwarded By</th>
            <th>View</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody id="sheet-table-body">
        @foreach($data as $row)
        <tr id="row-{{ $row->id }}" data-id="{{ $row->id }}">
            <td>{{ $row->sheet_row_number }}</td>
            <td><input type="text" class="form-control date-picker" data-key="Date" value="{{ $row->Date ? \Carbon\Carbon::parse($row->Date)->format('m/d/Y') : '' }}"></td>
            <td><input type="text" class="form-control name-input" data-key="Name" value="{{ $row->Name ?? '' }}" placeholder="Name"></td>
            <td><input type="email" class="form-control email-input" data-key="Email Address" value="{{ $row->Email_Address ?? '' }}" placeholder="E-mail"></td>
            <td><input type="tel" class="form-control phone-input" data-key="Phone Number" maxlength="12" value="{{ $row->Phone_Number ?? '' }}" placeholder="US number"></td>
            <td><input type="text" class="form-control location-autocomplete" data-key="Location" value="{{ $row->Location ?? '' }}" placeholder="Type location"></td>
            <td>
                @php $relOptions = ['YES','NO']; @endphp
                <select class="form-select dynamic-dropdown" data-key="Relocation">
                    <option value="">-- Select --</option>
                    @foreach($relOptions as $option)
                    <option value="{{ $option }}" {{ $row->Relocation === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" class="form-control date-picker" data-key="Graduation Date" value="{{ $row->Graduation_Date ? \Carbon\Carbon::parse($row->Graduation_Date)->format('m/d/Y') : '' }}"></td>
            <td>
                @php $immOptions = ['Dependent Visa','Global Visa','Graduate Visa','Student Visa','Citizen','Permanent Residence(ILR)']; @endphp
                <select class="form-select dynamic-dropdown" data-key="Immigration">
                    <option value="">-- Select --</option>
                    @foreach($immOptions as $option)
                    <option value="{{ $option }}" {{ $row->Immigration === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                @php $courseOptions = ['BA','SAS','JAVA','QA','SQL','PYTHON','DOT NET']; @endphp
                <select class="form-select dynamic-dropdown" data-key="Course">
                    <option value="">-- Select --</option>
                    @foreach($courseOptions as $option)
                    <option value="{{ $option }}" {{ $row->Course === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" class="form-control amount-input" data-key="Amount" value="{{ $row->Amount !== null ? '$'.number_format($row->Amount,2) : '' }}" placeholder="Amount"></td>
            <td><input type="text" class="form-control qualification-input" data-key="Qualification" value="{{ $row->Qualification ?? '' }}" placeholder="Qualification"></td>
            <td>
                @php $exeOptions = ['Called & Mailed','Ready To Paid','Not Interested','Others','N/A','VM','Busy']; @endphp
                <select class="form-select dynamic-dropdown" data-key="Exe Remarks">
                    <option value="">-- Select --</option>
                    @foreach($exeOptions as $option)
                    <option value="{{ $option }}" {{ $row->Exe_Remarks === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                @php $followOptions = ['Interested','Doubt need Clarification','Money Issue','Not Interested','Don\'t Call']; @endphp
                <select class="form-select dynamic-dropdown" data-key="1st Follow Up Remarks">
                    <option value="">-- Select --</option>
                    @foreach($followOptions as $option)
                    <option value="{{ $option }}" {{ $row->First_Follow_Up_Remarks === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                @php $timezoneOptions = ['EST','CST','MST','PST']; @endphp
                <select class="form-select dynamic-dropdown" data-key="Time Zone">
                    <option value="">-- Select --</option>
                    @foreach($timezoneOptions as $option)
                    <option value="{{ $option }}" {{ $row->Time_Zone === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" class="form-control forwardedBy-input" data-key="forwardedBy" value="{{ $row->forwarded_by ?? '' }}" readonly></td>
            <td>
                <input type="file" accept="application/pdf" class="d-none resume-input" data-key="View">
                <button type="button" class="btn btn-sm btn-info upload-btn">{{ !empty($row->resume) ? 'Change File' : 'Upload' }}</button>
                @if(!empty($row->resume))
                <a href="{{ url('dashboard/senior/google-sheet/view-resume/'.$row->id) }}" target="_blank" class="btn btn-sm btn-primary view-btn">View PDF</a>
                <a href="{{ url('dashboard/senior/google-sheet/download-resume/'.$row->id) }}" class="btn btn-sm btn-secondary download-btn">Download</a>
                @else
                <a href="#" target="_blank" class="btn btn-sm btn-primary view-btn d-none">View PDF</a>
                <a href="#" download class="btn btn-sm btn-secondary download-btn d-none">Download</a>
                @endif
            </td>
            <td class="text-center"><button class="btn btn-sm btn-success save-btn" data-id="{{ $row->id }}"><i class="fas fa-save"></i> Save</button></td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($data->hasPages())
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
    {{ $data->links('pagination::bootstrap-5') }}
</div>
@endif
</div>
@endif
