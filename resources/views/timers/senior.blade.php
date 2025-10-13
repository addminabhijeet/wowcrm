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
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
            </select>
            <form class="navbar-search">
                <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search">
                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
            </form>
        </div>
        <a href="javascript:void(0)" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            id="enableAll">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Enable All Junior
        </a>

        <a href="javascript:void(0)" class="btn btn-danger text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2"
            id="disableAll">
            <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
            Disable All Junior
        </a>
    </div>
    <div class="card-body p-24">
        <div class="row gy-4">
            @foreach($timers as $timer)
            <div class="col-xxl-3 col-md-6 user-grid-card">
                <div class="position-relative border radius-16 overflow-hidden" style="padding-top: 30px;">
                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" class="w-100 object-fit-cover" alt="">

                    <!-- Dropdown -->
                    <div class="dropdown position-absolute top-0 end-0 me-16 mt-16">
                        @if($timer['button_status'] == 0)
                        <button type="button" class="bg-danger w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white" disabled>
                            <iconify-icon icon="entypo:dots-three-vertical" class="icon"></iconify-icon>
                        </button>
                        @else
                        <button type="button" class="bg-white-gradient-light w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white" disabled>
                            <iconify-icon icon="entypo:dots-three-vertical" class="icon"></iconify-icon>
                        </button>
                        @endif
                    </div>

                    <div class="ps-16 pb-16 pe-16 text-center mt--50">
                        <img src="{{ $timer['image'] ? asset('assets/images/user-grid/' . $timer['image']) : asset('assets/images/user-grid/user-grid-bg1.png') }}" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover" alt="">
                        <h6 class="text-lg mb-0 mt-4">{{ $timer['name'] }}</h6>
                        <span class="text-secondary-light mb-16">{{ $timer['email'] }}</span>

                        <!-- Timer Widget -->
                        <div class="timer-widget"
                            data-user="{{ $timer['user_id'] }}"
                            data-remaining="{{ $timer['remaining_seconds'] }}"
                            data-elapsed="{{ $timer['elapsed_seconds'] }}"
                            data-status="{{ $timer['status'] }}"
                            style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">

                            <!-- Countdown -->
                            <div style="margin-right:10px;text-align:center;min-width:60px;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                    <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                                    <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Countdown</small>
                                </div>
                                <span class="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ gmdate('H:i:s', $timer['remaining_seconds']) }}
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
                                    {{ gmdate('H:i:s', $timer['elapsed_seconds']) }}
                                </span>
                            </div>



                            <!-- Control Buttons -->
                            <div class="seniorcontrolButtons" id="seniorcontrolButtons_{{ $timer['user_id'] }}" style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                                <button data-type="resumebreak" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#d4edda;border:1px solid #28a745;display:flex;align-items:center;justify-content:center;font-size:12px;color:#28a745;">
                                    <iconify-icon icon="mdi:play" style="margin-right:2px;font-size:14px;"></iconify-icon>Resume
                                </button>
                                <button data-type="lunch" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#ffc107;">
                                    <iconify-icon icon="mdi:food" style="margin-right:2px;font-size:14px;"></iconify-icon>Lunch
                                </button>
                                <button data-type="tea" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#8b4513;">
                                    <iconify-icon icon="mdi:coffee" style="margin-right:2px;font-size:14px;"></iconify-icon>Tea
                                </button>
                                <button data-type="break" data-user="{{ $timer['user_id'] }}" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#007bff;">
                                    <iconify-icon icon="mdi:pause" style="margin-right:2px;font-size:14px;"></iconify-icon>Break
                                </button>
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        @if($timer['button_status'] == 0)
                        <a href="javascript:void(0)"
                            class="bg-primary-50 text-primary-600 hover-bg-primary-600 hover-text-white p-10 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center justify-content-center mt-16 fw-medium gap-2 w-100 enable-junior"
                            data-user="{{ $timer['user_id'] }}">
                            Enable Junior
                            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        </a>
                        @else
                        <a href="javascript:void(0)"
                            class="bg-danger-50 text-danger-600 hover-bg-danger-600 hover-text-white p-10 text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center justify-content-center mt-16 fw-medium gap-2 w-100 disable-junior"
                            data-user="{{ $timer['user_id'] }}">
                            Disable Junior
                            <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
                        </a>
                        @endif

                        <!-- Login/Logout Button -->
                        @if($timer['button_status'] == 0)
                        <button
                            data-type="login"
                            data-user="{{ $timer['user_id'] }}"
                            style="width:100%; height:40px; border-radius:14px; background:#0d6efd; border:1px solid #0b5ed7; color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; gap:6px; margin-top:16px; cursor:pointer;">
                            <iconify-icon icon="mdi:play" style="font-size:16px;"></iconify-icon>
                            Log In
                        </button>
                        @else
                        <button
                            data-type="logout"
                            data-user="{{ $timer['user_id'] }}"
                            style="width:100%; height:40px; border-radius:14px; background:#dc3545; border:1px solid #b02a37; color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; gap:6px; margin-top:16px; cursor:pointer;">
                            <iconify-icon icon="mdi:pause" style="font-size:16px;"></iconify-icon>
                            Log Out
                        </button>
                        @endif



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
    // Format seconds to HH:MM:SS
    function formatTime(sec) {
        sec = Math.max(0, Math.floor(sec));
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        const s = sec % 60;
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    }

    // Fetch all timers from backend every second
    function updateAllTimers() {
        fetch("{{ route('timer.alljuniors') }}")
            .then(res => res.json())
            .then(timers => {
                if (!Array.isArray(timers)) {
                    console.warn("Invalid timer response:", timers);
                    return;
                }

                timers.forEach(data => {
                    const widget = document.querySelector(`.timer-widget[data-user='${data.user_id}']`);
                    if (!widget) return;

                    widget.dataset.remaining = data.remaining_seconds;
                    widget.dataset.elapsed = data.elapsed_seconds;
                    widget.dataset.status = data.status;

                    widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                    widget.querySelector('.elapsed').innerText = formatTime(data.elapsed_seconds);

                    // Optional: notify when timer hits 0
                    if (data.remaining_seconds <= 0 && data.status === 'finished') {
                        alert(`User ${data.user_id} has finished their 9-hour session.`);
                    }
                });
            })
            .catch(err => console.error("Timer fetch error:", err));
    }
    setInterval(updateAllTimers, 1000); // sync with DB every second
