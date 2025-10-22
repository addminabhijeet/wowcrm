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
            events: "{{ route('calendar.allJuniorEvents', ['userId' => $junior->id]) }}",

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

                // ✅ Helper for proper HH:MM:SS formatting
                function formatTimeSeconds(sec) {
                    sec = Number(sec) || 0;
                    sec = Math.max(0, Math.round(sec));
                    const h = Math.floor(sec / 3600);
                    const m = Math.floor((sec % 3600) / 60);
                    const s = sec % 60;
                    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                }

                if (eventsOnDate.length > 0) {
                    let totalBreakSec = 0;
                    let totalWorkSec = 0;
                    let lastPauseTime = null;
                    let tableRows = '';

                    const chronologicalEvents = [...eventsOnDate];

                    // Find the event titled 'start' (case-insensitive)
                    const startEvent = chronologicalEvents.find(ev => ev.title.toLowerCase() === 'start');

                    const startTime = startEvent && startEvent.start ?
                        new Date(startEvent.start).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        }) :
                        'null';
                    const endTime = chronologicalEvents[chronologicalEvents.length - 1].end ?
                        new Date(chronologicalEvents[chronologicalEvents.length - 1].end).toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit',
                            second: '2-digit'
                        }) :
                        'null';

                    for (let i = 0; i < chronologicalEvents.length; i++) {
                        const event = chronologicalEvents[i];
                        const eTime = new Date(event.start);
                        const type = (event.extendedProps.pause_type || '').toLowerCase();
                        let breakTime = 0,
                            workTime = 0;

                        if (type === 'inactive') lastPauseTime = eTime;
                        else if ((type === 'resume' || type === 'running') && lastPauseTime) {
                            breakTime = (eTime - lastPauseTime) / 1000;
                            totalBreakSec += breakTime;
                            lastPauseTime = null;
                        }

                        // ✅ Calculate accurate duration until next event or end
                        let durationSec = 0;
                        const nextEvent = chronologicalEvents[i + 1];

                        if (nextEvent) {
                            // Consider candidate times from the next event (start and end) and choose the earliest one that's after current start
                            const candidates = [];
                            const nextStart = nextEvent.start ? new Date(nextEvent.start) : null;
                            const nextEnd = nextEvent.end ? new Date(nextEvent.end) : null;
                            if (nextStart) candidates.push(nextStart);
                            if (nextEnd) candidates.push(nextEnd);

                            // Also consider current event's own end as a fallback candidate
                            const eventEnd = event.end ? new Date(event.end) : null;
                            if (eventEnd) candidates.push(eventEnd);

                            // Filter candidates to those strictly after eTime
                            const validCandidates = candidates.filter(t => t && t > eTime);

                            if (validCandidates.length > 0) {
                                // pick the earliest valid candidate
                                let earliest = validCandidates.reduce((a, b) => (a < b ? a : b));
                                durationSec = (earliest - eTime) / 1000;
                            } else {
                                // No valid candidate after eTime; duration remains 0
                                durationSec = 0;
                            }
                        } else if (event.end) {
                            // last event with valid end time
                            const evEnd = new Date(event.end);
                            if (evEnd > eTime) durationSec = (evEnd - eTime) / 1000;
                            else durationSec = 0;
                        }

                        // ✅ Add only active work duration to total work seconds
                        if (
                            type !== 'inactive' &&
                            event.title.toLowerCase() !== 'break' &&
                            event.title.toLowerCase() !== 'tea' &&
                            event.title.toLowerCase() !== 'lunch'
                        ) {
                            totalWorkSec += durationSec;
                        }

                        tableRows += `
<tr>
    <td>${event.title}</td>
    <td>
    ${eTime.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
    ${
        (() => {
            const nextEvent = chronologicalEvents[i + 1];
            if (!nextEvent) return '';
            const nextStart = nextEvent.start ? new Date(nextEvent.start) : null;
            const nextEnd = nextEvent.end ? new Date(nextEvent.end) : null;
            // choose the earliest time from nextStart/nextEnd that is after eTime
            const candidates = [];
            if (nextStart) candidates.push(nextStart);
            if (nextEnd) candidates.push(nextEnd);
            const valid = candidates.filter(t => t && t > eTime);
            if (valid.length === 0) {
                // fallback to current event end if present and after eTime
                if (event.end && new Date(event.end) > eTime) {
                    return ' - ' + new Date(event.end).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                }
                return '';
            }
            const chosen = valid.reduce((a, b) => (a < b ? a : b));
            return ' - ' + chosen.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        })()
    }
</td>
    <td>${formatTimeSeconds(durationSec)}</td>
</tr>`;
                    }

                    // ✅ Calculate total / elapsed / remaining times correctly
                    const targetSec = 8 * 3600;
                    const elapsedSec = totalWorkSec;
                    const remainingSec = Math.max(targetSec - totalWorkSec, 0);
                    const completed = totalWorkSec >= targetSec ? "✅ Yes" : "❌ No";

                    tableRows += `
<tr class="fw-bold text-success">
    <td colspan="2" class="text-end">Total</td>
    <td>${formatTimeSeconds(elapsedSec)}</td>
</tr>
<tr class="fw-bold text-primary">
    <td colspan="2" class="text-end">Elapsed / Remaining</td>
    <td colspan="2">${formatTimeSeconds(elapsedSec)} / ${formatTimeSeconds(remainingSec)}</td>
</tr>`;

                    modalBody.innerHTML = `
<div class="summary border-bottom pb-3 mb-3">
    <h5 class="fw-semibold text-success">Summary</h5>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>8 Hours Completed:</strong>
            <span class="badge ${elapsedSec >= targetSec ? 'bg-success' : 'bg-danger'} fs-6">${completed}</span>
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
        <span>${formatTimeSeconds(elapsedSec)}</span>
    </div>
    <div class="d-flex justify-content-between fw-bold text-primary">
        <span>Elapsed / Remaining:</span>
        <span>${formatTimeSeconds(elapsedSec)} / ${formatTimeSeconds(remainingSec)}</span>
    </div>
</div>`;

                    // --- MERGE & SHOW ONLY FIRST AND LAST TIME FOR CONSECUTIVE DUPLICATE EVENTS ---
                    const tbody = modalBody.querySelector('tbody');
                    const allRows = Array.from(tbody.querySelectorAll('tr'));
                    let mergedRows = [];
                    let prevEventName = '';

                    for (let i = 0; i < allRows.length; i++) {
                        const curr = allRows[i];
                        if (!curr || curr.classList.contains('fw-bold')) continue;

                        const currEvent = curr.cells[0]?.textContent.trim();
                        const currTime = curr.cells[1]?.textContent.trim();
                        const currDuration = curr.cells[2]?.textContent.trim();

                        if (currEvent === 'Resumebreak') continue;

                        let firstTime = currTime.split(' - ')[0];
                        let lastTime = currTime.split(' - ').pop();

                        // Check consecutive duplicates
                        let j = i + 1;
                        while (j < allRows.length) {
                            const next = allRows[j];
                            if (!next || next.classList.contains('fw-bold')) break;

                            const nextEvent = next.cells[0]?.textContent.trim();
                            const nextTimeRaw = next.cells[1]?.textContent.trim();
                            const nextTime = nextTimeRaw.split(' - ').pop();

                            if (nextEvent === currEvent) {
                                lastTime = nextTime; // extend last time
                                j++;
                            } else break;
                        }

                        // Create merged row
                        const mergedRow = document.createElement('tr');
                        mergedRow.innerHTML = `
<td>${currEvent}</td>
<td>${firstTime} - ${lastTime}</td>
<td>${currDuration}</td>`;
                        mergedRows.push(mergedRow);

                        prevEventName = currEvent;
                        i = j - 1; // skip processed rows
                    }

                    tbody.innerHTML = '';
                    mergedRows.forEach(r => tbody.appendChild(r));

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

            // Group events by date
            allEvents.forEach(ev => {
                const dateKey = ev.startStr.slice(0, 10); // YYYY-MM-DD
                if (!grouped[dateKey]) grouped[dateKey] = [];
                grouped[dateKey].push(ev);
            });

            Object.keys(grouped).forEach(dateStr => {
                const dayEvents = grouped[dateStr];

                // ✅ Sum remaining_seconds for the day
                const totalWorkSec = dayEvents.reduce((sum, ev) => {
                    return sum + (parseInt(ev.extendedProps.remaining_seconds) || 0);
                }, 0);

                const cell = calendarEl.querySelector(`.fc-daygrid-day[data-date='${dateStr}']`);
                if (cell) {
                    if (totalWorkSec < 8 * 3600) {
                        cell.style.backgroundColor = 'rgba(220,50,50,0.3)'; // Less than 8h
                    } else {
                        cell.style.backgroundColor = 'rgba(0,123,255,0.08)'; // Completed ≥8h
                    }
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
</style>
@endsection