@extends('layout.layout')
@php
    $title = 'SMTP -> Call Report Mail';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');
    // Same wording as the time-slot titles on dashboard/group/senior/mail/chart, e.g. "8:00pm - 9:00pm", "11:00pm - 12:00am"
    $label = fn ($h) => \Carbon\Carbon::createFromTime($h)->format('g:ia') . ' - ' . \Carbon\Carbon::createFromTime(($h + 1) % 24)->format('g:ia');
@endphp

@section('content')
    <div class="card h-100 p-0 radius-12">
        <div class="card-header border-bottom bg-base py-16 px-24 d-flex flex-wrap gap-2 align-items-center justify-content-between">
            <h6 class="mb-0">Call Report Mail (C&amp;M Count)</h6>
            <a href="{{ route('smtp.reportmail.list') }}" class="btn btn-outline-primary btn-sm">View send list</a>
        </div>
        <div class="card-body p-24">
            <p class="text-secondary-light text-sm mb-20">
                The C&amp;M count report (<code>dashboard/group/senior/mail/chart</code>) is emailed automatically in each hour ticked below.
                All hours are Kolkata (IST) time; it is now <strong>{{ $nowIst }} IST</strong>.
                The mail goes out once per ticked hour, within about 5 minutes after the hour starts.
            </p>

            <form method="POST" action="{{ route('smtp.reportmail.update') }}">
                @csrf

                <div class="row g-3 mb-20">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-sm">To</label>
                        <input type="text" name="emails" class="form-control" placeholder="main@example.com, second@example.com"
                            value="{{ old('emails', implode(', ', $recipients['to'])) }}">
                        @error('emails')<div class="text-danger-main text-sm mt-8">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-sm">CC</label>
                        <input type="text" name="cc" class="form-control" placeholder="cc1@example.com, cc2@example.com"
                            value="{{ old('cc', implode(', ', $recipients['cc'])) }}">
                        @error('cc')<div class="text-danger-main text-sm mt-8">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 mb-12">
                    <label class="form-label fw-semibold text-sm mb-0">Send in these hours (IST)</label>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="nightHours">Select all</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearHours">Clear all</button>
                </div>

                {{-- Exactly the report's items: the 9 slots (8:00pm to 5:00am), then "Total C&M Count" at the end --}}
                <div class="row g-2 mb-8" id="hourGrid">
                    @foreach (array_merge(range(20, 23), range(0, 4)) as $h)
                        <div class="col-6 col-sm-4 col-md-3 col-xl-2">
                            <label class="d-flex align-items-center gap-2 border radius-8 px-12 py-8" style="cursor:pointer;">
                                <input type="checkbox" class="form-check-input mt-0" name="hours[]" value="{{ $h }}"
                                    @checked(in_array($h, old('hours', $hours)))>
                                <span class="text-sm">{{ $label($h) }}</span>
                            </label>
                        </div>
                    @endforeach
                    <div class="col-12 col-sm-8 col-md-6 col-xl-4">
                        <label class="d-flex align-items-center gap-2 border border-success-main bg-success-focus radius-8 px-12 py-8" style="cursor:pointer;">
                            <input type="checkbox" class="form-check-input mt-0" name="hours[]" value="5"
                                @checked(in_array(5, old('hours', $hours)))>
                            <span class="text-sm fw-semibold">Total C&amp;M Count</span>
                        </label>
                    </div>
                </div>
                <div class="text-secondary-light text-sm mb-20">
                    Each slot is sent when that hour begins. <strong>Total C&amp;M Count</strong> is the full report with every slot,
                    sent at 5:00am after the last slot (4:00am - 5:00am) has finished.
                </div>
                @error('hours.*')<div class="text-danger-main text-sm mb-12">{{ $message }}</div>@enderror

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    @if (session('success'))
                        <span class="text-success-main text-sm">{{ session('success') }}</span>
                    @endif
                </div>
            </form>

            <hr class="my-24">

            <form method="POST" action="{{ route('smtp.reportmail.send') }}" onsubmit="this.querySelector('button').disabled=true;this.querySelector('button').innerText='Sending...';">
                @csrf
                <button type="submit" class="btn btn-outline-primary">Send report now (test)</button>
                <span class="text-secondary-light text-sm ms-8">Uses the saved recipients. Result appears in the send list.</span>
            </form>

            @php
                $slotEndHours = array_flip(\App\Services\GroupCallReportMail::HOUR_SLOTS);
                $nowHour = (int) now('Asia/Kolkata')->format('G');
                $defaultSlot = 8;
                for ($i = 0; $i < 24; $i++) {
                    $h = ($nowHour - $i + 24) % 24;
                    if (isset(\App\Services\GroupCallReportMail::HOUR_SLOTS[$h])) { $defaultSlot = \App\Services\GroupCallReportMail::HOUR_SLOTS[$h]; break; }
                }
            @endphp
            <form method="POST" action="{{ route('smtp.reportmail.sendslot') }}" class="mt-20 d-flex flex-wrap align-items-center gap-3"
                  onsubmit="var b=this.querySelector('button');b.disabled=true;b.innerText='Sending...';">
                @csrf
                <select name="slot" class="form-select w-auto">
                    @foreach (\App\Services\GroupCallReportMail::SLOTS as $i => $slotDef)
                        @if ($i < count(\App\Services\GroupCallReportMail::SLOTS) - 1)
                            <option value="{{ $i }}" @selected($i === $defaultSlot)>{{ $slotDef['title'] }} (sent at {{ str_pad($slotEndHours[$i], 2, '0', STR_PAD_LEFT) }}:00)</option>
                        @endif
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Send slot report now</button>
                <span class="text-secondary-light text-sm">Sends the latest report of the chosen slot to the saved recipients (the last slot also sends the full report), only when the call data of that slot has been uploaded. Result appears in the send list.</span>
            </form>

            @php
                $held = \App\Services\GroupCallReportMail::heldRows();
                $heldShifts = collect($held)->pluck('shift')->unique()->values();
            @endphp
            <hr class="my-24">
            <h6 class="mb-8">Mails waiting for call data</h6>
            <div class="text-secondary-light text-sm mb-12">
                A slot mail is never sent without its call duration. It is held until the PBX sheet covering that slot has been uploaded
                (Call Duration &rarr; Upload Report) and is then sent automatically, in slot order. Held mails never expire by themselves.
            </div>
            @if (count($held))
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle">
                        <thead><tr><th>Shift (8:00pm IST start)</th><th>Slot</th><th>Waiting since (IST)</th><th class="text-center">Action</th></tr></thead>
                        <tbody>
                            @foreach ($held as $h)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($h['shift'])->format('d M Y') }}</td>
                                    <td>{{ $h['title'] }}</td>
                                    <td>{{ $h['since'] }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('smtp.reportmail.release') }}" class="d-inline"
                                              onsubmit="return confirm('Send this slot mail now WITHOUT call duration?');">
                                            @csrf
                                            <input type="hidden" name="date" value="{{ $h['date'] }}">
                                            <input type="hidden" name="hour" value="{{ $h['hour'] }}">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Send now without call duration</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-12">
                    @foreach ($heldShifts as $shift)
                        <form method="POST" action="{{ route('smtp.reportmail.discard') }}"
                              onsubmit="return confirm('Discard all held mails of this shift? They will never be sent.');">
                            @csrf
                            <input type="hidden" name="shift" value="{{ $shift }}">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Discard held mails of {{ \Carbon\Carbon::parse($shift)->format('d M Y') }}</button>
                        </form>
                    @endforeach
                </div>
            @else
                <div class="text-sm text-secondary-light">No mail is waiting for call data.</div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            var boxes = document.querySelectorAll('#hourGrid input[type=checkbox]');
            document.getElementById('nightHours').addEventListener('click', function () {
                boxes.forEach(function (b) { var h = +b.value; b.checked = (h >= 20 || h <= 5); });
            });
            document.getElementById('clearHours').addEventListener('click', function () {
                boxes.forEach(function (b) { b.checked = false; });
            });
        })();
    </script>
@endsection