</script>

<script>
    // Setup control buttons for all timer widgets
    function setupseniorControlButtons() {
        document.querySelectorAll('.seniorcontrolButtons button').forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.getAttribute('data-type');
                const userId = btn.getAttribute('data-user');

                console.log(`[Action] Button clicked: ${type} (User ID: ${userId})`);

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
                        console.log("[Action] Response:", data);

                        if (data.success === false && data.notice_status === 1) {
                            showOverlay(data.message || "Please wait for senior to enable.");
                            return;
                        }

                        // Update individual timer UI for this user only
                        const timerWidget = document.querySelector(`.timer-widget[data-user='${userId}']`);
                        if (timerWidget) {
                            timerWidget.dataset.status = data.status;
                            timerWidget.dataset.remainingSeconds = data.remaining_seconds;
                            timerWidget.dataset.elapsedSeconds = data.elapsed_seconds;
                            updateUI(userId, data); // assume updateUI handles per-user updates
                        }
                    })
                    .catch(err => console.error("[Action] Failed to send:", err));
            });
        });

    }

    // Initialize once DOM is ready
    document.addEventListener("DOMContentLoaded", () => {
        console.debug("[Debug] DOM fully loaded. Setting up senior control buttons...");
        setupseniorControlButtons();
    });
</script>




<script>
    // Toggle single junior enable/disable
    function toggleButtonStatus(userId, action) {
        console.log(`Toggling user ${userId} to ${action}...`);

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
                console.log(`Response for user ${userId}:`, data);

                if (!data.success) {
                    console.error(`Failed to toggle user ${userId}:`, data.message);
                    return;
                }

                updateUserCard(userId, data.button_status);
            })
            .catch(err => console.error(`Fetch error for user ${userId}:`, err));
    }

    // Update UI for a given user card
    function updateUserCard(userId, buttonStatus) {
        const card = document.querySelector(`.user-grid-card[data-user-id='${userId}']`);
        if (!card) {
            console.warn(`Card not found for user_id ${userId}`);
            return;
        }

        const dropdown = card.querySelector('.dropdown button');
        const btnContainer = card.querySelector('.ps-16');

        // Update dropdown style
        if (dropdown) {
            dropdown.className = buttonStatus == 0 ?
                'bg-danger w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white' :
                'bg-white-gradient-light w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white';
        }

        // Update action button
        if (btnContainer) {
            btnContainer.innerHTML = buttonStatus == 0 ?
                `
                <a href="javascript:void(0)" class="btn btn-primary enable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${userId}">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Enable Junior
                </a>` :
                `
                <a href="javascript:void(0)" class="btn btn-danger disable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${userId}">
                    <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
                    Disable Junior
                </a>`;
        }

        // Re-bind events after DOM change
        setupStatusButtons();
    }

    // Bind all existing buttons
    function setupStatusButtons() {
        document.querySelectorAll('.enable-junior').forEach(btn => {
            btn.onclick = () => toggleButtonStatus(btn.dataset.user, 'enable');
        });
        document.querySelectorAll('.disable-junior').forEach(btn => {
            btn.onclick = () => toggleButtonStatus(btn.dataset.user, 'disable');
        });
    }

    // Auto update button status every second
    function checkButtonStatus() {
        fetch("{{ route('button.status') }}")
            .then(response => response.json())
            .then(data => {
                // Expecting format: [{user_id: 1, button_status: 1}, {user_id: 2, button_status: 0}, ...]
                if (Array.isArray(data)) {
                    data.forEach(user => {
                        updateUserCard(user.user_id, user.button_status);
                    });
                } else {
                    console.warn("Unexpected data format:", data);
                }
            })
            .catch(err => console.error("Polling error:", err));
    }

    // Initial setup
    setupStatusButtons();
    checkButtonStatus();

    // Poll every 1 second
    setInterval(checkButtonStatus, 1000);
