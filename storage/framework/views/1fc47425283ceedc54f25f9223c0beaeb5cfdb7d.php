<?php if(isset($templates['how-it-work'][0]) && $howItWork = $templates['how-it-work'][0]): ?>
    <?php $__env->startPush('style'); ?>
        <style>
            #banner-wrap::before {
                background-image: linear-gradient(90deg, rgba(0, 0, 0, 0.1) 0%, rgba(0, 0, 0, 0.1) 100%), url(<?php echo e(getFile(config('location.content.path').@$howItWork->templateMedia()->image)); ?>);
            }
        </style>
    <?php $__env->stopPush(); ?>

    <!-- BANNER-WRAP -->
    <section id="banner-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-6">
                    <div class="youtube-wrapper wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.15s">
                        <div class="btn-container">
                            <div class="btn-play grow-play">
                                <i class="icofont-ui-play"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 offset-md-1 col-md-5">
                    <div class="wrapper wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.3s">
                        <h3 class="h3 mb-30"><?php echo app('translator')->get(@$howItWork->description->title); ?></h3>
                        <div class="vertical-timeline">
                            <?php if(isset($contentDetails['how-it-work'])): ?>
                            <?php $__currentLoopData = $contentDetails['how-it-work']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k =>  $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="media align-items-center mb-20">
                                    <div class="media-counter"><span><?php echo e(++$k); ?></span></div>
                                    <div class="media-body ml-20">
                                        <h6 class="media-title mb-10"><?php echo app('translator')->get(@$item->description->title); ?></h6>
                                        <p class="text">
                                            <?php echo app('translator')->get(@$item->description->short_description); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /BANNER-WRAP -->
<?php endif; ?>

<?php $__env->startPush('extra-content'); ?>
    <?php if(isset($templates['how-it-work'][0]) && $howItWork = $templates['how-it-work'][0]): ?>

    <!-- MODAL-VIDEO -->
    <div id="modal-video">
        <div class="modal-wrapper">
            <div class="modal-content">
                <div class="btn-close">&times;</div>
                <div class="modal-container">
                    <iframe width="100%" height="100%"
                            src="<?php echo e(@optional($howItWork->templateMedia())->youtube_link); ?>"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- /MODAL-VIDEO -->
    <?php endif; ?>
<?php $__env->stopPush(); ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/how-it-work.blade.php ENDPATH**/ ?>