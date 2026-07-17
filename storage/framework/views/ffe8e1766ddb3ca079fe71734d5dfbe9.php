<?php
$title='View Senior Group';
$role = auth()->user()->role ?? '';
if($role === 'admin'){
$subTitle = 'Super Admin';
} elseif ($role === 'operation') {
$subTitle = 'Operation Manager';
} else{
$subTitle = 'role';
}
$script ='<script>
    // ======================== Upload Image Start =====================
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                $("#imagePreview").hide().fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imageUpload").change(function() {
        readURL(this);
    });

    // ================== Password Show Hide Js Start ==========
    function initializePasswordToggle(toggleSelector) {
        $(toggleSelector).on("click", function() {
            $(this).toggleClass("ri-eye-off-line");
            var input = $($(this).attr("data-toggle"));
            if (input.attr("type") === "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    }
    // Call the function
    initializePasswordToggle(".toggle-password");
    // ========================= Password Show Hide Js End ===========================
</script>';
?>

<?php $__env->startSection('content'); ?>

<div class="row gy-4 mb-5">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body p-24">
                <form action="<?php echo e(route('users.seniorgroup.update', $user->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                            <div class="row align-items-end"> <!-- ✅ align properly -->

                                <!-- Dropdown -->
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="junior" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                            Select Junior <span class="text-danger-600">*</span>
                                        </label>

                                        <select name="group[]" id="junior" class="form-control radius-8 form-select" required>
                                            <option value="" disabled selected>Select Junior</option>

                                            <?php $__currentLoopData = $juniors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $junior): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(!in_array($junior->id, $user->group ?? [])): ?>
                                            <option value="<?php echo e($junior->id); ?>">
                                                <?php echo e($junior->name); ?>

                                            </option>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </select>
                                    </div>
                                </div>

                                <!-- Button -->
                                <div class="col-sm-6 d-flex align-items-end justify-content-start">
                                    <div class="mb-20 w-100">
                                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row gy-4">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body p-24">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel">
                        <div class="row">
                            <div class="col-12 mt-3">
                                <h6>Assigned Juniors</h6>
                                <?php if(!empty($user->group)): ?>
                                <?php
                                $assignedJuniors = \App\Models\User::whereIn('id', $user->group)->get();
                                ?>

                                <?php $__currentLoopData = $assignedJuniors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $junior): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="d-flex justify-content-between align-items-center border p-2 mb-2 radius-8">
                                    <div>
                                        <?php echo e($junior->name); ?> (<?php echo e($junior->email); ?>)
                                    </div>

                                    <form action="<?php echo e(route('users.seniorgroup.remove', [$user->id, $junior->id])); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <p>No juniors assigned.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/norloxsolutionscrm.com/wowcrm/resources/views/user/senioreditgroup.blade.php ENDPATH**/ ?>