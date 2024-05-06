
<?php if(isset($contentDetails['feature'])): ?>
    <!-- FEATURE -->
    <section id="feature">
        <div class="feature-wrapper">
            <div class="container">
                <div class="row">
                    <?php $__currentLoopData = $contentDetails['feature']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="col-md-4">
                            <div class="card-type-1 card wow fadeInUp" data-wow-duration="1s" data-wow-dealy="0.1s">
                                <div class="card-icon">
                                    <img class="card-img-top" src="<?php echo e(getFile(config('location.content.path').@$feature->content->contentMedia->description->image)); ?>" alt="....">
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo app('translator')->get(@$feature->description->information); ?></h3>
                                    <h5 class="card-text"><?php echo app('translator')->get(@$feature->description->title); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>
        </div>
    </section>
    <!-- /FEATURE -->
<?php endif; ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/feature.blade.php ENDPATH**/ ?>