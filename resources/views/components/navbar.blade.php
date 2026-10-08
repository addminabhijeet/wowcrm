@php
$userImage = Auth::user()->image
? asset('assets/images/user-grid/' . Auth::user()->image)
: asset('assets/images/users/user1.png');
@endphp

<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">

                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>

                {{-- Call duration of the logged-in junior (replaces the old timer widget): the shift total so far + a short note --}}
                @if ((auth()->user()->role ?? '') === 'junior' && class_exists(\App\Services\CallDurationSummary::class))
                @php
                    $callPill = \App\Services\CallDurationSummary::pill(auth()->user());
                    $pillColor = ['delay' => ['#fff3cd', '#8a6d00'], 'none' => ['#f8d7da', '#b02a37'], 'final' => ['#d4edda', '#1e7e34']][$callPill['state'] ?? 'delay'] ?? ['#fff3cd', '#8a6d00'];
                @endphp
                @if ($callPill)
                <style>
                    /* centred in the navbar (the navbar is position: sticky, so it is the anchor); on small screens it stays in the flow */
                    .call-pill-center { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); z-index: 1; }
                    @media (max-width: 767.98px) { .call-pill-center { position: static; transform: none; } }
                </style>
                <div id="callDurationPill" class="call-pill-center" title="{{ $callPill['title'] }}"
                    style="display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 12px;box-shadow:0 1px 3px rgba(0,0,0,0.08);white-space:nowrap;">
                    <iconify-icon icon="mdi:phone-in-talk-outline" style="color:#05A9A4;font-size:16px;"></iconify-icon>
                    <span style="color:#6c757d;font-size:11px;">Calls</span>
                    <strong style="color:#212529;font-size:14px;">{{ $callPill['value'] }}</strong>
                    <span style="background:{{ $pillColor[0] }};color:{{ $pillColor[1] }};border-radius:10px;padding:1px 8px;font-size:10px;font-weight:600;">{{ $callPill['tag'] }}</span>
                </div>
                @endif
                @endif
            </div>
        </div>

        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle
                    class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

                <div class="dropdown">
                    <button
                        class="position-relative has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                        type="button" data-bs-toggle="dropdown">

                        <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>

                        <span id="message-badge"
                            class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger d-none"
                            style="
                                font-size:11px;
                                min-width:22px;
                                height:22px;
                                display:inline-flex;
                                align-items:center;
                                justify-content:center;
                                box-shadow:0 0 8px rgba(255,0,0,0.5);
                                background:rgba(255,0,0,0.85);
                                backdrop-filter:blur(8px);
                            ">
                            0
                        </span>

                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                        <div
                            class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
                            </div>
                            <span
                                id="message-count"
                                class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">
                                0
                            </span>
                        </div>

                        <div
                            id="chat-message-list"
                            class="max-h-400-px overflow-y-auto scroll-sm pe-4">
                        </div>

                        <div class="text-center py-12 px-16">
                            <a href="{{ route('chat.junior') }}" class="text-primary-600 fw-semibold text-md">See All
                                Message</a>
                        </div>
                    </div>
                </div>

                <div class="dropdown" style="width:auto;">
                    <a href="{{ route('admin.notifications') }}"
                        style="text-decoration:none; color:inherit; display:block;">

                        <button id="notificationDropdownBtn"
                            class="position-relative has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true"
                            style="
                transition:0.25s ease;
                box-shadow:0 6px 18px rgba(0,0,0,0.15);
                background:rgba(255,255,255,0.40);
                backdrop-filter:blur(16px) saturate(200%);
                -webkit-backdrop-filter:blur(16px) saturate(200%);
            ">
                            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>

                            <!-- Unread badge -->
                            <span id="unread-badge"
                                class="badge position-absolute top-0 start-100 translate-middle rounded-pill bg-danger d-none"
                                style="
                    font-size:11px; min-width:22px; height:22px;
                    display:inline-flex; align-items:center; justify-content:center;
                    box-shadow:0 0 8px rgba(255,0,0,0.5);
                    background:rgba(255,0,0,0.85);
                    backdrop-filter:blur(8px);
                ">
                                0
                            </span>
                        </button>

                        <div class="dropdown-menu to-top dropdown-menu-lg p-0"
                            style="
                width:520px;
                max-width:92vw;
                background:rgba(255,255,255,0.12);
                backdrop-filter:blur(30px) saturate(240%);
                -webkit-backdrop-filter:blur(30px) saturate(240%);
                border-radius:24px;
                border:1px solid rgba(255,255,255,0.55);
                box-shadow:
                    0 20px 50px rgba(0,0,0,0.35),
                    inset 0 0 20px rgba(255,255,255,0.25);
                overflow:hidden;
                cursor:pointer;
                transform:translateY(8px);
                transition:0.35s ease;
             ">

                            <div class="d-flex justify-content-between align-items-center px-12 py-12 border-bottom"
                                style="
                    background:rgba(255,255,255,0.20);
                    backdrop-filter:blur(20px);
                    -webkit-backdrop-filter:blur(20px);
                    border-bottom:1px solid rgba(255,255,255,0.45);
                    cursor:pointer;
                    font-weight:600;
                    box-shadow:inset 0 -1px 8px rgba(255,255,255,0.25);
                 ">
                                <strong style="font-size:17px;">Notifications</strong>
                                <button id="markAllReadBtn" class="btn btn-link btn-sm text-muted"
                                    style="
                        font-size:13px; text-decoration:none;
                        transition:0.2s;
                        color:#6c757d !important;
                    ">
                                    Mark all as read
                                </button>
                            </div>

                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4"
                                style="
                    cursor:pointer;
                    backdrop-filter:blur(14px);
                    -webkit-backdrop-filter:blur(14px);
                 ">
                                <div id="latest-notification-box" class="p-12"
                                    style="
                        white-space:normal;
                        word-wrap:break-word;
                        line-height:1.55;
                        font-size:14px;
                        background:rgba(255,255,255,0.16);
                        border-radius:16px;
                        margin:10px;
                        padding:20px;
                        backdrop-filter:blur(12px);
                        -webkit-backdrop-filter:blur(12px);
                        cursor:pointer;
                        border:1px solid rgba(255,255,255,0.40);
                        box-shadow:
                            0 4px 16px rgba(0,0,0,0.12),
                            inset 0 0 12px rgba(255,255,255,0.25);
                     ">
                                    <p class="text-muted small mb-0">No notifications</p>
                                </div>
                            </div>

                            <!-- Bottom section -->
                            <div class="text-center py-14 px-16 hover-bg-neutral-100 cursor-pointer"
                                style="
                    background:rgba(255,255,255,0.14);
                    border-top:1px solid rgba(255,255,255,0.32);
                    backdrop-filter:blur(18px);
                    -webkit-backdrop-filter:blur(18px);
                    font-size:15px;
                    font-weight:600;
                    color:#3b5bfd;
                    transition:0.25s ease;
                    box-shadow:inset 0 4px 12px rgba(255,255,255,0.15);
                 ">
                                <span>See All Notifications</span>
                            </div>

                        </div>
                    </a>
                </div>






                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button"
                        data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->image ? asset('storage/app/public/' . Auth::user()->image) : asset('assets/images/user-grid/user-grid-bg1.png') }}" alt="image"
                            class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div
                            class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">{{ Auth::user()->name }}</h6>
                                <span class="text-secondary-light fw-medium text-sm">{{ Auth::user()->email }}</span>
                            </div>
                            <button type="button" class="hover-text-danger">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>
                        <ul class="to-top-list">
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon> My
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-primary d-flex align-items-center gap-3"
                                    href="">
                                    <iconify-icon icon="icon-park-outline:setting-two"
                                        class="icon text-xl"></iconify-icon> Setting
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item text-black px-0 py-8 hover-bg-transparent hover-text-danger d-flex align-items-center gap-3"
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon> Log Out
                                </a>

                                {{-- Hidden logout form --}}
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        let lastNotificationId = localStorage.getItem("last_notification_id") || null;
        let lastNotificationTime = localStorage.getItem("last_notification_time") || null;

        let dropdownTimer = null;
        const pollInterval = 60000;

        const latestBox = document.querySelector('#latest-notification-box');
        const dropdownBtn = document.querySelector('#notificationDropdownBtn');
        const unreadBadge = document.querySelector('#unread-badge');
        const markAllBtn = document.querySelector('#markAllReadBtn');


        function setBadge(count) {
            if (!unreadBadge) return;

            if (count > 0) {
                unreadBadge.textContent = count > 99 ? '99+' : count;
                unreadBadge.classList.remove('d-none');
            } else {
                unreadBadge.classList.add('d-none');
            }
        }


        function fetchLatestNotification() {

            fetch("{{ route('admin.latest.notification') }}", {
                    credentials: "same-origin"
                })
                .then(res => res.json())
                .then(response => {

                    if (typeof response.unread_count !== "undefined") {
                        setBadge(response.unread_count);
                    }

                    if (!response.status || !response.html) return;

                    const latestId = response.id;
                    const latestTime = response.created_at; // timestamp


                    // 1. ID changed
                    // 2. timestamp is newer
                    if (response.unread_count > 0 &&
                        (lastNotificationId != latestId || lastNotificationTime != latestTime)) {


                        // Save ID + timestamp
                        localStorage.setItem("last_notification_id", latestId);
                        localStorage.setItem("last_notification_time", latestTime);

                        // Update HTML
                        if (latestBox) {
                            latestBox.innerHTML = response.html;

                            latestBox.firstElementChild?.classList.add("flash-new");
                            setTimeout(() => latestBox.firstElementChild?.classList.remove("flash-new"), 1400);
                        }

                        // Show dropdown ONLY for TRUE new notification
                        const dropdown = new bootstrap.Dropdown(dropdownBtn);
                        dropdown.show();

                        if (dropdownTimer) clearTimeout(dropdownTimer);
                        dropdownTimer = setTimeout(() => dropdown.hide(), 8000);

                        return;
                    }

                    // If NOT a new notification → DO NOT open dropdown
                    // Just ensure HTML is loaded once if empty
                    if (!latestBox.innerHTML.trim()) {
                        latestBox.innerHTML = response.html;
                    }

                })
                .catch(err => console.error("Fetch latest notification error:", err));
        }


        function markAllRead() {
            fetch("{{ route('admin.notifications.markallread') }}", {
                    method: "PUT", // Match the route
                    credentials: "same-origin",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken || ""
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(json => {
                    if (json.status) {
                        setBadge(0);

                        // Save latest ID so popup NEVER reopens
                        if (json.latest_id) {
                            localStorage.setItem("last_notification_id", json.latest_id);
                        }
                        if (json.latest_time) {
                            localStorage.setItem("last_notification_time", Number(json.latest_time));
                        }
                    }
                })
                .catch(err => console.error('Error marking notifications as read:', err));
        }



        // Mark on dropdown open
        if (dropdownBtn) {
            dropdownBtn.addEventListener("show.bs.dropdown", () => {
                setTimeout(() => markAllRead(), 8000);
            });
        }

        if (markAllBtn) {
            markAllBtn.addEventListener("click", (e) => {
                e.preventDefault();
                markAllRead();
            });
        }

        fetchLatestNotification();
        setInterval(fetchLatestNotification, pollInterval);

    })();
