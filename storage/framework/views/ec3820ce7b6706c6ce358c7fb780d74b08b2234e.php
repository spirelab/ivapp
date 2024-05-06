<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('Create a Plan'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <a href="<?php echo e(route('admin.planList')); ?>" class="btn btn-sm  btn-primary mr-2">
                    <span><i class="fas fa-arrow-left"></i> <?php echo app('translator')->get('Back'); ?></span>
                </a>
            </div>

            <form method="post" action="<?php echo e(route('admin.planStore')); ?>" class="form-row justify-content-center">
                <?php echo csrf_field(); ?>
                <div class="col-md-8">

                <div class="row ">
                    <div class=" col-md-6">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Name'); ?></label>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" placeholder="<?php echo app('translator')->get('Plan Name'); ?>" class="form-control" >
                            <?php $__errorArgs = ['name'];
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
                    </div>

                    <div class=" col-md-6">
                        <div class="form-group">
                            <label><?php echo app('translator')->get('Badge Name'); ?>  <small>(<?php echo e(trans('Optional')); ?>)</small></label>
                            <input type="text" name="badge" value="<?php echo e(old('badge')); ?>" placeholder="<?php echo app('translator')->get('eg. premium, popular'); ?>" class="form-control" >
                            <?php $__errorArgs = ['badge'];
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
                    </div>


                    <div class="col-sm-6 form-group">
                        <label><?php echo app('translator')->get('Plan Price Type'); ?></label>
                        <input data-toggle="toggle" id="plan_price_type" class="amount" data-onstyle="success"
                        data-offstyle="info" data-on="Fixed" data-off="Range" data-width="100%"
                        type="checkbox" checked name="plan_price_type">
                        <?php $__errorArgs = ['plan_price_type'];
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


                    <div class="form-group col-md-6 fixedAmount d-block">
                        <label><?php echo app('translator')->get('Fixed Amount'); ?></label>
                        <div class="input-group mb-3">
                            <input type="text" name="fixed_amount" class="form-control" placeholder="0.00" >
                            <div class="input-group-append">
                                <span class="input-group-text"><?php echo app('translator')->get(config('basic.currency_symbol')); ?></span>
                            </div>
                        </div>
                        <?php $__errorArgs = ['fixed_amount'];
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


                    <div class="form-group col-md-6 rangeAmount d-none">
                        <label><?php echo app('translator')->get('Minimum Amount'); ?></label>
                        <div class="input-group mb-3">
                            <input type="text" name="minimum_amount" class="form-control" placeholder="0.00" >
                            <div class="input-group-append">
                                <span class="input-group-text"><?php echo app('translator')->get(config('basic.currency_symbol')); ?></span>
                            </div>
                        </div>
                        <?php $__errorArgs = ['minimum_amount'];
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

                    <div class="form-group col-md-6 rangeAmount d-none">
                        <label><?php echo app('translator')->get('Maximum Amount'); ?></label>
                        <div class="input-group mb-3">
                            <input type="text" name="maximum_amount" class="form-control" placeholder="0.00" >
                            <div class="input-group-append">
                                <span class="input-group-text"><?php echo app('translator')->get(config('basic.currency_symbol')); ?></span>
                            </div>
                        </div>
                        <?php $__errorArgs = ['maximum_amount'];
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


                    <div class="form-group col-md-6">
                        <label><?php echo app('translator')->get('Yield'); ?></label>
                        <div class="input-group mb-3">
                            <input type="text" name="profit" class="form-control" placeholder="0.00">
                            <div class="input-group-append">
                                <select name="profit_type" id="profit_type" class="form-control">
                                    <option value="1">%</option>
                                    <option value="0"><?php echo app('translator')->get(config('basic.currency_symbol')); ?></option>
                                </select>
                            </div>
                        </div>
                        <?php $__errorArgs = ['profit'];
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


                    <div class="form-group col-md-6">
                        <label for="schedule"><?php echo app('translator')->get('Accrual'); ?></label>
                        <select name="schedule" id="schedule" class="form-control">
                            <option value="" disabled><?php echo app('translator')->get('Select a Period'); ?></option>
                            <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->time); ?>"><?php echo app('translator')->get('Every'); ?> <?php echo e($item->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['schedule'];
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


                    <div class="form-group col-sm-6 ">
                        <label><?php echo app('translator')->get('Return'); ?></label>

                        <input data-toggle="toggle" id="is_lifetime" data-onstyle="success"
                               data-offstyle="info" data-on="PERIOD" data-off="LIFETIME" data-width="100%"
                               type="checkbox" checked name="is_lifetime">

                        <?php $__errorArgs = ['is_lifetime'];
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

                    <div class="form-group col-md-6 repeatable d-block">
                        <label><?php echo app('translator')->get('Maturity'); ?></label>
                        <div class="input-group mb-3">
                            <input type="text" name="repeatable" value="<?php echo e(old('repeatable')); ?>" class="form-control" placeholder="<?php echo app('translator')->get('How many times'); ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><?php echo app('translator')->get('Times'); ?></span>
                            </div>
                        </div>

                        <?php $__errorArgs = ['repeatable'];
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


                    <div class="form-group col-sm-4 ">
                        <label><?php echo app('translator')->get('Capital back'); ?></label>
                        <input data-toggle="toggle" id="is_capital_back" data-onstyle="success" data-offstyle="info" data-on="YES" data-off="NO" data-width="100%" type="checkbox" checked name="is_capital_back">
                        <?php $__errorArgs = ['is_capital_back'];
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

                    <div class="form-group col-sm-4 ">
                        <label><?php echo app('translator')->get('Featured'); ?></label>
                        <input data-toggle="toggle" id="featured" data-onstyle="success" data-offstyle="info" data-on="YES" data-off="NO" data-width="100%" type="checkbox" checked name="featured">
                        <?php $__errorArgs = ['featured'];
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


                    <div class="form-group col-sm-4 ">
                        <label><?php echo app('translator')->get('Status'); ?></label>
                        <input data-toggle="toggle" id="status" data-onstyle="success"
                               data-offstyle="info" data-on="Active" data-off="Deactive" data-width="100%"
                               type="checkbox" checked name="status">
                        <?php $__errorArgs = ['status'];
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



                </div>


                <button type="submit" class="btn waves-effect waves-light btn-rounded btn-primary btn-block mt-3"><span><i
                            class="fas fa-save pr-2"></i> <?php echo app('translator')->get('Save Changes'); ?></span></button>

                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('js'); ?>
    <script>
        "use strict";
        $(document).on('change','#plan_price_type', function () {
            var isCheck = $(this).prop('checked');
            if (isCheck == false) {
                $('.rangeAmount').addClass('d-block');
                $('.fixedAmount').removeClass('d-block');
                $('.fixedAmount').addClass('d-none');
            } else {
                $('.rangeAmount').removeClass('d-block');
                $('.fixedAmount').addClass('d-block');
            }
        });

        $(document).on('change','#is_lifetime', function () {
            var isCheck = $(this).prop('checked');

            if(isCheck == false){
                $('.repeatable').removeClass('d-block');
                $('.repeatable').addClass('d-none');
            }else {
                $('.repeatable').removeClass('d-none');
                $('.repeatable').addClass('d-block');
            }

        });

        $(document).ready(function () {
            $('select[name=schedule]').select2({
                selectOnClose: true
            });
        });


    </script>

    <?php if($errors->any()): ?>
        <?php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        ?>
        <script>
            "use strict";
            <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure("<?php echo e(trans($error)); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/admin/plan/create.blade.php ENDPATH**/ ?>