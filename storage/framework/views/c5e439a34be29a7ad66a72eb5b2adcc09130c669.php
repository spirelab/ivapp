<?php $__env->startSection('title',__('Login')); ?>


<?php $__env->startSection('content'); ?>
    <section id="about-us" class="about-page secbg-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-block py-5">
                        <form class="login-form" action="<?php echo e(route('login')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="signin">
                                <h3 class="title mb-30"><?php echo app('translator')->get('Login Form'); ?></h3>

                                <div class="form-group mb-30">
                                    <input class="form-control" type="text" name="username"  placeholder="<?php echo app('translator')->get('Email Or Username'); ?>">
                                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger  mt-1"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><span class="text-danger  mt-1"><?php echo e($message); ?></span><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group mb-20">
                                    <input class="form-control" type="password" name="password"  placeholder="<?php echo app('translator')->get('Password'); ?>">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="text-danger mt-1"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <?php if(basicControl()->reCaptcha_status_login): ?>
                                    <div class="box mb-4 form-group">
                                        <?php echo NoCaptcha::renderJs(session()->get('trans')); ?>

                                        <?php echo NoCaptcha::display($basic->theme == 'deepblack' ? ['data-theme' => 'dark'] : []); ?>

                                        <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="text-danger mt-1"><?php echo app('translator')->get($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                <?php endif; ?>

                                <div
                                    class="remember-me d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-sm-between mb-30">
                                    <div class="checkbox custom-control custom-checkbox mt-10">
                                        <input id="remember" type="checkbox" class="custom-control-input" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <label class="custom-control-label" for="remember"><?php echo app('translator')->get('Remember Me'); ?></label>
                                    </div>
                                    <a class="text-white mt-10"  href="<?php echo e(route('password.request')); ?>"><?php echo app('translator')->get("Forgot password?"); ?></a>
                                </div>

                                <div class="btn-area">
                                    <button class="btn-login login-auth-btn" type="submit"><span><?php echo app('translator')->get('Login'); ?></span></button>
                                </div>

                                <div class="login-query mt-30 text-center">
                                    <a  href="<?php echo e(route('register')); ?>"><?php echo app('translator')->get("Don't have any account? Sign Up"); ?></a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="connectivity wow fadeIn" data-wow-duration="1s" data-wow-delay="0.35s">
                        <div class="d-flex align-items-center justify-content-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make($theme.'layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/auth/login.blade.php ENDPATH**/ ?>