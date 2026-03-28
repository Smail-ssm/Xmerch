

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-box"></i> <?php echo e(__('Ready to Ship')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-printer-dashboard')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Dashboard')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <?php if($orders->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Order')); ?></th>
                                        <th><?php echo e(__('Customer')); ?></th>
                                        <th><?php echo e(__('Shipping Address')); ?></th>
                                        <th><?php echo e(__('Printed')); ?></th>
                                        <th><?php echo e(__('Actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong>#<?php echo e($order->order_number); ?></strong></td>
                                        <td><?php echo e($order->customer_name); ?></td>
                                        <td>
                                            <?php echo e($order->shipping_address ?? $order->customer_address); ?>,
                                            <?php echo e($order->shipping_city ?? $order->customer_city); ?>,
                                            <?php echo e($order->shipping_country ?? $order->customer_country); ?>

                                        </td>
                                        <td><?php echo e($order->printed_at ? $order->printed_at->diffForHumans() : '-'); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('admin-printer-show', $order->id)); ?>" class="btn btn-sm btn-info" title="View Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin-printer-label', $order->id)); ?>" target="_blank" class="btn btn-sm btn-dark" title="Print Label">
                                                    <i class="fas fa-barcode"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin-printer-shipped-action', $order->id)); ?>" method="POST" style="display:inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-primary" title="Mark Shipped">
                                                        <i class="fas fa-shipping-fast"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo e($orders->links()); ?>

                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-box-open" style="font-size:48px;color:#ddd;"></i>
                            <p class="text-muted mt-3"><?php echo e(__('No orders ready to ship')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\printer\ready-to-ship.blade.php ENDPATH**/ ?>