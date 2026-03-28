

<?php $__env->startSection('styles'); ?>
<style>
.print-job-container {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 25px;
}

.design-preview {
    background: #2d2d2d;
    border-radius: 16px;
    padding: 30px;
    text-align: center;
}
.design-preview img {
    max-width: 100%;
    max-height: 500px;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
}
.design-placeholder {
    background: #444;
    padding: 100px 50px;
    border-radius: 8px;
    color: #888;
}
.design-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    justify-content: center;
}
.design-actions a, .design-actions button {
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.btn-download { background: #4facfe; color: #fff; }
.btn-print { background: #11998e; color: #fff; }

.order-info-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}
.order-info-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    padding: 20px;
}
.order-info-header h3 {
    margin: 0;
    font-size: 20px;
}
.order-info-header .status {
    display: inline-block;
    padding: 5px 15px;
    border-radius: 20px;
    font-size: 12px;
    margin-top: 10px;
}
.status.pending { background: rgba(255,255,255,0.2); }
.status.printing { background: #4facfe; }
.status.printed { background: #11998e; }

.order-info-body {
    padding: 20px;
}
.info-section {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}
.info-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}
.info-section h5 {
    font-size: 14px;
    color: #999;
    margin-bottom: 10px;
    text-transform: uppercase;
}
.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}
.info-row label { color: #666; }
.info-row span { font-weight: 600; }

.product-list {
    max-height: 300px;
    overflow-y: auto;
}
.product-item {
    display: flex;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}
.product-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
}
.product-details h6 {
    margin: 0 0 5px;
    font-size: 14px;
}
.product-details p {
    margin: 0;
    font-size: 12px;
    color: #666;
}

.action-buttons {
    padding: 20px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
}
.action-buttons a, .action-buttons button {
    flex: 1;
    padding: 15px;
    text-align: center;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}
.action-buttons a:hover, .action-buttons button:hover {
    transform: translateY(-2px);
}
.btn-start { background: #4facfe; color: #fff; }
.btn-done { background: #11998e; color: #fff; }
.btn-ship { background: #667eea; color: #fff; }

/* Print Specification Box */
.print-specs {
    background: #fff3cd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}
.print-specs h5 {
    margin: 0 0 10px;
    color: #856404;
}
.spec-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.spec-item {
    background: rgba(255,255,255,0.5);
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 13px;
}
.spec-item label {
    display: block;
    font-size: 11px;
    color: #856404;
}
.spec-item span {
    font-weight: bold;
    color: #333;
}

@media (max-width: 991px) {
    .print-job-container {
        grid-template-columns: 1fr;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-print"></i> <?php echo e(__('Print Job')); ?> #<?php echo e($order->order_number); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-printer-queue')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back to Queue')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <div class="print-job-container">
            
            <div>
                <div class="design-preview">
                    <?php if($order->print_file): ?>
                        <img src="<?php echo e($order->print_file); ?>" alt="Design">
                    <?php else: ?>
                        <div class="design-placeholder">
                            <i class="fas fa-image" style="font-size:48px;display:block;margin-bottom:15px;"></i>
                            <p>No high-res print file found</p>
                            <small>The manufacturing file is missing for this product/order.</small>
                        </div>
                    <?php endif; ?>
                    
                    <div class="design-actions">
                        <?php if($order->print_file): ?>
                        <a href="<?php echo e($order->print_file); ?>" download class="btn-download">
                            <i class="fas fa-download"></i> Download Print File
                        </a>
                        <?php endif; ?>
                        <button onclick="window.print()" class="btn-print">
                            <i class="fas fa-print"></i> Print Work Order
                        </button>
                    </div>
                </div>
                
                
                <div class="print-specs mt-4">
                    <h5><i class="fas fa-cog"></i> Print Specifications</h5>
                    <div class="spec-grid">
                        <div class="spec-item">
                            <label>Print Method</label>
                            <span>DTG (Direct to Garment)</span>
                        </div>
                        <div class="spec-item">
                            <label>Size</label>
                            <?php
                                $firstItem = isset($cart['items']) ? reset($cart['items']) : null;
                                $size = $firstItem['size'] ?? 'Standard';
                            ?>
                            <span><?php echo e($size); ?></span>
                        </div>
                        <div class="spec-item">
                            <label>Color</label>
                            <?php
                                $color = $firstItem['color'] ?? 'N/A';
                            ?>
                            <span><?php echo e($color); ?></span>
                        </div>
                        <div class="spec-item">
                            <label>Quantity</label>
                            <span><?php echo e($order->totalQty); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="order-info-card">
                <div class="order-info-header">
                    <h3>Order #<?php echo e($order->order_number); ?></h3>
                    <span class="status <?php echo e(str_replace('_', '-', $order->print_status)); ?>">
                        <?php echo e($order->print_status_label); ?>

                    </span>
                </div>
                
                <div class="order-info-body">
                    
                    <div class="info-section">
                        <h5><i class="fas fa-clock"></i> Timeline</h5>
                        <div class="info-row">
                            <label>Ordered</label>
                            <span><?php echo e($order->created_at->format('M d, Y H:i')); ?></span>
                        </div>
                        <?php if($order->printed_at): ?>
                        <div class="info-row">
                            <label>Printed</label>
                            <span><?php echo e($order->printed_at->format('M d, Y H:i')); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($order->shipped_at): ?>
                        <div class="info-row">
                            <label>Shipped</label>
                            <span><?php echo e($order->shipped_at->format('M d, Y H:i')); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    
                    <div class="info-section">
                        <h5><i class="fas fa-map-marker-alt"></i> Ship To</h5>
                        <p style="margin:0;line-height:1.6;">
                            <strong><?php echo e($order->shipping_name ?? $order->customer_name); ?></strong><br>
                            <?php echo e($order->shipping_address ?? $order->customer_address); ?><br>
                            <?php echo e($order->shipping_city ?? $order->customer_city); ?>, <?php echo e($order->shipping_zip ?? $order->customer_zip); ?><br>
                            <?php echo e($order->shipping_country ?? $order->customer_country); ?><br>
                            <i class="fas fa-phone"></i> <?php echo e($order->shipping_phone ?? $order->customer_phone); ?>

                        </p>
                    </div>

                    
                    <div class="info-section">
                        <h5><i class="fas fa-box"></i> Products (<?php echo e($order->totalQty); ?>)</h5>
                        <div class="product-list">
                            <?php if(isset($cart['items'])): ?>
                            <?php $__currentLoopData = $cart['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="product-item">
                                <?php
                                    $photo = isset($item['item']['photo']) ? $item['item']['photo'] : 'placeholder.jpg';
                                ?>
                                <img src="<?php echo e(asset('assets/images/products/' . $photo)); ?>" alt="">
                                <div class="product-details">
                                    <h6><?php echo e($item['item']['name'] ?? 'Product'); ?></h6>
                                    <p>Qty: <?php echo e($item['qty'] ?? 1); ?></p>
                                    <?php if(isset($item['size'])): ?>
                                    <p>Size: <?php echo e($item['size']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="action-buttons">
                    <?php if(in_array($order->print_status, ['pending_print', 'print_ready'])): ?>
                    <form action="<?php echo e(route('admin-printer-start', $order->id)); ?>" method="POST" style="width:100%;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-start" style="width:100%;">
                            <i class="fas fa-play"></i> Start Printing
                        </button>
                    </form>
                    <?php elseif($order->print_status == 'manufacturing'): ?>
                    <button type="button" class="btn-start" style="width:100%; opacity:0.7;" disabled>
                        <i class="fas fa-cogs"></i> In Manufacturing
                    </button>
                    <?php elseif($order->print_status == 'printing'): ?>
                    <form action="<?php echo e(route('admin-printer-printed', $order->id)); ?>" method="POST" style="width:100%;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-done" style="width:100%;">
                            <i class="fas fa-check"></i> Mark as Printed
                        </button>
                    </form>
                    <?php elseif($order->print_status == 'printed'): ?>
                    <div style="display:flex; flex-direction:column; gap:10px; width:100%;">
                        <a href="<?php echo e(route('admin-printer-label', $order->id)); ?>" target="_blank" class="btn-print" style="background:#000; color:#fff; text-align:center;">
                            <i class="fas fa-barcode"></i> Print Shipping Label
                        </a>
                        <form action="<?php echo e(route('admin-printer-shipped-action', $order->id)); ?>" method="POST" style="width:100%;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-ship" style="width:100%;">
                                <i class="fas fa-shipping-fast"></i> Mark as Shipped
                            </button>
                        </form>
                    </div>
                    <?php else: ?>
                    <span style="flex:1;text-align:center;color:#11998e;">
                        <i class="fas fa-check-circle"></i> Order Completed
                    </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\printer\show.blade.php ENDPATH**/ ?>