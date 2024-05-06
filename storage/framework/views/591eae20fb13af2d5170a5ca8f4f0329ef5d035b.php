<?php if(isset($templates['news-letter'][0]) && $newsLetter = $templates['news-letter'][0]): ?>


<!-- REFFERAL -->
<section id="refferal">
    <div class="container">
        <div class="row" id="subscribe">
            
            <div <?php if(isset($templates['news-letter-referral'][0]) && 0 < count($referralLevel)): ?> class="col-md-6" <?php else: ?> class="col-md-12"  <?php endif; ?>>
                <div
                    class="d-flex align-items-center justify-content-center justify-content-md-start h-fill wow fadeInLeft"
                    data-wow-duration="1s" data-wow-delay="0.15s">
                    <div class="w-fill text-center text-md-left pr-30">
                        <h3 class="h3 text-capitalize"><?php echo app('translator')->get(@$newsLetter->description->title); ?></h3>
                        <p class="text mt-20 mb-20"><?php echo app('translator')->get(@$newsLetter->description->sub_title); ?></p>
                        <div class="subscribe" >
                            <form class="subscribe-form" action="<?php echo e(route('subscribe')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input class="form-control" name="email" type="email" placeholder="<?php echo app('translator')->get('Email Address'); ?>">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-white"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <button class="btn-subscribe" type="submit"><?php echo e(trans('Subscribe')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <?php if(isset($templates['news-letter-referral'][0]) && 0 < count($referralLevel) && $newsLetterReferral = $templates['news-letter-referral'][0]): ?>
            <div class="col-md-6">
                <div class="refferal-offer wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.15s">
                    <h3 class="h3 text-capitalize"><?php echo app('translator')->get(@$newsLetterReferral->description->title); ?></h3>
                    <p class="text text-capitalize mb-30"><?php echo app('translator')->get(@$newsLetterReferral->description->sub_title); ?> </p>
                    <div class="row">
                        <?php $__currentLoopData = $referralLevel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6">
                            <div class="media align-items-center">
                                <div class="media-icon">
                                    <img src="<?php echo e(asset($themeTrue.'images/icon/dashboard_3.png')); ?>" alt="Icon Missing">
                                </div>
                                <div class="media-body ml-20">
                                    <p class="text"><?php echo e(trans('Level')); ?> <?php echo e($data->level); ?> <?php echo e(trans('Instant')); ?> <strong class="themecolor"><?php echo e($data->percent); ?>%</strong></p>
                                    <p class="text"><?php echo app('translator')->get('Bonus Reward'); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- /REFFERAL -->
<?php endif; ?>
<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/news-letter.blade.php ENDPATH**/ ?>