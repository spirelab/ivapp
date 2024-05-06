<?php if(isset($templates['testimonial'][0]) && $testimonial = $templates['testimonial'][0]): ?>


    <!-- TESTIMONIAL -->
    <section id="testimonial">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get($testimonial->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get($testimonial->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get($testimonial->description->short_title); ?></p>
                    </div>
                </div>
            </div>

            <div class="slider-container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.15s">
                <div class="d-flex justify-content-center">
                    <div class="col-lg-6">
                        <div class="<?php echo e((session()->get('rtl') == 1) ? 'slider-testimonial-rtl': 'slider-testimonial'); ?>">

                            <?php if(isset($contentDetails['testimonial'])): ?>
                                <?php $__currentLoopData = $contentDetails['testimonial']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="slider-item">
                                        <div class="testimonial-item">
                                            <div class="media align-items-center">
                                                <div class="client-fig">
                                                    <img
                                                        src="<?php echo e(getFile(config('location.content.path').@$data->content->contentMedia->description->image)); ?>"
                                                        alt="...">
                                                </div>
                                                <div class="media-body ml-20">
                                                    <h6 class="h6 mb-5"> <?php echo app('translator')->get(@$data->description->name); ?></h6>
                                                    <p class="text"><?php echo app('translator')->get(@$data->description->designation); ?></p>
                                                </div>
                                            </div>
                                            <p class="text fontubonto font-weight-medium mt-15">
                                                <?php echo app('translator')->get(@$data->description->description); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>


                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center">
                    <div class="col-md-8">
                        <div class="slider  <?php echo e((session()->get('rtl') == 1) ? 'slider-nav-rtl': 'slider-nav'); ?>">
                            <?php if(isset($contentDetails['testimonial'])): ?>
                            <?php $__currentLoopData = $contentDetails['testimonial']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="slider-nav-item">
                                    <div class="testimonial-nav">
                                        <div class="slider-nav-center">
                                            <img
                                                src="<?php echo e(getFile(config('location.content.path').@$data->content->contentMedia->description->image)); ?>"
                                                alt="...">
                                        </div>
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
    <!-- /TESTIMONIAL -->
<?php endif; ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/testimonial.blade.php ENDPATH**/ ?>