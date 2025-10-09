@extends('layout.layout')

@php
$title = 'Senior Dashboard';
$subTitle = 'All Junior Timers';
@endphp

@section('content')
<div class="card h-100 p-0 radius-12">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <span class="text-md fw-medium text-secondary-light mb-0">Show</span>
            <select class="form-select form-select-sm w-auto ps-12 py-6 radius-12 h-40-px">
                @for($i = 1; $i <= 10; $i++)
                    <option>{{ $i }}</option>
                @endfor
            </select>
            <form class="navbar-search">
                <input type="text" class="bg-base h-40-px w-auto" name="search" placeholder="Search">
                <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
            </form>
        </div>
        <a href="javascript:void(0)" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" id="enableAll">
            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
            Enable All Junior
        </a>
        <a href="javascript:void(0)" class="btn btn-danger text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" id="disableAll">
            <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
            Disable All Junior
        </a>
    </div>

    <div class="card-body p-24">
        <div class="row gy-4">
            @foreach($timers as $timer)
            <div class="col-xxl-3 col-md-6 user-grid-card" data-user-id="{{ $timer['user_id'] }}">
                <div class="position-relative border radius-16 overflow-hidden" style="padding-top: 30px;">
                    <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" class="w-100 object-fit-cover" alt="">
                    <div class="dropdown position-absolute top-0 end-0 me-16 mt-16">
                        <button type="button"
                            class="{{ $timer['button_status'] == 0 ? 'bg-danger' : 'bg-white-gradient-light' }} w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white"
                            disabled>
                            <iconify-icon icon="entypo:dots-three-vertical" class="icon"></iconify-icon>
                        </button>
                    </div>

                    <div class="ps-16 pb-16 pe-16 text-center mt--50">
                        <img src="{{ asset('assets/images/user-grid/user-grid-img1.png') }}" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover" alt="">
                        <h6 class="text-lg mb-0 mt-4">{{ $timer['name'] }}</h6>
                        <span class="text-secondary-light mb-16">{{ $timer['email'] }}</span>

                        <!-- Timer Widget -->
                        <div class="timer-widget"
                            data-user="{{ $timer['user_id'] }}"
                            data-remaining="{{ $timer['remaining_seconds'] }}"
                            data-elapsed="{{ $timer['elapsed_seconds'] }}"
                            data-status="{{ $timer['status'] }}"
                            style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">

                            <div style="margin-right:10px;text-align:center;min-width:60px;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                    <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                                    <small style="color:#6c757d;font-size:10px;">Countdown</small>
                                </div>
                                <span class="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;">
                                    {{ gmdate('H:i:s', $timer['remaining_seconds']) }}
                                </span>
                            </div>

                            <div style="width:1px;background:#dee2e6;margin:0 4px;"></div>

                            <div style="margin-right:10px;text-align:center;min-width:60px;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                    <iconify-icon icon="mdi:clock-outline" style="color:#28a745;font-size:14px;"></iconify-icon>
                                    <small style="color:#6c757d;font-size:10px;">Elapsed</small>
                                </div>
                                <span class="elapsed" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;">
                                    {{ gmdate('H:i:s', $timer['elapsed_seconds']) }}
                                </span>
                            </div>

                            <!-- Dynamic Buttons -->
                            <div class="seniorcontrolButtons" style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                                @php
                                    $actions = [
                                        'resumebreak' => ['label' => 'Resume', 'color' => '#28a745', 'bg' => '#d4edda', 'icon' => 'mdi:play'],
                                        'lunch' => ['label' => 'Lunch', 'color' => '#ffc107', 'bg' => '#f8f9fa', 'icon' => 'mdi:food'],
                                        'tea' => ['label' => 'Tea', 'color' => '#8b4513', 'bg' => '#f8f9fa', 'icon' => 'mdi:coffee'],
                                        'break' => ['label' => 'Break', 'color' => '#007bff', 'bg' => '#f8f9fa', 'icon' => 'mdi:pause']
                                    ];
                                @endphp
                                @foreach($actions as $type => $btn)
                                <button data-type="{{ $type }}" style="width:65px;height:28px;border-radius:14px;background:{{ $btn['bg'] }};border:1px solid {{ $btn['color'] }};display:flex;align-items:center;justify-content:center;font-size:12px;color:{{ $btn['color'] }};">
                                    <iconify-icon icon="{{ $btn['icon'] }}" style="margin-right:2px;font-size:14px;"></iconify-icon>{{ $btn['label'] }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        @if($timer['button_status'] == 0)
                        <a href="javascript:void(0)" class="enable-junior btn mt-16 w-100" data-user="{{ $timer['user_id'] }}">
                            Enable Junior
                        </a>
                        @else
                        <a href="javascript:void(0)" class="disable-junior btn mt-16 w-100" data-user="{{ $timer['user_id'] }}">
                            Disable Junior
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div id="statusOverlay"></div>

<script>
    const formatTime = sec => {
        sec = Math.max(0, Math.floor(sec));
        const h = Math.floor(sec / 3600), m = Math.floor((sec % 3600) / 60), s = sec % 60;
        return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
    };

    const updateAllTimers = () => {
        fetch("{{ route('timer.alljuniors') }}")
        .then(res => res.json())
        .then(timers => {
            if (!Array.isArray(timers)) return console.warn("Invalid timer response:", timers);
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
        }).catch(err => console.error("Timer fetch error:", err));
    };

    const setupSeniorControls = () => {
        document.querySelectorAll('.timer-widget').forEach(widget => {
            const userId = widget.dataset.user;
            widget.querySelectorAll('.seniorcontrolButtons button').forEach(btn => {
                btn.addEventListener('click', () => {
                    const action = btn.dataset.type;
                    fetch("{{ route('timer.update') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({ action, user_id: userId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success && data.notice_status) {
                            alert(data.message || "Please wait for senior to enable.");
                            return;
                        }
                        widget.dataset.remaining = data.remaining_seconds;
                        widget.dataset.elapsed = data.elapsed_seconds;
                        widget.dataset.status = data.status;

                        widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                        widget.querySelector('.elapsed').innerText = formatTime(data.elapsed_seconds);
                    })
                    .catch(err => console.error(err));
                });
            });
        });
    };

    setupSeniorControls();
    setInterval(updateAllTimers, 1000);
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


@endsection