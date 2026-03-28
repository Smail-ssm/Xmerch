

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-industry"></i> <?php echo e(__('Manufacturing Queue')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-manufacturing-dashboard')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back to Dashboard')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> <?php echo e(__('In Manufacturing')); ?></h5>
                    <h2 class="mb-0"><?php echo e($stats['in_manufacturing'] ?? 0); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> <?php echo e(__('Print Ready')); ?></h5>
                    <h2 class="mb-0"><?php echo e($stats['print_ready'] ?? 0); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="mb-0"><i class="fas fa-print"></i> <?php echo e(__('Now Printing')); ?></h5>
                    <h2 class="mb-0"><?php echo e($stats['printing'] ?? 0); ?></h2>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <input type="checkbox" id="select-all" class="mr-2">
                    <span><?php echo e(__('Select All')); ?></span>
                </div>
                <button class="btn btn-success" id="batch-ready" disabled>
                    <i class="fas fa-check"></i> <?php echo e(__('Mark Selected as Print Ready')); ?>

                </button>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><?php echo e(__('Orders in Manufacturing')); ?></h5>
        </div>
        <div class="card-body">
            <?php if($orders->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="40px"></th>
                            <th><?php echo e(__('Order #')); ?></th>
                            <th><?php echo e(__('Customer')); ?></th>
                            <th><?php echo e(__('Items')); ?></th>
                            <th><?php echo e(__('Ordered')); ?></th>
                            <th><?php echo e(__('Actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="order-checkbox" value="<?php echo e($order->id); ?>">
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin-manufacturing-show', $order->id)); ?>">
                                    <strong>#<?php echo e($order->order_number); ?></strong>
                                </a>
                            </td>
                            <td>
                                <div><?php echo e($order->customer_name); ?></div>
                                <small class="text-muted"><?php echo e($order->customer_email); ?></small>
                            </td>
                            <td>
                                
                                <?php $__currentLoopData = $order->cart_items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mb-2 p-2 bg-light rounded">
                                    <strong><?php echo e($item['item']['name'] ?? 'Product'); ?></strong>
                                    <div class="d-flex gap-2 mt-1">
                                        <span class="badge badge-primary"><?php echo e(__('Qty:')); ?> <?php echo e($item['qty'] ?? 1); ?></span>
                                        <?php if(isset($item['size'])): ?>
                                        <span class="badge badge-info"><?php echo e(__('Size:')); ?> <?php echo e($item['size']); ?></span>
                                        <?php endif; ?>
                                        <?php if(isset($item['color'])): ?>
                                        <span class="badge badge-secondary" style="background: <?php echo e($item['color']); ?>; color: <?php echo e(in_array(strtolower($item['color']), ['white', 'yellow', 'beige', 'cream']) ? '#000' : '#fff'); ?>">
                                            <?php echo e(__('Color:')); ?> <?php echo e($item['color']); ?>

                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <div><?php echo e($order->created_at->format('M d, Y')); ?></div>
                                <small class="text-muted"><?php echo e($order->created_at->diffForHumans()); ?></small>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin-manufacturing-show', $order->id)); ?>" class="btn btn-sm btn-info" title="<?php echo e(__('View Details')); ?>">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin-manufacturing-mark-ready', $order->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success" title="<?php echo e(__('Mark Print Ready')); ?>" onclick="return confirm('<?php echo e(__('Mark this order as Print Ready?')); ?>')">
                                            <i class="fas fa-check"></i> <?php echo e(__('Ready')); ?>

                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                <?php echo e($orders->links()); ?>

            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h4><?php echo e(__('Manufacturing Queue is Empty')); ?></h4>
                <p class="text-muted"><?php echo e(__('All orders have been processed and sent to printing.')); ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').on('change', function() {
        $('.order-checkbox').prop('checked', $(this).is(':checked'));
        updateBatchButton();
    });

    // Individual checkbox change
    $(document).on('change', '.order-checkbox', function() {
        updateBatchButton();
    });

    function updateBatchButton() {
        var selectedCount = $('.order-checkbox:checked').length;
        $('#batch-ready').prop('disabled', selectedCount === 0);
        if (selectedCount > 0) {
            $('#batch-ready').html('<i class="fas fa-check"></i> <?php echo e(__("Mark")); ?> ' + selectedCount + ' <?php echo e(__("as Print Ready")); ?>');
        } else {
            $('#batch-ready').html('<i class="fas fa-check"></i> <?php echo e(__("Mark Selected as Print Ready")); ?>');
        }
    }

    // Batch mark as ready
    $('#batch-ready').on('click', function() {
        var selectedIds = [];
        $('.order-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            return;
        }

        if (!confirm('<?php echo e(__("Mark")); ?> ' + selectedIds.length + ' <?php echo e(__("orders as Print Ready?")); ?>')) {
            return;
        }

        $.ajax({
            url: '<?php echo e(route("admin-manufacturing-batch-ready")); ?>',
            type: 'POST',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                order_ids: selectedIds
            },
            success: function(response) {
                toastr.success(response.message);
                location.reload();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || '<?php echo e(__("An error occurred")); ?>');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\manufacturing\queue.blade.php ENDPATH**/ ?>