</script>


<script>
    // Bulk enable/disable all juniors
    function toggleAllStatus(action) {
        console.log(`Attempting to ${action} all juniors...`);
        fetch("{{ route('timer.toggleAllStatus') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: action
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    console.error('Bulk toggle failed:', data);
                    return;
                }

                console.log('Bulk toggle response:', data);

                // Update all user cards after bulk action
                if (Array.isArray(data.updated)) {
                    data.updated.forEach(user => {
                        updateUserCard(user.user_id, user.button_status);
                    });
                }

                // Re-bind events
                setupStatusButtons();
            })
            .catch(err => console.error('Bulk toggle fetch error:', err));
    }

    // Update UI for a given user card
    function updateUserCard(userId, buttonStatus) {
        const card = document.querySelector(`.user-grid-card[data-user-id='${userId}']`);
        if (!card) return;

        const btnContainer = card.querySelector('.ps-16');
        const dropdown = card.querySelector('.dropdown button');

        // Update dropdown style
        if (dropdown) {
            dropdown.className = buttonStatus ?
                'bg-white-gradient-light w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white' :
                'bg-danger w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white';
        }

        // Update action button
        if (btnContainer) {
            btnContainer.innerHTML = buttonStatus ?
                `
                <a href="javascript:void(0)" class="btn btn-danger disable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${userId}">
                    <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
                    Disable Junior
                </a>` :
                `
                <a href="javascript:void(0)" class="btn btn-primary enable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${userId}">
                    <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                    Enable Junior
                </a>`;
        }
    }

    // Bind enable/disable events
    function setupStatusButtons() {
        document.querySelectorAll('.enable-junior').forEach(btn => {
            btn.onclick = () => toggleButtonStatus(btn.dataset.user, 'enable');
        });
        document.querySelectorAll('.disable-junior').forEach(btn => {
            btn.onclick = () => toggleButtonStatus(btn.dataset.user, 'disable');
        });
    }

    // Auto update button status for all juniors
    function checkButtonStatus() {
        fetch("{{ route('button.status') }}")
            .then(response => response.json())
            .then(data => {
                // Expecting format: [{user_id: 1, button_status: 1}, {user_id: 2, button_status: 0}, ...]
                if (Array.isArray(data)) {
                    data.forEach(user => {
                        updateUserCard(user.user_id, user.button_status);
                    });
                    setupStatusButtons();
                } else {
                    console.warn("Unexpected response:", data);
                }
            })
            .catch(err => console.error("Polling error:", err));
    }

    // Bind top buttons
    const enableAllBtn = document.getElementById('enableAll');
    const disableAllBtn = document.getElementById('disableAll');

    if (enableAllBtn) enableAllBtn.onclick = () => toggleAllStatus('enable');
    if (disableAllBtn) disableAllBtn.onclick = () => toggleAllStatus('disable');

    // Run once immediately
    checkButtonStatus();

    // Poll every 1 second
    setInterval(checkButtonStatus, 1000);
</script>

<script>
    $(document).on('click', 'button[data-type="logout"]', function(e) {
        e.preventDefault();
        const userId = $(this).data('user'); // fallback ID

        if (!confirm("Are you sure you want to log out this user?")) return;

        $.ajax({
            url: "{{ route('ajax.logout') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                user_id: userId
            },
            beforeSend: function() {
                console.log("[Logout] Request sent for user ID:", userId);
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    // Optionally reload or update UI
                    $(`#seniorcontrolButtons_${userId}`).find('button').prop('disabled', true);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert("Something went wrong while logging out.");
            }
        });
    });
</script>

@endsection