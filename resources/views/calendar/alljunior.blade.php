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

                // Sort latest first
                eventsOnDate.sort((a, b) => new Date(b.start) - new Date(a.start));

                if (eventsOnDate.length > 0) {
                    let totalBreakSec = 0;
                    let totalWorkSec = 0;
                    let lastPauseTime = null;
                    let tableRows = '';

                    const chronologicalEvents = [...eventsOnDate].reverse(); // earliest first

                    for (let i = 0; i < chronologicalEvents.length; i++) {
                        const event = chronologicalEvents[i];
                        const eTime = new Date(event.start);
                        const type = (event.extendedProps.pause_type || '').toLowerCase();
                        let breakTime = 0,
                            workTime = 0;

                        if (type === 'inactive') {
                            lastPauseTime = eTime;
                        } else if ((type === 'resume' || type === 'running') && lastPauseTime) {
                            // Calculate break
                            breakTime = (eTime - lastPauseTime) / 1000; // seconds
                            totalBreakSec += breakTime;
                            lastPauseTime = null;
                        }

                        // Work time = difference to previous event minus break
                        if (i > 0) {
                            let prevTime = new Date(chronologicalEvents[i - 1].start);
                            workTime = (eTime - prevTime) / 1000; // seconds
                            if (workTime < 0) workTime = 0;
                            totalWorkSec += workTime;
                        }

                        tableRows += `
                <tr>
    <td>${event.title}</td>
    <td>${eTime.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</td>
    <td>
        Work: ${formatTime(workTime)}
        ${breakTime > 0 ? ` / Break: ${formatTime(breakTime)}` : ''}
    </td>
</tr>
            `;
                    }

                    // Calculate elapsed and remaining hours
                    const targetSec = 8 * 3600; // 8 hours target
                    const elapsedSec = totalWorkSec;
                    const remainingSec = Math.max(targetSec - totalWorkSec, 0);

                    // Add total row
                    tableRows += `
            <tr class="fw-bold text-success">
                <td colspan="2" class="text-end">Total</td>
                <td>${formatTime(totalWorkSec)}</td>
            </tr>
            <tr class="fw-bold text-primary">
                <td colspan="2" class="text-end">Elapsed / Remaining</td>
                <td colspan="2">${formatTime(elapsedSec)} / ${formatTime(remainingSec)}</td>
            </tr>
        `;

                    const completed = totalWorkSec >= targetSec ? "✅ Yes" : "❌ No";

                    modalBody.innerHTML = `
            <div class="summary border-bottom pb-3 mb-3">
    <h5 class="fw-semibold text-success">Summary</h5>
    <p><strong>8 Hours Completed:</strong> ${completed}</p>
</div>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Event</th>
            <th>Time</th>
            <th>Duration</th> <!-- Combined Work + Break -->
        </tr>
    </thead>
    <tbody>
        ${tableRows}
    </tbody>
</table>
        `;
                    // ✅ Hide rows with same data as previous row
                    const rows = modalBody.querySelectorAll('tbody tr');
                    for (let i = 1; i < rows.length - 2; i++) { // skip first and last total rows
                        const currCells = Array.from(rows[i].cells).map(td => td.textContent.trim());
                        const prevCells = Array.from(rows[i - 1].cells).map(td => td.textContent.trim());
                        if (JSON.stringify(currCells) === JSON.stringify(prevCells)) {
                            rows[i].style.display = 'none';
                        }
                    }

                } else {
                    modalBody.innerHTML = '<p class="text-center text-muted">No events on this date.</p>';
                }

                modal.show();
            }


        });

        calendar.render();

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