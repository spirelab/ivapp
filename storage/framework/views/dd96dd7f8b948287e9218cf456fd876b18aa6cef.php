<!-- PAYMENT-METHOD -->
<section id="payment-method">
    <div class="container">

        <?php if(isset($templates['we-accept'][0]) && $weAccept = $templates['we-accept'][0]): ?>
            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get(@$weAccept->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get(@$weAccept->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get(@$weAccept->description->short_details); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>


        <div class="carousel-container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s">
            <div class="<?php echo e((session()->get('rtl') == 1) ? 'carousel-payment-rtl': 'carousel-payment'); ?>  owl-carousel owl-theme">
                <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item-carousel">
                    <div class="payment-fig">
                        <img src="<?php echo e(getFile(config('location.gateway.path').@$gateway->image)); ?>" alt="<?php echo e(@$gateway->name); ?>">
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<!-- /PAYMENT-METHOD -->
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/we-accept.blade.php ENDPATH**/ ?>