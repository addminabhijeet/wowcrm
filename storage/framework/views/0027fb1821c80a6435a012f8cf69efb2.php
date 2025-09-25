<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                <!-- Sidebar toggles -->
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>

                <!-- Timer Widget -->
                <div style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">

                    <!-- Countdown -->
                    <div style="margin-right:10px;text-align:center;min-width:60px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                            <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                            <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Countdown</small>
                        </div>
                        <span id="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">09:00:00</span>
                    </div>

                    <!-- Divider -->
                    <div style="width:1px;background:#dee2e6;margin:0 4px;"></div>

                    <!-- Elapsed -->
                    <div style="margin-right:10px;text-align:center;min-width:60px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                            <iconify-icon icon="mdi:clock-outline" style="color:#28a745;font-size:14px;"></iconify-icon>
                            <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Elapsed</small>
                        </div>
                        <span id="elapsed" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">00:00:00</span>
                    </div>

                    <!-- Control Buttons -->
                    <div id="controlButtons" style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
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

        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                <div class="dropdown d-none d-sm-inline-block">
                    <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                        <img src="<?php echo e(asset('assets/images/lang-flag.png')); ?>" alt="image" class="w-24 h-24 object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language</h6>
                            </div>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
                            <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                <label class="form-check-label line-height-1 fw-medium text-secondary-light" for="english">
                                    <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <img src="<?php echo e(asset('assets/images/flags/flag1.png')); ?>" alt="" class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                        <span class="text-md fw-semibold mb-0">English</span>
                                    </span>
                                </label>
                                <input class="form-check-input" type="radio" name="crypto" id="english">
                            </div>

                        </div>
                    </div>
                </div><!-- Language dropdown end -->

                <div class="dropdown">
                    <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                        <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
                            </div>
                            <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">

                            <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                        <img src="<?php echo e(asset('assets/images/notification/profile-3.png')); ?>" alt="">
                                        <span class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...</p>
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                    <span class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                </div>
                            </a>

                        </div>
                        <div class="text-center py-12 px-16">
                            <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Message</a>
                        </div>
                    </div>
                </div><!-- Message dropdown end -->

                <div class="dropdown">
                    <button class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown">
                        <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                            </div>
                            <span class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                        </div>

                        <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
                            <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                        <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Congratulations</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-200-px">Your profile has been Verified. Your profile has been Verified</p>
                                    </div>
                                </div>
                                <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                            </a>

                            <a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                    <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                        <img src="<?php echo e(asset('assets/images/notification/profile-1.png')); ?>" alt="">
                                    </span>
                                    <div>
                                        <h6 class="text-md fw-semibold mb-4">Ronald Richards</h6>
                                        <p class="mb-0 text-sm text-secondary-light text-w-200-px">You can stitch between artboards</p>
                                    </div>
                                </div>
                                <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                            </a>

                        </div>

                        <div class="text-center py-12 px-16">
                            <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All Notification</a>
                        </div>

                    </div>
                </div><!-- Notification dropdown end -->

                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                        <img src="<?php echo e(asset('assets/images/user.png')); ?>" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2"><?php echo e(Auth::user()->name); ?></h6>
                                <span class="text-secondary-light fw-medium text-sm"><?php echo e(Auth::user()->email); ?></span>
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        <ul class="to-top-list">
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3" href="">
                                    <iconify-icon icon="icon-park-outline:setting-two" class="icon text-xl"></iconify-icon> Setting
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                    href="<?php echo e(route('logout')); ?>"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                </a>

                                
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Full screen overlay for Active/Inactive notice */
    #statusOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: bold;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        flex-direction: column;
        transition: opacity 0.3s ease;
    }

    #statusOverlay.show {
        opacity: 1;
        pointer-events: auto;
    }
</style>

<div id="statusOverlay"></div>

