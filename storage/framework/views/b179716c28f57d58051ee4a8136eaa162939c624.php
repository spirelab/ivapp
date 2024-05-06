<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('App Setting'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">

            <form method="post" action="" class="needs-validation base-form">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label class="font-weight-bold"><?php echo app('translator')->get('APP COLOR'); ?></label>
                        <input type="color" name="app_color"
                               value="<?php echo e(old('app_color') ?? $control->app_color ?? '#6777ef'); ?>"
                               required="required" class="form-control ">
                        <?php $__errorArgs = ['app_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group col-sm-4 col-12">
                        <label class="font-weight-bold"><?php echo app('translator')->get('APP VERSION'); ?></label>
                        <input type="text" name="app_version"
                               value="<?php echo e(old('app_version') ?? $control->app_version ?? '1.1.0'); ?>"
                               required="required" class="form-control ">

                        <?php $__errorArgs = ['app_version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group col-sm-4 col-12">
                        <label class="font-weight-bold"><?php echo app('translator')->get('APP BUILD'); ?></label>
                        <input type="text" name="app_build"
                               value="<?php echo e(old('app_build') ?? $control->app_build ?? '25,26,27'); ?>"
                               required="required" class="form-control ">

                        <?php $__errorArgs = ['app_build'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>


                    <div class="form-group col-sm-6 col-md-4 col-lg-3 ">
                        <label class="text-dark"><?php echo app('translator')->get('Is Major Version'); ?></label>
                        <div class="custom-switch-btn">
                            <input type='hidden' value='1' name='is_major'>
                            <input type="checkbox" name="is_major" class="custom-switch-checkbox"
                                   id="is_major"
                                   value="0" <?php echo e(($control->is_major == 0) ? 'checked' : ''); ?> >
                            <label class="custom-switch-checkbox-label" for="is_major">
                                <span class="custom-switch-checkbox-inner"></span>
                                <span class="custom-switch-checkbox-switch"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> <?php echo app('translator')->get('Save Changes'); ?></span></button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/admin/app-controls.blade.php ENDPATH**/ ?>