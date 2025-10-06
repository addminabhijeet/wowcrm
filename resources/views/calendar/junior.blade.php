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

<!-- Modal for showing events of a selected date -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="eventModalLabel">Events on <span id="modalDate"></span></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-24" id="modalBody">
                <!-- Events will be dynamically appended here -->
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
            // Filter events on the clicked date
            var eventsOnDate = calendar.getEvents().filter(event => {
                return event.startStr.slice(0,10) === info.dateStr;
            });

            var modalBody = document.getElementById('modalBody');
            modalBody.innerHTML = '';

            if(eventsOnDate.length > 0) {
                eventsOnDate.forEach(function(event) {
                    modalBody.innerHTML += `
                        <div class="mb-24 border-bottom pb-16">
                            <div class="mb-12">
                                <span class="text-secondary-light txt-sm fw-medium">Title</span>
                                <h6 class="text-primary-light fw-semibold text-md mt-4">${event.title}</h6>
                            </div>
                            <div class="mb-12">
                                <span class="text-secondary-light txt-sm fw-medium">Start Date</span>
                                <h6 class="text-primary-light fw-semibold text-md mt-4">${new Date(event.start).toLocaleString()}</h6>
                            </div>
                            <div class="mb-12">
                                <span class="text-secondary-light txt-sm fw-medium">End Date</span>
                                <h6 class="text-primary-light fw-semibold text-md mt-4">${event.end ? new Date(event.end).toLocaleString() : ''}</h6>
                            </div>
                            <div class="mb-12">
                                <span class="text-secondary-light txt-sm fw-medium">Description</span>
                                <h6 class="text-primary-light fw-semibold text-md mt-4">${event.extendedProps.description || 'N/A'}</h6>
                            </div>
                            <div class="mb-12">
                                <span class="text-secondary-light txt-sm fw-medium">Label</span>
                                <h6 class="text-primary-light fw-semibold text-md mt-4 d-flex align-items-center gap-2">
                                    <span class="w-8-px h-8-px rounded-circle"></span>
                                    ${event.extendedProps.label || 'General'}
                                </h6>
                            </div>
                        </div>
                    `;
                });
            } else {
                modalBody.innerHTML = '<p class="text-secondary-light">No events on this date.</p>';
            }

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
