<?php
$title='Calendar';
$subTitle = 'Calendar';
?>

<?php $__env->startSection('content'); ?>

<div class="row gy-4">
    <div class="col-xxl-3 col-lg-4">
        <div class="card h-100 p-0">
            <div class="card-body p-24">
                <div class="mt-32">
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="event-item d-flex align-items-center justify-content-between gap-4 pb-16 mb-16 border border-start-0 border-end-0 border-top-0">
                        <div class="">
                            <div class="d-flex align-items-center gap-10">
                                <span class="w-12-px h-12-px bg-warning-600 rounded-circle fw-medium"></span>
                                <span class="text-secondary-light">
                                    <?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d M Y, h:i A')); ?> -
                                    <?php echo e(\Carbon\Carbon::parse($event->end_date)->format('d M Y, h:i A')); ?>

                                </span>
                            </div>
                            <span class="text-primary-light fw-semibold text-md mt-4">Take <?php echo e($event->status); ?> for <?php echo e($event->pause_type); ?></span>
                        </div>
                        <div class="dropdown">
                            <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="entypo:dots-three-vertical" class="icon text-secondary-light"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu p-12 border bg-base shadow">
                                <li>
                                    <button type="button" class="dropdown-item px-16 py-8 rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-10"
                                        data-bs-toggle="modal"
                                        data-bs-target="#eventModal<?php echo e($event->id); ?>">
                                        <iconify-icon icon="hugeicons:view" class="icon text-lg line-height-1"></iconify-icon>
                                        View
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <!-- Modal for event details -->
                    <div class="modal fade" id="eventModal<?php echo e($event->id); ?>" tabindex="-1" aria-labelledby="eventModalLabel<?php echo e($event->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content radius-16 bg-base">
                                <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                                    <h1 class="modal-title fs-5" id="eventModalLabel<?php echo e($event->id); ?>">View Details</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-24">
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Title</span>
                                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4"><?php echo e($event->title); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Start Date</span>
                                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4"><?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d M Y, h:i A')); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">End Date</span>
                                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4"><?php echo e(\Carbon\Carbon::parse($event->end_date)->format('d M Y, h:i A')); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Description</span>
                                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4"><?php echo e($event->description ?? 'N/A'); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Label</span>
                                        <h6 class="text-primary-light fw-semibold text-md mb-0 mt-4 d-flex align-items-center gap-2">
                                            <span class="w-8-px h-8-px rounded-circle"></span>
                                            <?php echo e($event->label ?? 'General'); ?>

                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xxl-9 col-lg-8">
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

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // default view
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: "<?php echo e(route('calendar.juniorEvents')); ?>", // AJAX source
            eventColor: '#378006', // default color if label_color missing
            eventClick: function(info) {
                // Populate modal with event info
                document.getElementById('eventModalTitle').innerText = info.event.title;
                document.getElementById('eventModalStart').innerText = new Date(info.event.start).toLocaleString();
                document.getElementById('eventModalEnd').innerText = info.event.end ? new Date(info.event.end).toLocaleString() : '';
                document.getElementById('eventModalDescription').innerText = info.event.extendedProps.description || 'N/A';
                document.getElementById('eventModalLabel').innerText = info.event.extendedProps.label || 'General';

                // Show modal
                var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                modal.show();
            }
        });

        calendar.render();
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\calendar\junior.blade.php ENDPATH**/ ?>