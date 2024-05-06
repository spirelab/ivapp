<!-- BLOG -->
<section id="blog">
    <div class="container">
        <?php if(isset($templates['blog'][0]) && $blog = $templates['blog'][0]): ?>
            <div class="d-flex justify-content-center">
                <div class="col-lg-6">
                    <div class="heading-container">
                        <h6 class="topheading"><?php echo app('translator')->get(@$blog->description->title); ?></h6>
                        <h3 class="heading"><?php echo app('translator')->get(@$blog->description->sub_title); ?></h3>
                        <p class="slogan"><?php echo app('translator')->get(@$blog->description->short_title); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(isset($contentDetails['blog'])): ?>
        <div class="blog-wrapper">
            <div class="row">
                <?php $__currentLoopData = $contentDetails['blog']->take(3)->sortDesc(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-6 col-lg-4">
                        <a class="card-blog card wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.15s"
                           href="<?php echo e(route('blogDetails',[slug(@$data->description->title), $data->content_id])); ?>">
                            <div class="fig-container">
                                <img
                                    src="<?php echo e(getFile(config('location.content.path').'thumb_'.@$data->content->contentMedia->description->image)); ?>"
                                    alt="Image Missing">
                            </div>
                            <h5 class="h5 mt-5 mb-5"><?php echo e(\Illuminate\Support\Str::limit(@$data->description->title,40)); ?></h5>
                            <p class="text">
                                <?php echo app('translator')->get(\Illuminate\Support\Str::limit(strip_tags(@$data->description->description), 120)); ?>
                            </p>
                            <div class="date-wrapper colorbg-1">
                                <h4 class="font-weight-medium fontubonto"><?php echo e(dateTime(@$data->created_at,'d')); ?></h4>
                                <h4 class="font-weight-medium fontubonto"><?php echo e(dateTime(@$data->created_at,'M')); ?></h4>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
            <?php endif; ?>
    </div>
</section>
<!-- /BLOG -->

<?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/themes/deepblue/sections/blog.blade.php ENDPATH**/ ?>