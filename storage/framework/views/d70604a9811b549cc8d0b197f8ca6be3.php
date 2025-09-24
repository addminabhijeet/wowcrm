

<?php $__env->startSection('content'); ?>
<div class="container">
    <h2 class="mb-4">Login History</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>User Name</th>
                <th>Email</th>
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Logged In At</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $login): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($login->user->name ?? 'Unknown'); ?></td>
                    <td><?php echo e($login->user->email ?? 'N/A'); ?></td>
                    <td><?php echo e($login->ip_address); ?></td>
                    <td style="max-width: 300px; word-break: break-word;">
                        <?php echo e($login->user_agent); ?>

                    </td>
                    <td><?php echo e($login->logged_in_at->format('d M Y, h:i A')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center">No login records found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\auth\history.blade.php ENDPATH**/ ?>