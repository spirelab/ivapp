<?php if(isset($templates['deposit-withdraw'][0]) && $depositWithdraw = $templates['deposit-withdraw'][0]): ?>
    <section id="deposit-withdraw">
    <div class="container">
        <div class="d-flex justify-content-center">
            <div class="col-lg-6">
                <div class="heading-container">
                    <p class="topheading"><?php echo app('translator')->get(@$depositWithdraw->description->title); ?></p>
                    <h3 class="heading"><?php echo app('translator')->get(@$depositWithdraw->description->sub_title); ?></h3>
                    <p class="slogan"><?php echo app('translator')->get(@$depositWithdraw->description->short_title); ?></p>
                </div>
            </div>
        </div>

        <ul id="pills-tab" role="tablist" class="nav nav-pills justify-content-center wow fadeInUp"
            data-wow-duration="1s" data-wow-delay="0.15s">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#deposit-tab" role="tab">
                    <span><?php echo e(trans('Deposit')); ?></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#withdraw-tab" role="tab">
                    <span><?php echo e(trans('Withdraw')); ?></span>
                </a>
            </li>
        </ul>
        <div class="tab-content wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
            <div id="deposit-tab" class="tab-pane fade show active" role="tabpanel">
                <div class="statistics-wrapper">
                    <div class="data-table-container ">
                        <div class="data-table-header">
                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Name'); ?></p>
                                </div>
                            </div>
                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Amount'); ?></p>
                                </div>
                            </div>
                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Gateway'); ?></p>
                                </div>
                            </div>

                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Date'); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="data-column">
                                <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="data-content-wrapper">
                                    <div class="media align-items-center">
                                        <img src="<?php echo e(getFile(config('location.user.path').optional($item->user)->image)); ?>" alt="<?php echo app('translator')->get('Image Missing'); ?>">
                                        <p class="text ml-10"><?php echo e(optional($item->user)->fullname); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                            <div class="data-column">
                                <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="data-content-wrapper">
                                    <p class="text"><?php echo e($basic->currency_symbol); ?> <?php echo e(getAmount($item->amount)); ?></p>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                            <div class="data-column">
                                <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="data-content-wrapper">
                                    <p class="text"><?php echo e(optional($item->gateway)->name); ?></p>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="data-column">
                                <?php $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="data-content-wrapper">
                                    <p class="text"><?php echo e(dateTime($item->created_at)); ?></p>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="withdraw-tab" class="tab-pane fade" role="tabpanel">
                <div class="statistics-wrapper">
                    <div class="data-table-container">
                        <div class="data-table-header">
                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Name'); ?></p>
                                </div>
                            </div>
                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Amount'); ?></p>
                                </div>
                            </div>
                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Gateway'); ?></p>
                                </div>
                            </div>

                            <div class="data-column">
                                <div class="data-column-header">
                                    <p class="text"><?php echo app('translator')->get('Date'); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="data-table">
                            <div class="data-column">

                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="data-content-wrapper">
                                        <div class="media align-items-center">
                                            <img src="<?php echo e(getFile(config('location.user.path').optional($item->user)->image)); ?>" alt="<?php echo app('translator')->get('Image Missing'); ?>">
                                            <p class="text ml-10"><?php echo e(optional($item->user)->fullname); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="data-column">
                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="data-content-wrapper">
                                        <p class="text"><?php echo e($basic->currency_symbol); ?> <?php echo e(getAmount($item->amount)); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                            <div class="data-column">
                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="data-content-wrapper">
                                        <p class="text"><?php echo e(optional($item->method)->name); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="data-column">
                                <?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="data-content-wrapper">
                                        <p class="text"><?php echo e(dateTime($item->created_at)); ?></p>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/deposit-withdraw.blade.php ENDPATH**/ ?>