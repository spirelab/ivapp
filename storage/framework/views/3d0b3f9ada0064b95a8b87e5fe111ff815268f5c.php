<!-- TOPBAR -->
<section id="topbar">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="topbar-contact">
                    <div class="d-flex flex-wrap justify-content-between">
                        <?php if(isset($contactUs['contact-us'][0]) && $contact = $contactUs['contact-us'][0]): ?>
                            <ul class="topbar-contact-list d-flex flex-wrap  justify-content-between justify-content-lg-start">
                                <li><i class="icofont-envelope"></i><span
                                        class="ml-5"><?php echo app('translator')->get(@$contact->description->email); ?></span></li>
                                <li class="ml-sm-3 ml-0"><i class="icofont-android-tablet"></i><span
                                        class="ml-5"><?php echo app('translator')->get(@$contact->description->phone); ?></span></li>
                            </ul>
                        <?php endif; ?>
                        <div class="d-md-none">
                            <?php if(isset($contentDetails['social'])): ?>
                                <div class="topbar-social">
                                    <?php $__currentLoopData = $contentDetails['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a <?php if($k == 0): ?>class="pl-0" <?php endif; ?> href="<?php echo e(@$data->content->contentMedia->description->link); ?>"><i class="<?php echo e(@$data->content->contentMedia->description->icon); ?>"></i></a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="topbar-content d-flex align-items-center justify-content-between justify-content-md-end">
                    <div class="language-wrapper">
                        <div class="control-plugin">
                            <div class="language"
                                 data-input-name="country3"
                                 data-selected-country="<?php echo e(app()->getLocale() ? : 'US'); ?>"
                                 data-button-size="btn-sm"
                                 data-button-type="btn-info"
                                 data-scrollable="true"
                                 data-scrollable-height="250px"
                                 data-countries='<?php echo e($languages); ?>'>
                            </div>
                        </div>
                    </div>

                    <?php if(isset($contentDetails['social'])): ?>
                    <div class="topbar-social d-none d-md-block">
                        <?php $__currentLoopData = $contentDetails['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a <?php if($k == 0): ?>class="pl-0" <?php endif; ?> href="<?php echo e(@$data->content->contentMedia->description->link); ?>"><i class="<?php echo e(@$data->content->contentMedia->description->icon); ?>"></i></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php endif; ?>
                    <div class="login-signup d-md-none">
                        <a href="javascript:void(0)"><?php echo app('translator')->get('Login'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /TOPBAR -->
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/partials/topbar.blade.php ENDPATH**/ ?>