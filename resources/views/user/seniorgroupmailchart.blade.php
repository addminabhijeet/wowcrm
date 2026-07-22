@extends('layout.layout')

@section('content')

@php
$timeSlots = [
[
// Merge 8-9 AM + 9-10 AM + 10-11 AM
'title' => '8:00pm - 9:00pm',
'fields' => ['t8to9am', 't9to10am', 't10to11am']
],
[
'title' => '9:00pm - 10:00pm',
'fields' => ['t11to12pm']
],
[
'title' => '10:00pm - 11:00pm',
'fields' => ['t12to1pm']
],
[
'title' => '11:00pm - 12:00am',
'fields' => ['t1to2pm']
],
[
'title' => '12:00am - 1:00am',
'fields' => ['t2to3pm']
],
[
'title' => '1:00am - 2:00am',
'fields' => ['t3to4pm']
],
[
'title' => '2:00am - 3:00am',
'fields' => ['t4to5pm']
],
[
'title' => '3:00am - 4:00am',
'fields' => ['t5to6pm']
],
[
// Merge 6-7 PM + 7-8 PM
'title' => '4:00am - 5:00am',
'fields' => ['t6to7pm', 't7to8pm']
],
[
'title' => 'Total C&M Count',
'fields' => [
't8to9am',
't9to10am',
't10to11am',
't11to12pm',
't12to1pm',
't1to2pm',
't2to3pm',
't3to4pm',
't4to5pm',
't5to6pm',
't6to7pm',
't7to8pm'
]
],
];
@endphp

<div class="container-fluid">

    @foreach($timeSlots as $slot)

    <div class="card mb-5">

        <div class="card-body" id="copySection{{ $loop->index }}">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <h4 class="mb-0">
                    C&amp;M Count
                </h4>

                <button
                    type="button"
                    class="btn btn-primary btn-sm"
                    onclick="copySection('copySection{{ $loop->index }}', this)">
                    Copy
                </button>

            </div>

            <p>
                <strong>Date:</strong>
                {{ \Carbon\Carbon::today()->format('d-m-Y') }}
            </p>

            <p>
                <strong>{{ $slot['title'] }}</strong>
            </p>

            @foreach($seniors as $senior)

            <h5 class="mt-4">
                Team - {{ $senior->name }}
            </h5>

            <div>....................................</div>

            @if($senior->juniors->count())

            @foreach($senior->juniors as $junior)

            @php
            $count = 0;

            foreach ($slot['fields'] as $field) {
            $count += ($junior->{$field} ?? 0);
            }

            $todayLogin = \App\Models\Logins::where('user_id', $junior->id)
                ->whereDate('logged_in_at', \Carbon\Carbon::today())
                ->exists();

            $displayValue = $todayLogin ? $count : 'ab';
            @endphp

            {{ $junior->name }} - {{ $displayValue }}
            <br>

            @endforeach

            @else

            No juniors assigned.

            @endif

            <br>
            xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
            <br><br>

            @endforeach

            <p class="mt-4">
                <strong>
                    The above call numbers include only the "C&amp;M" counts.
                </strong>
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

        // Show the button again
        button.style.display = '';

        navigator.clipboard.writeText(text).then(function() {

            const originalText = button.innerHTML;

            button.innerHTML = 'Copied';

            setTimeout(function() {
                button.innerHTML = originalText;
            }, 1500);

        }).catch(function(error) {

            console.error(error);
            alert('Failed to copy.');

        });

    }
</script>

@endsection