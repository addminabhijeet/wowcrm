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


                <div style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">


                    <div style="margin-right:10px;text-align:center;min-width:60px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                            <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                            <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Countdown</small>
                        </div>
                        <span id="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">09:00:00</span>
                    </div>


                    <div style="width:1px;background:#dee2e6;margin:0 4px;"></div>


                    <div style="margin-right:10px;text-align:center;min-width:60px;">
                        <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                            <iconify-icon icon="mdi:clock-outline" style="color:#28a745;font-size:14px;"></iconify-icon>
                            <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Elapsed</small>
                        </div>
                        <span id="elapsed" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">00:00:00</span>
                    </div>

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

                    <!-- Placeholder for Start button -->
                    <div id="startButtonContainer" style="display:none;align-items:center;gap:4px;flex-wrap:wrap;margin-left:4px;">
                        <button id="startButton" style="width:65px;height:28px;border-radius:14px;background:#28a745;border:1px solid #1e7e34;display:flex;align-items:center;justify-content:center;font-size:12px;color:#fff;">
                            <iconify-icon icon="mdi:play" style="margin-right:2px;font-size:14px;"></iconify-icon>Start
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">
                <button type="button" data-theme-toggle class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>

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
                                        <img src="{{ asset('assets/images/notification/profile-3.png') }}" alt="">
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
                </div>

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
                                        <img src="{{ asset('assets/images/notification/profile-1.png') }}" alt="">
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
                </div>

                <div class="dropdown">
                    <button class="d-flex justify-content-center align-items-center rounded-circle" type="button" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/images/user.png') }}" alt="image" class="w-40-px h-40-px object-fit-cover rounded-circle">
                    </button>
                    <div class="dropdown-menu to-top dropdown-menu-sm">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
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