<script>
    let timerInterval, backendSyncInterval;
    let remainingSeconds = Number("<?php echo e($remaining_seconds ?? 0); ?>");
    let elapsedSeconds = Number("<?php echo e($elapsed_seconds ?? 0); ?>");
    let status = "<?php echo e($status ?? 'running'); ?>";

    let inactiveTimeout;
    const INACTIVE_LIMIT = 2 * 60 * 1000; // 2 minutes

    function formatTime(sec) {
        sec = Math.floor(sec);
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        const s = sec % 60;
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    function updateUI() {
        document.getElementById('countdown').innerText = formatTime(remainingSeconds);
        document.getElementById('elapsed').innerText = formatTime(elapsedSeconds);
    }

    function forceLogout() {
        fetch("<?php echo e(route('logout')); ?>", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                "Content-Type": "application/json"
            }
        }).then(() => window.location.href = "/login");
    }

    // Overlay function
    let overlayTimeout;

    function showOverlay(message) {
        const overlay = document.getElementById('statusOverlay');
        overlay.innerText = message;
        overlay.classList.add('show');

        clearTimeout(overlayTimeout);
        overlayTimeout = setTimeout(() => {
            overlay.classList.remove('show');
        }, 3000);
    }

    function startTimer() {
        clearInterval(timerInterval);
        clearInterval(backendSyncInterval);

        timerInterval = setInterval(() => {
            if (status === 'running' && remainingSeconds > 0) {
                remainingSeconds--;
                elapsedSeconds++;
                updateUI();
            }
        }, 1000);

        backendSyncInterval = setInterval(syncWithBackend, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
        clearInterval(backendSyncInterval);
    }

    function syncWithBackend() {
        fetch("<?php echo e(route('timer.update')); ?>", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: 'tick'
                })
            })
            .then(res => res.json())
            .then(data => {
                //  Show overlay if notice triggered
                if (data.notice_status === 1 && data.message) {
                    showOverlay(data.message);
                }

                remainingSeconds = data.remaining_seconds;
                elapsedSeconds = data.elapsed_seconds;
                status = data.status;
                updateUI();

                if (data.logout) {
                    stopTimer();
                    alert("Your 9-hour work session has ended.");
                    forceLogout();
                }
            });
    }


    // Handle control buttons
    document.querySelectorAll('#controlButtons button').forEach(btn => {
        btn.addEventListener('click', () => {
            const type = btn.getAttribute('data-type');

            fetch("<?php echo e(route('timer.update')); ?>", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: type
                    })
                })
                .then(res => res.json())
                .then(data => {
                    //  If button not enabled
                    if (data.success === false && data.notice_status === 1) {
                        showOverlay(data.message || "Please wait for senior to enable.");
                        return; // stop further UI updates
                    }

                    // Normal update
                    remainingSeconds = data.remaining_seconds;
                    elapsedSeconds = data.elapsed_seconds;
                    status = data.status;
                    updateUI();
                });
        });
    });


    // Inactivity detection
    function resetInactiveTimer() {
        clearTimeout(inactiveTimeout);
        inactiveTimeout = setTimeout(() => {
            showOverlay("You were inactive! Timer stopped.");
            stopTimer();
        }, INACTIVE_LIMIT);
    }

    // Active state (resume silently)
    function handleActiveState() {
        fetch("<?php echo e(route('timer.update')); ?>", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "resume"
                })
            })
            .then(res => res.json())
            .then(data => {
                remainingSeconds = data.remaining_seconds;
                elapsedSeconds = data.elapsed_seconds;
                status = data.status;
                updateUI();
            });

        resetInactiveTimer();
    }

    // Listen for activity
    ['mousemove', 'keydown', 'scroll', 'click'].forEach(evt => {
        window.addEventListener(evt, resetInactiveTimer);
    });

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            handleActiveState();
        }
    });

    // Initialize
    updateUI();
    resetInactiveTimer();
    handleActiveState();
    startTimer();
</script><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\components\navbar.blade.php ENDPATH**/ ?>