</script>



<style>
    @keyframes flashNew {
        0% {
            background: rgba(0, 123, 255, .1);
            transform: translateY(-4px);
        }

        100% {
            background: transparent;
            transform: translateY(0);
        }
    }

    .flash-new {
        animation: flashNew 0.9s ease;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function loadMessages() {

            fetch("{{ route('chat.latestMessages') }}")
                .then(response => response.json())
                .then(data => {

                    const countElement = document.getElementById('message-count');

                    if (countElement) {
                        countElement.innerText = data.count;
                    }

                    const messageBadge = document.getElementById('message-badge');

                    if (messageBadge) {

                        if (data.count > 0) {

                            messageBadge.innerText =
                                data.count > 99 ? '99+' : data.count;

                            messageBadge.classList.remove('d-none');

                        } else {

                            messageBadge.classList.add('d-none');

                        }

                    }

                    let html = '';

                    data.users.forEach(user => {
                        const chatBaseUrl = "{{ route('chat.junior') }}";
                        html += `
                        <a href="${chatBaseUrl}?user=${user.id}"
                           class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">

                            <div class="d-flex align-items-center gap-3">

                                <span class="w-40-px h-40-px rounded-circle">

                                    <img
                                        src="${user.image ?
                                        '/storage/app/public/' + user.image :
                                        '/assets/images/user-grid/user-grid-bg1.png'}"
                                        class="w-40-px h-40-px rounded-circle">

                                </span>

                                <div>

                                    <h6 class="text-md fw-semibold mb-1">
                                        ${user.name}
                                    </h6>

                                    <p class="mb-0 text-sm text-secondary-light">

                                        ${
                                            user.lastChat?.message ??
                                            user.lastChat?.file_name ??
                                            'New message'
                                        }

                                    </p>

                                </div>

                            </div>

                            <div class="text-end">

                                <span class="mt-1 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">
                                    ${user.unreadCount}
                                </span>

                            </div>

                        </a>
                    `;
                    });

                    const listElement = document.getElementById('chat-message-list');

                    if (listElement) {
                        listElement.innerHTML = html;
                    }
                }).catch(err => {
                    console.error('Error loading messages:', err);
                });

        }

        loadMessages();

        setInterval(loadMessages, 30000);

    });
</script>