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
    // Called & Mailed count of the same card, same numbers as dashboard/group/senior/mail/chart
    $cmValue = fn ($m, $slot) => $slot === null ? $m['cm_total'] : ($m['cm_slots'][$slot] ?? 0);
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
            </form>
        </div>
    </div>

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
                        {{ $m['name'] }} - {{ $m['absent'] ? 'ab' : $fmt($value($m['slots'], $m['total'], $card['slot'])) . ' | C&M ' . $cmValue($m, $card['slot']) }}
                        <br>
                    @empty
                        No juniors assigned.
                    @endforelse

                    <br>
                    xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
                    <br><br>
                @endforeach

                <p class="mt-4">
                    <strong>The above call numbers include the "Call Duration" (h:mm:ss, inbound + outbound) and only the "C&amp;M" counts.</strong>
                </p>
            </div>
        </div>
    @endforeach
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
