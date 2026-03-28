

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">
                    <i class="fas fa-clock"></i> <?php echo e(__('Print Queue')); ?>

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
                    <a href="<?php echo e(route('admin-printjob-queue')); ?>" class="btn btn-warning btn-sm mr-2">
                        <i class="fas fa-clock"></i> <?php echo e(__('Queued')); ?>

                    </a>
                    <a href="<?php echo e(route('admin-printjob-printing')); ?>" class="btn btn-outline-info btn-sm mr-2">
                        <i class="fas fa-print"></i> <?php echo e(__('Printing')); ?>

                    </a>
                    <a href="<?php echo e(route('admin-printjob-completed')); ?>" class="btn btn-outline-success btn-sm mr-2">
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-clock text-warning"></i> <?php echo e(__('Queued Jobs')); ?> (<?php echo e($jobs->total()); ?>)</h5>
                    <div>
                        <button class="btn btn-sm btn-success" id="bulk-start" disabled>
                            <i class="fas fa-play"></i> <?php echo e(__('Start Selected')); ?>

                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($jobs->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th><?php echo e(__('ID')); ?></th>
                                    <th><?php echo e(__('Order')); ?></th>
                                    <th><?php echo e(__('Product')); ?></th>
                                    <th><?php echo e(__('Qty')); ?></th>
                                    <th><?php echo e(__('Quality')); ?></th>
                                    <th><?php echo e(__('Priority')); ?></th>
                                    <th><?php echo e(__('Est. Time')); ?></th>
                                    <th><?php echo e(__('Created')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><input type="checkbox" class="job-checkbox" value="<?php echo e($job->id); ?>"></td>
                                    <td><?php echo e($job->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin-order-show', $job->order_id)); ?>" target="_blank">
                                            #<?php echo e(optional($job->order)->order_number ?? 'N/A'); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($job->mockup_preview): ?>
                                            <img src="<?php echo e(asset('assets/images/products/' . $job->mockup_preview)); ?>" 
                                                 alt="" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 4px;">
                                            <?php endif; ?>
                                            <span><?php echo e(Str::limit(optional($job->product)->name ?? 'N/A', 30)); ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-primary"><?php echo e($job->quantity); ?></span></td>
                                    <td>
                                        <?php
                                            $qualityBadge = [
                                                'standard' => 'secondary',
                                                'premium' => 'info',
                                                'deluxe' => 'warning'
                                            ];
                                        ?>
                                        <span class="badge badge-<?php echo e($qualityBadge[$job->quality_tier] ?? 'secondary'); ?>">
                                            <?php echo e(ucfirst($job->quality_tier)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                            $priorityBadge = [1 => 'danger', 2 => 'warning', 3 => 'secondary'];
                                            $priorityLabel = [1 => 'High', 2 => 'Medium', 3 => 'Low'];
                                        ?>
                                        <span class="badge badge-<?php echo e($priorityBadge[$job->priority] ?? 'secondary'); ?>">
                                            <?php echo e($priorityLabel[$job->priority] ?? 'Normal'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($job->estimated_time_minutes ?? 30); ?> <?php echo e(__('min')); ?></td>
                                    <td><?php echo e($job->created_at->diffForHumans()); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?php echo e(route('admin-printjob-show', $job->id)); ?>" class="btn btn-info" title="<?php echo e(__('View')); ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-success start-job" data-id="<?php echo e($job->id); ?>" title="<?php echo e(__('Start')); ?>">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <button class="btn btn-secondary hold-job" data-id="<?php echo e($job->id); ?>" title="<?php echo e(__('Hold')); ?>">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </div>
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
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted"><?php echo e(__('No jobs in queue')); ?></p>
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
    // Select all
    $('#select-all').on('change', function() {
        $('.job-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkButtons();
    });

    $('.job-checkbox').on('change', function() {
        updateBulkButtons();
    });

    function updateBulkButtons() {
        var selected = $('.job-checkbox:checked').length;
        $('#bulk-start').prop('disabled', selected === 0);
    }

    // Start single job
    $(document).on('click', '.start-job', function() {
        var id = $(this).data('id');
        if (confirm('<?php echo e(__("Start printing this job?")); ?>')) {
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

    // Bulk start
    $('#bulk-start').on('click', function() {
        var ids = [];
        $('.job-checkbox:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length > 0 && confirm('<?php echo e(__("Start printing")); ?> ' + ids.length + ' <?php echo e(__("jobs?")); ?>')) {
            $.ajax({
                url: '<?php echo e(route("admin-printjob-bulk")); ?>',
                type: 'POST',
                data: {_token: '<?php echo e(csrf_token()); ?>', action: 'start', job_ids: ids},
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\printjob\queue.blade.php ENDPATH**/ ?>