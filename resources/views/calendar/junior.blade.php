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

                <!-- Legend -->
                <div class="calendar-legend d-flex align-items-center mb-3">
                    <div class="legend-item d-flex align-items-center me-3">
                        <div class="legend-color" style="background-color: rgba(220,50,50,0.3); width: 20px; height: 20px; border-radius: 4px; margin-right: 6px;"></div>
                        <span>Less than 8h Work</span>
                    </div>
                    <div class="legend-item d-flex align-items-center">
                        <div class="legend-color" style="background-color: rgba(0,123,255,0.08); width: 20px; height: 20px; border-radius: 4px; margin-right: 6px;"></div>
                        <span>Completed ≥8h</span>
                    </div>
                </div>

                <!-- Calendar -->
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

        function formatTime(seconds) {
            seconds = Math.floor(seconds);
            const hrs = Math.floor(seconds / 3600);
            const mins = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            const hh = String(hrs).padStart(2, '0');
            const mm = String(mins).padStart(2, '0');
            const ss = String(secs).padStart(2, '0');
            return `${hh}:${mm}:${ss}`;
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
                return {
                    domNodes: []
                };
            },
            eventDidMount: function(info) {
                info.el.remove();
                const cell = info.el.closest('.fc-daygrid-day');
                if (cell) cell.classList.add('has-event');
            },
            datesSet: function() {
                highlightUnderworkedDays(calendar);
            },
            dateClick: function(info) {
                modalDate.textContent = info.dateStr;
                modalBody.innerHTML = '';

                const eventsOnDate = calendar.getEvents().filter(e => e.startStr.slice(0, 10) === info.dateStr);

                // Sort earliest first
                eventsOnDate.sort((a, b) => new Date(a.start) - new Date(b.start));

                if (eventsOnDate.length > 0) {
                    let totalWorkSec = 0;
                    let totalBreakSec = 0;
                    let lastWorkStart = null;
                    let lastBreakStart = null;
                    let tableRows = '';

                    const chronologicalEvents = [...eventsOnDate];

                    for (let i = 0; i < chronologicalEvents.length; i++) {
                        const event = chronologicalEvents[i];
                        const eTime = new Date(event.start);
                        const type = (event.extendedProps.pause_type || '').toLowerCase();

                        // next event
                        const next = chronologicalEvents[i + 1];
                        const nextTime = next ? new Date(next.start) : null;
                        const diffSec = nextTime ? (nextTime - eTime) / 1000 : 0;

                        let durationSec = Math.max(0, diffSec);
                        let label = '';

                        if (type === 'running') {
                            totalWorkSec += durationSec;
                            label = 'Work';
                        } else if (type === 'paused') {
                            totalBreakSec += durationSec;
                            label = 'Break';
                        }

                        // build row
                        tableRows += `
<tr>
    <td>${event.title}</td>
    <td>${eTime.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit', second:'2-digit' })}${nextTime ? ' - ' + nextTime.toLocaleTimeString([], { hour:'2-digit', minute:'2-digit', second:'2-digit' }) : ''}</td>
    <td>${formatTime(durationSec)}</td>
</tr>`;
                    }

                    // --- Work summary ---
                    const totalElapsedSec = totalWorkSec + totalBreakSec;
                    const targetSec = 8 * 3600;
                    const remainingSec = Math.max(targetSec - totalWorkSec, 0);
                    const completed = totalWorkSec >= targetSec ? "✅ Yes" : "❌ No";

                    tableRows += `
<tr class="fw-bold text-success">
    <td colspan="2" class="text-end">Total Work</td>
    <td>${formatTime(totalWorkSec)}</td>
</tr>
<tr class="fw-bold text-warning">
    <td colspan="2" class="text-end">Total Breaks</td>
    <td>${formatTime(totalBreakSec)}</td>
</tr>
<tr class="fw-bold text-primary">
    <td colspan="2" class="text-end">Elapsed / Remaining</td>
    <td>${formatTime(totalWorkSec)} / ${formatTime(remainingSec)}</td>
</tr>`;

                    const firstEvent = chronologicalEvents[0];
                    const lastEvent = chronologicalEvents[chronologicalEvents.length - 1];

                    const startTime = firstEvent.start ? new Date(firstEvent.start).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    }) : '—';

                    const endTime = lastEvent.start ? new Date(lastEvent.start).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    }) : '—';

                    modalBody.innerHTML = `
<div class="summary border-bottom pb-3 mb-3">
    <h5 class="fw-semibold text-success">Summary</h5>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>8 Hours Completed:</strong>
            <span class="badge ${totalWorkSec >= targetSec ? 'bg-success' : 'bg-danger'} fs-6">${completed}</span>
        </div>
        <div>
            <strong>Start Time:</strong>
            <span class="badge fs-6 bg-danger">${startTime}</span>
        </div>
        <div>
            <strong>End Time:</strong>
            <span class="badge fs-6 bg-danger">${endTime}</span>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-sm table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Event</th>
                <th>Time</th>
                <th>Duration</th>
            </tr>
        </thead>
        <tbody>${tableRows}</tbody>
    </table>
</div>

<div class="totals mt-3">
    <div class="d-flex justify-content-between fw-bold text-success">
        <span>Total Work Time:</span>
        <span>${formatTime(totalWorkSec)}</span>
    </div>
    <div class="d-flex justify-content-between fw-bold text-primary">
        <span>Elapsed / Remaining:</span>
        <span>${formatTime(totalWorkSec)} / ${formatTime(remainingSec)}</span>
    </div>
</div>`;

                    modal.show();
                } else {
                    modalBody.innerHTML = '<p class="text-center text-muted">No events on this date.</p>';
                }
            }

        });

        calendar.render()

        function highlightUnderworkedDays(calendar) {
            const allEvents = calendar.getEvents();
            const grouped = {};
            allEvents.forEach(ev => {
                const dateKey = new Date(ev.start).toISOString().split('T')[0];
                if (!grouped[dateKey]) grouped[dateKey] = [];
                grouped[dateKey].push(ev);
            });

            Object.keys(grouped).forEach(dateStr => {
                const dayEvents = grouped[dateStr];
                let totalBreakSec = 0,
                    lastPauseTime = null;

                dayEvents.forEach(ev => {
                    const eTime = new Date(ev.start);
                    const pauseType = (ev.extendedProps.pause_type || '').toLowerCase();
                    if (pauseType === 'inactive') lastPauseTime = eTime;
                    else if (pauseType === 'resume' && lastPauseTime) {
                        totalBreakSec += (eTime - lastPauseTime) / 1000;
                        lastPauseTime = null;
                    }
                });

                const startTime = new Date(dayEvents[0].start);
                const endTime = new Date(dayEvents[dayEvents.length - 1].start);
                const totalDaySec = (endTime - startTime) / 1000;
                const totalWorkSec = totalDaySec - totalBreakSec;

                const cell = calendarEl.querySelector(`.fc-daygrid-day[data-date='${dateStr}']`);
                if (cell) {
                    if (totalWorkSec < 8 * 3600) cell.style.backgroundColor = 'rgba(220,50,50,0.3)';
                    else cell.style.backgroundColor = 'rgba(0,123,255,0.08)';
                }
            });
        }


        function generateDailyPDF(events, dateStr) {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text(`Daily Report: ${dateStr}`, 10, 20);

            let y = 30;
            events.forEach(ev => {
                const time = new Date(ev.start).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                doc.setFontSize(12);
                doc.text(`${time} - ${ev.title} (${ev.extendedProps.status})`, 10, y);
                y += 10;
            });

            doc.save(`Daily_Report_${dateStr}.pdf`);
        }

        function generateMonthlyPDF(events) {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(16);
            doc.text(`Monthly Report`, 10, 20);

            let y = 30;
            events.forEach(ev => {
                const date = new Date(ev.start).toLocaleDateString();
                const time = new Date(ev.start).toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                doc.setFontSize(12);
                doc.text(`${date} ${time} - ${ev.title} (${ev.extendedProps.status})`, 10, y);
                y += 10;
                if (y > 280) {
                    doc.addPage();
                    y = 20;
                }
            });

            doc.save(`Monthly_Report.pdf`);
        }

    });
</script>

<style>
    .calendar-legend {
        font-size: 14px;
    }

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
        transition: background-color 0.2s ease;
    }

    .fc-daygrid-day.has-event:hover {
        background-color: rgba(0, 123, 255, 0.15);
    }

    .fc-daygrid-day:hover {
        cursor: pointer;
        background-color: rgba(0, 0, 0, 0.02);
    }

    .fc .fc-button {
        padding: 0.2em 0.65em !important;
    }
</style>
@endsection