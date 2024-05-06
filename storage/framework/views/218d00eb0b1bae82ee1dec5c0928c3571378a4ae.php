
    <!-- INVESTMENT-PLAN -->
    <section id="investment-plan">
        <div class="container">
            <?php if(isset($templates['why-chose-us'][0]) && $whyChoseUs = $templates['why-chose-us'][0]): ?>

            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get($whyChoseUs->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get($whyChoseUs->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get($whyChoseUs->description->short_details); ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>


            <?php if(isset($contentDetails['why-chose-us'])): ?>
            <div class="investment-plan-wrapper">
                <div class="row">
                    <?php $__currentLoopData = $contentDetails['why-chose-us']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="card-type-1 card align-items-start wow fadeInLeft" data-wow-duration="1s"
                                 data-wow-delay="0.15s">
                                <div class="media">
                                    <div class="card-icon">
                                        <img
                                            src="<?php echo e(getFile(config('location.content.path').@$item->content->contentMedia->description->image)); ?>"
                                            alt="...">
                                    </div>
                                    <div class="media-body ml-20">
                                        <h5 class="mb-15"><?php echo app('translator')->get(@$item->description->title); ?></h5>
                                        <p class="text">
                                            <?php echo app('translator')->get(@$item->description->information); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
                <?php endif; ?>
        </div>
    </section>
    <!-- /INVESTMENT-PLAN -->
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/why-chose-us.blade.php ENDPATH**/ ?>