

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-print"></i> <?php echo e(__('Print Job Details')); ?> #<?php echo e($job->id); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-printjob-index')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back to Dashboard')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Job Information')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%"><?php echo e(__('Status')); ?></th>
                                    <td><?php echo $job->status_badge; ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Order')); ?></th>
                                    <td>
                                        <a href="<?php echo e(route('admin-order-show', $job->order_id)); ?>" target="_blank">
                                            #<?php echo e($job->order->order_number ?? 'N/A'); ?>

                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Product')); ?></th>
                                    <td><?php echo e($job->product->name ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Quantity')); ?></th>
                                    <td><span class="badge badge-primary"><?php echo e($job->quantity); ?></span></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Quality Tier')); ?></th>
                                    <td><?php echo e(ucfirst($job->quality_tier)); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%"><?php echo e(__('Priority')); ?></th>
                                    <td><?php echo e($job->priority_label); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Assigned To')); ?></th>
                                    <td><?php echo e($job->printer->name ?? __('Unassigned')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Created')); ?></th>
                                    <td><?php echo e($job->created_at->format('M d, Y H:i')); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Started')); ?></th>
                                    <td><?php echo e($job->started_at ? $job->started_at->format('M d, Y H:i') : '-'); ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo e(__('Completed')); ?></th>
                                    <td><?php echo e($job->completed_at ? $job->completed_at->format('M d, Y H:i') : '-'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <?php if($job->notes): ?>
                    <div class="alert alert-info">
                        <strong><?php echo e(__('Notes')); ?>:</strong> <?php echo e($job->notes); ?>

                    </div>
                    <?php endif; ?>

                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted"><?php echo e(__('Estimated Time')); ?></h6>
                                    <h3><?php echo e($job->estimated_time_minutes ?? 30); ?> <small><?php echo e(__('min')); ?></small></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted"><?php echo e(__('Actual Time')); ?></h6>
                                    <h3><?php echo e($job->actual_time_minutes ?? '-'); ?> <small><?php echo e($job->actual_time_minutes ? __('min') : ''); ?></small></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted"><?php echo e(__('Efficiency')); ?></h6>
                                    <?php if($job->actual_time_minutes && $job->estimated_time_minutes): ?>
                                        <?php
                                            $efficiency = round(($job->estimated_time_minutes / $job->actual_time_minutes) * 100);
                                        ?>
                                        <h3 class="<?php echo e($efficiency >= 100 ? 'text-success' : 'text-warning'); ?>">
                                            <?php echo e($efficiency); ?>%
                                        </h3>
                                    <?php else: ?>
                                        <h3>-</h3>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($job->order): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Customer Information')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><?php echo e(__('Name')); ?>:</strong> <?php echo e($job->order->customer_name); ?></p>
                            <p><strong><?php echo e(__('Email')); ?>:</strong> <?php echo e($job->order->customer_email); ?></p>
                            <p><strong><?php echo e(__('Phone')); ?>:</strong> <?php echo e($job->order->customer_phone); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><?php echo e(__('Shipping Address')); ?>:</strong></p>
                            <p>
                                <?php echo e($job->order->shipping_address); ?><br>
                                <?php echo e($job->order->shipping_city); ?>, <?php echo e($job->order->shipping_state); ?><br>
                                <?php echo e($job->order->shipping_country); ?> <?php echo e($job->order->shipping_zip); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="col-lg-4">
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Actions')); ?></h5>
                </div>
                <div class="card-body">
                    <?php if($job->status === 'queued'): ?>
                        <button class="btn btn-success btn-block mb-2 start-job" data-id="<?php echo e($job->id); ?>">
                            <i class="fas fa-play"></i> <?php echo e(__('Start Printing')); ?>

                        </button>
                        <button class="btn btn-secondary btn-block mb-2 hold-job" data-id="<?php echo e($job->id); ?>">
                            <i class="fas fa-pause"></i> <?php echo e(__('Put On Hold')); ?>

                        </button>
                    <?php elseif($job->status === 'printing'): ?>
                        <button class="btn btn-success btn-block mb-2 complete-job" data-id="<?php echo e($job->id); ?>">
                            <i class="fas fa-check"></i> <?php echo e(__('Mark Complete')); ?>

                        </button>
                        <button class="btn btn-danger btn-block mb-2 fail-job" data-id="<?php echo e($job->id); ?>">
                            <i class="fas fa-times"></i> <?php echo e(__('Mark Failed')); ?>

                        </button>
                    <?php elseif($job->status === 'on_hold'): ?>
                        <button class="btn btn-warning btn-block mb-2 resume-job" data-id="<?php echo e($job->id); ?>">
                            <i class="fas fa-play"></i> <?php echo e(__('Resume')); ?>

                        </button>
                    <?php elseif($job->status === 'failed'): ?>
                        <button class="btn btn-warning btn-block mb-2 resume-job" data-id="<?php echo e($job->id); ?>">
                            <i class="fas fa-redo"></i> <?php echo e(__('Retry Job')); ?>

                        </button>
                    <?php endif; ?>

                    <?php if($job->design_file): ?>
                    <a href="<?php echo e(asset('assets/files/designs/' . $job->design_file)); ?>" class="btn btn-primary btn-block mb-2" download>
                        <i class="fas fa-download"></i> <?php echo e(__('Download Print File')); ?>

                    </a>
                    <?php elseif($job->product && $job->product->print_file): ?>
                    <a href="<?php echo e(asset('assets/files/designs/' . $job->product->print_file)); ?>" class="btn btn-primary btn-block mb-2" download>
                        <i class="fas fa-download"></i> <?php echo e(__('Download Print File')); ?>

                    </a>
                    <?php else: ?>
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('No print file available')); ?>

                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><?php echo e(__('Product Preview')); ?></h5>
                </div>
                <div class="card-body text-center">
                    <?php if($job->mockup_preview): ?>
                        <img src="<?php echo e(asset('assets/images/products/' . $job->mockup_preview)); ?>" 
                             alt="Product Preview" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    <?php elseif($job->product && $job->product->photo): ?>
                        <img src="<?php echo e(asset('assets/images/products/' . $job->product->photo)); ?>" 
                             alt="Product" 
                             class="img-fluid rounded" 
                             style="max-height: 300px;">
                    <?php else: ?>
                        <div class="text-muted py-5">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p><?php echo e(__('No preview available')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Start job
    $(document).on('click', '.start-job', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?php echo e(url("admin/printjobs")); ?>/' + id + '/start',
            type: 'POST',
            data: {_token: '<?php echo e(csrf_token()); ?>'},
            success: function(response) {
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || 'An error occurred');
            }
        });
    });

    // Complete job
    $(document).on('click', '.complete-job', function() {
        var id = $(this).data('id');
        var notes = prompt('<?php echo e(__("Completion notes (optional):")); ?>');
        if (notes !== null) {
            $.ajax({
                url: '<?php echo e(url("admin/printjobs")); ?>/' + id + '/complete',
                type: 'POST',
                data: {_token: '<?php echo e(csrf_token()); ?>', notes: notes},
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Fail job
    $(document).on('click', '.fail-job', function() {
        var id = $(this).data('id');
        var reason = prompt('<?php echo e(__("Reason for failure:")); ?>');
        if (reason) {
            $.ajax({
                url: '<?php echo e(url("admin/printjobs")); ?>/' + id + '/fail',
                type: 'POST',
                data: {_token: '<?php echo e(csrf_token()); ?>', reason: reason},
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Hold job
    $(document).on('click', '.hold-job', function() {
        var id = $(this).data('id');
        var reason = prompt('<?php echo e(__("Reason for holding:")); ?>');
        if (reason) {
            $.ajax({
                url: '<?php echo e(url("admin/printjobs")); ?>/' + id + '/hold',
                type: 'POST',
                data: {_token: '<?php echo e(csrf_token()); ?>', reason: reason},
                success: function(response) {
                    toastr.success(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.error || 'An error occurred');
                }
            });
        }
    });

    // Resume job
    $(document).on('click', '.resume-job', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?php echo e(url("admin/printjobs")); ?>/' + id + '/resume',
            type: 'POST',
            data: {_token: '<?php echo e(csrf_token()); ?>'},
            success: function(response) {
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || 'An error occurred');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views/admin/printjob/show.blade.php ENDPATH**/ ?>