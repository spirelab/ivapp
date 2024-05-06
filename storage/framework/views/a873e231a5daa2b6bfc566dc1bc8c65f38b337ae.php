<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get($page_title); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="alert alert-warning my-5 m-0 m-md-4" role="alert">
        <i class="fas fa-info-circle mr-2"></i> <?php echo app('translator')->get("N.B: Pull up or down the rows to sort the ranking list order that how do you want to display the ranking in admin and user panel."); ?>
    </div>

    <div class="card card-primary m-0 m-md-4 my-4 m-md-0 shadow">
        <div class="card-body">
            <div class="media mb-4 justify-content-end">
                <?php if(adminAccessRoute(config('role.manage_plan.access.add'))): ?>
                    <a href="<?php echo e(route('admin.rankCreate')); ?>" class="btn btn-sm  btn-primary mr-2">
                        <span><i class="fas fa-plus"></i> <?php echo app('translator')->get('Add New'); ?></span>
                    </a>
                <?php endif; ?>
            </div>


            <div class="table-responsive">
                <table class="categories-show-table table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col"><?php echo app('translator')->get('Rank Name'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Rank Lavel'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Rank Icon'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Min Invest'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Min Deposit'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Min Earning'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Details'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Status'); ?></th>
                        <th scope="col"><?php echo app('translator')->get('Action'); ?></th>
                    </tr>
                    </thead>

                    <tbody id="sortable">
                    <?php $__empty_1 = true; $__currentLoopData = $allRankings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr data-id="<?php echo e($item->id); ?>">
                            <td data-label="<?php echo app('translator')->get('Rank Name'); ?>">
                                <?php echo app('translator')->get($item->rank_name); ?>
                            </td>
                            <td data-label="<?php echo app('translator')->get('Rank Level'); ?>">
                                <p class="font-weight-bold"><?php echo e($item->rank_lavel); ?></p>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Rank icon'); ?>">
                                <img src="<?php echo e(getFile(config('location.rank.path').$item->rank_icon)); ?>"
                                     alt="<?php echo app('translator')->get('not found'); ?>" width="60">
                            </td>

                            <td data-label="<?php echo app('translator')->get('Minimum Earning'); ?>">
                                <p class="font-weight-bold"><?php echo e($item->min_invest); ?> <?php echo e(config('basic.currency')); ?></p>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Minimum Earning'); ?>">
                                <p class="font-weight-bold"><?php echo e($item->min_deposit); ?> <?php echo e(config('basic.currency')); ?></p>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Minimum Earning'); ?>">
                                <p class="font-weight-bold"><?php echo e($item->min_earning); ?> <?php echo e(config('basic.currency')); ?></p>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Bonus'); ?>">
                                <p class="font-weight-bold"><?php echo app('translator')->get($item->description); ?></p>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Status'); ?>">

                                <span
                                    class="<?php echo e($item->status == 1 ? 'badge-success' : 'badge-danger'); ?>  badge badge-pill badge-rounded">
                                    <?php if($item->status == 1): ?>
                                        <?php echo app('translator')->get('Active'); ?>
                                    <?php else: ?>
                                        <?php echo app('translator')->get('Deactive'); ?>
                                    <?php endif; ?>
                                </span>
                            </td>

                            <td data-label="<?php echo app('translator')->get('Action'); ?>">
                                <div class="dropdown show">
                                    <a class="dropdown-toggle p-3" href="#" id="dropdownMenuLink" data-toggle="dropdown"
                                       aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="<?php echo e(route('admin.rankEdit',$item->id)); ?>">
                                            <i class="fa fa-edit text-warning pr-2"
                                               aria-hidden="true"></i> <?php echo app('translator')->get('Edit'); ?>
                                        </a>
                                        <a href="javascript:void(0)" class="dropdown-item notiflix-confirm"
                                           data-route="<?php echo e(route('admin.rankDelete',$item->id)); ?>"
                                           data-toggle="modal"
                                           data-target="#delete-modal">
                                            <i class="fa fa-trash text-warning pr-2"
                                               aria-hidden="true"></i> <?php echo app('translator')->get('Delete'); ?>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="100%" class="text-center"><?php echo app('translator')->get('No Data Found'); ?></td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="delete-modal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="primary-header-modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="primary-header-modalLabel"><?php echo app('translator')->get('Delete Confirmation'); ?>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-hidden="true">×
                    </button>
                </div>
                <div class="modal-body">
                    <p><?php echo app('translator')->get('Are you sure to delete this?'); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light"
                            data-dismiss="modal"><?php echo app('translator')->get('Close'); ?></button>
                    <form action="" method="post" class="deleteRoute">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('delete'); ?>
                        <button type="submit" class="btn btn-primary"><?php echo app('translator')->get('Yes'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-lib'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/admin/css/jquery-ui.min.css')); ?>">
    <link href="<?php echo e(asset('assets/admin/css/dataTables.bootstrap4.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('assets/global/js/jquery-ui.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/admin/js/datatable-basic.init.js')); ?>"></script>


    <?php if($errors->any()): ?>
        <?php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        ?>
        <script>
            "use strict";
            <?php $__currentLoopData = $errors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            Notiflix.Notify.Failure("<?php echo e(trans($error)); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>

    <script>
        'use strict'
        $('.notiflix-confirm').on('click', function () {
            var route = $(this).data('route');
            $('.deleteRoute').attr('action', route)
        })
    </script>



    <script>
        "use strict";
        $(document).ready(function () {
            $("#sortable").sortable({
                update: function (event, ui) {
                    var methods = [];
                    $('#sortable tr').each(function (key, val) {
                        let methodId = $(val).data('id');
                        methods.push(methodId);
                    });

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        'url': "<?php echo e(route('admin.sort.badges')); ?>",
                        'method': "POST",
                        'data': {
                            sort: methods
                        },
                        success: function (data) {
                            console.log(data);
                        }

                    });

                }
            });
            $("#sortable").disableSelection();
        });


    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /www/wwwroot/invest.xtake.com/resources/views/admin/rank/index.blade.php ENDPATH**/ ?>