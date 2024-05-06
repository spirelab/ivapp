<!-- FOOTER -->
<footer id="footer">
    <div class="container">
        <div class="row responsive-footer">
            <div class="col-sm-6 col-lg-4">
                <div class="footer-links wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.15s">
                    <div class="footer-brand">
                        <img src="<?php echo e(getFile(config('location.logoIcon.path').'logo.png')); ?>" alt="...">

                        <?php if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0]): ?>
                            <p class="text mt-30 mb-30">
                                <?php echo app('translator')->get(strip_tags(@$contact->description->footer_short_details)); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php if(isset($contentDetails['social'])): ?>
                    <div class="footer-social mt-5">
                        <?php $__currentLoopData = $contentDetails['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a class="social-icon facebook" href="<?php echo e(@$data->content->contentMedia->description->link); ?>">
                                <i class="<?php echo e(@$data->content->contentMedia->description->icon); ?>"></i>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="footer-links  wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.3s">
                    <h5 class="h5"><?php echo e(trans('Useful Links')); ?></h5>
                    <ul class="">
                        <li>
                            <a href="<?php echo e(route('home')); ?>"><i class="icofont-thin-right"></i> <?php echo app('translator')->get('Home'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('about')); ?>"><i class="icofont-thin-right"></i> <?php echo app('translator')->get('About Us'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('blog')); ?>"><i class="icofont-thin-right"></i> <?php echo app('translator')->get('Blog'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo e(route('faq')); ?>"><i class="icofont-thin-right"></i> <?php echo app('translator')->get('FAQ'); ?></a>
                        </li>


                        <?php if(isset($contentDetails['support'])): ?>
                            <?php $__currentLoopData = $contentDetails['support']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <a href="<?php echo e(route('getLink', [slug($data->description->title), $data->content_id])); ?>"><i class="icofont-thin-right"></i> <?php echo app('translator')->get($data->description->title); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>


            <?php if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0]): ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="footer-address  wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.45s">
                        <h5 class="h5"><?php echo e(trans('Contact')); ?></h5>
                        <ul>
                            <li class="d-flex align-items-center mb-10">
                                <i class="icofont-android-tablet"></i>
                                <span class="ml-10"><?php echo app('translator')->get(@$contact->description->phone); ?></span>
                            </li>
                            <li class="d-flex align-items-center mb-10">
                                <i class="icofont-envelope"></i>
                                <span class="ml-10"><?php echo app('translator')->get(@$contact->description->email); ?></span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="icofont-map-pins"></i>
                                <span class="ml-10"><?php echo app('translator')->get(@$contact->description->address); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>


    <div class="copy-rights">
        <div class="container">
            <p class="wow fadeIn" data-wow-duration="1s" data-wow-delay="0.35s">
                <?php echo app('translator')->get('Copyright'); ?> &copy; <?php echo e(date('Y')); ?> <?php echo app('translator')->get($basic->site_title); ?> <?php echo app('translator')->get('All Rights Reserved'); ?></p>
        </div>
    </div>

</footer>
<!-- /FOOTER -->


<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/partials/footer.blade.php ENDPATH**/ ?>