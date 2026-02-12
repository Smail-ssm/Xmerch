

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-check-circle"></i> <?php echo e(__('Completed Jobs')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-printjob-index')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back to Dashboard')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-2">
                    <a href="<?php echo e(route('admin-printjob-queue')); ?>" class="btn btn-outline-warning btn-sm mr-2">
                        <i class="fas fa-clock"></i> <?php echo e(__('Queued')); ?>

                    </a>
                    <a href="<?php echo e(route('admin-printjob-printing')); ?>" class="btn btn-outline-info btn-sm mr-2">
                        <i class="fas fa-print"></i> <?php echo e(__('Printing')); ?>

                    </a>
                    <a href="<?php echo e(route('admin-printjob-completed')); ?>" class="btn btn-success btn-sm mr-2">
                        <i class="fas fa-check"></i> <?php echo e(__('Completed')); ?>

                    </a>
                    <a href="<?php echo e(route('admin-printjob-failed')); ?>" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-times"></i> <?php echo e(__('Failed')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-check text-success"></i> <?php echo e(__('Completed Jobs')); ?> (<?php echo e($jobs->total()); ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if($jobs->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('ID')); ?></th>
                                    <th><?php echo e(__('Order')); ?></th>
                                    <th><?php echo e(__('Product')); ?></th>
                                    <th><?php echo e(__('Qty')); ?></th>
                                    <th><?php echo e(__('Printer')); ?></th>
                                    <th><?php echo e(__('Completed')); ?></th>
                                    <th><?php echo e(__('Duration')); ?></th>
                                    <th><?php echo e(__('Notes')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($job->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin-order-show', $job->order_id)); ?>" target="_blank">
                                            #<?php echo e($job->order->order_number ?? 'N/A'); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($job->mockup_preview): ?>
                                            <img src="<?php echo e(asset('assets/images/products/' . $job->mockup_preview)); ?>" 
                                                 alt="" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                            <?php endif; ?>
                                            <span><?php echo e(Str::limit($job->product->name ?? 'N/A', 30)); ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-success"><?php echo e($job->quantity); ?></span></td>
                                    <td><?php echo e($job->printer->name ?? __('Unassigned')); ?></td>
                                    <td><?php echo e($job->completed_at ? $job->completed_at->format('M d, H:i') : '-'); ?></td>
                                    <td>
                                        <?php if($job->actual_time_minutes): ?>
                                            <span class="text-success"><?php echo e($job->actual_time_minutes); ?> <?php echo e(__('min')); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(Str::limit($job->notes, 30) ?? '-'); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin-printjob-show', $job->id)); ?>" class="btn btn-sm btn-info" title="<?php echo e(__('View')); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($jobs->links()); ?>

                    </div>
                    <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted"><?php echo e(__('No completed jobs yet')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views/admin/printjob/completed.blade.php ENDPATH**/ ?>