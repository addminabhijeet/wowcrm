@extends('layout.layout')

@section('content')

@php
$timeSlots = [
['title' => '8:00am - 9:00am', 'field' => 't8to9am'],
['title' => '9:00am - 10:00am', 'field' => 't9to10am'],
['title' => '10:00am - 11:00am','field' => 't10to11am'],
['title' => '11:00am - 12:00pm','field' => 't11to12pm'],
['title' => '12:00pm - 1:00pm', 'field' => 't12to1pm'],
['title' => '1:00pm - 2:00pm', 'field' => 't1to2pm'],
['title' => '2:00pm - 3:00pm', 'field' => 't2to3pm'],
['title' => '3:00pm - 4:00pm', 'field' => 't3to4pm'],
['title' => '4:00pm - 5:00pm', 'field' => 't4to5pm'],
['title' => '5:00pm - 6:00pm', 'field' => 't5to6pm'],
['title' => '6:00pm - 7:00pm', 'field' => 't6to7pm'],
['title' => '7:00pm - 8:00pm', 'field' => 't7to8pm'],
];
@endphp

<div class="container-fluid">

    @foreach($timeSlots as $slot)

    <div class="card mb-5">
        <div class="card-body">

            <h4><strong>C&amp;M Count</strong></h4>

            <p><strong>Date:</strong> {{ \Carbon\Carbon::today()->format('d-m-Y') }}</p>

            <p><strong>{{ $slot['title'] }}</strong></p>

            @foreach($seniors as $senior)

            <h5 class="mt-4">
                <strong>Team - {{ $senior->name }}</strong>
            </h5>

            <div>....................................</div>

            @if($senior->juniors->count())

            @foreach($senior->juniors as $junior)

            {{ $junior->name }} -
            {{ $junior->{$slot['field']} ?? 0 }}

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

@endsection