<style>
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
[UI] Updating display → Remaining: 32397.465258 Elapsed: 2.5347419999998237 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32395.451693, elapsed_seconds: 4.548307000000932, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32395.451693 Elapsed: 4.548307000000932 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32393.44692, elapsed_seconds: 6.553080000001501, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32393.44692 Elapsed: 6.553080000001501 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32391.461042, elapsed_seconds: 8.538958000001003, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32391.461042 Elapsed: 8.538958000001003 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32389.457933, elapsed_seconds: 10.542066999998497, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32389.457933 Elapsed: 10.542066999998497 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32387.450296, elapsed_seconds: 12.54970400000093, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32387.450296 Elapsed: 12.54970400000093 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32385.460795, elapsed_seconds: 14.539205000000948, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32385.460795 Elapsed: 14.539205000000948 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32383.448495, elapsed_seconds: 16.55150499999945, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32383.448495 Elapsed: 16.55150499999945 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32381.448783, elapsed_seconds: 18.55121700000018, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32381.448783 Elapsed: 18.55121700000018 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32379.461516, elapsed_seconds: 20.538484000000608, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32379.461516 Elapsed: 20.538484000000608 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32377.46093, elapsed_seconds: 22.5390699999989, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32377.46093 Elapsed: 22.5390699999989 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32375.446187, elapsed_seconds: 24.553812999998627, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32375.446187 Elapsed: 24.553812999998627 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32373.457614, elapsed_seconds: 26.542386000000988, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32373.457614 Elapsed: 26.542386000000988 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32371.455183, elapsed_seconds: 28.544817000001785, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32371.455183 Elapsed: 28.544817000001785 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32369.443514, elapsed_seconds: 30.556486000001314, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32369.443514 Elapsed: 30.556486000001314 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32367.460486, elapsed_seconds: 32.539514000000054, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32367.460486 Elapsed: 32.539514000000054 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32365.458458, elapsed_seconds: 34.541541999999026, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32365.458458 Elapsed: 34.541541999999026 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32363.457255, elapsed_seconds: 36.54274499999883, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32363.457255 Elapsed: 36.54274499999883 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32361.459138, elapsed_seconds: 38.54086200000165, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32361.459138 Elapsed: 38.54086200000165 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32359.450209, elapsed_seconds: 40.54979100000128, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32359.450209 Elapsed: 40.54979100000128 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32357.448059, elapsed_seconds: 42.551941000001534, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32357.448059 Elapsed: 42.551941000001534 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32355.448311, elapsed_seconds: 44.5516889999999, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32355.448311 Elapsed: 44.5516889999999 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32353.451645, elapsed_seconds: 46.54835499999899, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32353.451645 Elapsed: 46.54835499999899 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32351.451346, elapsed_seconds: 48.54865399999835, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32351.451346 Elapsed: 48.54865399999835 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32349.453946, elapsed_seconds: 50.54605399999855, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32349.453946 Elapsed: 50.54605399999855 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32347.458574, elapsed_seconds: 52.541425999999774, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32347.458574 Elapsed: 52.541425999999774 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32345.461127, elapsed_seconds: 54.53887300000133, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32345.461127 Elapsed: 54.53887300000133 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32343.467953, elapsed_seconds: 56.5320470000006, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32343.467953 Elapsed: 56.5320470000006 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32341.466743, elapsed_seconds: 58.53325699999914, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32341.466743 Elapsed: 58.53325699999914 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32339.450061, elapsed_seconds: 60.54993900000045, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32339.450061 Elapsed: 60.54993900000045 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32337.461857, elapsed_seconds: 62.53814300000158, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32337.461857 Elapsed: 62.53814300000158 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32335.458115, elapsed_seconds: 64.54188499999873, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32335.458115 Elapsed: 64.54188499999873 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32333.450423, elapsed_seconds: 66.54957700000159, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32333.450423 Elapsed: 66.54957700000159 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seconds: 32331.463326, elapsed_seconds: 68.53667399999904, status: 'running', pause_type: 'resume', …}
junior:391 [UI] Updating display → Remaining: 32331.463326 Elapsed: 68.53667399999904 Status: running
junior:576 [Status Check] Response: {button_status: 0}
junior:593 [Status Check] Start button visible, control buttons hidden.
junior:417 [Sync] Sending tick to backend...
junior:569 [Status Check] Fetching button status...
junior:430 [Sync] Response: {success: true, remaining_seco

public function updateTimer(Request $request)
{
$user = Auth::user();
$action = $request->input('action');

// Get the latest timer log for the user
$timer = UserTimerLog::where('user_id', $user->id)->latest()->first();
if (!$timer) {
return response()->json(['error' => 'Timer not found'], 404);
}

// Current time (uses default timezone)
$currentTime = now();

// Fetch work day duration from settings
$timerSetting = TimerSetting::first();
if (!$timerSetting) {
return response()->json(['error' => 'Timer settings not configured'], 500);
}

$workDaySeconds = $timerSetting->work_day_seconds;

// ⏱ Update remaining seconds if timer is running
if ($timer->status === 'running') {
$secondsPassed = $currentTime->diffInSeconds($timer->updated_at);
$timer->remaining_seconds = max(0, $timer->remaining_seconds - $secondsPassed);
}

// 🧭 Handle actions
if ($action === 'resume') {
$timer->status = 'running';
$timer->pause_type = 'resume';
} elseif ($action !== 'tick') {
$timer->status = 'paused';
$timer->pause_type = $action;
}

// 🕓 Update timestamp and save
$timer->updated_at = $currentTime;
$timer->save();

// 🔢 Calculate elapsed time
$elapsedSeconds = max(0, $workDaySeconds - $timer->remaining_seconds);


// 🧾 Log pause/resume event (only if not a tick update)
if ($action !== 'tick') {
UserTimerPause::create([
'user_timer_log_id' => $timer->id,
'user_id' => $user->id,
'status' => $timer->status,
'pause_type' => $timer->pause_type,
'remaining_seconds' => $timer->remaining_seconds,
'elapsed_seconds' => $elapsedSeconds,
'event_time' => $currentTime,
]);
}

// 🧠 Return response
return response()->json([
'success' => true,
'remaining_seconds' => $timer->remaining_seconds,
'elapsed_seconds' => $elapsedSeconds,
'status' => $timer->status,
'pause_type' => $timer->pause_type,
'notice_status' => $timer->notice_status,
'logout' => $timer->remaining_seconds <= 0
    ]);
    }

    public function junior()
    {
    // Fetch timer settings
    $settings=TimerSetting::first();
    if (!$settings) {
    return response()->json(['error' => 'Timer settings not configured'], 500);
    }
    $workDaySeconds = $settings->work_day_seconds;

    $user = Auth::user();
    $timer = UserTimerLog::where('user_id', $user->id)->latest()->first();

    $remaining_seconds = $workDaySeconds;
    $elapsed_seconds = 0;
    $status = 'running';
    $button_status = 1; // default to show if no timer exists

    if ($timer) {
    $remaining_seconds = $timer->remaining_seconds;
    $elapsed_seconds = $workDaySeconds - $remaining_seconds;
    $status = $timer->status;
    $button_status = $timer->button_status ?? 1;
    }

    return view('dashboard.junior', compact(
    'remaining_seconds',
    'elapsed_seconds',
    'status',
    'button_status'
    ));
    }

    <div id="statusOverlay"></div>

    <script>
        // ===============================
        // Timer Variables
        // ===============================
        let backendSyncInterval;
        let remainingSeconds = Number("{{ $remaining_seconds ?? 0 }}");
        let elapsedSeconds = Number("{{ $elapsed_seconds ?? 0 }}");
        let status = "{{ $status ?? 'running' }}";

        let inactiveTimeout;
        const INACTIVE_LIMIT = 2 * 60 * 1000; // 2 minutes inactivity
        let overlayTimeout;

        // ===============================
        // Helper Functions
        // ===============================
        function formatTime(sec) {
            sec = Math.floor(sec);
            const h = Math.floor(sec / 3600);
            const m = Math.floor((sec % 3600) / 60);
            const s = sec % 60;
            return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
        }

        function updateUI() {
            console.log("[UI] Updating display → Remaining:", remainingSeconds, "Elapsed:", elapsedSeconds, "Status:", status);
            const countdownElem = document.getElementById('countdown');
            const elapsedElem = document.getElementById('elapsed');

            if (countdownElem) countdownElem.innerText = formatTime(remainingSeconds);
            if (elapsedElem) elapsedElem.innerText = formatTime(elapsedSeconds);
        }

        function showOverlay(message) {
            console.log("[Overlay] Message:", message);
            const overlay = document.getElementById('statusOverlay');
            if (!overlay) return;

            overlay.innerText = message;
            overlay.classList.add('show');

            clearTimeout(overlayTimeout);
            overlayTimeout = setTimeout(() => {
                overlay.classList.remove('show');
            }, 3000);
        }

        // ===============================
        // Backend Sync
        // ===============================
        function syncWithBackend() {
            console.log("[Sync] Sending tick to backend...");
            fetch("{{ route('timer.update') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: 'tick'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("[Sync] Response:", data);

                    if (!data.success) {
                        console.warn("[Sync] No success response");
                        return;
                    }

                    if (data.notice_status === 1 && data.message) {
                        showOverlay(data.message);
                    }

                    remainingSeconds = data.remaining_seconds;
                    elapsedSeconds = data.elapsed_seconds;
                    status = data.status;
                    updateUI();

                    if (data.logout) {
                        console.warn("[Sync] Work session ended. Logging out...");
                        clearInterval(backendSyncInterval);
                        alert("Your 9-hour work session has ended.");
                        // forceLogout();
                    }
                })
                .catch(err => console.error("[Sync] Timer sync failed:", err));
        }

        // ===============================
        // Control Buttons (Pause/Resume/etc)
        // ===============================
        document.querySelectorAll('#controlButtons button').forEach(btn => {
            btn.addEventListener('click', () => {
                const type = btn.getAttribute('data-type');
                console.log("[Action] Button clicked:", type);

                fetch("{{ route('timer.update') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            action: type
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        console.log("[Action] Response:", data);

                        if (data.success === false && data.notice_status === 1) {
                            showOverlay(data.message || "Please wait for senior to enable.");
                            return;
                        }

                        remainingSeconds = data.remaining_seconds;
                        elapsedSeconds = data.elapsed_seconds;
                        status = data.status;
                        updateUI();
                    })
                    .catch(err => console.error("[Action] Failed to send:", err));
            });
        });

        // ===============================
        // Inactivity Handling
        // ===============================
        function resetInactiveTimer() {
            clearTimeout(inactiveTimeout);
            console.log("[Inactivity] Timer reset");

            inactiveTimeout = setTimeout(() => {
                console.warn("[Inactivity] User inactive! Pausing timer...");
                showOverlay("You were inactive! Timer stopped.");

                fetch("{{ route('timer.update') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: "pause"
                    })
                }).catch(err => console.error("[Inactivity] Pause request failed:", err));
            }, INACTIVE_LIMIT);
        }

        function handleActiveState() {
            console.log("[Active] User active again, resuming...");
            fetch("{{ route('timer.update') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        action: "resume"
                    })
                })
                .then(res => res.json())
                .then(data => {
                    console.log("[Active] Resume response:", data);
                    if (data.success) {
                        remainingSeconds = data.remaining_seconds;
                        elapsedSeconds = data.elapsed_seconds;
                        status = data.status;
                        updateUI();
                    }
                })
                .catch(err => console.error("[Active] Resume request failed:", err));

            resetInactiveTimer();
        }

        ['mousemove', 'keydown', 'scroll', 'click'].forEach(evt => {
            window.addEventListener(evt, resetInactiveTimer);
        });

        document.addEventListener('visibilitychange', () => {
            console.log("[Visibility] Changed:", document.visibilityState);
            if (document.visibilityState === 'visible') handleActiveState();
        });

        // ===============================
        // Initialize Timer
        // ===============================
        console.log("[Init] Timer script initializing...");
        updateUI();
        resetInactiveTimer();
        handleActiveState();

        backendSyncInterval = setInterval(syncWithBackend, 1000);
        console.log("[Init] Backend sync interval started (1s)");
    </script>

    <script>
        // ===============================
        // Button Status Check
        // ===============================
        function checkButtonStatus() {
            console.log("[Status Check] Fetching button status...");
            fetch("{{ route('button.status') }}")
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    console.log("[Status Check] Response:", data);

                    const controlButtons = document.getElementById('controlButtons');
                    const startButtonContainer = document.getElementById('startButtonContainer');

                    if (!controlButtons || !startButtonContainer) {
                        console.warn("[Status Check] Required elements not found in DOM.");
                        return;
                    }

                    if (data.button_status == 1) {
                        controlButtons.style.display = 'flex';
                        startButtonContainer.style.display = 'none';
                        console.log("[Status Check] Control buttons visible, start button hidden.");
                    } else {
                        controlButtons.style.display = 'none';
                        startButtonContainer.style.display = 'flex';
                        console.log("[Status Check] Start button visible, control buttons hidden.");
                    }
                })
                .catch(err => console.error("[Status Check] Error fetching button status:", err));
        }

        checkButtonStatus();
        setInterval(checkButtonStatus, 1000);
    </script>

    correct the time, here it decrease 2 second in 1 second and increase elasped second 2 sec in 1 sec,


    <script>
        // ===============================
        // Button Status Check
        // ===============================
        function checkButtonStatus() {
            console.log("[Status Check] Fetching button status...");
            fetch("{{ route('button.status') }}")
                .then(response => {
                    if (!response.ok) throw new Error("Network response was not ok");
                    return response.json();
                })
                .then(data => {
                    console.log("[Status Check] Response:", data);

                    const controlButtons = document.getElementById('controlButtons');
                    const startButtonContainer = document.getElementById('startButtonContainer');

                    if (!controlButtons || !startButtonContainer) {
                        console.warn("[Status Check] Required elements not found in DOM.");
                        return;
                    }

                    if (data.button_status == 1) {
                        controlButtons.style.display = 'flex';
                        startButtonContainer.style.display = 'none';
                        console.log("[Status Check] Control buttons visible, start button hidden.");
                    } else {
                        controlButtons.style.display = 'none';
                        startButtonContainer.style.display = 'flex';
                        console.log("[Status Check] Start button visible, control buttons hidden.");
                    }
                })
                .catch(err => console.error("[Status Check] Error fetching button status:", err));
        }

        checkButtonStatus();
        setInterval(checkButtonStatus, 1000);
    </script>