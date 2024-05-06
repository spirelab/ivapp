<!-- MODAL-LOGIN -->
<div id="modal-login">
    <div class="modal-wrapper">
        <div class="modal-login-body">
            <div class="btn-close">&times;</div>
            <div class="form-block">
                <form class="login-form" id="login-form" action="<?php echo e(route('loginModal')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="signin">
                        <h3 class="title mb-30"><?php echo app('translator')->get('Login'); ?></h3>

                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control" type="text" name="username" placeholder="<?php echo app('translator')->get('Username'); ?>">
                            <span class="text-danger emailError"></span>
                            <span class="text-danger usernameError"></span>
                        </div>

                        <div class="form-group mb-20">
                            <input  autocomplete="off" class="form-control" type="password" name="password" placeholder="<?php echo app('translator')->get('Password'); ?>">
                            <span class="text-danger passwordError"></span>
                        </div>

                        <div
                            class="remember-me d-flex flex-column flex-sm-row align-items-center justify-content-center justify-content-sm-between mb-30">
                            <div class="checkbox custom-control custom-checkbox mt-10">
                                <input  autocomplete="off" id="remember" type="checkbox" class="custom-control-input"
                                       name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="custom-control-label" for="remember"><?php echo app('translator')->get('Remember Me'); ?></label>
                            </div>
                            <a class="btn-forget mt-10" href="javascript:void(0)"><?php echo app('translator')->get("Forgot password?"); ?></a>
                        </div>

                        <div class="btn-area">
                            <button class="btn-login login-auth-btn" type="submit"><span><?php echo app('translator')->get('Login'); ?></span></button>
                        </div>

                        <div class="login-query mt-30 text-center">
                            <a class="btn-signup" href="javascript:void(0)"><?php echo app('translator')->get("Don't have any account? Sign Up"); ?></a>
                        </div>
                    </div>
                </form>


                <form class="login-form" id="reset-form" method="post" action="<?php echo e(route('password.email')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="reset-password">
                        <h3 class="title mb-30"><?php echo app('translator')->get("Reset Password"); ?></h3>
                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control" type="email" name="email" value="<?php echo e(old('email')); ?>"
                                   placeholder="<?php echo app('translator')->get('Enter your Email Address'); ?>">
                            <span class="text-danger emailError"></span>
                        </div>

                        <div class="btn-area">
                            <button class="btn-login login-recover-auth-btn" type="submit">
                                <span><?php echo app('translator')->get('Send Password Reset Link'); ?></span></button>
                        </div>
                        <div class="login-query mt-30 text-center">
                            <a class="btn-login-back "
                               href="javascript:void(0)"><?php echo app('translator')->get("Already have any account? Login"); ?></a>
                        </div>
                    </div>
                </form>


                <form class="login-form" id="signup-form" action="<?php echo e(route('register')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="register">
                        <h3 class="title mb-30"><?php echo app('translator')->get('SIGN UP FORM'); ?></h3>

                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control" type="text" name="firstname" value="<?php echo e(old('firstname')); ?>"
                                   placeholder="<?php echo app('translator')->get('First Name'); ?>">
                            <span class="text-danger firstnameError"></span>
                        </div>

                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control " type="text" name="lastname" value="<?php echo e(old('lastname')); ?>"
                                   placeholder="<?php echo app('translator')->get('Last Name'); ?>">
                            <span class="text-danger lastnameError"></span>
                        </div>

                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control " type="text" name="username" value="<?php echo e(old('username')); ?>"
                                   placeholder="<?php echo app('translator')->get('Username'); ?>">
                            <span class="text-danger usernameError"></span>
                        </div>

                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control" type="text" name="email" value="<?php echo e(old('email')); ?>"
                                   placeholder="<?php echo app('translator')->get('Email Address'); ?>">
                            <span class="text-danger emailError"></span>
                        </div>


                        <div class="form-group mb-30">
                            <?php
                                $country_code = (string) @getIpInfo()['code'] ?: null;
                                $myCollection = collect(config('country'))->map(function($row) {
                                    return collect($row);
                                });
                                $countries = $myCollection->sortBy('code');
                            ?>


                            <div class="input-group ">
                                <div class="input-group-prepend w-50">
                                    <select name="phone_code" class="form-control country_code dialCode-change">
                                        <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value['phone_code']); ?>"
                                                    data-name="<?php echo e($value['name']); ?>"
                                                    data-code="<?php echo e($value['code']); ?>"
                                                <?php echo e($country_code == $value['code'] ? 'selected' : ''); ?>

                                            > <?php echo e($value['name']); ?> (<?php echo e($value['phone_code']); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <input  autocomplete="off" type="text" name="phone" class="form-control dialcode-set" value="<?php echo e(old('phone')); ?>"
                                       placeholder="<?php echo app('translator')->get('Your Phone Number'); ?>">
                            </div>

                            <span class="text-danger phoneError"></span>

                            <input  autocomplete="off" type="hidden" name="country_code" value="<?php echo e(old('country_code')); ?>" class="text-dark">
                        </div>


                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control" type="password" name="password" value="<?php echo e(old('password')); ?>"
                                   placeholder="<?php echo app('translator')->get('Password'); ?>">
                            <span class="text-danger passwordError"></span>
                        </div>

                        <div class="form-group mb-30">
                            <input  autocomplete="off" class="form-control" type="password" name="password_confirmation"
                                   placeholder="<?php echo app('translator')->get('Confirm Password'); ?>">
                        </div>

                        <div class="btn-area">
                            <button class="btn-login login-signup-auth-btn" type="submit"><span><?php echo app('translator')->get('Sign Up'); ?></span>
                            </button>
                        </div>
                        <div class="login-query mt-30 text-center">
                            <a class="btn-login-back"
                               href="javascript:void(0)"><?php echo app('translator')->get("Already have an account? Login"); ?></a>
                        </div>
                    </div>

                </form>
            </div>


            <div class="connectivity wow fadeIn" data-wow-duration="1s" data-wow-delay="0.35s">

            </div>
        </div>
    </div>
</div>
<!-- /MODAL-LOGIN -->



<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        $(document).ready(function () {
            setDialCode();
            $(document).on('change', '.dialCode-change', function () {
                setDialCode();
            });
            function setDialCode() {
                let currency = $('.dialCode-change').val();
                $('.dialcode-set').val(currency);
            }

        });

    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/partials/modal-form.blade.php ENDPATH**/ ?>