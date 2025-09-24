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

    <section class="auth forgot-password-page bg-base d-flex flex-wrap">
        <div class="auth-left d-lg-block d-none">
            <div class="d-flex align-items-center flex-column h-100 justify-content-center">
                <img src="<?php echo e(asset('assets/images/auth/forgot-pass-img.png')); ?>" alt="">
            </div>
        </div>
        <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
            <div class="max-w-464-px mx-auto w-100">
                <div>
                    <h4 class="mb-12">Forgot Password</h4>
                    <p class="mb-32 text-secondary-light text-lg">Enter the email address associated with your account and we will send you a link to reset your password.</p>
                </div>
                <form action="#">
                    <div class="icon-field">
                        <span class="icon top-50 translate-middle-y">
                            <iconify-icon icon="mage:email"></iconify-icon>
                        </span>
                        <input type="email" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Enter Email">
                    </div>
                    <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32" data-bs-toggle="modal" data-bs-target="#exampleModal">Continue</button>

                    <div class="text-center">
                    <a  href="<?php echo e(route('signin')); ?>" class="text-primary-600 fw-bold mt-24">Back to Sign In</a>
                    </div>

                    <div class="mt-120 text-center text-sm">
                        <p class="mb-0">Already have an account? <a  href="<?php echo e(route('signin')); ?>" class="text-primary-600 fw-semibold">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-dialog-centered">
            <div class="modal-content radius-16 bg-base">
                <div class="modal-body p-40 text-center">
                    <div class="mb-32">
                        <img src="<?php echo e(asset('assets/images/auth/envelop-icon.png')); ?>" alt="">
                    </div>
                    <h6 class="mb-12">Verify your Email</h6>
                    <p class="text-secondary-light text-sm mb-0">Thank you, check your email for instructions to reset your password</p>
                    <button type="button" class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">Skip</button>
                    <div class="mt-32 text-sm">
                        <p class="mb-0">Don’t receive an email? <a  href="" class="text-primary-600 fw-semibold">Resend</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php if (isset($component)) { $__componentOriginal2a2e30ee7946b5afacadfde3b701b26e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2a2e30ee7946b5afacadfde3b701b26e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.script','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('script'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2a2e30ee7946b5afacadfde3b701b26e)): ?>
<?php $attributes = $__attributesOriginal2a2e30ee7946b5afacadfde3b701b26e; ?>
<?php unset($__attributesOriginal2a2e30ee7946b5afacadfde3b701b26e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2a2e30ee7946b5afacadfde3b701b26e)): ?>
<?php $component = $__componentOriginal2a2e30ee7946b5afacadfde3b701b26e; ?>
<?php unset($__componentOriginal2a2e30ee7946b5afacadfde3b701b26e); ?>
<?php endif; ?>

</body>

</html><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\auth\forgotPassword.blade.php ENDPATH**/ ?>