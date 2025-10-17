@extends('layout.layout')

@php
$title = 'Senior Dashboard';
$subTitle = 'All Junior Timers';
$script = '<script>
    $(".delete-btn").on("click", function() {
        $(this).closest(".user-grid-card").addClass("d-none")
    });
</script>';
@endphp

@section('content')
<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
            <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                <option>1</option>
            </select>
            <form class="navbar-search">
                <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search">
                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
            </form>
        </div>
    </div>
    <div class="card-body p-24">
        <div class="row gy-4">
            @foreach($timers as $timer)
            <div class="col-xxl-3 col-md-6 user-grid-card">
                <div class="position-relative border radius-16 overflow-hidden" style="padding-top: 30px;">
                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" class="w-100 object-fit-cover" alt="">
                    <div class="ps-16 pb-16 pe-16 text-center mt--50">
                        <img src="{{ $timer['image'] ? asset('assets/images/user-grid/' . $timer['image']) : asset('assets/images/user-grid/user-grid-bg1.png') }}" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover" alt="">
                        <h6 class="text-lg mb-0 mt-4">{{ $timer['name'] }}</h6>
                        <span class="text-secondary-light mb-16">{{ $timer['email'] }}</span>
                        <div class="timer-widget"
                            data-user="{{ $timer['user_id'] }}"
                            data-remaining="{{ $timer['remaining_seconds'] }}"
                            data-elapsed="{{ $timer['elapsed_seconds'] }}"
                            data-status="{{ $timer['status'] }}"
                            style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:5px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">
                            <div style="margin-right:10px;text-align:center;min-width:60px;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                    <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                                    <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Countdown</small>
                                </div>
                                <span class="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ gmdate('H:i:s', $timer['remaining_seconds']) }}
                                </span>
                            </div>
                            <div style="width:1px;background:#dee2e6;margin:0 4px;"></div>
                            <div style="margin-right:10px;text-align:center;min-width:60px;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                    <iconify-icon icon="mdi:clock-outline" style="color:#28a745;font-size:14px;"></iconify-icon>
                                    <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Elapsed</small>
                                </div>
                                <span class="elapsed" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ gmdate('H:i:s', $timer['elapsed_seconds']) }}
                                </span>
                            </div>
                            <div class="seniorcontrolButtons_{{ $timer['user_id'] }}" id="seniorcontrolButtons_{{ $timer['user_id'] }}_container" style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                                <button id="resumebreak_{{ $timer['user_id'] }}" class="senior-btn resumebreak_{{ $timer['user_id'] }}" data-type="resumebreak" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#d4edda;border:1px solid #28a745;display:flex;align-items:center;justify-content:center;font-size:12px;color:#28a745;">
                                    <iconify-icon icon="mdi:play" style="margin-right:2px;font-size:14px;"></iconify-icon>Resume
                                </button>
                                <button id="lunch_{{ $timer['user_id'] }}" class="senior-btn lunch_{{ $timer['user_id'] }}" data-type="lunch" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#ffc107;">
                                    <iconify-icon icon="mdi:food" style="margin-right:2px;font-size:14px;"></iconify-icon>Lunch
                                </button>
                                <button id="tea_{{ $timer['user_id'] }}" class="senior-btn tea_{{ $timer['user_id'] }}" data-type="tea" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#8b4513;">
                                    <iconify-icon icon="mdi:coffee" style="margin-right:2px;font-size:14px;"></iconify-icon>Tea
                                </button>
                                <button id="break_{{ $timer['user_id'] }}" class="senior-btn break_{{ $timer['user_id'] }}" data-type="break" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#007bff;">
                                    <iconify-icon icon="mdi:pause" style="margin-right:2px;font-size:14px;"></iconify-icon>Break
                                </button>
                            </div>
                        </div>
                        @if($timer['button_status'] == 0)
                        <button id="loginBtn_{{ $timer['user_id'] }}" class="senior-btn loginBtn_{{ $timer['user_id'] }}" data-type="login" data-user="{{ $timer['user_id'] }}" style="width:100%; height:40px; border-radius:14px; background:#0d6efd; border:1px solid #b0c6e6ff; color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; gap:6px; margin-top:16px; cursor:pointer;">
                            <iconify-icon icon="mdi:play" style="font-size:16px;"></iconify-icon>Enable Junior
                        </button>
                        @else
                        <button id="logoutBtn_{{ $timer['user_id'] }}" class="senior-btn logoutBtn_{{ $timer['user_id'] }}" data-type="logout" data-user="{{ $timer['user_id'] }}" style="width:100%; height:40px; border-radius:14px; background:#dc3545; border:1px solid #d8adb1ff; color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; gap:6px; margin-top:16px; cursor:pointer;">
                            <iconify-icon icon="mdi:pause" style="font-size:16px;"></iconify-icon>Disable Junior
                        </button>
                        @endif
                        <div class="position-absolute top-0 end-0 me-16 mt-16" id="badgeContainer_{{ $timer['user_id'] }}">
                            <span class="pause-badge px-3 py-2 radius-8 shadow-sm" data-user="{{ $timer['user_id'] }}">
                                @if(!empty($timer['pause_type']))
                                @if($timer['pause_type'] == 'lunch')
                                Lunch Break
                                @elseif($timer['pause_type'] == 'tea')
                                Tea Break
                                @elseif($timer['pause_type'] == 'break')
                                Short Break
                                @elseif($timer['pause_type'] == 'resume')
                                Resumed
                                @else
                                Unknown
                                @endif
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-24">
            <span>Showing 1 to 10 of 12 entries</span>
            <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="javascript:void(0)">
                        <iconify-icon icon="ep:d-arrow-left" class=""></iconify-icon>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md bg-primary-600 text-white" href="javascript:void(0)">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px" href="javascript:void(0)">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="javascript:void(0)">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="javascript:void(0)">4</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="javascript:void(0)">5</a>
                </li>
                <li class="page-item">
                    <a class="page-link bg-neutral-200 text-secondary-light fw-semibold radius-8 border-0 d-flex align-items-center justify-content-center h-32-px w-32-px text-md" href="javascript:void(0)">
                        <iconify-icon icon="ep:d-arrow-right" class=""></iconify-icon>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="statusOverlay"></div>

