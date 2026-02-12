

<?php $__env->startSection('styles'); ?>
<style>
.queue-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.batch-actions {
    display: none;
    gap: 10px;
}
.batch-actions.show { display: flex; }

.order-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-left: 4px solid #f5576c;
    transition: all 0.3s;
}
.order-card:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.order-card.selected {
    border-left-color: #4facfe;
    background: #f0f7ff;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}
.order-number {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}
.order-date {
    color: #999;
    font-size: 13px;
}

.order-items {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.order-item {
    display: flex;
    gap: 10px;
    align-items: center;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    flex: 1;
    min-width: 200px;
}
.order-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}
.item-info h6 {
    margin: 0 0 5px;
    font-size: 14px;
}
.item-info p {
    margin: 0;
    font-size: 12px;
    color: #666;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    border-top: 1px solid #eee;
}
.customer-info {
    font-size: 13px;
    color: #666;
}
.customer-info i { margin-right: 5px; }

.action-btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}
.action-btn.primary { background: #4facfe; color: #fff; }
.action-btn.success { background: #11998e; color: #fff; }
.action-btn.outline { background: transparent; border: 2px solid #ddd; color: #666; }
.action-btn:hover { transform: translateY(-2px); }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-clock"></i> <?php echo e(__('Print Queue')); ?>

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
                        
                        <div class="queue-header">
                            <div>
                                <strong><?php echo e($orders->total()); ?></strong> orders waiting to print
                            </div>
                            <div class="batch-actions" id="batch-actions">
                                <form action="<?php echo e(route('admin-printer-batch-start')); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="order_ids" id="selected-ids">
                                    <button type="submit" class="action-btn primary">
                                        <i class="fas fa-play"></i> Start Selected
                                    </button>
                                </form>
                            </div>
                            <label style="cursor:pointer;">
                                <input type="checkbox" id="select-all"> Select All
                            </label>
                        </div>

                        <?php if($orders->count() > 0): ?>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $cart = json_decode($order->cart, true);
                                $items = $cart['items'] ?? [];
                                $isEligible = $order->isEligibleForProduction();
                            ?>
                            <div class="order-card <?php echo e($isEligible ? 'eligible' : ''); ?>" data-id="<?php echo e($order->id); ?>" style="<?php echo e($isEligible ? 'border-left-color: #38ef7d; background: #f0fff4;' : ''); ?>">
                                <div class="order-header">
                                    <div>
                                        <input type="checkbox" class="order-checkbox" value="<?php echo e($order->id); ?>">
                                        <span class="order-number">#<?php echo e($order->order_number); ?></span>
                                        <?php if($isEligible): ?>
                                            <span class="badge badge-success ml-2"><i class="fas fa-rocket"></i> READY</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="order-date">
                                        <i class="fas fa-clock"></i> <?php echo e($order->created_at->diffForHumans()); ?>

                                    </div>
                                </div>

                                <div class="order-items">
                                    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="order-item">
                                        <?php
                                            $photo = isset($item['item']['photo']) ? $item['item']['photo'] : 'placeholder.jpg';
                                        ?>
                                        <img src="<?php echo e(asset('assets/images/products/' . $photo)); ?>" alt="">
                                        <div class="item-info">
                                            <h6><?php echo e($item['item']['name'] ?? 'Product'); ?></h6>
                                            <p>Qty: <?php echo e($item['qty'] ?? 1); ?></p>
                                            <?php if(isset($item['size'])): ?>
                                            <p>Size: <?php echo e($item['size']); ?></p>
                                            <?php endif; ?>
                                            <?php if(isset($item['color'])): ?>
                                            <p>Color: <?php echo e($item['color']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                <div class="order-footer">
                                    <div class="customer-info">
                                        <i class="fas fa-user"></i> <?php echo e($order->customer_name); ?>

                                        <span style="margin-left:15px;">
                                            <i class="fas fa-map-marker-alt"></i> <?php echo e($order->customer_city); ?>, <?php echo e($order->customer_country); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('admin-printer-show', $order->id)); ?>" class="action-btn outline">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <a href="<?php echo e(route('admin-printer-start', $order->id)); ?>" class="action-btn primary">
                                            <i class="fas fa-play"></i> Start Printing
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            <?php echo e($orders->links()); ?>

                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle" style="font-size:64px;color:#11998e;"></i>
                            <h4 class="mt-3"><?php echo e(__('All caught up!')); ?></h4>
                            <p class="text-muted"><?php echo e(__('No orders waiting to print.')); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
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
        $('.order-checkbox').prop('checked', $(this).is(':checked'));
        updateBatchActions();
    });
    
    // Individual checkbox
    $('.order-checkbox').on('change', function() {
        updateBatchActions();
        $(this).closest('.order-card').toggleClass('selected', $(this).is(':checked'));
    });
    
    function updateBatchActions() {
        var selected = $('.order-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        $('#selected-ids').val(JSON.stringify(selected));
        $('#batch-actions').toggleClass('show', selected.length > 0);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views/admin/printer/queue.blade.php ENDPATH**/ ?>