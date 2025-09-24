<?php
    $role = Auth::user()->role;
?>

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>

    <div>
        <a href="" class="sidebar-logo">
            <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="site logo" class="light-logo">
            <img src="<?php echo e(asset('assets/images/logo-light.png')); ?>" alt="site logo" class="dark-logo">
            <img src="<?php echo e(asset('assets/images/logo-icon.png')); ?>" alt="site logo" class="logo-icon">
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">

            
            <?php if($role === 'junior'): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Junior Dashboard</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('calendar.juniorUser')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Junior Cal.</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Database</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('google.sheet.junior')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database Sheet</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Report</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('call.reports.junior')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Timer</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('timer.junior')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            
            <?php if($role === 'senior'): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Senior Dashboard</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('calendar.seniorUser')); ?>"><i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>Senior Cal.</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Database</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('google.sheet.senior')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database Sheet</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Report</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('call.reports.senior')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Timer</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('timer.senior')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Timer Report</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            
            <?php if($role === 'accountant'): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>Accountant Dashboard</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('calendar.seniorUser')); ?>"><i class="ri-circle-fill circle-icon text-info-main w-auto"></i>Accountant Cal.</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Database</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href=""><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database Sheet</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Report</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('call.reports.accountant')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            
            <?php if($role === 'admin'): ?>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                        <span>Dashboard</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('dashboard.index')); ?>"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Admin Dashboard</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('calendar.index')); ?>"><i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>Candidates Cal.</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Database</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('google.sheet.index')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Database Sheet</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Report</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('call.reports.index')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Call Report</a></li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Timer</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="<?php echo e(route('timer.admin')); ?>"><i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>Timer Report</a></li>
                    </ul>
                </li>
            <?php endif; ?>

        </ul>
    </div>
</aside>
<?php /**PATH C:\xampp\htdocs\wowdash\resources\views\components\sidebar.blade.php ENDPATH**/ ?>