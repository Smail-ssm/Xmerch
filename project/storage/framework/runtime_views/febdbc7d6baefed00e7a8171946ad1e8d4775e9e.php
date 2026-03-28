<?php $__env->startSection('content'); ?>
                        <div class="content-area no-padding">
                            <div class="add-product-content1">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="table-responsive show-table">
                                                            <table class="table">
                                                                <tr>
                                                                    <th><?php echo e(__('Reviewer')); ?></th>
                                                                    <td><?php echo e($data->user->name); ?></td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?php echo e(__('Email')); ?>:</th>
                                                                    <td><?php echo e($data->user->email); ?></td>
                                                                </tr>
                                                                <?php if($data->user->phone != ""): ?>
                                                                <tr>
                                                                    <th><?php echo e(__('Phone')); ?>:</th>
                                                                    <td><?php echo e($data->user->phone); ?></td>
                                                                </tr>
                                                                <?php endif; ?>
                                                                <tr>
                                                                    <th><?php echo e(__('Rating')); ?>:</th>
                                                                    <td>
                                                                        <div class="ratings">
                                                                            <div class="empty-stars"></div>
                                                                            <div class="rating-wrap">
                                                                                <p><i class="fas fa-star text-yellow"></i><span> <?php echo e(App\Models\Rating::ratings($data->product->id)); ?>%</span></p>
                                                                             </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th><?php echo e(__('Reviewed at')); ?>:</th>
                                                                    <td><?php echo e(date('d-M-Y h:i:s',strtotime($data->review_date))); ?></td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <h5 class="review">
                                                        <?php echo e(__('Review')); ?>:
                                                        </h5>
                                                        <p class="review-text">
                                                            <?php echo e($data->review); ?>

                                                        </p>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\rating\show.blade.php ENDPATH**/ ?>