<?php
$title = 'Senior Dashboard';
$subTitle = 'All Junior Timers';
?>

<?php $__env->startSection('content'); ?>
<div class="row gy-4">
    <?php $__currentLoopData = $timers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-xxl-3 col-md-6 user-grid-card">
        <div class="position-relative border radius-16 overflow-hidden">
            <img src="<?php echo e(asset('assets/images/user-grid/user-grid-bg1.png')); ?>" class="w-100 object-fit-cover" alt="">

            <div class="ps-16 pb-16 pe-16 text-center mt--50">
                <img src="<?php echo e(asset('assets/images/user-grid/user-grid-img1.png')); ?>" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover" alt="">
                <h6 class="text-lg mb-0 mt-4"><?php echo e($timer['name']); ?></h6>
                <span class="text-secondary-light mb-16"><?php echo e($timer['email']); ?></span>

                <!-- Timer Widget -->
                <div class="timer-widget"
                    data-user="<?php echo e($timer['user_id']); ?>"
                    data-remaining="<?php echo e($timer['remaining_seconds']); ?>"
                    data-elapsed="<?php echo e($timer['elapsed_seconds']); ?>"
                    data-status="<?php echo e($timer['status']); ?>"
                    style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">

                    <!-- Countdown -->
                    <div style="margin-right:10px;text-align:center;min-width:60px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                            <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                            <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Countdown</small>
                        </div>
                        <span class="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?php echo e(gmdate('H:i:s', $timer['remaining_seconds'])); ?>

                        </span>
                    </div>

                    <!-- Divider -->
                    <div style="width:1px;background:#dee2e6;margin:0 4px;"></div>

                    <!-- Elapsed -->
                    <div style="margin-right:10px;text-align:center;min-width:60px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                            <iconify-icon icon="mdi:clock-outline" style="color:#28a745;font-size:14px;"></iconify-icon>
                            <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Elapsed</small>
                        </div>
                        <span class="elapsed" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?php echo e(gmdate('H:i:s', $timer['elapsed_seconds'])); ?>

                        </span>
                    </div>

                    <!-- Control Buttons -->
                    <div class="controlButtons" style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                        <button data-type="resume" style="width:65px;height:28px;border-radius:14px;background:#d4edda;border:1px solid #28a745;display:flex;align-items:center;justify-content:center;font-size:12px;color:#28a745;">
                            <iconify-icon icon="mdi:play" style="margin-right:2px;font-size:14px;"></iconify-icon>Resume
                        </button>
                        <button data-type="lunch" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#ffc107;">
                            <iconify-icon icon="mdi:food" style="margin-right:2px;font-size:14px;"></iconify-icon>Lunch
                        </button>
                        <button data-type="tea" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#8b4513;">
                            <iconify-icon icon="mdi:coffee" style="margin-right:2px;font-size:14px;"></iconify-icon>Tea
                        </button>
                        <button data-type="break" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#007bff;">
                            <iconify-icon icon="mdi:pause" style="margin-right:2px;font-size:14px;"></iconify-icon>Break
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php $__env->stopSection(); ?>



<div id="statusOverlay"></div>
<?php $__env->startSection('scripts'); ?>
<script>
    function formatTime(sec) {
        sec = Math.max(0, Math.floor(sec));
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        const s = sec % 60;
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    // Local tick for smooth countdown
    function localTick() {
        document.querySelectorAll('.timer-widget').forEach(widget => {
            let remaining = parseInt(widget.dataset.remaining);
            let elapsed   = parseInt(widget.dataset.elapsed);

            if (widget.dataset.status === 'running' && remaining > 0) {
                remaining -= 1;
                elapsed   += 1;

                widget.dataset.remaining = remaining;
                widget.dataset.elapsed   = elapsed;

                widget.querySelector('.countdown').innerText = formatTime(remaining);
                widget.querySelector('.elapsed').innerText   = formatTime(elapsed);

                if (remaining <= 0) {
                    alert("User " + widget.dataset.user + " has finished their 9-hour session.");
                }
            }
        });
    }

    // Bulk refresh from server (every 10s)
    function updateAllTimers() {
        fetch("<?php echo e(route('timer.alljuniors')); ?>")
            .then(res => res.json())
            .then(timers => {
                timers.forEach(data => {
                    const widget = document.querySelector(`.timer-widget[data-user='${data.user_id}']`);
                    if (!widget) return;

                    widget.dataset.remaining = data.remaining_seconds;
                    widget.dataset.elapsed   = data.elapsed_seconds;
                    widget.dataset.status    = data.status;

                    widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                    widget.querySelector('.elapsed').innerText   = formatTime(data.elapsed_seconds);
                });
            })
            .catch(err => console.error("Timer bulk fetch error", err));
    }

    function setupControlButtons() {
        document.querySelectorAll('.timer-widget').forEach(widget => {
            const userId = widget.dataset.user;

            widget.querySelectorAll('button').forEach(btn => {
                btn.addEventListener('click', () => {
                    const action = btn.dataset.type;

                    fetch("<?php echo e(route('timer.updatejunior')); ?>", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({ user_id: userId, action })
                        })
                        .then(res => res.json())
                        .then(data => {
                            widget.dataset.remaining = data.remaining_seconds;
                            widget.dataset.elapsed   = data.elapsed_seconds;
                            widget.dataset.status    = data.status;

                            widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                            widget.querySelector('.elapsed').innerText   = formatTime(data.elapsed_seconds);
                        });
                });
            });
        });
    }

    // 🚀 Initialize
    setupControlButtons();
    setInterval(localTick, 1000);       // smooth countdown every second
    setInterval(updateAllTimers, 10000); // sync with DB every 10s
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views/timers/senior.blade.php ENDPATH**/ ?>