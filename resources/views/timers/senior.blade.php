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
                <div class="position-relative border radius-16 overflow-hidden">
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

    // Local tick for smooth countdown
    function localTick() {
        document.querySelectorAll('.timer-widget').forEach(widget => {
            let remaining = parseInt(widget.dataset.remaining);
            let elapsed = parseInt(widget.dataset.elapsed);

            if (widget.dataset.status === 'running' && remaining > 0) {
                remaining -= 1;
                elapsed += 1;

                widget.dataset.remaining = remaining;
                widget.dataset.elapsed = elapsed;

                widget.querySelector('.countdown').innerText = formatTime(remaining);
                widget.querySelector('.elapsed').innerText = formatTime(elapsed);

                if (remaining <= 0) {
                    alert("User " + widget.dataset.user + " has finished their 9-hour session.");
                }
            }
        });
    }

    // Bulk refresh from server (every 10s)
    function updateAllTimers() {
        fetch("{{ route('timer.alljuniors') }}")
            .then(res => res.json())
            .then(timers => {
                timers.forEach(data => {
                    const widget = document.querySelector(`.timer-widget[data-user='${data.user_id}']`);
                    if (!widget) return;

                    widget.dataset.remaining = data.remaining_seconds;
                    widget.dataset.elapsed = data.elapsed_seconds;
                    widget.dataset.status = data.status;

                    widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                    widget.querySelector('.elapsed').innerText = formatTime(data.elapsed_seconds);
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

                    fetch("{{ route('timer.updatejunior') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                user_id: userId,
                                action
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            widget.dataset.remaining = data.remaining_seconds;
                            widget.dataset.elapsed = data.elapsed_seconds;
                            widget.dataset.status = data.status;

                            widget.querySelector('.countdown').innerText = formatTime(data.remaining_seconds);
                            widget.querySelector('.elapsed').innerText = formatTime(data.elapsed_seconds);
                        });
                });
            });
        });
    }

    // 🚀 Initialize
    setupControlButtons();
    setInterval(localTick, 1000); // smooth countdown every second
    setInterval(updateAllTimers, 10000); // sync with DB every 10s
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

            const card = document.querySelector(`.user-grid-card[data-user-id='${userId}']`);
            if (!card) {
                console.warn(`Card not found for user_id ${userId}`);
                return;
            }

            const dropdown = card.querySelector('.dropdown button');
            const btnContainer = card.querySelector('.ps-16');

            // Update dropdown style
            if (dropdown) {
                dropdown.className = data.button_status == 0
                    ? 'bg-danger w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white'
                    : 'bg-white-gradient-light w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white';
            } else {
                console.warn(`Dropdown not found for user ${userId}`);
            }

            // Update action button
            if (btnContainer) {
                btnContainer.innerHTML = data.button_status == 0
                    ? `
                    <a href="javascript:void(0)" class="btn btn-primary enable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${userId}">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Enable Junior
                    </a>`
                    : `
                    <a href="javascript:void(0)" class="btn btn-danger disable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${userId}">
                        <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
                        Disable Junior
                    </a>`;
            } else {
                console.warn(`Button container not found for user ${userId}`);
            }

            // Re-bind events after DOM change
            setupStatusButtons();
        })
        .catch(err => console.error(`Fetch error for user ${userId}:`, err));
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

    // Initial binding on page load
    setupStatusButtons();
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
            body: JSON.stringify({ action: action })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                console.error('Bulk toggle failed:', data);
                return;
            }

            console.log('Bulk toggle response:', data);

            data.updated.forEach(user => {
                const card = document.querySelector(`.user-grid-card[data-user-id='${user.user_id}']`);
                if (!card) {
                    console.warn(`User card not found for user_id ${user.user_id}`);
                    return;
                }

                const btnContainer = card.querySelector('.ps-16');
                const dropdown = card.querySelector('.dropdown button');

                // Update dropdown style
                if (!dropdown) {
                    console.warn(`Dropdown button not found for user_id ${user.user_id}`);
                } else {
                    dropdown.className = user.button_status
                        ? 'bg-white-gradient-light w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white'
                        : 'bg-danger w-32-px h-32-px radius-8 border d-flex justify-content-center align-items-center text-white';
                }

                // Update action button
                if (!btnContainer) {
                    console.warn(`Button container not found for user_id ${user.user_id}`);
                } else {
                    btnContainer.innerHTML = user.button_status
                        ? `
                        <a href="javascript:void(0)" class="btn btn-danger disable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${user.user_id}">
                            <iconify-icon icon="ic:baseline-minus" class="icon text-xl line-height-1"></iconify-icon>
                            Disable Junior
                        </a>`
                        : `
                        <a href="javascript:void(0)" class="btn btn-primary enable-junior text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2" data-user="${user.user_id}">
                            <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                            Enable Junior
                        </a>`;
                }
            });

            // Re-bind events
            setupStatusButtons();
        })
        .catch(err => console.error('Bulk toggle fetch error:', err));
    }

    // Bind top buttons
    const enableAllBtn = document.getElementById('enableAll');
    const disableAllBtn = document.getElementById('disableAll');

    if (enableAllBtn) enableAllBtn.onclick = () => toggleAllStatus('enable');
    if (disableAllBtn) disableAllBtn.onclick = () => toggleAllStatus('disable');
</script>



@endsection