@php
$role = Auth::user()->role;
@endphp

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div class="sidebar-logo d-flex justify-content-center align-items-center" style="text-align:center; padding: 20px 0;">
        <a href="#">
            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo" style="max-width: 120px; display: block;">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo" style="max-width: 120px; display: none;">
            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon" style="max-width: 60px; display: none;">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            {{-- ================= Junior ================= --}}
            @if($role === 'junior')
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('dashboard.junior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Junior Dashboard</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Calendar</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.junior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Junior Cal.</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Database</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.junior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database Sheet</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.junior.candm') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database Sheet C&M</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Report</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.junior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Daily Report</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.juniormonthly') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Monthly Report</a></li>
                </ul>
            </li>


            @endif

            {{-- ================= Senior ================= --}}
            @if($role === 'senior')
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('dashboard.senior') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Dashboard</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Calendar</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.seniorUser') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Personal Calendar</a></li>
                </ul>

                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.allJuniorlist') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Junior Calendar</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Database</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.senior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.seniorcandm') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Called & Mailed</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.seniorpaid') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Ready To Paid</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Report</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.senior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Daily Personal Report</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.seniormonthly') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Monthly Personal Report</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.alljuniorlist') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Team Report</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Timer</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('timer.senior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Timer Report</a></li>
                </ul>
            </li>
            @endif

            {{-- ================= Accountant ================= --}}
            @if($role === 'accountant')
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('dashboard.accountant') }}"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>Accountant Dashboard</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Calendar</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>Calendar</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Database</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.accountant') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Report</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                </ul>
            </li>
            @endif

            {{-- ================= Admin ================= --}}
            @if($role === 'admin')
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('dashboard.admin') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Dashboard</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Users</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('users.admin') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Admin</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('users.junior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Junior</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('users.senior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Senior</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('users.accountant') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Accountant</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('users.trainer') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Trainer</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Calendar</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.allAdminlist') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Admin Calendar</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.allJuniorlist') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Junior Calendar</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.allSeniorlist') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Senior Calendar</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.allAccountantlist') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Account Calendar</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('calendar.allTrainerlist') }}"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Trainer Calendar</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Database</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.index') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Report</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.alljuniorlist') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Team Report</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('call.reports.allseniorlist') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Senior Report</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Accountant Report</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Trainer Report</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Timer</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('timer.allsenior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Senior Timer</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('timer.senior') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Junior Timer</a></li>
                </ul>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('timer.admin') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Timer Setting</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>SMTP</span>
                </a>

                <ul class="sidebar-submenu">
                    <li><a href="{{ route('smtp.editall') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>All User SMTP</a></li>
                </ul>

            </li>
            @endif

            {{-- ================= Trainer ================= --}}
            @if($role === 'trainer')
            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('dashboard.trainer') }}"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Dashboard</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                    <span>Calendar</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Calendar</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Database</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href="{{ route('google.sheet.trainer') }}"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Report</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a href="javascript:void(0)">
                    <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                    <span>Timer</span>
                </a>
                <ul class="sidebar-submenu">
                    <li><a href=""><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Timer Report</a></li>
                </ul>
            </li>
            @endif

        </ul>
    </div>
</aside>