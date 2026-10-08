@extends('layout.layout')
@php
    $title = 'Call Duration -> Group Report';
    $role = auth()->user()->role ?? '';
    $subTitle = $role === 'admin' ? 'Super Admin' : ($role === 'operation' ? 'Operation Manager' : 'role');

    // Same cards as dashboard/group/senior/mail/chart: 8:00pm - 9:00pm ... 4:00am - 5:00am, then the total
    $cards = [];
    foreach ($slotTitles as $i => $t) {
        $cards[] = ['title' => $t, 'slot' => $i];
    }
    $cards[] = ['title' => 'Total Call Duration', 'slot' => null];
    $value = fn ($slots, $total, $slot) => $slot === null ? $total : ($slots[$slot] ?? 0);
@endphp

@section('content')
<div class="container-fluid">

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('senior.excelgroup') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-sm">Date</label>
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Show</button>
                    <a href="{{ route('senior.excelgroup') }}" class="btn btn-outline-secondary">Today</a>
                </div>
                <div class="col-md-6 text-secondary-light text-sm">
                    Calls of {{ $dateLabel }} from 8:00pm IST to 5:00am IST, {{ number_format($calls) }} calls. The PBX export is in US Eastern time
                    (America/New_York); each call is converted to IST and counted in its IST hour.
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))<div class="alert alert-success mb-4">{{ session('success') }}</div>@endif

    @foreach ($cards as $card)
        <div class="card mb-5">
            <div class="card-body" id="copySection{{ $loop->index }}">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Call Duration</h4>
                    <button type="button" class="btn btn-primary btn-sm" onclick="copySection('copySection{{ $loop->index }}', this)">Copy</button>
                </div>

                <p><strong>Date:</strong> {{ $dateLabel }}</p>
                <p><strong>{{ $card['title'] }}</strong></p>

                @foreach ($teams as $team)
                    <h5 class="mt-4">Team - {{ $team['name'] }}</h5>
                    <div>....................................</div>

                    @forelse ($team['members'] as $m)
                        {{ $m['name'] }} - {{ $m['absent'] ? 'ab' : $fmt($value($m['slots'], $m['total'], $card['slot'])) }}
                        <br>
                    @empty
                        No juniors assigned.
                    @endforelse

                    <br>
                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                    <br><br>
                @endforeach

                @php
                    $otherRows = collect($others)->filter(fn ($o) => $value($o['slots'], $o['total'], $card['slot']) > 0);
                @endphp
                @if ($otherRows->isNotEmpty())
                    <h5 class="mt-4">Other extensions</h5>
                    <div>....................................</div>
                    @foreach ($otherRows as $o)
                        {{ $o['name'] }} - {{ $fmt($value($o['slots'], $o['total'], $card['slot'])) }}
                        <br>
                    @endforeach
                    <br>
                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                    <br><br>
                @endif

                <p class="mt-4">
                    <strong>The above call numbers include only the "Call Duration" (h:mm:ss, inbound + outbound).</strong>
                </p>
            </div>
        </div>
    @endforeach

    {{-- Extension -> recruiter assignment (the PBX only knows extensions) --}}
    <div class="card mb-5">
        <div class="card-header">
            <h6 class="mb-0">Extensions</h6>
            <div class="text-secondary-light text-sm mt-4">Choose the recruiter of each extension seen in the uploaded calls. "Suggested" comes from the name the PBX shows for that extension and is only saved when you click Save.</div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('senior.excelgroup.extensions') }}">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle">
                        <thead><tr><th>Extension</th><th>Name in PBX</th><th>Recruiter</th></tr></thead>
                        <tbody>
                            @forelse ($mapping as $row)
                                <tr>
                                    <td>{{ $row['ext'] }}</td>
                                    <td>{{ $row['hint'] ?: '-' }}</td>
                                    <td>
                                        <select name="map[{{ $row['ext'] }}]" class="form-select">
                                            <option value="">-- not assigned --</option>
                                            @foreach ($people as $p)
                                                <option value="{{ $p->id }}" @selected(($row['user_id'] ?? $row['suggest']) == $p->id)>{{ $p->name }} ({{ $p->role }})</option>
                                            @endforeach
                                        </select>
                                        @if ($row['suggest'] && !$row['user_id'])<span class="text-warning-main text-sm">Suggested</span>@endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-secondary-light py-24">Upload call records first (Upload Report).</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if (count($mapping))<button type="submit" class="btn btn-primary mt-16">Save</button>@endif
            </form>
        </div>
    </div>
</div>

<script>
    function copySection(sectionId, button) {
        const element = document.getElementById(sectionId);

        // Hide the button temporarily so its text isn't copied
        button.style.display = 'none';
        const text = element.innerText;
        button.style.display = '';

        navigator.clipboard.writeText(text).then(function () {
            const originalText = button.innerHTML;
            button.innerHTML = 'Copied';
            setTimeout(function () { button.innerHTML = originalText; }, 1500);
        }).catch(function (error) {
            console.error(error);
            alert('Failed to copy.');
        });
    }
</script>
@endsection
