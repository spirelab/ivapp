<!-- FAQ -->
<section id="faq">
    <div class="container">
        <?php if(isset($templates['faq'][0]) && $faq = $templates['faq'][0]): ?>
            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get(@$faq->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get(@$faq->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get(@$faq->description->short_details); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="faq-wrapper">
            <div class="faq-accordion">
                <?php if(isset($contentDetails['faq'])): ?>
                    <?php $__currentLoopData = $contentDetails['faq']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="faq-card card">
                            <div class="card-header">
                                <button class="btn-faq rotate-icon">
                                    <?php echo app('translator')->get(@$data->description->title); ?>
                                </button>
                            </div>
                            <div class="card-body <?php echo e(($k == 0) ? 'preview' : ''); ?> ">
                                <div class="faq-content">
                                    <p class="text">
                                        <?php echo app('translator')->get(@$data->description->description); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- /FAQ -->
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/faq.blade.php ENDPATH**/ ?>