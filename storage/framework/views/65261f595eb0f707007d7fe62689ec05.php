<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<?php if (isset($component)) { $__componentOriginal0f509fab02c45445826003a1e50db506 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0f509fab02c45445826003a1e50db506 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.head','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('head'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0f509fab02c45445826003a1e50db506)): ?>
<?php $attributes = $__attributesOriginal0f509fab02c45445826003a1e50db506; ?>
<?php unset($__attributesOriginal0f509fab02c45445826003a1e50db506); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0f509fab02c45445826003a1e50db506)): ?>
<?php $component = $__componentOriginal0f509fab02c45445826003a1e50db506; ?>
<?php unset($__componentOriginal0f509fab02c45445826003a1e50db506); ?>
<?php endif; ?>

<body>

<section class="auth bg-base d-flex flex-wrap">
    <div class="auth-left d-lg-block d-none">
        <div class="d-flex align-items-center flex-column h-100 justify-content-center">
            <img src="<?php echo e(asset('assets/images/auth/auth-img.png')); ?>" alt="">
        </div>
    </div>

    <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
        <div class="max-w-464-px mx-auto w-100">
            <div>
                <a href="" class="mb-40 max-w-290-px">
                    <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="">
                </a>
                <h4 class="mb-12">Sign In to your Account</h4>
                <p class="mb-32 text-secondary-light text-lg">Welcome back! Please enter your details</p>
            </div>

            <form method="POST" action="<?php echo e(route('login.submit')); ?>">
                <?php echo csrf_field(); ?>

                <!-- Email -->
                <div class="icon-field mb-16">
                    <span class="icon top-50 translate-middle-y">
                        <iconify-icon icon="mage:email"></iconify-icon>
                    </span>
                    <input type="email" id="email" name="email"
                           value="<?php echo e(old('email')); ?>" required autocomplete="email"
                           class="form-control h-56-px bg-neutral-50 radius-12"
                           placeholder="Email" autofocus>
                </div>

                <!-- Password -->
                <div class="position-relative mb-20">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
                        </span>
                        <input type="password" id="password" name="password"
                               required autocomplete="current-password"
                               class="form-control h-56-px bg-neutral-50 radius-12"
                               placeholder="Password">
                    </div>
                    <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                          data-toggle="#password"></span>
                </div>

                <!-- Remember + Forgot -->
                <div class="d-flex justify-content-between gap-2">
                    <div class="form-check style-check d-flex align-items-center">
                        <input class="form-check-input border border-neutral-300"
                               type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="javascript:void(0)" class="text-primary-600 fw-medium">Forgot Password?</a>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
                    Sign In
                </button>

                <!-- Social Login -->
                <!-- <div class="mt-32 center-border-horizontal text-center">
                    <span class="bg-base z-1 px-4">Or sign in with</span>
                </div>

                <div class="mt-32 d-flex align-items-center gap-3">
                    <button type="button"
                            class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 line-height-1 bg-hover-primary-50">
                        <iconify-icon icon="ic:baseline-facebook"
                                      class="text-primary-600 text-xl line-height-1"></iconify-icon>
                        Facebook
                    </button>
                    <button type="button"
                            class="fw-semibold text-primary-light py-16 px-24 w-50 border radius-12 text-md d-flex align-items-center justify-content-center gap-12 line-height-1 bg-hover-primary-50">
                        <iconify-icon icon="logos:google-icon"
                                      class="text-primary-600 text-xl line-height-1"></iconify-icon>
                        Google
                    </button>
                </div> -->

                <!-- Register Link -->
                <div class="mt-32 text-center text-sm">
                    <p class="mb-0">
                        Don’t have an account?
                        <a href="<?php echo e(route('register')); ?>" class="text-primary-600 fw-semibold">Sign Up</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Add jQuery before your custom script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        $(".toggle-password").on("click", function () {
            try {
                let input = $($(this).data("toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    $(this).removeClass("ri-eye-line").addClass("ri-eye-off-line");
                } else {
                    input.attr("type", "password");
                    $(this).removeClass("ri-eye-off-line").addClass("ri-eye-line");
                }
            } catch (error) {
                console.log("Password toggle error:", error);
            }
        });
    });
</script>




</body>
</html>

<?php /**PATH C:\xampp\htdocs\wowdash\resources\views/auth/signin.blade.php ENDPATH**/ ?>