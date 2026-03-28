

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-calendar-alt"></i> <?php echo e(__('Production Schedule')); ?></h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <h5><?php echo e(__('Upcoming Production')); ?></h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Order')); ?></th>
                                    <th><?php echo e(__('Customer')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                   <th><?php echo e(__('Status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $upcomingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>#<?php echo e($order->order_number); ?></td>
                                    <td><?php echo e($order->customer_name); ?></td>
                                    <td><?php echo e($order->created_at->format('Y-m-d H:i')); ?></td>
                                    <td><span class="badge badge-warning"><?php echo e($order->print_status_label); ?></span></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\manufacturing\schedule.blade.php ENDPATH**/ ?>