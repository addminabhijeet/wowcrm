@extends('layout.layout')

@php
$title = 'Calendar';
$subTitle = 'Calendar';
@endphp

@section('content')
<div class="row gy-4">
    <div class="col-12">
        <div class="card h-100 p-0">
            <div class="card-body p-24">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border-0">
                <h1 class="modal-title fs-5" id="eventModalLabel">
                    Events on <span id="modalDate"></span>
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

        eventContent: function() { return { domNodes: [] }; },

        eventDidMount: function(info) {
            info.el.remove();
            const cell = info.el.closest('.fc-daygrid-day');
            if (cell) {
                cell.classList.add('has-event');
            }
        },

        dateClick: function(info) {
            var eventsOnDate = calendar.getEvents().filter(e => e.startStr.slice(0, 10) === info.dateStr);
            eventsOnDate.sort((a, b) => new Date(a.start) - new Date(b.start));

            var modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = '';

            if (eventsOnDate.length > 0) {
                eventsOnDate.forEach(event => {
                    modalBody.innerHTML += `
                        <div class="event-item p-16 mb-16 border rounded bg-light">
                            <h5 class="fw-semibold mb-8">${event.title}</h5>
                            <p><strong>Status:</strong> ${event.extendedProps.status}</p>
                            <p><strong>Time:</strong> ${new Date(event.start).toLocaleString()}</p>
                            <p><strong>Remaining Seconds:</strong> ${event.extendedProps.remaining_seconds}</p>
                            <p><strong>Pause Type:</strong> ${event.extendedProps.pause_type}</p>
                        </div>
                    `;
                });
            } else {
                modalBody.innerHTML = '<p class="text-center text-muted">No events on this date.</p>';
            }

            document.getElementById('modalDate').innerText = info.dateStr;
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        }
    });

    calendar.render();
});
</script>

<style>
.fc-event,
.fc-daygrid-event,
.fc-event-dot,
.fc-event-main,
.fc-daygrid-day-events,
.fc-daygrid-event-harness,
.fc-daygrid-event-harness-abs {
    display: none !important;
}

.fc-daygrid-day-frame {
    min-height: 60px;
    padding: 4px;
    display: block !important;
}

.fc-day-today {
    background-color: rgba(0, 123, 255, 0.1) !important;
}

.fc-theme-standard td,
.fc-theme-standard th {
    border: 1px solid #e5e5e5 !important;
}

.fc-daygrid-day.has-event {
    background-color: rgba(0, 123, 255, 0.08);
    transition: background-color 0.2s ease;
}

.fc-daygrid-day.has-event:hover {
    background-color: rgba(0, 123, 255, 0.15);
}

.fc-daygrid-day:hover {
    cursor: pointer;
    background-color: rgba(0,0,0,0.02);
}
</style>
@endsection