<script>
    function formatTime(sec) {
        sec = Math.max(0, Math.floor(sec));
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        const s = sec % 60;
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    function updateAllTimers() {
        fetch("{{ route('timer.alljuniors') }}")
            .then(res => res.json())
            .then(timers => {
                if (!Array.isArray(timers)) return;
                timers.forEach(data => {
                    const widget = document.querySelector(`.timer-widget[data-user='${data.user_id}']`);
                    if (!widget) return;
                    widget.dataset.remaining = data.remaining_seconds;
                    widget.dataset.elapsed = data.elapsed_seconds;
                    widget.dataset.status = data.status;
                    widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                    widget.querySelector('.elapsed').innerText = formatTime(data.elapsed_seconds);
                    if (data.remaining_seconds <= 0 && data.status === 'finished') {
                        alert(`User ${data.user_id} has finished their 9-hour session.`);
                    }
                });
            })
            .catch(err => console.error("Timer fetch error:", err));
    }
    setInterval(updateAllTimers, 1000);
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setupSeniorControlButtons() {
            document.querySelectorAll('[id^="seniorcontrolButtons_"]').forEach(container => {
                container.querySelectorAll('button').forEach(btn => {
                    if (btn.dataset.listenerAttached) return;
                    btn.dataset.listenerAttached = true;
                    btn.addEventListener('click', () => {
                        const type = btn.dataset.type;
                        const userId = btn.dataset.user;
                        fetch("{{ route('timer.update') }}", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    action: type,
                                    user_id: userId
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                const timerWidget = document.querySelector(`.timer-widget[data-user='${userId}']`);
                                if (timerWidget) {
                                    timerWidget.dataset.status = data.status;
                                    timerWidget.dataset.remainingSeconds = data.remaining_seconds;
                                    timerWidget.dataset.elapsedSeconds = data.elapsed_seconds;
                                    timerWidget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                                    timerWidget.querySelector('.elapsed').innerText = formatTime(data.elapsed_seconds);

                                    const card = timerWidget.closest('.user-grid-card');
                                    if (card) {
                                        card.style.backgroundColor = data.status === 'paused' ? '#fff3cd' : data.status === 'running' ? '#d4edda' : '';
                                    }
                                }
                            })
                            .catch(err => console.error("[Action] Failed to send:", err));
                    });
                });
            });
        }
        setupSeniorControlButtons();
        setInterval(setupSeniorControlButtons, 1000);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function toggleButtonStatus(button) {
            const userId = button.dataset.user;
            const type = button.dataset.type;
            const action = type === "login" ? "enable" : "disable";
            fetch("{{ route('timer.toggleButtonStatus') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        action: action
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) updateButton(button, data.button_status, userId);
                })
                .catch(err => console.error(err));
        }

        function updateButton(button, status, userId) {
            const loginBtn = document.querySelector(`#loginBtn_${userId}`);
            const logoutBtn = document.querySelector(`#logoutBtn_${userId}`);
            if (status == 0 && loginBtn) {
                loginBtn.dataset.type = "login";
                loginBtn.style.background = "#0d6efd";
                loginBtn.style.border = "1px solid #b0c6e6ff";
                loginBtn.innerHTML = `<iconify-icon icon="mdi:play" style="font-size:16px;"></iconify-icon>Enable Junior`;
            } else if (logoutBtn) {
                logoutBtn.dataset.type = "logout";
                logoutBtn.style.background = "#dc3545";
                logoutBtn.style.border = "1px solid #d8adb1ff";
                logoutBtn.innerHTML = `<iconify-icon icon="mdi:pause" style="font-size:16px;"></iconify-icon>Disable Junior`;
            }
        }

        document.body.addEventListener("click", function(e) {
            const btn = e.target.closest("button[data-user]");
            if (btn) toggleButtonStatus(btn);
        });

        function checkButtonStatus() {
            fetch("{{ route('button.status') }}")
                .then(res => res.json())
                .then(data => {
                    if (Array.isArray(data)) {
                        data.forEach(user => {
                            const button = document.querySelector(`button[data-user='${user.user_id}']`);
                            if (button) updateButton(button, user.button_status, user.user_id);
                        });
                    }
                })
                .catch(err => console.error("Polling error:", err));
        }
        checkButtonStatus();
        setInterval(checkButtonStatus, 1000);
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updatePauseBadges() {
            fetch("{{ route('timers.latestPauseTypes') }}")
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        const badge = document.querySelector(`#badgeContainer_${item.user_id} .pause-badge[data-user='${item.user_id}']`);
                        if (!badge) return;
                        const type = item.pause_type || 'unknown';
                        badge.textContent = formatPauseText(type);
                        badge.className = `pause-badge pause-badge_${item.user_id} ${getBadgeClass(type)} px-3 py-2 radius-8 shadow-sm`;
                    });
                }).catch(err => console.error(err));
        }

        function formatPauseText(type) {
            switch (type) {
                case 'lunch':
                    return 'Lunch Break';
                case 'tea':
                    return 'Tea Break';
                case 'break':
                    return 'Short Break';
                case 'resume':
                    return 'Resumed';
                default:
                    return 'Unknown';
            }
        }

        function getBadgeClass(type) {
            switch (type) {
                case 'lunch':
                    return 'bg-danger text-white';
                case 'tea':
                    return 'bg-success text-white';
                case 'break':
                    return 'bg-warning text-white';
                case 'resume':
                    return 'bg-primary text-white';
                default:
                    return 'bg-secondary text-white';
            }
        }

        updatePauseBadges();
        setInterval(updatePauseBadges, 1000);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide break buttons for all users except the first one
    const containers = document.querySelectorAll('[id^="seniorcontrolButtons_"]');
    containers.forEach((container, index) => {
        const breakBtn = container.querySelector('button[id^="break_"]');
        if (breakBtn && index > 0) {
            breakBtn.style.display = 'none';
        }
    });

    // Existing updatePauseButtonsPerUser logic
    function updatePauseButtonsPerUser() {
        containers.forEach(container => {
            const userId = container.querySelector('button').dataset.user;

            fetch('{{ route("timer.checkPauseButtonsSenior") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(res => res.json())
            .then(data => {
                const resumeBtn = container.querySelector(`#resumebreak_${userId}`);
                const pauseBtns = [
                    container.querySelector(`#lunch_${userId}`),
                    container.querySelector(`#tea_${userId}`),
                    container.querySelector(`#break_${userId}`)
                ].filter(Boolean);

                // Show/hide buttons based on pause type
                if (['lunch', 'tea', 'break'].includes(data.pause_type)) {
                    pauseBtns.forEach(btn => btn.style.display = 'none');
                    if (resumeBtn) resumeBtn.style.display = 'flex';
                } else {
                    pauseBtns.forEach(btn => {
                        // Keep break hidden for all except first container
                        if (btn.id.startsWith('break_') && container !== containers[0]) {
                            btn.style.display = 'none';
                        } else {
                            btn.style.display = 'flex';
                        }
                    });
                    if (resumeBtn) resumeBtn.style.display = 'none';
                }

                // Update pause badge
                const badgeContainer = document.querySelector(`#badgeContainer_${userId}`);
                if (badgeContainer) {
                    const badge = badgeContainer.querySelector(`.pause-badge_${userId}`);
                    if (badge) {
                        const type = data.pause_type || 'resume';
                        badge.textContent = formatPauseText(type);
                        badge.className = `pause-badge pause-badge_${userId} ${getBadgeClass(type)} px-3 py-2 radius-8 shadow-sm`;
                    }
                }
            })
            .catch(err => console.error('Error fetching pause state for user', userId, err));
        });
    }

    updatePauseButtonsPerUser();
    setInterval(updatePauseButtonsPerUser, 1000);
});
</script>

@endsection