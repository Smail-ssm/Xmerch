

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-industry"></i> <?php echo e(__('Manufacturing Order')); ?> #<?php echo e($order->order_number); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-manufacturing-queue')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back to Queue')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-box"></i> <?php echo e(__('Products to Manufacture')); ?></h5>
                </div>
                <div class="card-body">
                    <?php $__currentLoopData = $order->product_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded p-3 mb-3">
                        <div class="row">
                            <div class="col-md-2">
                                <?php if($item['product']->photo): ?>
                                <img src="<?php echo e(asset('assets/images/products/' . $item['product']->photo)); ?>" 
                                     class="img-fluid rounded" alt="<?php echo e($item['product']->name); ?>">
                                <?php else: ?>
                                <div class="bg-light text-center py-4 rounded">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-10">
                                <h5 class="mb-2"><?php echo e($item['product']->name); ?></h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0"><?php echo e(__('Quantity')); ?></label>
                                        <h4 class="text-primary mb-0"><?php echo e($item['qty']); ?></h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0"><?php echo e(__('Size')); ?></label>
                                        <h4 class="mb-0">
                                            <?php if($item['size']): ?>
                                            <span class="badge badge-info" style="font-size: 16px;"><?php echo e($item['size']); ?></span>
                                            <?php else: ?>
                                            <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0"><?php echo e(__('Color')); ?></label>
                                        <h4 class="mb-0">
                                            <?php if($item['color']): ?>
                                            <span class="badge" style="background: <?php echo e($item['color']); ?>; color: <?php echo e(in_array(strtolower($item['color']), ['white', 'yellow', 'beige', 'cream']) ? '#000' : '#fff'); ?>; font-size: 16px;">
                                                <?php echo e($item['color']); ?>

                                            </span>
                                            <?php else: ?>
                                            <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="text-muted mb-0"><?php echo e(__('Price')); ?></label>
                                        <h4 class="mb-0"><?php echo e($order->currency_sign); ?><?php echo e(number_format($item['price'], 2)); ?></h4>
                                    </div>
                                </div>

                                
                                <?php if(!empty($item['print_file_url'])): ?>
                                <div class="mt-3">
                                    <a href="<?php echo e($item['print_file_url']); ?>" 
                                       class="btn btn-primary" download>
                                        <i class="fas fa-download"></i> <?php echo e(__('Download Print File')); ?>

                                    </a>
                                </div>
                                <?php else: ?>
                                <div class="mt-3 alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle"></i> <?php echo e(__('No print file available for this product')); ?>

                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <?php if($order->order_note): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sticky-note"></i> <?php echo e(__('Order Notes')); ?></h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?php echo e($order->order_note); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="col-lg-4">
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> <?php echo e(__('Order Status')); ?></h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th><?php echo e(__('Status')); ?></th>
                            <td>
                                <span class="badge badge-warning"><?php echo e(__('In Manufacturing')); ?></span>
                            </td>
                        </tr>
                        <tr>
                            <th><?php echo e(__('Order Date')); ?></th>
                            <td><?php echo e($order->created_at->format('M d, Y H:i')); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo e(__('Total Items')); ?></th>
                            <td><?php echo e($order->totalQty); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo e(__('Order Total')); ?></th>
                            <td><strong><?php echo e($order->currency_sign); ?><?php echo e(number_format($order->pay_amount, 2)); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user"></i> <?php echo e(__('Customer Information')); ?></h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong><?php echo e($order->customer_name); ?></strong></p>
                    <p class="mb-1"><i class="fas fa-envelope"></i> <?php echo e($order->customer_email); ?></p>
                    <p class="mb-0"><i class="fas fa-phone"></i> <?php echo e($order->customer_phone); ?></p>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shipping-fast"></i> <?php echo e(__('Shipping Address')); ?></h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><?php echo e($order->shipping_name ?? $order->customer_name); ?></p>
                    <p class="mb-1"><?php echo e($order->shipping_address ?? $order->customer_address); ?></p>
                    <p class="mb-1"><?php echo e($order->shipping_city ?? $order->customer_city); ?>, <?php echo e($order->shipping_state ?? $order->customer_state); ?></p>
                    <p class="mb-1"><?php echo e($order->shipping_zip ?? $order->customer_zip); ?></p>
                    <p class="mb-0"><?php echo e($order->shipping_country ?? $order->customer_country); ?></p>
                </div>
            </div>

            
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('admin-manufacturing-mark-ready', $order->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="btn btn-success btn-block btn-lg" 
                            onclick="return confirm('<?php echo e(__('Mark this order as Print Ready and send to printer queue?')); ?>')">
                            <i class="fas fa-check-circle"></i> <?php echo e(__('Mark as Print Ready')); ?>

                        </button>
                    </form>
                    <small class="text-muted d-block text-center mt-2">
                        <?php echo e(__('This will send the order to the Printer Queue')); ?>

                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\manufacturing\show.blade.php ENDPATH**/ ?>