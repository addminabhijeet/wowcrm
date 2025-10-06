<div id='calendar'></div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border-bottom">
                <h5 class="modal-title" id="modalDate"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-24" id="modalBody"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        events: "{{ route('calendar.juniorEvents') }}",
        eventContent: function(arg) {
            // Custom event content with colored dot
            const dot = document.createElement('span');
            dot.style.backgroundColor = arg.event.extendedProps.label_color;
            dot.className = 'event-dot';
            dot.title = arg.event.title;

            const wrapper = document.createElement('div');
            wrapper.appendChild(dot);
            wrapper.append(' ' + arg.event.title);

            return { domNodes: [wrapper] };
        },
        dateClick: function(info) {
            const eventsOnDate = calendar.getEvents()
                .filter(e => e.startStr.slice(0,10) === info.dateStr)
                .sort((a,b) => new Date(a.start) - new Date(b.start));

            const modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = '';

            if(eventsOnDate.length) {
                eventsOnDate.forEach(event => {
                    modalBody.innerHTML += `
                        <div class="event-item p-16 mb-16 border rounded shadow-sm bg-light">
                            <div class="d-flex justify-content-between mb-8">
                                <h5>${event.title}</h5>
                                <span class="badge" style="background-color:${event.extendedProps.label_color}; color:white;">
                                    ${event.extendedProps.label}
                                </span>
                            </div>
                            <p><strong>Time:</strong> ${new Date(event.start).toLocaleString()} - ${event.end ? new Date(event.end).toLocaleString() : 'N/A'}</p>
                            <p><strong>Description:</strong> ${event.extendedProps.description || 'N/A'}</p>
                        </div>
                    `;
                });
            } else {
                modalBody.innerHTML = '<p class="text-center text-secondary">No events on this date.</p>';
            }

            document.getElementById('modalDate').innerText = info.dateStr;
            new bootstrap.Modal(document.getElementById('eventModal')).show();
        }
    });

    calendar.render();
});
</script>

<style>
.event-dot { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:4px; }
.event-item h5 { font-size:16px; margin-bottom:0; }
.event-item { background:#f8f9fa; }
</style>
