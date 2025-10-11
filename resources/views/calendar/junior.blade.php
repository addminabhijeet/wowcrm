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


<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<!-- Include jsPDF -->
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
        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}`;
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        events: '/your-route-to-fetch-events',

        dateClick: function(info) {
            modalDate.textContent = info.dateStr;
            modalBody.innerHTML = '';

            const events = calendar.getEvents();
            const eventsOnDate = events.filter(event => {
                const eventDate = new Date(event.start).toISOString().split('T')[0];
                return eventDate === info.dateStr;
            });

            if (eventsOnDate.length > 0) {
                let totalBreakSec = 0;
                let lastPauseTime = null;

                // Top controls: PDF & Theme & Monthly Report
                modalBody.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <button class="btn btn-sm btn-outline-primary" id="downloadPDFBtn">Download PDF Report</button>
                            <button class="btn btn-sm btn-outline-success" id="downloadMonthlyBtn">Monthly Report</button>
                        </div>
                        <select class="form-select form-select-sm w-auto" id="themeSelector">
                            <option value="blue" selected>Blue Theme</option>
                            <option value="green">Green Theme</option>
                        </select>
                    </div>
                `;

                eventsOnDate.forEach(event => {
                    const eTime = new Date(event.start);
                    const pauseType = (event.extendedProps.pause_type || '').toLowerCase();

                    modalBody.innerHTML += `
                        <div class="event-item p-3 mb-3 border rounded bg-light shadow-sm">
                            <h5 class="fw-semibold mb-2 text-primary">${event.title}</h5>
                            <p><strong>Status:</strong> ${event.extendedProps.status}</p>
                            <p><strong>Time:</strong> ${eTime.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}</p>
                            <p><strong>Remaining Time:</strong> ${formatTime(event.extendedProps.remaining_seconds || 0)}</p>
                            <p><strong>Pause Type:</strong> ${pauseType}</p>
                        </div>
                    `;

                    if (pauseType === 'inactive') {
                        lastPauseTime = eTime;
                    } else if (pauseType === 'resume' && lastPauseTime) {
                        totalBreakSec += (eTime - lastPauseTime) / 1000;
                        lastPauseTime = null;
                    }
                });

                const startTime = new Date(eventsOnDate[0].start);
                const endTime = new Date(eventsOnDate[eventsOnDate.length - 1].start);
                const totalDaySec = (endTime - startTime) / 1000;
                const totalWorkSec = totalDaySec - totalBreakSec;
                const completed = totalWorkSec >= 8 * 3600 ? "✅ Yes" : "❌ No";

                modalBody.innerHTML += `
                    <div class="summary border-top pt-3 mt-4">
                        <h5 class="fw-semibold text-success">Summary</h5>
                        <p><strong>Total Time Logged:</strong> ${formatTime(totalDaySec)}</p>
                        <p><strong>Total Break Time:</strong> ${formatTime(totalBreakSec)}</p>
                        <p><strong>Effective Work Time:</strong> ${formatTime(totalWorkSec)}</p>
                        <p><strong>8 Hours Completed:</strong> ${completed}</p>
                    </div>
                `;

                // 🧾 Daily PDF
                const downloadBtn = modalBody.querySelector('#downloadPDFBtn');
                if (downloadBtn) {
                    downloadBtn.onclick = () => generatePDF(eventsOnDate, info.dateStr);
                }

                // 🗓️ Monthly PDF
                const downloadMonthlyBtn = modalBody.querySelector('#downloadMonthlyBtn');
                if (downloadMonthlyBtn) {
                    downloadMonthlyBtn.onclick = () => generateMonthlyPDF(calendar.getEvents());
                }

            } else {
                modalBody.innerHTML = '<p class="text-center text-muted">No events on this date.</p>';
            }

            modal.show();
        }
    });

    calendar.render();

    // ----------------------
    // PDF Generation Functions
    // ----------------------
    function generatePDF(events, dateStr) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');

        const theme = document.getElementById('themeSelector').value;
        const colors = {
            blue: { primary: [40, 60, 130], light: [230, 240, 255] },
            green: { primary: [22, 90, 50], light: [225, 245, 230] }
        };
        const selected = colors[theme];
        const logoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAACXBIWXMAAAsTAAALEwEAmpwYAAABpElEQVR4nO3QsQ2AIBAEwXvV/Tswo3aCscUlA1T+MQAAAAAAAAAAAAAAAAAAAOAq1F5zxt9K8c5O5R9Td3Pm+U+Se3L7f7t9y0hJAAAAAAAAAAAAAAAAAADg9+9BGgAAAAAAAAAAAAAAAM7fF2kAAAAAAAAAAAAAAAAAgIu0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAAC7QBqAAAAAAAAAAAAAAAAAAIi0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAAC7QBqAAAAAAAAAAAAAAAAAAIi0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAAC7QBqAAAAAAAAAAAAAAAAAAIi0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAPB8A6ZrJ3eA+jlNAAAAAElFTkSuQmCC';

        // Header
        doc.setFillColor(...selected.light);
        doc.rect(0, 0, 210, 25, 'F');
        doc.addImage(logoBase64, 'PNG', 10, 4, 20, 18);
        doc.setTextColor(...selected.primary);
        doc.setFontSize(18);
        doc.text("Employee Daily Work Report", 105, 15, { align: "center" });

        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text(`Date: ${dateStr}`, 10, 35);

        let y = 45;
        doc.setFontSize(11);
        doc.setTextColor(...selected.primary);
        doc.text("Event Details", 10, y);
        doc.setTextColor(0, 0, 0);
        y += 8;

        events.forEach(event => {
            const eTime = new Date(event.start);
            const pauseType = (event.extendedProps.pause_type || '').toLowerCase();

            doc.setFillColor(245, 245, 245);
            doc.rect(10, y - 4, 190, 30, 'F');
            doc.setDrawColor(200, 200, 200);
            doc.rect(10, y - 4, 190, 30);

            doc.text(`Event: ${event.title}`, 14, y);
            y += 6;
            doc.text(`Status: ${event.extendedProps.status}`, 14, y);
            y += 6;
            doc.text(`Time: ${eTime.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'})}`, 14, y);
            y += 6;
            doc.text(`Remaining: ${formatTime(event.extendedProps.remaining_seconds || 0)}`, 14, y);
            y += 6;
            doc.text(`Pause Type: ${pauseType}`, 14, y);
            y += 10;

            if (y > 260) { doc.addPage(); y = 20; }
        });

        doc.setFontSize(10);
        doc.setTextColor(120, 120, 120);
        doc.text("Generated by Work Tracker System © 2025", 105, 290, { align: "center" });

        doc.save(`work_report_${dateStr}_${theme}.pdf`);
    }

    function generateMonthlyPDF(events) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');

        const theme = document.getElementById('themeSelector').value;
        const colors = {
            blue: { primary: [40, 60, 130], light: [230, 240, 255] },
            green: { primary: [22, 90, 50], light: [225, 245, 230] }
        };
        const selected = colors[theme];
        const logoBase64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMgAAADICAYAAACtWK6eAAAACXBIWXMAAAsTAAALEwEAmpwYAAABpElEQVR4nO3QsQ2AIBAEwXvV/Tswo3aCscUlA1T+MQAAAAAAAAAAAAAAAAAAAOAq1F5zxt9K8c5O5R9Td3Pm+U+Se3L7f7t9y0hJAAAAAAAAAAAAAAAAAADg9+9BGgAAAAAAAAAAAAAAAM7fF2kAAAAAAAAAAAAAAAAAgIu0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAAC7QBqAAAAAAAAAAAAAAAAAAIi0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAAC7QBqAAAAAAAAAAAAAAAAAAIi0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAAC7QBqAAAAAAAAAAAAAAAAAAIi0AQAAAAAAAAAAAAAAAAAAu0AaAAAAAAAAAAAAAAAAAIDLtAEAAAAAAAAAAAAAAAAAALtAGgAAAAAAAAAAAAAAAAAAi7QBAAAAAAAAAAAAAAAAAPB8A6ZrJ3eA+jlNAAAAAElFTkSuQmCC';

        doc.setFillColor(...selected.light);
        doc.rect(0, 0, 210, 25, 'F');
        doc.addImage(logoBase64, 'PNG', 10, 4, 20, 18);
        doc.setTextColor(...selected.primary);
        doc.setFontSize(18);
        doc.text("Employee Monthly Work Report", 105, 15, { align: "center" });

        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text(`Generated Date: ${new Date().toISOString().split('T')[0]}`, 10, 35);

        let y = 45;
        doc.setFontSize(11);

        // Group events by date
        const grouped = {};
        events.forEach(ev => {
            const dateKey = new Date(ev.start).toISOString().split('T')[0];
            if (!grouped[dateKey]) grouped[dateKey] = [];
            grouped[dateKey].push(ev);
        });

        Object.keys(grouped).forEach(dateStr => {
            const dayEvents = grouped[dateStr];
            let totalBreakSec = 0;
            let lastPauseTime = null;

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
            const completed = totalWorkSec >= 8 * 3600 ? "✅ Yes" : "❌ No";

            doc.setTextColor(...selected.primary);
            doc.text(dateStr, 10, y);
            doc.setTextColor(0, 0, 0);
            y += 6;
            doc.text(`Total Logged: ${formatTime(totalDaySec)}`, 14, y);
            y += 5;
            doc.text(`Break Time: ${formatTime(totalBreakSec)}`, 14, y);
            y += 5;
            doc.text(`Effective Work: ${formatTime(totalWorkSec)}`, 14, y);
            y += 5;
            doc.text(`8 Hours Completed: ${completed}`, 14, y);
            y += 10;

            if (y > 260) { doc.addPage(); y = 20; }
        });

        doc.setFontSize(10);
        doc.setTextColor(120, 120, 120);
        doc.text("Generated by Work Tracker System © 2025", 105, 290, { align: "center" });

        doc.save(`monthly_report_${new Date().toISOString().split('T')[0]}_${theme}.pdf`);
    }

});
</script>
