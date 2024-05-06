<?php $__env->startSection('title',trans('Plan')); ?>

<?php $__env->startSection('content'); ?>

    <!-- INVESTMENT -->
    <section id="investment" class="secbg-1 <?php if(auth()->guard()->check()): ?> pt-0 <?php endif; ?>">
        <div class="<?php if(auth()->guard()->check()): ?> container-fluid <?php else: ?> container <?php endif; ?>">
            <?php if(auth()->guard()->guest()): ?>
                <?php if(isset($templates['investment'][0]) && $investment = $templates['investment'][0]): ?>
            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get(@$investment->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get(@$investment->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get(@$investment->description->short_details); ?></p>
                    </div>
                </div>
            </div>
                    <?php endif; ?>
            <?php endif; ?>

            <div class="investment-wrapper">
                <div class="row">
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $getTime = \App\Models\ManageTime::where('time', $data->schedule)->first();
                        ?>


                        <div class="<?php if(auth()->guard()->check()): ?> col-md-6 col-lg-4 col-xl-3 <?php else: ?> col-md-6 col-lg-4 <?php endif; ?>  ">
                            <div class="card-type-1 card align-items-start wow fadeInUp" data-wow-duration="1s"
                                 data-wow-delay="0.15s">
                                <?php if($data->badge): ?>
                                    <div class="featured"><span><?php echo e(__($data->badge)); ?></span></div>
                                <?php endif; ?>
                                <h4 class="h4"><?php echo app('translator')->get($data->name); ?></h4>

                                <h4 class="h4 themecolor">
                                    <?php echo e($data->price); ?>

                                </h4>
                                <div class="d-flex align-items-baseline">
                                    <h4 class="h4"> <?php echo e(getAmount($data->profit)); ?><?php echo e(($data->profit_type == 1) ? '%': trans($basic->currency)); ?></h4>
                                    <h6 class="ml-5"><?php echo app('translator')->get('Every'); ?> <?php echo e(trans($getTime->name)); ?> </h6>
                                </div>
                                <hr class="hr">

                                <p class="text"><?php echo app('translator')->get('Profit For'); ?>  <?php echo e(($data->is_lifetime ==1) ? trans('Lifetime') : trans('Every').' '.trans($getTime->name)); ?></p>
                                <p class="text">
                                    <?php echo app('translator')->get('Capital will back'); ?> :
                                    <span class="badge badge-<?php echo e(($data->is_capital_back ==1) ? 'success':'danger'); ?> px-2 py-1"><?php echo e(($data->is_capital_back ==1) ? trans('Yes'): trans('No')); ?></span>
                                </p>

                                <p class="text">
                                    <?php if($data->is_lifetime == 0): ?>
                                        <?php echo app('translator')->get('Total'); ?>   <?php echo e(trans($data->profit*$data->repeatable)); ?> <?php echo e(($data->profit_type == 1) ? '%': trans($basic->currency)); ?>

                                        <?php if($data->is_capital_back == 1): ?>
                                            + <span class="badge badge-success"><?php echo app('translator')->get('Capital'); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo app('translator')->get('Lifetime Earning'); ?>
                                    <?php endif; ?>
                                </p>

                                <button class="btn-base text-uppercase mt-30 investNow" type="button"
                                        data-price="<?php echo e($data->price); ?>"
                                        data-resource="<?php echo e($data); ?>"><?php echo app('translator')->get('Invest Now'); ?></button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    </section>
    <!-- /INVESTMENT -->


    <!-- MODAL-LOGIN -->
    <div id="modal-login">
        <div class="modal-wrapper">
            <div class="modal-login-body">
                <div class="btn-close">&times;</div>
                <div class="form-block pb-5">
                    <form class="login-form" id="invest-form" action="<?php echo e(route('user.purchase-plan')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="signin ">
                            <h3 class="title mb-30 plan-name"></h3>

                            <p class="text-success text-center price-range font-20"></p>
                            <p class="text-success text-center profit-details font-18"></p>
                            <p class="text-success text-center profit-validity pb-3 font-18"></p>


                            <div class="form-group  mb-30">
                                <strong class="text-white mb-2 d-block"><?php echo app('translator')->get('Select wallet'); ?></strong>
                                <select class="form-control" name="balance_type">
                                    <?php if(auth()->guard()->check()): ?>
                                        <option
                                            value="balance"><?php echo app('translator')->get('Deposit Balance - '.$basic->currency_symbol.getAmount(auth()->user()->balance)); ?></option>
                                        <option
                                            value="interest_balance"><?php echo app('translator')->get('Interest Balance -'.$basic->currency_symbol.getAmount(auth()->user()->interest_balance)); ?></option>
                                    <?php endif; ?>
                                    <option value="checkout"><?php echo app('translator')->get('Checkout'); ?></option>
                                </select>
                            </div>

                            <div class="form-group mb-30">
                                <input type="text" class="form-control invest-amount" id="amount" name="amount"
                                       value="<?php echo e(old('amount')); ?>"
                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                       autocomplete="off">
                            </div>
                            <input type="hidden" name="plan_id" class="plan-id">

                            <div class="btn-area mb-30">
                                <button class="btn-login login-auth-btn" type="submit"><span><?php echo app('translator')->get('Invest Now'); ?></span>
                                </button>
                            </div>

                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>


<?php $__env->stopSection(); ?>


<?php $__env->startPush('script'); ?>
    <script>
        "use strict";
        (function ($) {
            $(document).on('click', '.investNow', function () {
                $("#modal-login").toggleClass("modal-open");
                let data = $(this).data('resource');
                let price = $(this).data('price');


                let symbol = "<?php echo e(trans($basic->currency_symbol)); ?>";
                let currency = "<?php echo e(trans($basic->currency)); ?>";


                $('.price-range').text(`<?php echo app('translator')->get('Invest'); ?>: ${price}`);

                if (data.fixed_amount == '0') {
                    $('.invest-amount').val('');
                    $('#amount').attr('readonly', false);
                } else {
                    $('.invest-amount').val(data.fixed_amount);
                    $('#amount').attr('readonly', true);
                }

                $('.profit-details').html(`<strong> <?php echo app('translator')->get('Interest'); ?>: ${(data.profit_type == '1') ? `${data.profit} %` : `${data.profit} ${currency}`}  </strong>`);
                $('.profit-validity').html(`<strong>  <?php echo app('translator')->get('Per'); ?> ${data.schedule} <?php echo app('translator')->get('hours'); ?> ,  ${(data.is_lifetime == '0') ? `${data.repeatable} <?php echo app('translator')->get('times'); ?>` : `<?php echo app('translator')->get('Lifetime'); ?>`} </strong>`);
                $('.plan-name').text(data.name);
                $('.plan-id').val(data.id);
            });
        })(jQuery);


    </script>


    <?php if(count($errors) > 0 ): ?>
        <script>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure("<?php echo app('translator')->get($error); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>


<?php $__env->stopPush(); ?>

<?php echo $__env->make($extend_blade, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/plan.blade.php ENDPATH**/ ?>