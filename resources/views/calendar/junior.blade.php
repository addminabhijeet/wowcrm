@extends('layout.layout')

@php
$title='Calendar';
$subTitle = 'Calendar';
@endphp

@section('content')

<div class="row gy-4">
    <div class="col-12">
        <div class="card h-100 p-0">
            <div class="card-body p-24">
                <div id='wrap'>
                    <div id='calendar'></div>
                    <div style='clear:both'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for showing count of events on a selected date -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="eventModalLabel">Events on <span id="modalDate"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24" id="modalBody">
                <!-- Event count will be dynamically displayed here -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: "{{ route('calendar.juniorEvents') }}", // AJAX source
        eventColor: '#378006',
        dateClick: function(info) {
            // Count events on the clicked date
            var eventsOnDate = calendar.getEvents().filter(event => {
                return event.startStr.slice(0,10) === info.dateStr;
            });

            var modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = '';

            // Show total count instead of listing events
            modalBody.innerHTML = `
                <div class="text-center">
                    <h2 class="text-primary-light fw-semibold text-lg">${eventsOnDate.length}</h2>
                    <p class="text-secondary-light">event(s) on this date</p>
                </div>
            `;

            document.getElementById('modalDate').innerText = info.dateStr;

            // Show modal
            var modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();
        }
    });

    calendar.render();
});
</script>

@endsection
