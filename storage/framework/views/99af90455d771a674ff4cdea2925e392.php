

<?php
    $title = 'Calendar';
    $subTitle = 'Calendar';
?>

<?php $__env->startSection('content'); ?>

<div class="row gy-4">
    
    <div class="col-xxl-3 col-lg-4">
        <div class="card h-100 p-0">
            <div class="card-body p-24">
                <h5 class="mb-16 text-secondary-light">Events</h5>
                <div class="mt-16">
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="event-item d-flex align-items-center justify-content-between gap-4 p-12 mb-12 border rounded shadow-sm">
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-10">
                                <span class="w-12 h-12 rounded-circle" style="background-color: '#FFA500' "></span>
                                <span class="text-secondary-light">
                                    <?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d M Y, h:i A')); ?> -
                                    <?php echo e(\Carbon\Carbon::parse($event->end_date)->format('d M Y, h:i A')); ?>

                                </span>
                            </div>
                            <span class="fw-semibold text-primary-light">Take <?php echo e($event->status); ?> for <?php echo e($event->pause_type); ?></span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <iconify-icon icon="entypo:dots-three-vertical" class="icon"></iconify-icon>
                            </button>
                            <ul class="dropdown-menu p-12 shadow">
                                <li>
                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#eventModal<?php echo e($event->id); ?>">
                                        <iconify-icon icon="hugeicons:view" class="icon"></iconify-icon>
                                        View
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="eventModal<?php echo e($event->id); ?>" tabindex="-1" aria-labelledby="eventModalLabel<?php echo e($event->id); ?>" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content radius-16 bg-base">
                                <div class="modal-header py-16 px-24 border-0">
                                    <h5 class="modal-title" id="eventModalLabel<?php echo e($event->id); ?>">Event Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-24">
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Title</span>
                                        <h6 class="text-primary-light fw-semibold mt-4"><?php echo e($event->title ?? $event->pause_type); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Start Date</span>
                                        <h6 class="text-primary-light fw-semibold mt-4"><?php echo e(\Carbon\Carbon::parse($event->start_date)->format('d M Y, h:i A')); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">End Date</span>
                                        <h6 class="text-primary-light fw-semibold mt-4"><?php echo e(\Carbon\Carbon::parse($event->end_date)->format('d M Y, h:i A')); ?></h6>
                                    </div>
                                    <div class="mb-12">
                                        <span class="text-secondary-light txt-sm fw-medium">Description</span>
                                        <h6 class="text-primary-light fw-semibold mt-4"><?php echo e($event->description ?? 'N/A'); ?></h6>
                                    </div>
                                    <div class="mb-12 d-flex align-items-center gap-2">
                                        <span class="text-secondary-light txt-sm fw-medium">Label</span>
                                        <span class="w-8 h-8 rounded-circle" style="background-color: '#FFA500' "></span>
                                        <span class="text-primary-light fw-semibold"><?php echo e($event->label ?? 'General'); ?></span>
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
                <div id='calendar'></div>
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
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: "<?php echo e(route('calendar.juniorEvents')); ?>",
        eventColor: '#378006',
        eventClick: function(info) {
            var modalEl = document.getElementById('eventModal');
            // populate modal dynamically if using single modal, or use multiple modals already coded
        }
    });

    calendar.render();
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views/calendar/junior.blade.php ENDPATH**/ ?>