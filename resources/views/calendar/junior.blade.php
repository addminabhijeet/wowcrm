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
        console.log("[Calendar] DOM loaded — initializing FullCalendar...");

        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) {
            console.error("[Calendar] Element with ID 'calendar' not found!");
            return;
        }

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

            eventContent: function() {
                console.log("[Calendar] eventContent called");
                return {
                    domNodes: []
                };
            },

            eventDidMount: function(info) {
                console.log("[Calendar] eventDidMount:", info.event.title, info.event.startStr);
                info.el.remove();
                const cell = info.el.closest('.fc-daygrid-day');
                if (cell) {
                    console.log("[Calendar] Adding 'has-event' class to cell for date:", cell.dataset.date);
                    cell.classList.add('has-event');
                } else {
                    console.warn("[Calendar] Could not find cell for event:", info.event.title);
                }
            },

            dateClick: function(info) {
                console.log("[Calendar] dateClick:", info.dateStr);

                var eventsOnDate = calendar.getEvents().filter(e => e.startStr.slice(0, 10) === info.dateStr);
                console.log(`[Calendar] Found ${eventsOnDate.length} event(s) on this date.`);

                eventsOnDate.sort((a, b) => new Date(a.start) - new Date(b.start));

                var modalBody = document.getElementById('modalBody');
                if (!modalBody) {
                    console.error("[Calendar] Modal body element not found!");
                    return;
                }
                modalBody.innerHTML = '';

                if (eventsOnDate.length > 0) {
                    eventsOnDate.forEach(event => {
                        console.log("[Calendar] Rendering event in modal:", event.title, event.extendedProps);
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
                    console.log("[Calendar] No events found for this date.");
                    modalBody.innerHTML = '<p class="text-center text-muted">No events on this date.</p>';
                }

                document.getElementById('modalDate').innerText = info.dateStr;

                console.log("[Calendar] Opening event modal...");
                new bootstrap.Modal(document.getElementById('eventModal')).show();
            }
        });

        console.log("[Calendar] Rendering calendar...");
        calendar.render();

        console.log("[Calendar] Initialization complete ✅");
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
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>
@endsection