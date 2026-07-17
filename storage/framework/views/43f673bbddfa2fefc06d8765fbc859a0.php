<?php
    $title='Dashboard';
    $subTitle = 'Candidate';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
?>

<?php $__env->startSection('content'); ?>

<p>Welcome Back</p>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/norloxsolutionscrm.com/wowcrm/resources/views/dashboard/customer.blade.php ENDPATH**/ ?>