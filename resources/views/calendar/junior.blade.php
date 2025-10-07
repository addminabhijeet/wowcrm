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

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="eventModalLabel">Events on <span id="modalDate"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24" id="modalBody"></div>
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
        events: "{{ route('calendar.juniorEvents') }}",
        displayEventTime: false,
        displayEventEnd: false,

        // Prevent default event display completely
        eventContent: function() {
            return { domNodes: [] };
        },

        // Ensure no inline color or background is applied
        eventDidMount: function(info) {
            info.el.style.backgroundColor = 'transparent';
            info.el.style.borderColor = 'transparent';
            info.el.style.color = 'transparent';
            info.el.style.boxShadow = 'none';
        },

        // Handle date click to show modal with events
        dateClick: function(info) {
            var eventsOnDate = calendar.getEvents().filter(event => {
                return event.startStr.slice(0, 10) === info.dateStr;
            });

            eventsOnDate.sort((a, b) => new Date(a.start) - new Date(b.start));

            var modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = '';

            if (eventsOnDate.length > 0) {
                eventsOnDate.forEach(function(event) {
                    modalBody.innerHTML += `
                        <div class="event-item p-16 mb-16 border rounded shadow-sm bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-8">
                                <h5 class="fw-semibold">${event.title}</h5>
                            </div>
                            <p class="mb-4"><strong>Status:</strong> ${event.extendedProps.status}</p>
                            <p class="mb-4"><strong>Time:</strong> ${new Date(event.start).toLocaleString()}</p>
                            <p class="mb-4"><strong>Remaining Seconds:</strong> ${event.extendedProps.remaining_seconds}</p>
                            <p class="mb-0"><strong>Pause Type:</strong> ${event.extendedProps.pause_type}</p>
                        </div>
                    `;
                });
            } else {
                modalBody.innerHTML = '<p class="text-secondary-light text-center">No events on this date.</p>';
            }

            document.getElementById('modalDate').innerText = info.dateStr;

            var modal = new bootstrap.Modal(document.getElementById('eventModal'));
            modal.show();
        }
    });

    calendar.render();
});
</script>

<style>
/* Completely hide all event visuals */
.fc-event,
.fc-daygrid-event,
.fc-event-dot,
.fc-event-main,
.fc-daygrid-day-events,
.fc-daygrid-day-top .fc-daygrid-event-harness {
    background: transparent !important;
    border: none !important;
    color: transparent !important;
    box-shadow: none !important;
    visibility: hidden !important;
}

/* Remove spacing left by hidden events */
.fc-daygrid-day-frame {
    min-height: auto !important;
}

/* Keep cells and calendar clean */
.fc-daygrid-day-number {
    z-index: 2;
}
</style>

@endsection
