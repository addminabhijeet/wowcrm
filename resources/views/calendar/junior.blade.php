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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const modalEl = document.getElementById('eventModal');
    const modal = new bootstrap.Modal(modalEl);
    const modalBody = document.getElementById('modalBody');
    const modalDate = document.getElementById('modalDate');

    function formatTime(sec) {
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
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
            return { domNodes: [] }; // hide default events
        },

        eventDidMount: function(info) {
            info.el.remove(); // hide default event element
            const cell = info.el.closest('.fc-daygrid-day');
            if (cell) cell.classList.add('has-event');
        },

        datesSet: function() {
            // After calendar renders, color-code days < 8h
            highlightUnderworkedDays(calendar);
        },

        dateClick: function(info) {
            modalDate.textContent = info.dateStr;
            modalBody.innerHTML = '';

            const eventsOnDate = calendar.getEvents().filter(e => e.startStr.slice(0,10) === info.dateStr);
            eventsOnDate.sort((a,b) => new Date(a.start) - new Date(b.start));

            if(eventsOnDate.length > 0){
                let totalBreakSec=0, lastPauseTime=null;

                modalBody.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button class="btn btn-sm btn-outline-primary" id="downloadPDFBtn">Download Daily PDF</button>
                            <button class="btn btn-sm btn-outline-success" id="downloadMonthlyBtn">Download Monthly PDF</button>
                        </div>
                        <select class="form-select form-select-sm w-auto" id="themeSelector">
                            <option value="blue" selected>Blue Theme</option>
                            <option value="green">Green Theme</option>
                        </select>
                    </div>
                `;

                eventsOnDate.forEach(event => {
                    const eTime = new Date(event.start);
                    modalBody.innerHTML += `
                        <div class="event-item p-16 mb-16 border rounded bg-light">
                            <h5 class="fw-semibold mb-8 text-primary">${event.title}</h5>
                            <p><strong>Status:</strong> ${event.extendedProps.status}</p>
                            <p><strong>Time:</strong> ${eTime.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</p>
                            <p><strong>Remaining Seconds:</strong> ${event.extendedProps.remaining_seconds}</p>
                            <p><strong>Pause Type:</strong> ${event.extendedProps.pause_type}</p>
                        </div>
                    `;

                    const pauseType = (event.extendedProps.pause_type||'').toLowerCase();
                    if(pauseType==='inactive') lastPauseTime=eTime;
                    else if(pauseType==='resume' && lastPauseTime){
                        totalBreakSec += (eTime-lastPauseTime)/1000;
                        lastPauseTime=null;
                    }
                });

                const startTime = new Date(eventsOnDate[0].start);
                const endTime = new Date(eventsOnDate[eventsOnDate.length-1].start);
                const totalDaySec = (endTime-startTime)/1000;
                const totalWorkSec = totalDaySec - totalBreakSec;
                const completed = totalWorkSec >= 8*3600 ? "✅ Yes" : "❌ No";

                modalBody.innerHTML += `
                    <div class="summary border-top pt-3 mt-4">
                        <h5 class="fw-semibold text-success">Summary</h5>
                        <p><strong>Total Time Logged:</strong> ${formatTime(totalDaySec)}</p>
                        <p><strong>Total Break Time:</strong> ${formatTime(totalBreakSec)}</p>
                        <p><strong>Effective Work Time:</strong> ${formatTime(totalWorkSec)}</p>
                        <p><strong>8 Hours Completed:</strong> ${completed}</p>
                    </div>
                `;

                document.getElementById('downloadPDFBtn').onclick = () => generateDailyPDF(eventsOnDate, info.dateStr);
                document.getElementById('downloadMonthlyBtn').onclick = () => generateMonthlyPDF(calendar.getEvents());
            } else {
                modalBody.innerHTML = '<p class="text-center text-muted">No events on this date.</p>';
            }

            modal.show();
        }
    });

    calendar.render();

    // ✅ Color-code calendar cells for days < 8h work
    function highlightUnderworkedDays(calendar){
        const allEvents = calendar.getEvents();
        const grouped = {};

        allEvents.forEach(ev=>{
            const dateKey = new Date(ev.start).toISOString().split('T')[0];
            if(!grouped[dateKey]) grouped[dateKey]=[];
            grouped[dateKey].push(ev);
        });

        Object.keys(grouped).forEach(dateStr=>{
            const dayEvents = grouped[dateStr];
            let totalBreakSec=0, lastPauseTime=null;

            dayEvents.forEach(ev=>{
                const eTime = new Date(ev.start);
                const pauseType=(ev.extendedProps.pause_type||'').toLowerCase();
                if(pauseType==='inactive') lastPauseTime=eTime;
                else if(pauseType==='resume' && lastPauseTime){
                    totalBreakSec += (eTime-lastPauseTime)/1000;
                    lastPauseTime=null;
                }
            });

            const startTime = new Date(dayEvents[0].start);
            const endTime = new Date(dayEvents[dayEvents.length-1].start);
            const totalDaySec = (endTime-startTime)/1000;
            const totalWorkSec = totalDaySec - totalBreakSec;

            const cell = calendarEl.querySelector(`.fc-daygrid-day[data-date='${dateStr}']`);
            if(cell){
                if(totalWorkSec < 8*3600){
                    cell.style.backgroundColor = 'rgba(220,50,50,0.3)'; // Red highlight
                } else {
                    cell.style.backgroundColor = 'rgba(0,123,255,0.08)'; // Normal has-event color
                }
            }
        });
    }

    // --- Existing PDF functions (generateDailyPDF, generateMonthlyPDF) remain exactly the same ---
    function generateDailyPDF(events, dateStr){ /* ... keep previous code ... */ }
    function generateMonthlyPDF(events){ /* ... keep previous code ... */ }

});
</script>

<style>
.fc-event, .fc-daygrid-event, .fc-event-dot, .fc-event-main,
.fc-daygrid-day-events, .fc-daygrid-event-harness, .fc-daygrid-event-harness-abs {
    display:none !important;
}
.fc-daygrid-day-frame { min-height:60px; padding:4px; display:block !important; }
.fc-day-today { background-color: rgba(0,123,255,0.1) !important; }
.fc-theme-standard td, .fc-theme-standard th { border:1px solid #e5e5e5 !important; }
.fc-daygrid-day.has-event { transition: background-color 0.2s ease; }
.fc-daygrid-day.has-event:hover { background-color: rgba(0,123,255,0.15); }
.fc-daygrid-day:hover { cursor:pointer; background-color: rgba(0,0,0,0.02); }
</style>
@endsection
