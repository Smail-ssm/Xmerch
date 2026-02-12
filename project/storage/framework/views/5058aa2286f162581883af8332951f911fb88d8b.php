

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-shipping-fast"></i> <?php echo e(__('Shipped Orders')); ?>

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
                        
                        <?php if($orders->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Order')); ?></th>
                                        <th><?php echo e(__('Customer')); ?></th>
                                        <th><?php echo e(__('Destination')); ?></th>
                                        <th><?php echo e(__('Shipped')); ?></th>
                                        <th><?php echo e(__('Actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><strong>#<?php echo e($order->order_number); ?></strong></td>
                                        <td><?php echo e($order->customer_name); ?></td>
                                        <td><?php echo e($order->shipping_city ?? $order->customer_city); ?>, <?php echo e($order->shipping_country ?? $order->customer_country); ?></td>
                                        <td>
                                            <?php if($order->shipped_at): ?>
                                            <?php echo e($order->shipped_at->format('M d, Y H:i')); ?>

                                            <?php else: ?>
                                            -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin-printer-show', $order->id)); ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <?php echo e($orders->links()); ?>

                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-truck" style="font-size:48px;color:#ddd;"></i>
                            <p class="text-muted mt-3"><?php echo e(__('No shipped orders yet')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views/admin/printer/shipped.blade.php ENDPATH**/ ?>