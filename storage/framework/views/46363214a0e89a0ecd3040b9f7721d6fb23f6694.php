<?php if(isset($templates['investor'][0]) && $investor = $templates['investor'][0]): ?>

    <!-- INVESTOR -->
    <section id="investor">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get(@$investor->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get(@$investor->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get(@$investor->description->short_title); ?></p>
                    </div>
                </div>
            </div>

            <div class="carousel-container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s">
                <div class="<?php echo e((session()->get('rtl') == 1) ? 'carousel-investor-rtl': 'carousel-investor'); ?> owl-carousel owl-theme">
                    <?php if(isset($investors)): ?>
                    <?php $__currentLoopData = $investors->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item-carousel">
                            <div class="card align-items-center">
                                <div class="investor-fig">
                                    <div class="img-container">
                                        <img class="img-circle" src="<?php echo e(getFile(config('location.user.path').optional($item->user)->image)); ?>"
                                             alt="<?php echo app('translator')->get('Investor Image Missing'); ?>">
                                    </div>
                                </div>
                                <h5 class="h5 font-weight-medium mt-25"><?php echo app('translator')->get(optional($item->user)->username); ?></h5>
                                <p class="text"><?php echo app('translator')->get('Investor'); ?> </p>
                                <hr class="hr mt-20 mb-20">
                                <p class="text themecolor text-uppercase mb-10"><?php echo app('translator')->get('Invest'); ?>: <?php echo e($basic->currency_symbol); ?><?php echo e(getAmount($item->totalAmount)); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <!-- /INVESTOR -->
<?php endif; ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/investor.blade.php ENDPATH**/ ?>