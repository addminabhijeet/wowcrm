<!DOCTYPE html>
<html>
<head>
    <title>Job Portal</title>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
        <div style="padding:10px;background:#eee;">
            Logged in as <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->role); ?>)
            <form method="POST" action="<?php echo e(route('logout')); ?>" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit">Logout</button>
            </form>
        </div>
    <?php endif; ?>

    <div>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\wowdash\resources\views\layouts\app.blade.php ENDPATH**/ ?>