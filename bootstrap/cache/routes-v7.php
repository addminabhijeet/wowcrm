<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/_ignition/health-check' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.healthCheck',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/execute-solution' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.executeSolution',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/_ignition/update-config' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ignition.updateConfig',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::r2VSiGK7C25WUbSE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/index' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.admin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.admin.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.admin.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/junior' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/junior/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.junior.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/junior/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.junior.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/senior' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/senior/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/senior/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/trainer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.trainer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/trainer/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.trainer.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/trainer/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.trainer.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/accountant' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.accountant',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/accountant/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.accountant.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/accountant/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.accountant.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/associate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.associate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/associate/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.associate.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/associate/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.associate.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/seniorassociate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorassociate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/seniorassociate/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorassociate.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/seniorassociate/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorassociate.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/operation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.operation',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/operation/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.operation.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/operation/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.operation.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/resource' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.resource',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/resource/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.resource.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/resource/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.resource.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/support' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.support',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/support/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.support.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/support/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.support.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/writer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.writer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/writer/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.writer.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/writer/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.writer.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/customer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.customer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/customer/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.customer.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/customer/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.customer.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/latest-notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.latest.notification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/latest-markallread' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.notifications.markallread',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.notifications',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.admin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.senior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/customer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.customer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/career' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.career',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.accountant',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.trainer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/support' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.support',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/writer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.writer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/resource' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.resource',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/operation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.operation',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/button/status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'button.status',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/start-timer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.start',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/start-timer-hide' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.starthide',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/check-pause-buttons' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.checkPauseButtons',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/check-pause-buttons-senior' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.checkPauseButtonsSenior',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/calendar/events' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.juniorEvents',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.seniorUser',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar/alljuniorlist' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allJuniorlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar/alladminlist' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allAdminlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar/events' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.seniorEvents',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/calendar/update-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.updateStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/calendar/setting' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.setting',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/holiday/save' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.holiday',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/holiday/by-month' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.month',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar/allseniorlist' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allSeniorlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar/allaccountantlist' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allAccountantlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/calendar/alltrainerlist' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allTrainerlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/google-sheet/fetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.adminfetch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/google-sheet/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.adminstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/google-sheet/adminupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'adminupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/google-sheet/adminstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'adminstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.senior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-follow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorfollow',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/career/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.career',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/writer/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.writer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-candm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorcandm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorsearch',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-admincandm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.senioradmincandm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-mod' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniormod',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-tra' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniortra',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-tra-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniortraotp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-tra-follow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniortrafollow',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/junior/transfers-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'junior.transfers.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/junior/rejected-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'junior.rejected.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-modcandm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniormodcandm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-modcandmfollow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniormodcandmfollow',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-paidins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorpaidins',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-paid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorpaid',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-con' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorcon',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet-paid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountantpaid',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet-con' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountantcon',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet-ver' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountantver',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/fetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorfetch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/pdfstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorpdfstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorupdatemod' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorupdatemod',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorupdatecon' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorupdatecon',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorstoremod' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorstoremod',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-tra' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniortra.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-tra-otp' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniortraotp.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-seniortrafollow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniortrafollow.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-seniorupdatemod' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorupdatemod.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-follow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorfollow.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-modcandm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniormodcandm.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-modcandmfollow' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniormodcandmfollow.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorcandm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorcandm.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/seniorsearch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorsearch.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'senior.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/search-paid' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'senior.suggestionspaid',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/career/google-sheet/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'career.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet/searchmod' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'senior.suggestionsmod',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accountant.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'trainer.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'junior.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/juniorrej/google-sheet/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniorrej.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/searchcandm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniorcandm.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/searchtra' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniortra.suggestions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-other' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniorother',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-vm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniorvm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-rej' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniorrej',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-candm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.junior.candm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-tra' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.junior.tra',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-modcandm' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniormodcandm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet-candm-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniorcandmupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/google-sheet-candm-update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'seniorcandmupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/fetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniorfetch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/pdfstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniorpdfstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/juniorstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniorstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/juniorupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniorupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/google-sheet/juniorupdaterejected' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'juniorupdaterejected',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.trainer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet-completed' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.trainercompleted',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet/fetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.trainerfetch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet/pdfstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.trainerpdfstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet/trainerstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'trainerstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/trainer/google-sheet/trainerupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'trainerupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountant',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/fetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountantfetch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/pdfstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountantpdfstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/accountantstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accountantstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/accountantupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accountantupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/writterupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'writterupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/accountantupdatecon' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accountantupdatecon',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/google-sheet/accountantupdatever' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'accountantupdatever',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/check-email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'check.uniqueemail',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/candidate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.accountant',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/candidate/fetch' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.accountantfetch',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/candidate/pdfstore' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.accountantpdfstore',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/juniormonthly/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.juniormonthly',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.senior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/seniormonthly/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.seniormonthly',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/alljuniorlist/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alljuniorlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/preallseniorlist/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.preallseniorlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/allseniorlist/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allseniorlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/neverreached/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.neverreached',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/neverreached/call-reports/export' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.neverreached.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/allaccountantlist/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allaccountantlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/alltrainerlist/call-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alltrainerlist',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/alltrainerlist/call-reports-sender' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.sender',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/smtp/add' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'smtp.add',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/smtp/editall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'smtp.editall',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/target/targetall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.all',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/target/allowedall' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'allowed.all',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/upload-generated-pdfs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'upload.generated.pdfs',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/target/add-ip' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.addip',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/smtp/allupdate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'smtp.addupdate',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/seniortimer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.senior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/senior/allseniortimer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.allsenior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/timer/all-juniors' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.alljuniors',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/juniortimer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/timer/toggle-button-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.toggleButtonStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/timer/toggle-all-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.toggleAllStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/admin/timer-settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.admin',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/timers/work-day' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.updateWorkDay',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/timers/base-time' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timer.updateBaseTime',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/timers/latest-pause-types' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'timers.latestPauseTypes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/pdf/acceptance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.acceptance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/pdf/consultation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.consultation',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/pdf/delivery' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.delivery',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/pdf/payment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.payment',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/pdf/deliveryuk' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.deliveryuk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/accountant/pdf/paymentuk' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.paymentuk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/seniorassociate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.seniorassociate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/seniorassociate/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorassociate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/associate/google-sheet' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.associate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/associate' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'dashboard.associate',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/associate/candidateadd' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'all.associate.add',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/group/senior' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'senior.group',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/group/senior/mail' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'senior.groupmail',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/group/senior/mail/chart' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'senior.groupmailchart',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/dashboard/junior/chat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.junior',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chat/send' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/latest-messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.latestMessages',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/chat/refresh-users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'chat.refreshUsers',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/logins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logins',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout-user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ajax.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login-user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ajax.login',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logincheckStatus-user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'ajax.logincheckStatus',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/registersubmit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/loginsubmit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login-history' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login.history',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/dashboard/(?|a(?|dmin/(?|([^/]++)/edit(*:46)|update/([^/]++)(*:68)|destroy/([^/]++)(*:91)|junior/(?|([^/]++)/edit(*:121)|update/([^/]++)(*:144)|destroy/([^/]++)(*:168))|s(?|enior(?|/(?|([^/]++)/edit(?|(*:209)|group(?|(*:225)|mail(*:237)))|update/([^/]++)(*:262)|destroy/([^/]++)(*:286))|group(?|/(?|update/([^/]++)(*:322)|([^/]++)/remove/([^/]++)(*:354))|mail/(?|update/([^/]++)(*:386)|([^/]++)/remove/([^/]++)(*:418)))|associate/(?|([^/]++)/edit(*:454)|update/([^/]++)(?|(*:480))))|upport/(?|([^/]++)/edit(*:514)|update/([^/]++)(?|(*:540))))|trainer/(?|([^/]++)/edit(*:575)|update/([^/]++)(?|(*:601)))|a(?|ccountant/(?|([^/]++)/edit(*:641)|update/([^/]++)(?|(*:667)))|ssociate/(?|([^/]++)/edit(*:702)|update/([^/]++)(?|(*:728))))|operation/(?|([^/]++)/edit(*:765)|update/([^/]++)(?|(*:791)))|resource/(?|([^/]++)/edit(*:826)|update/([^/]++)(?|(*:852)))|writer/(?|([^/]++)/edit(*:885)|update/([^/]++)(?|(*:911)))|c(?|ustomer/(?|([^/]++)/edit(*:949)|update/([^/]++)(*:972)|destroy/([^/]++)(*:996))|alendar(?|(?:/([^/]++)(?:/([^/]++))?)?(*:1043)|(*:1052)))|google\\-sheet/(?|update/([^/]++)(*:1095)|view\\-resume/([^/]++)(*:1125)|download\\-resume/([^/]++)(*:1159)))|ccountant/(?|ca(?|lendar(?:/([^/]++)(?:/([^/]++))?)?(*:1222)|ndidate/pdfupdate/([^/]++)(*:1257))|google\\-sheet/(?|pdfupdate/([^/]++)(*:1302)|view\\-resume/([^/]++)(*:1332)|download\\-resume/([^/]++)(*:1366)))|ll(?|trainer(?|list/call\\-reports\\-allreport(?|/([^/]++)(*:1433)|\\-pdf/([^/]++)(*:1456))|monthly/call\\-reports/([^/]++)(*:1496)|daily/call\\-reports/([^/]++)(*:1533))|junior(?|monthly/call\\-reports/([^/]++)(*:1582)|daily/call\\-reports/([^/]++)(*:1619)|weekly/call\\-reports/([^/]++)(*:1657))|senior(?|monthly/call\\-reports/([^/]++)(*:1706)|daily/call\\-reports/([^/]++)(*:1743)|weekly/call\\-reports/([^/]++)(*:1781))|accountant(?|monthly/call\\-reports/([^/]++)(*:1834)|daily/call\\-reports/([^/]++)(*:1871)))|ssociate/candidate/(?|services/([^/]++)/([^/]++)(*:1930)|([^/]++)/([^/]++)(*:1956)))|t(?|rainer/(?|calendar(?:/([^/]++)(?:/([^/]++))?)?(*:2017)|google\\-sheet/(?|pdfupdate/([^/]++)(*:2061)|view\\-resume/([^/]++)(*:2091)|download\\-resume/([^/]++)(*:2125)))|arget/(?|edit/([^/]++)(*:2158)|add/([^/]++)(*:2179)|save/([^/]++)(*:2201)|delete/([^/]++)(*:2225)))|s(?|en(?|ior(?|/(?|calendar/all(?|junior(?|/([^/]++)(*:2288)|events/([^/]++)(*:2312))|senior(?|/([^/]++)(*:2340)|events/([^/]++)(*:2364))|accountant(?|/([^/]++)(*:2396)|events/([^/]++)(*:2420))|trainer(?|/([^/]++)(*:2449)|events/([^/]++)(*:2473)))|google\\-sheet/(?|pdfupdate/([^/]++)(*:2519)|view\\-(?|resume/([^/]++)(*:2552)|updateresume/([^/]++)(*:2582)|a(?|cceptance(?|/([^/]++)(*:2616)|sign/([^/]++)(*:2638))|udio/([^/]++)(*:2661))|consultation/([^/]++)(*:2692)|delivery(?|/([^/]++)(*:2721)|sign/([^/]++)(*:2743))|payment(?|/([^/]++)(*:2772)|sign/([^/]++)(*:2794)))|download\\-(?|resume/([^/]++)(*:2833)|updateresume/([^/]++)(*:2863)|a(?|cceptance(?|/([^/]++)(*:2897)|sign/([^/]++)(*:2919))|udio/([^/]++)(*:2942))|consultation/([^/]++)(*:2973)|delivery(?|/([^/]++)(*:3002)|sign/([^/]++)(*:3024))|payment/([^/]++)(*:3050))))|associate/candidate/([^/]++)(*:3090))|d\\-payment\\-mail/([^/]++)(*:3125))|mtp/(?|edit/([^/]++)(*:3155)|update/([^/]++)(*:3179)|test/([^/]++)(*:3201)))|junior/google\\-sheet/(?|pdfupdate/([^/]++)(*:3254)|view\\-resume/([^/]++)(*:3284)|download\\-resume/([^/]++)(*:3318))|preallsenior(?|monthly/call\\-reports/([^/]++)(*:3373)|daily/call\\-reports/([^/]++)(*:3410)|weekly/call\\-reports/([^/]++)(*:3448)))|/candidate/([^/]++)/(?|save\\-(?|followups(*:3500)|p(?|rofile(*:3519)|ayment(*:3534))|edu(*:3547))|autosave(*:3565))|/t(?|arget/delete\\-ip/([^/]++)(*:3605)|emplate/([^/]++)/edit(*:3635)|raining/([^/]++)/trastatus(*:3670))|/email\\-template/([^/]++)(*:3705)|/resumes/(?|upload/([^/]++)(*:3741)|([^/]++)/status(*:3765))|/payment/([^/]++)/status(*:3799))/?$}sDu',
    ),
    3 => 
    array (
      46 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.admin.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      68 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.admin.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      91 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.admin.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      121 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.junior.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      144 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.junior.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      168 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.junior.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      209 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      225 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.editgroup',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      237 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.editgroupmail',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      262 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      286 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.senior.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      322 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorgroup.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      354 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorgroup.remove',
          ),
          1 => 
          array (
            0 => 'senior',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      386 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorgroupmail.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      418 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorgroupmail.remove',
          ),
          1 => 
          array (
            0 => 'senior',
            1 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      454 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorassociate.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      480 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorassociate.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.seniorassociate.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      514 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.support.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      540 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.support.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.support.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      575 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.trainer.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      601 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.trainer.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.trainer.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      641 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.accountant.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      667 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.accountant.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.accountant.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      702 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.associate.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      728 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.associate.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.associate.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      765 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.operation.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      791 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.operation.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.operation.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      826 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.resource.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      852 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.resource.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.resource.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      885 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.writer.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      911 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.writer.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'users.writer.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      949 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.customer.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      972 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.customer.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      996 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'users.customer.destroy',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1043 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.index',
            'month' => NULL,
            'year' => NULL,
          ),
          1 => 
          array (
            0 => 'month',
            1 => 'year',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1052 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.adminUser',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1095 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.adminupdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1125 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.admin.resume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1159 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.admin.resume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1222 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.accountantUser',
            'month' => NULL,
            'year' => NULL,
          ),
          1 => 
          array (
            0 => 'month',
            1 => 'year',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1257 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.accountantpdfupdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1302 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.accountantpdfupdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1332 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'viewaccountantResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1366 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadaccountantResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1433 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allreport',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allreport.pdf',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1496 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alltrainermonthly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1533 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alltrainerdaily',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1582 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alljuniormonthly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1619 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alljuniordaily',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1657 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.alljuniorweekly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1706 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allseniormonthly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1743 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allseniordaily',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1781 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allseniorweekly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1834 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allaccountantmonthly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1871 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.allaccountantdaily',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1930 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'all.associate.services',
          ),
          1 => 
          array (
            0 => 'userId',
            1 => 'forwardedBy',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1956 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'all.associate.candidate',
          ),
          1 => 
          array (
            0 => 'userId',
            1 => 'forwardedBy',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2017 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.trainerUser',
            'month' => NULL,
            'year' => NULL,
          ),
          1 => 
          array (
            0 => 'month',
            1 => 'year',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2061 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.trainerpdfupdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2091 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'viewtrainerResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2125 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadtrainerResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2158 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2179 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.add',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2201 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.save',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2225 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.delete',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2288 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.alljuniorUser',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2312 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allJuniorEvents',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2340 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allseniorUser',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2364 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allSeniorEvents',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2396 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allaccountantUser',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2420 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allAccountantEvents',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2449 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.alltrainerUser',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2473 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'calendar.allTrainerEvents',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2519 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.seniorpdfupdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2552 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'viewseniorResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2582 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.updateresume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2616 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.acceptance',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2638 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.acceptancesign',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2661 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.audio',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2692 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.consultation',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2721 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.delivery',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2743 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.deliverysign',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2772 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.payment',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2794 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'view.paymentsign',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2833 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadseniorResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2863 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.updateresume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2897 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.acceptance',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2919 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.acceptancesign',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2942 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.audio',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2973 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.consultation',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3002 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.delivery',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3024 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.deliverysign',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3050 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'download.payment',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3090 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'all.seniorassociate.candidate',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3125 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'send.payment.mail',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3155 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'smtp.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3179 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'smtp.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3201 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'smtp.test',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3254 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'google.sheet.juniorpdfupdate',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3284 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'viewjuniorResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3318 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'downloadjuniorResume',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3373 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.preallseniormonthly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3410 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.preallseniordaily',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3448 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'call.reports.preallseniorweekly',
          ),
          1 => 
          array (
            0 => 'userId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.saveFollowups',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3519 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.saveProfile',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3534 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.savePayment',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3547 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.saveEdu',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3565 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'candidate.autoSave',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3605 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'target.deleteip',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3635 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'template.edit',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3670 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'training.updateStatus',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3705 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'template.update',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3741 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'resumes.upload',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3765 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'resumes.updateStatus',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3799 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'payment.updateStatus',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'ignition.healthCheck' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '_ignition/health-check',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\HealthCheckController',
        'as' => 'ignition.healthCheck',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.executeSolution' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/execute-solution',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\ExecuteSolutionController',
        'as' => 'ignition.executeSolution',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ignition.updateConfig' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => '_ignition/update-config',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'Spatie\\LaravelIgnition\\Http\\Middleware\\RunnableSolutionsEnabled',
        ),
        'uses' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController@__invoke',
        'controller' => 'Spatie\\LaravelIgnition\\Http\\Controllers\\UpdateConfigController',
        'as' => 'ignition.updateConfig',
        'namespace' => NULL,
        'prefix' => '_ignition',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::r2VSiGK7C25WUbSE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:844:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'C:\\\\xampp\\\\htdocs\\\\wowcrm\\\\vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Foundation\\\\Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"00000000000004c70000000000000000";}}',
        'as' => 'generated::r2VSiGK7C25WUbSE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.admin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/index',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@index',
        'controller' => 'App\\Http\\Controllers\\UserController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.admin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.admin.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@admincreate',
        'controller' => 'App\\Http\\Controllers\\UserController@admincreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.admin.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.admin.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@adminstore',
        'controller' => 'App\\Http\\Controllers\\UserController@adminstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.admin.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.admin.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@adminedit',
        'controller' => 'App\\Http\\Controllers\\UserController@adminedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.admin.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.admin.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@adminupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@adminupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.admin.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.admin.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@admindestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@admindestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.admin.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/junior',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@junior',
        'controller' => 'App\\Http\\Controllers\\UserController@junior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.junior.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/junior/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@juniorcreate',
        'controller' => 'App\\Http\\Controllers\\UserController@juniorcreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.junior.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.junior.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/junior/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@juniorstore',
        'controller' => 'App\\Http\\Controllers\\UserController@juniorstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.junior.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.junior.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/junior/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@junioredit',
        'controller' => 'App\\Http\\Controllers\\UserController@junioredit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.junior.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.junior.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/junior/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@juniorupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@juniorupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.junior.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.junior.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/junior/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@juniordestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@juniordestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.junior.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/senior',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@senior',
        'controller' => 'App\\Http\\Controllers\\UserController@senior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/senior/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorcreate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorcreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/senior/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorstore',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/senior/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@senioredit',
        'controller' => 'App\\Http\\Controllers\\UserController@senioredit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.editgroup' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/senior/{id}/editgroup',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@senioreditgroup',
        'controller' => 'App\\Http\\Controllers\\UserController@senioreditgroup',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.editgroup',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.editgroupmail' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/senior/{id}/editgroupmail',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@senioreditgroupmail',
        'controller' => 'App\\Http\\Controllers\\UserController@senioreditgroupmail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.editgroupmail',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/senior/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorgroup.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/seniorgroup/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorgroupupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorgroupupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorgroup.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorgroupmail.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/seniorgroupmail/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorgroupmailupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorgroupmailupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorgroupmail.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorgroup.remove' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'dashboard/admin/seniorgroup/{senior}/remove/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorgroupremove',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorgroupremove',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorgroup.remove',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorgroupmail.remove' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'dashboard/admin/seniorgroupmail/{senior}/remove/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorgroupmailremove',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorgroupmailremove',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorgroupmail.remove',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.senior.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/senior/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniordestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@seniordestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.senior.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.trainer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/trainer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@trainer',
        'controller' => 'App\\Http\\Controllers\\UserController@trainer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.trainer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.trainer.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/trainer/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@trainercreate',
        'controller' => 'App\\Http\\Controllers\\UserController@trainercreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.trainer.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.trainer.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/trainer/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@trainerstore',
        'controller' => 'App\\Http\\Controllers\\UserController@trainerstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.trainer.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.trainer.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/trainer/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@traineredit',
        'controller' => 'App\\Http\\Controllers\\UserController@traineredit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.trainer.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.trainer.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/trainer/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@trainerupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@trainerupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.trainer.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.trainer.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/trainer/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@trainerdestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@trainerdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.trainer.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.accountant' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/accountant',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@accountant',
        'controller' => 'App\\Http\\Controllers\\UserController@accountant',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.accountant',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.accountant.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/accountant/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@accountantcreate',
        'controller' => 'App\\Http\\Controllers\\UserController@accountantcreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.accountant.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.accountant.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/accountant/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@accountantstore',
        'controller' => 'App\\Http\\Controllers\\UserController@accountantstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.accountant.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.accountant.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/accountant/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@accountantedit',
        'controller' => 'App\\Http\\Controllers\\UserController@accountantedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.accountant.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.accountant.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/accountant/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@accountantupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@accountantupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.accountant.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.accountant.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/accountant/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@accountantdestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@accountantdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.accountant.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.associate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/associate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@associate',
        'controller' => 'App\\Http\\Controllers\\UserController@associate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.associate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.associate.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/associate/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@associatecreate',
        'controller' => 'App\\Http\\Controllers\\UserController@associatecreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.associate.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.associate.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/associate/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@associatestore',
        'controller' => 'App\\Http\\Controllers\\UserController@associatestore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.associate.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.associate.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/associate/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@associateedit',
        'controller' => 'App\\Http\\Controllers\\UserController@associateedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.associate.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.associate.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/associate/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@associateupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@associateupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.associate.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.associate.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/associate/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@associatedestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@associatedestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.associate.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorassociate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/seniorassociate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorassociate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorassociate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorassociate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorassociate.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/seniorassociate/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorassociatecreate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorassociatecreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorassociate.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorassociate.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/seniorassociate/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorassociatestore',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorassociatestore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorassociate.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorassociate.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/seniorassociate/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorassociateedit',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorassociateedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorassociate.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorassociate.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/seniorassociate/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorassociateupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorassociateupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorassociate.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.seniorassociate.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/seniorassociate/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorassociatedestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorassociatedestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.seniorassociate.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.operation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/operation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@operation',
        'controller' => 'App\\Http\\Controllers\\UserController@operation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.operation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.operation.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/operation/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@operationcreate',
        'controller' => 'App\\Http\\Controllers\\UserController@operationcreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.operation.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.operation.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/operation/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@operationstore',
        'controller' => 'App\\Http\\Controllers\\UserController@operationstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.operation.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.operation.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/operation/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@operationedit',
        'controller' => 'App\\Http\\Controllers\\UserController@operationedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.operation.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.operation.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/operation/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@operationupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@operationupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.operation.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.operation.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/operation/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@operationdestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@operationdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.operation.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.resource' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/resource',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resource',
        'controller' => 'App\\Http\\Controllers\\UserController@resource',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.resource',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.resource.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/resource/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resourcecreate',
        'controller' => 'App\\Http\\Controllers\\UserController@resourcecreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.resource.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.resource.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/resource/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resourcestore',
        'controller' => 'App\\Http\\Controllers\\UserController@resourcestore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.resource.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.resource.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/resource/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resourceedit',
        'controller' => 'App\\Http\\Controllers\\UserController@resourceedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.resource.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.resource.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/resource/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resourceupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@resourceupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.resource.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.resource.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/resource/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@resourcedestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@resourcedestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.resource.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.support' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/support',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@support',
        'controller' => 'App\\Http\\Controllers\\UserController@support',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.support',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.support.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/support/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@supportcreate',
        'controller' => 'App\\Http\\Controllers\\UserController@supportcreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.support.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.support.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/support/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@supportstore',
        'controller' => 'App\\Http\\Controllers\\UserController@supportstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.support.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.support.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/support/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@supportedit',
        'controller' => 'App\\Http\\Controllers\\UserController@supportedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.support.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.support.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/support/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@supportupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@supportupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.support.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.support.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/support/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@supportdestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@supportdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.support.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.writer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/writer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@writer',
        'controller' => 'App\\Http\\Controllers\\UserController@writer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.writer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.writer.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/writer/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@writtercreate',
        'controller' => 'App\\Http\\Controllers\\UserController@writtercreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.writer.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.writer.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/writer/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@writterstore',
        'controller' => 'App\\Http\\Controllers\\UserController@writterstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.writer.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.writer.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/writer/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@writteredit',
        'controller' => 'App\\Http\\Controllers\\UserController@writteredit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.writer.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.writer.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/writer/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@writterupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@writterupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.writer.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.writer.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/writer/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@writterdestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@writterdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.writer.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.customer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/customer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@customer',
        'controller' => 'App\\Http\\Controllers\\UserController@customer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.customer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.customer.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/customer/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@customercreate',
        'controller' => 'App\\Http\\Controllers\\UserController@customercreate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.customer.create',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.customer.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/customer/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@customerstore',
        'controller' => 'App\\Http\\Controllers\\UserController@customerstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.customer.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.customer.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/customer/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@customeredit',
        'controller' => 'App\\Http\\Controllers\\UserController@customeredit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.customer.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.customer.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/customer/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@customerupdate',
        'controller' => 'App\\Http\\Controllers\\UserController@customerupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.customer.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'users.customer.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/customer/destroy/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@customerdestroy',
        'controller' => 'App\\Http\\Controllers\\UserController@customerdestroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'users.customer.destroy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.latest.notification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/latest-notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@latestNotification',
        'controller' => 'App\\Http\\Controllers\\DashboardController@latestNotification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.latest.notification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.notifications.markallread' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/admin/latest-markallread',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@markAllRead',
        'controller' => 'App\\Http\\Controllers\\DashboardController@markAllRead',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.notifications.markallread',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.notifications' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@adminnotification',
        'controller' => 'App\\Http\\Controllers\\DashboardController@adminnotification',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'admin.notifications',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.admin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@index',
        'controller' => 'App\\Http\\Controllers\\DashboardController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.admin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@junior',
        'controller' => 'App\\Http\\Controllers\\DashboardController@junior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.senior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@senior',
        'controller' => 'App\\Http\\Controllers\\DashboardController@senior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.senior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.customer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/customer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@customer',
        'controller' => 'App\\Http\\Controllers\\DashboardController@customer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.customer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.career' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/career',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@career',
        'controller' => 'App\\Http\\Controllers\\DashboardController@career',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.career',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.accountant' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@accountant',
        'controller' => 'App\\Http\\Controllers\\DashboardController@accountant',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.accountant',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.trainer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@trainer',
        'controller' => 'App\\Http\\Controllers\\DashboardController@trainer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.trainer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.support' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/support',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@support',
        'controller' => 'App\\Http\\Controllers\\DashboardController@support',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.support',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.writer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/writer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@writer',
        'controller' => 'App\\Http\\Controllers\\DashboardController@writer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.writer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.resource' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/resource',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@resource',
        'controller' => 'App\\Http\\Controllers\\DashboardController@resource',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.resource',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.operation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/operation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@operation',
        'controller' => 'App\\Http\\Controllers\\DashboardController@operation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.operation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'button.status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'button/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:79:"function () {
        return \\response()->json([\'button_status\' => 0]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005270000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'button.status',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.start' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/start-timer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:77:"function () {
        return \\response()->json([\'success\' => false]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005290000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.start',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.starthide' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/start-timer-hide',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:75:"function () {
        return \\response()->json([\'exists\' => true]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000052b0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.starthide',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.checkPauseButtons' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/check-pause-buttons',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:79:"function () {
        return \\response()->json([\'pause_type\' => null]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000052d0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.checkPauseButtons',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.checkPauseButtonsSenior' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/check-pause-buttons-senior',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:79:"function () {
        return \\response()->json([\'pause_type\' => null]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000052f0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.checkPauseButtonsSenior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/calendar/{month?}/{year?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@index',
        'controller' => 'App\\Http\\Controllers\\CalendarController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.accountantUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/calendar/{month?}/{year?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@accountantUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@accountantUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.accountantUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.trainerUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer/calendar/{month?}/{year?}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@trainerUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@trainerUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.trainerUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@juniorUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@juniorUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.juniorEvents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/calendar/events',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@juniorEvents',
        'controller' => 'App\\Http\\Controllers\\CalendarController@juniorEvents',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.juniorEvents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.seniorUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@seniorUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@seniorUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.seniorUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allJuniorlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alljuniorlist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allJuniorlist',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allJuniorlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allJuniorlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allAdminlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alladminlist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allAdminlist',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allAdminlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allAdminlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.alljuniorUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alljunior/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@alljuniorUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@alljuniorUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.alljuniorUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allseniorUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/allsenior/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allseniorUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allseniorUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allseniorUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allaccountantUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/allaccountant/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allaccountantUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allaccountantUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allaccountantUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.alltrainerUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alltrainer/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@alltrainerUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@alltrainerUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.alltrainerUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allJuniorEvents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alljuniorevents/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@getallJuniorEvents',
        'controller' => 'App\\Http\\Controllers\\CalendarController@getallJuniorEvents',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allJuniorEvents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allSeniorEvents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/allseniorevents/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@getallSeniorEvents',
        'controller' => 'App\\Http\\Controllers\\CalendarController@getallSeniorEvents',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allSeniorEvents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allAccountantEvents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/allaccountantevents/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@getallAccountantEvents',
        'controller' => 'App\\Http\\Controllers\\CalendarController@getallAccountantEvents',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allAccountantEvents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allTrainerEvents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alltrainerevents/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@getallTrainerEvents',
        'controller' => 'App\\Http\\Controllers\\CalendarController@getallTrainerEvents',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allTrainerEvents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.seniorEvents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/events',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@SeniorEvents',
        'controller' => 'App\\Http\\Controllers\\CalendarController@SeniorEvents',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.seniorEvents',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/calendar/update-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\CalendarController@updateStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.setting' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/calendar/setting',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@setting',
        'controller' => 'App\\Http\\Controllers\\CalendarController@setting',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.setting',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.holiday' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'holiday/save',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@saveHoliday',
        'controller' => 'App\\Http\\Controllers\\CalendarController@saveHoliday',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.holiday',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.month' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'holiday/by-month',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@getHolidaysByMonth',
        'controller' => 'App\\Http\\Controllers\\CalendarController@getHolidaysByMonth',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.month',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.adminUser' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@adminUser',
        'controller' => 'App\\Http\\Controllers\\CalendarController@adminUser',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.adminUser',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allSeniorlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/allseniorlist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allSeniorlist',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allSeniorlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allSeniorlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allAccountantlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/allaccountantlist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allAccountantlist',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allAccountantlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allAccountantlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'calendar.allTrainerlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/calendar/alltrainerlist',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CalendarController@allTrainerlist',
        'controller' => 'App\\Http\\Controllers\\CalendarController@allTrainerlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'calendar.allTrainerlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@admin',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@admin',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.adminfetch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/google-sheet/fetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@adminfetch',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@adminfetch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.adminfetch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.adminupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'dashboard/admin/google-sheet/update/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@adminupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@adminupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.adminupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.adminstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/google-sheet/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@adminstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@adminstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.adminstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'adminupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/google-sheet/adminupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@adminupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@adminupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'adminupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'adminstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/admin/google-sheet/adminstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@adminstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@adminstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'adminstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.admin.resume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/google-sheet/view-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewadminResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewadminResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.admin.resume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.admin.resume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/google-sheet/download-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadadminResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadadminResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.admin.resume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.senior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@senior',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@senior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.senior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorfollow' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-follow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorfollow',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorfollow',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorfollow',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.career' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/career/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@career',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@career',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.career',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.writer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/writer/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@writer',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@writer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.writer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorcandm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-candm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcandm',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcandm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorcandm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorsearch' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorsearch',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorsearch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorsearch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.senioradmincandm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-admincandm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@senioradmincandm',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@senioradmincandm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.senioradmincandm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniormod' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-mod',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniormod',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniormod',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniormod',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniortra' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-tra',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniortra',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniortra',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniortra',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniortraotp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-tra-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniortraotp',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniortraotp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniortraotp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniortrafollow' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-tra-follow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniortrafollow',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniortrafollow',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniortrafollow',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'junior.transfers.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'junior/transfers-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdatetra',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdatetra',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'junior.transfers.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'junior.rejected.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'junior/rejected-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdaterej',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdaterej',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'junior.rejected.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniormodcandm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-modcandm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandm',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniormodcandm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniormodcandmfollow' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-modcandmfollow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandmfollow',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandmfollow',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniormodcandmfollow',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorpaidins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-paidins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpaidins',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpaidins',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorpaidins',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorpaid' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-paid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpaid',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpaid',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorpaid',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorcon' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet-con',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcon',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcon',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorcon',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountantpaid' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet-paid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantpaid',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantpaid',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountantpaid',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountantcon' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet-con',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantcon',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantcon',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountantcon',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountantver' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet-ver',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantver',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantver',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountantver',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorfetch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/fetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorfetch',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorfetch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorfetch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorpdfupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'dashboard/senior/google-sheet/pdfupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpdfupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpdfupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorpdfupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorpdfstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/pdfstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpdfstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpdfstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorpdfstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorupdatemod' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorupdatemod',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdatemod',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdatemod',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorupdatemod',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorupdatecon' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorupdatecon',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdatecon',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdatecon',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorupdatecon',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorstoremod' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorstoremod',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorstoremod',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorstoremod',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorstoremod',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'viewseniorResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'viewseniorResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadseniorResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'downloadseniorResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.updateresume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-updateresume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorUpdateResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorUpdateResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.updateresume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.updateresume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-updateresume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorUpdateResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorUpdateResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.updateresume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.acceptance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-acceptance/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorAcceptance',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorAcceptance',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.acceptance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.acceptancesign' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-acceptancesign/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorAcceptanceSign',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorAcceptanceSign',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.acceptancesign',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.acceptance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-acceptance/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorAcceptance',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorAcceptance',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.acceptance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.acceptancesign' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-acceptancesign/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorAcceptanceSign',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorAcceptanceSign',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.acceptancesign',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.consultation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-consultation/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorConsultation',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorConsultation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.consultation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.consultation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-consultation/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorconsultation',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorconsultation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.consultation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.delivery' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-delivery/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorDelivery',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorDelivery',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.delivery',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.delivery' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-delivery/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorDelivery',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorDelivery',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.delivery',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.deliverysign' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-deliverysign/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorDeliverySign',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorDeliverySign',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.deliverysign',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.deliverysign' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-deliverysign/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorDeliverySign',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorDeliverySign',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.deliverysign',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.payment' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-payment/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorPayment',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorPayment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.payment',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.paymentsign' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-paymentsign/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorPaymentSign',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorPaymentSign',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.paymentsign',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.payment' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-payment/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorPayment',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorPayment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.payment',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'view.audio' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/view-audio/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorAudio',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewseniorAudio',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'view.audio',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'download.audio' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/download-audio/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorAudio',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadseniorAudio',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'download.audio',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniortra.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-tra',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniortraSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniortraSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniortra.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniortraotp.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-tra-otp',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniortraotpSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniortraotpSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniortraotp.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniortrafollow.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-seniortrafollow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniortrafollowSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniortrafollowSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniortrafollow.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorupdatemod.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-seniorupdatemod',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdatemodSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorupdatemodSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorupdatemod.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorfollow.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-follow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorfollowSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorfollowSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorfollow.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniormodcandm.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-modcandm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandmSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandmSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniormodcandm.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniormodcandmfollow.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-modcandmfollow',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandmfollowSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniormodcandmfollowSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniormodcandmfollow.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorcandm.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorcandm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcandmSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcandmSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorcandm.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorsearch.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/seniorsearch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorsearchSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorsearchSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorsearch.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'senior.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'senior.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'senior.suggestionspaid' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/search-paid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpaidSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorpaidSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'senior.suggestionspaid',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'career.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/career/google-sheet/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@careerSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@careerSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'career.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'senior.suggestionsmod' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/google-sheet/searchmod',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorSuggestionsmod',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorSuggestionsmod',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'senior.suggestionsmod',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accountant.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'accountant.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'trainer.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer/google-sheet/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainerSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainerSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'trainer.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'junior.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'junior.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniorrej.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/juniorrej/google-sheet/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorrejSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorrejSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniorrej.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniorcandm.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet/searchcandm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorcandmSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorcandmSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniorcandm.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniortra.suggestions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet/searchtra',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniortraSuggestions',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniortraSuggestions',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniortra.suggestions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@junior',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@junior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniorother' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet-other',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorother',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorother',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniorother',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniorvm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet-vm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorvm',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorvm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniorvm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniorrej' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet-rej',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorrej',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorrej',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniorrej',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.junior.candm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet-candm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorcandm',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorcandm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.junior.candm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.junior.tra' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet-tra',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniortra',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniortra',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.junior.tra',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniormodcandm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet-modcandm',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniormodcandm',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniormodcandm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniormodcandm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniorcandmupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/junior/google-sheet-candm-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorcandmupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorcandmupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniorcandmupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'seniorcandmupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/senior/google-sheet-candm-update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcandmupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorcandmupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'seniorcandmupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniorfetch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/junior/google-sheet/fetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorfetch',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorfetch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniorfetch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniorpdfupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'dashboard/junior/google-sheet/pdfupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorpdfupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorpdfupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniorpdfupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.juniorpdfstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/junior/google-sheet/pdfstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorpdfstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorpdfstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.juniorpdfstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniorstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/junior/google-sheet/juniorstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniorstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniorupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/junior/google-sheet/juniorupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniorupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'juniorupdaterejected' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/junior/google-sheet/juniorupdaterejected',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdaterejected',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@juniorupdaterejected',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'juniorupdaterejected',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'viewjuniorResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet/view-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewjuniorResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewjuniorResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'viewjuniorResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadjuniorResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/google-sheet/download-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadjuniorResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadjuniorResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'downloadjuniorResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.trainer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainer',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainer',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.trainer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.trainercompleted' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer/google-sheet-completed',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainercompleted',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainercompleted',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.trainercompleted',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.trainerfetch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/trainer/google-sheet/fetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainerfetch',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainerfetch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.trainerfetch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.trainerpdfupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'dashboard/trainer/google-sheet/pdfupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainerpdfupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainerpdfupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.trainerpdfupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.trainerpdfstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/trainer/google-sheet/pdfstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainerpdfstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainerpdfstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.trainerpdfstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'trainerstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/trainer/google-sheet/trainerstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainertore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainertore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'trainerstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'trainerupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/trainer/google-sheet/trainerupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@trainerupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@trainerupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'trainerupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'viewtrainerResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer/google-sheet/view-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewtrainerResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewtrainerResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'viewtrainerResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadtrainerResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/trainer/google-sheet/download-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadtrainerResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadtrainerResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'downloadtrainerResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountant' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountant',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountant',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountant',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountantfetch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/fetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantfetch',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantfetch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountantfetch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountantpdfupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'dashboard/accountant/google-sheet/pdfupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantpdfupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantpdfupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountantpdfupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.accountantpdfstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/pdfstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantpdfstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantpdfstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.accountantpdfstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accountantstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/accountantstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantstore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'accountantstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accountantupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/accountantupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'accountantupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'writterupdate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/writterupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@writterupdate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@writterupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'writterupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accountantupdatecon' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/accountantupdatecon',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantupdatecon',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantupdatecon',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'accountantupdatecon',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'accountantupdatever' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/google-sheet/accountantupdatever',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@accountantupdatever',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@accountantupdatever',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'accountantupdatever',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'viewaccountantResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet/view-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@viewaccountantResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@viewaccountantResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'viewaccountantResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'downloadaccountantResume' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/google-sheet/download-resume/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@downloadaccountantResume',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@downloadaccountantResume',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'downloadaccountantResume',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'check.uniqueemail' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/check-email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@checkEmail',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@checkEmail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'check.uniqueemail',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.accountant' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/candidate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateController@accountant',
        'controller' => 'App\\Http\\Controllers\\CandidateController@accountant',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.accountant',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.accountantfetch' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/candidate/fetch',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateController@accountantfetch',
        'controller' => 'App\\Http\\Controllers\\CandidateController@accountantfetch',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.accountantfetch',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.accountantpdfupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'dashboard/accountant/candidate/pdfupdate/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateController@accountantpdfupdate',
        'controller' => 'App\\Http\\Controllers\\CandidateController@accountantpdfupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.accountantpdfupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.accountantpdfstore' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/accountant/candidate/pdfstore',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateController@accountantpdfstore',
        'controller' => 'App\\Http\\Controllers\\CandidateController@accountantpdfstore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.accountantpdfstore',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.saveFollowups' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'candidate/{id}/save-followups',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@saveFollowups',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@saveFollowups',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.saveFollowups',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.saveProfile' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'candidate/{id}/save-profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@saveProfile',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@saveProfile',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.saveProfile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.autoSave' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'candidate/{id}/autosave',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@autoSave',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@autoSave',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.autoSave',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.saveEdu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'candidate/{id}/save-edu',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@saveEdu',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@saveEdu',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.saveEdu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'candidate.savePayment' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'candidate/{id}/save-payment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@savePayment',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@savePayment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'candidate.savePayment',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@index',
        'controller' => 'App\\Http\\Controllers\\CallReportController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@junior',
        'controller' => 'App\\Http\\Controllers\\CallReportController@junior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.juniormonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/juniormonthly/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@juniormonthly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@juniormonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.juniormonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.senior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@senior',
        'controller' => 'App\\Http\\Controllers\\CallReportController@senior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.senior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.seniormonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/seniormonthly/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@seniormonthly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@seniormonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.seniormonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alljuniorlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alljuniorlist/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alljuniorlist',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alljuniorlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alljuniorlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.preallseniorlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/preallseniorlist/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@preallseniorlist',
        'controller' => 'App\\Http\\Controllers\\CallReportController@preallseniorlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.preallseniorlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allseniorlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allseniorlist/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allseniorlist',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allseniorlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allseniorlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.neverreached' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/neverreached/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@neverreached',
        'controller' => 'App\\Http\\Controllers\\CallReportController@neverreached',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.neverreached',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.neverreached.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/neverreached/call-reports/export',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@neverreachedExport',
        'controller' => 'App\\Http\\Controllers\\CallReportController@neverreachedExport',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.neverreached.export',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allaccountantlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allaccountantlist/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allaccountantlist',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allaccountantlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allaccountantlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alltrainerlist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alltrainerlist/call-reports',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alltrainerlist',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alltrainerlist',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alltrainerlist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.sender' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alltrainerlist/call-reports-sender',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@reportsender',
        'controller' => 'App\\Http\\Controllers\\CallReportController@reportsender',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.sender',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allreport' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alltrainerlist/call-reports-allreport/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allreport',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allreport',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allreport',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allreport.pdf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/alltrainerlist/call-reports-allreport-pdf/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allreportPdf',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allreportPdf',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allreport.pdf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alljuniormonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alljuniormonthly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alljuniormonthly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alljuniormonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alljuniormonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allseniormonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allseniormonthly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allseniormonthly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allseniormonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allseniormonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.preallseniormonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/preallseniormonthly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PreCallReportController@preallseniormonthly',
        'controller' => 'App\\Http\\Controllers\\PreCallReportController@preallseniormonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.preallseniormonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alltrainermonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alltrainermonthly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alltrainermonthly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alltrainermonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alltrainermonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allaccountantmonthly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allaccountantmonthly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allaccountantmonthly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allaccountantmonthly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allaccountantmonthly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alljuniordaily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alljuniordaily/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alljuniordaily',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alljuniordaily',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alljuniordaily',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alljuniorweekly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alljuniorweekly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alljuniorweekly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alljuniorweekly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alljuniorweekly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allaccountantdaily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allaccountantdaily/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allaccountantdaily',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allaccountantdaily',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allaccountantdaily',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.alltrainerdaily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/alltrainerdaily/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@alltrainerdaily',
        'controller' => 'App\\Http\\Controllers\\CallReportController@alltrainerdaily',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.alltrainerdaily',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allseniordaily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allseniordaily/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allseniordaily',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allseniordaily',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allseniordaily',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.preallseniordaily' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/preallseniordaily/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PreCallReportController@preallseniordaily',
        'controller' => 'App\\Http\\Controllers\\PreCallReportController@preallseniordaily',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.preallseniordaily',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.allseniorweekly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/allseniorweekly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@allseniorweekly',
        'controller' => 'App\\Http\\Controllers\\CallReportController@allseniorweekly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.allseniorweekly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'call.reports.preallseniorweekly' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/preallseniorweekly/call-reports/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PreCallReportController@preallseniorweekly',
        'controller' => 'App\\Http\\Controllers\\PreCallReportController@preallseniorweekly',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'call.reports.preallseniorweekly',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'smtp.add' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/smtp/add',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@add',
        'controller' => 'App\\Http\\Controllers\\DashboardController@add',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'smtp.add',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'smtp.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/smtp/edit/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@edit',
        'controller' => 'App\\Http\\Controllers\\DashboardController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'smtp.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'smtp.editall' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/smtp/editall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@editall',
        'controller' => 'App\\Http\\Controllers\\DashboardController@editall',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'smtp.editall',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/target/edit/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@targetedit',
        'controller' => 'App\\Http\\Controllers\\DashboardController@targetedit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.add' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/target/add/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@targetadd',
        'controller' => 'App\\Http\\Controllers\\DashboardController@targetadd',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.add',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.save' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/target/save/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@targetSave',
        'controller' => 'App\\Http\\Controllers\\DashboardController@targetSave',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.save',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.delete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/target/delete/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@targetDelete',
        'controller' => 'App\\Http\\Controllers\\DashboardController@targetDelete',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.delete',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.all' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/target/targetall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@targetall',
        'controller' => 'App\\Http\\Controllers\\DashboardController@targetall',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.all',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'allowed.all' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/target/allowedall',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@allowedall',
        'controller' => 'App\\Http\\Controllers\\DashboardController@allowedall',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'allowed.all',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'upload.generated.pdfs' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/upload-generated-pdfs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@uploadGeneratedPdfs',
        'controller' => 'App\\Http\\Controllers\\DashboardController@uploadGeneratedPdfs',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'upload.generated.pdfs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.addip' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'target/add-ip',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@addIp',
        'controller' => 'App\\Http\\Controllers\\DashboardController@addIp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.addip',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'target.deleteip' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'target/delete-ip/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@deleteIp',
        'controller' => 'App\\Http\\Controllers\\DashboardController@deleteIp',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'target.deleteip',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'smtp.addupdate' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/smtp/allupdate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@addupdate',
        'controller' => 'App\\Http\\Controllers\\DashboardController@addupdate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'smtp.addupdate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'smtp.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'dashboard/smtp/update/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@update',
        'controller' => 'App\\Http\\Controllers\\DashboardController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'smtp.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'smtp.test' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/smtp/test/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@test',
        'controller' => 'App\\Http\\Controllers\\DashboardController@test',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'smtp.test',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'send.payment.mail' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/send-payment-mail/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@sendPaymentMail',
        'controller' => 'App\\Http\\Controllers\\DashboardController@sendPaymentMail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'send.payment.mail',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.senior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/seniortimer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\TimerController@seniorTimers',
        'controller' => 'App\\Http\\Controllers\\TimerController@seniorTimers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.senior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.allsenior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/senior/allseniortimer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\TimerController@allseniorTimers',
        'controller' => 'App\\Http\\Controllers\\TimerController@allseniorTimers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.allsenior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.alljuniors' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'timer/all-juniors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:59:"function () {
        return \\response()->json([]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005f20000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.alljuniors',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/juniortimer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\TimerController@juniorTimers',
        'controller' => 'App\\Http\\Controllers\\TimerController@juniorTimers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.toggleButtonStatus' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'timer/toggle-button-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:111:"function () {
        return \\response()->json([\'success\' => false, \'message\' => \'Timer is disabled\']);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005f50000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.toggleButtonStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.toggleAllStatus' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'timer/toggle-all-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:111:"function () {
        return \\response()->json([\'success\' => false, \'message\' => \'Timer is disabled\']);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005f70000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.toggleAllStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.admin' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/admin/timer-settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\TimerController@index',
        'controller' => 'App\\Http\\Controllers\\TimerController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.admin',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.updateWorkDay' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'timers/work-day',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\TimerController@updateWorkDay',
        'controller' => 'App\\Http\\Controllers\\TimerController@updateWorkDay',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.updateWorkDay',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timer.updateBaseTime' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'timers/base-time',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\TimerController@updateBaseTime',
        'controller' => 'App\\Http\\Controllers\\TimerController@updateBaseTime',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timer.updateBaseTime',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'timers.latestPauseTypes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'timers/latest-pause-types',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:59:"function () {
        return \\response()->json([]);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000005fc0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'timers.latestPauseTypes',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.acceptance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/pdf/acceptance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfController@acceptance',
        'controller' => 'App\\Http\\Controllers\\PdfController@acceptance',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pdf.acceptance',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.consultation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/pdf/consultation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfController@consultation',
        'controller' => 'App\\Http\\Controllers\\PdfController@consultation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pdf.consultation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.delivery' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/pdf/delivery',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfController@delivery',
        'controller' => 'App\\Http\\Controllers\\PdfController@delivery',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pdf.delivery',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.payment' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/pdf/payment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfController@payment',
        'controller' => 'App\\Http\\Controllers\\PdfController@payment',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pdf.payment',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.deliveryuk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/pdf/deliveryuk',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfController@deliveryuk',
        'controller' => 'App\\Http\\Controllers\\PdfController@deliveryuk',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pdf.deliveryuk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.paymentuk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/accountant/pdf/paymentuk',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfController@paymentuk',
        'controller' => 'App\\Http\\Controllers\\PdfController@paymentuk',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pdf.paymentuk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.seniorassociate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/seniorassociate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@seniorassociate',
        'controller' => 'App\\Http\\Controllers\\DashboardController@seniorassociate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.seniorassociate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.seniorassociate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/seniorassociate/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@seniorassociate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@seniorassociate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.seniorassociate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'all.seniorassociate.candidate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/seniorassociate/candidate/{userId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@seniorassociate',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@seniorassociate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'all.seniorassociate.candidate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'google.sheet.associate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/associate/google-sheet',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@associate',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@associate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'google.sheet.associate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'dashboard.associate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/associate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DashboardController@associate',
        'controller' => 'App\\Http\\Controllers\\DashboardController@associate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'dashboard.associate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'all.associate.services' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/associate/candidate/services/{userId}/{forwardedBy}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@associateservices',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@associateservices',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'all.associate.services',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'all.associate.candidate' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/associate/candidate/{userId}/{forwardedBy}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CandidateDetailsController@associate',
        'controller' => 'App\\Http\\Controllers\\CandidateDetailsController@associate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'all.associate.candidate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'all.associate.add' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'dashboard/associate/candidateadd',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\GoogleSheetController@candidateStore',
        'controller' => 'App\\Http\\Controllers\\GoogleSheetController@candidateStore',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'all.associate.add',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'senior.group' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/group/senior',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorgroup',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorgroup',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'senior.group',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'senior.groupmail' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/group/senior/mail',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\UserController@seniorgroupmail',
        'controller' => 'App\\Http\\Controllers\\UserController@seniorgroupmail',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'senior.groupmail',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'senior.groupmailchart' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/group/senior/mail/chart',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\CallReportController@seniorgroupmailchart',
        'controller' => 'App\\Http\\Controllers\\CallReportController@seniorgroupmailchart',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'senior.groupmailchart',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.junior' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'dashboard/junior/chat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@junior',
        'controller' => 'App\\Http\\Controllers\\ChatController@junior',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.junior',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'chat/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@send',
        'controller' => 'App\\Http\\Controllers\\ChatController@send',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.latestMessages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'latest-messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@latestMessages',
        'controller' => 'App\\Http\\Controllers\\ChatController@latestMessages',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.latestMessages',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'chat.refreshUsers' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'chat/refresh-users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'allowedip',
          2 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ChatController@refreshChatUsers',
        'controller' => 'App\\Http\\Controllers\\ChatController@refreshChatUsers',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'chat.refreshUsers',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\LoginsController@index',
        'controller' => 'App\\Http\\Controllers\\LoginsController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logins',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ajax.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout-user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@ajaxLogout',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@ajaxLogout',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'ajax.logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ajax.login' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login-user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@ajaxLogin',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@ajaxLogin',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'ajax.login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'ajax.logincheckStatus' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logincheckStatus-user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@ajaxCheckStatus',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@ajaxCheckStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'ajax.logincheckStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@showRegistrationForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@showRegistrationForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'registersubmit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisterController@register',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisterController@register',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@showLoginForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@showLoginForm',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'loginsubmit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@login',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@login',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@logout',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@logout',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'template.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'template/{id}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\EmailTemplateController@edit',
        'controller' => 'App\\Http\\Controllers\\EmailTemplateController@edit',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'template.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'template.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'email-template/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\EmailTemplateController@update',
        'controller' => 'App\\Http\\Controllers\\EmailTemplateController@update',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'template.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'resumes.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'resumes/upload/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
          3 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ResumeController@upload',
        'controller' => 'App\\Http\\Controllers\\ResumeController@upload',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'resumes.upload',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'resumes.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'resumes/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\ResumeController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\ResumeController@updateStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'resumes.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'payment.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'payment/{id}/status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\PaymentController@updateStatus',
        'controller' => 'App\\Http\\Controllers\\PaymentController@updateStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'payment.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'training.updateStatus' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'training/{id}/trastatus',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\PaymentController@traupdateStatus',
        'controller' => 'App\\Http\\Controllers\\PaymentController@traupdateStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'training.updateStatus',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login.history' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login-history',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'web',
          2 => 'allowedip',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@loginHistory',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@loginHistory',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login.history',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Controller@index',
        'controller' => 'App\\Http\\Controllers\\Controller@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
