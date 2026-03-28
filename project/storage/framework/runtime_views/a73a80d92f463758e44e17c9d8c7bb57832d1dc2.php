
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
                                                                <th><?php echo e(__('Reporter')); ?></th>
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
                                                                <th><?php echo e(__('Reported at')); ?>:</th>
                                                                <td><?php echo e(date('d-M-Y h:i:s',strtotime($data->created_at))); ?></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                                    <div class="col-lg-6">
                                                    <h5 class="comment">
                                                        <?php echo e(__('Title')); ?>:
                                                        </h5>
                                                        <p class="comment-text"> 
                                                            <?php echo e($data->title); ?>

                                                        </p>

                                                    <h5 class="comment">
                                                        <?php echo e(__('Note')); ?>:
                                                        </h5>
                                                        <p class="comment-text"> 
                                                            <?php echo e($data->note); ?>

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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\report\show.blade.php ENDPATH**/ ?>