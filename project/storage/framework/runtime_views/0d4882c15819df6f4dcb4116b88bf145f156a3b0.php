

<?php $__env->startSection('styles'); ?>
<style>
    :root {
        --primary-color: #2563eb; /* Modern Blue */
        --success-color: #10b981; /* Modern Green */
        --warning-color: #f59e0b; /* A Modern Orange */
        --gray-50: #f9fafb;
        --gray-100: #f3f4f6;
        --gray-500: #6b7280;
        --gray-800: #1f2937;
    }

    .content-area {
        background-color: var(--gray-50);
        padding: 30px;
    }

    .card-clean {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        transition: box-shadow 0.2s;
        margin-bottom: 24px;
    }

    .card-clean:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    /* KPI Cards */
    .kpi-stat {
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .kpi-icon.blue { background: #eff6ff; color: var(--primary-color); }
    .kpi-icon.green { background: #ecfdf5; color: var(--success-color); }
    .kpi-icon.orange { background: #fffbeb; color: var(--warning-color); }
    .kpi-icon.purple { background: #f5f3ff; color: #8b5cf6; }

    .kpi-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-800);
        line-height: 1.2;
    }

    .kpi-label {
        color: var(--gray-500);
        font-size: 14px;
        font-weight: 500;
    }

    /* Filters */
    .filter-wrapper {
        background: #fff;
        padding: 16px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .custom-select-clean {
        background-color: #fff;
        border: 1px solid #d1d5db;
        color: #374151;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        margin-right: 12px;
    }

    .btn-clean-primary {
        background-color: var(--primary-color);
        color: #fff;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: none;
        transition: background-color 0.15s;
    }

    .btn-clean-primary:hover {
        background-color: #1d4ed8;
    }

    /* Table */
    .table-header {
        background-color: var(--gray-50);
        border-bottom: 1px solid #e5e7eb;
        padding: 16px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .table-responsive {
        padding: 0;
    }

    .table-clean {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-clean th {
        background-color: var(--gray-50);
        color: var(--gray-500);
        font-weight: 600;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 12px 24px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    .table-clean td {
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        vertical-align: middle;
        color: #4b5563;
    }

    .table-clean tr:last-child td {
        border-bottom: none;
    }

    .product-info {
        display: flex;
        align-items: center;
    }

    .product-image {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        margin-right: 16px;
        object-fit: cover;
    }

    .progress-bar-bg {
        width: 100px;
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: 999px;
    }

    /* Demand Grid */
    .demand-card {
        text-align: center;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        height: 100%;
    }

    .demand-img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        margin-bottom: 12px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1" style="font-weight: 700; color: #111827;"><?php echo e(__('Production Analytics')); ?></h3>
            <p class="mb-0 text-muted"><?php echo e(__('Overview for')); ?> <span style="font-weight: 600; color: #374151;"><?php echo e($periodInfo['month_name']); ?></span></p>
        </div>
        <div class="d-flex align-items-center">
            <a href="<?php echo e(route('admin-manufacturing-dashboard')); ?>" class="btn btn-outline-secondary btn-sm mr-2">
                <i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?>

            </a>
        </div>
    </div>

    
    <div class="filter-wrapper">
        <div class="d-flex align-items-center">
            <span class="mr-3" style="font-weight: 600; color: #374151;"><?php echo e(__('Report Period:')); ?></span>
            <form action="<?php echo e(route('admin-manufacturing-analytics')); ?>" method="GET" class="d-flex align-items-center">
                <select name="month" class="custom-select-clean">
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?php echo e($m); ?>" <?php echo e($periodInfo['month_num'] == $m ? 'selected' : ''); ?>>
                            <?php echo e(\Carbon\Carbon::create(null, $m, 1)->translatedFormat('F')); ?>

                        </option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="custom-select-clean">
                    <?php for($y = date('Y') - 2; $y <= date('Y') + 2; $y++): ?>
                        <option value="<?php echo e($y); ?>" <?php echo e($periodInfo['year'] == $y ? 'selected' : ''); ?>>
                            <?php echo e($y); ?>

                        </option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn-clean-primary">
                    <?php echo e(__('Update Report')); ?>

                </button>
            </form>
        </div>
        <div class="text-right">
             <button class="btn btn-sm btn-light border" onclick="window.print()">
                <i class="fas fa-print mr-1"></i> <?php echo e(__('Print')); ?>

            </button>
        </div>
    </div>

    
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card-clean kpi-stat">
                <div>
                    <div class="kpi-label"><?php echo e(__('Total Runs')); ?></div>
                    <div class="kpi-value"><?php echo e(number_format($periodInfo['total_orders'])); ?></div>
                </div>
                <div class="kpi-icon blue">
                    <i class="fas fa-clipboard-list"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card-clean kpi-stat">
                <div>
                    <div class="kpi-label"><?php echo e(__('Units Produced')); ?></div>
                    <div class="kpi-value"><?php echo e(number_format($periodInfo['total_units'])); ?></div>
                </div>
                <div class="kpi-icon green">
                    <i class="fas fa-industry"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card-clean kpi-stat">
                <div>
                    <div class="kpi-label"><?php echo e(__('Product Types')); ?></div>
                    <div class="kpi-value"><?php echo e(count($manufacturedProducts)); ?></div>
                </div>
                <div class="kpi-icon orange">
                    <i class="fas fa-boxes"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card-clean kpi-stat">
                <div>
                    <div class="kpi-label"><?php echo e(__('Performance')); ?></div>
                    <div class="kpi-value">
                        <?php echo e($periodInfo['total_units'] > 0 ? round($periodInfo['total_units'] / max(1, $periodInfo['total_orders']), 1) : 0); ?>

                        <span style="font-size: 14px; color: #9ca3af; font-weight: 400;">u/run</span>
                    </div>
                </div>
                <div class="kpi-icon purple">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card-clean">
        <div class="table-header">
            <h5 class="mb-0" style="font-weight: 600; color: #111827;"><?php echo e(__('Manufacturing Details')); ?></h5>
            <small class="text-muted"><?php echo e(__('Breakdown by Product')); ?></small>
        </div>
        <div class="table-responsive">
            <table class="table-clean">
                <thead>
                    <tr>
                        <th width="40%"><?php echo e(__('Product')); ?></th>
                        <th width="15%" class="text-center"><?php echo e(__('Volume')); ?></th>
                        <th width="20%"><?php echo e(__('Share')); ?></th>
                        <th width="15%" class="text-center"><?php echo e(__('Last Active')); ?></th>
                        <th width="10%" class="text-right"><?php echo e(__('Batches')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $manufacturedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="<?php echo e(asset('assets/images/products/'.$data['photo'])); ?>" class="product-image" alt="">
                                <div>
                                    <div style="font-weight: 600; color: #111827;"><?php echo e($data['name']); ?></div>
                                    <span style="font-size: 12px; color: #9ca3af;">ID: #<?php echo e($data['id']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span style="font-weight: 700; color: #1f2937;"><?php echo e($data['total_qty']); ?></span>
                            <span style="font-size: 12px; color: #9ca3af;">units</span>
                        </td>
                        <td>
                            <?php 
                                $maxQty = max(array_column($manufacturedProducts, 'total_qty'));
                                $percent = $maxQty > 0 ? ($data['total_qty'] / $maxQty) * 100 : 0;
                            ?>
                            <div class="d-flex align-items-center">
                                <div class="progress-bar-bg mr-2">
                                    <div class="progress-bar-fill" style="width: <?php echo e($percent); ?>%;"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div style="font-size: 14px;"><?php echo e($data['last_produced']->format('M d')); ?></div>
                            <small class="text-muted"><?php echo e($data['last_produced']->format('H:i')); ?></small>
                        </td>
                        <td class="text-right">
                             <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted" type="button" data-toggle="dropdown">
                                    <?php echo e(count($data['orders'])); ?> <?php echo e(__('Runs')); ?> <i class="fas fa-chevron-down ml-1" style="font-size: 10px;"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="padding: 0; border-radius: 8px; overflow: hidden;">
                                    <div class="bg-light px-3 py-2 border-bottom">
                                        <small class="font-weight-bold text-muted"><?php echo e(__('Recent Batches')); ?></small>
                                    </div>
                                    <div style="max-height: 200px; overflow-y: auto;">
                                        <?php $__currentLoopData = $data['orders']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderRun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="d-flex justify-content-between px-3 py-2 border-bottom-light">
                                            <span>#<?php echo e($orderRun['order_number']); ?></span>
                                            <span class="font-weight-bold text-primary">+<?php echo e($orderRun['qty']); ?></span>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="fas fa-inbox fa-2x mb-3 text-gray-300"></i>
                                <p><?php echo e(__('No manufacturing data found for this period.')); ?></p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <?php if(count($topProducts) > 0): ?>
    <div class="mt-5">
        <h5 class="mb-4" style="font-weight: 600; color: #374151;"><?php echo e(__('Top Selling Products')); ?> <small class="text-muted">(Last 30 Days)</small></h5>
        <div class="row">
            <?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-2 col-lg-3 col-md-4 mb-4">
                <div class="demand-card">
                    <img src="<?php echo e(asset('assets/images/products/'.$product->photo)); ?>" class="demand-img" alt="">
                    <h6 class="text-truncate mb-2" style="font-size: 14px; font-weight: 600;"><?php echo e($product->name); ?></h6>
                    <span class="badge badge-light border">
                        <?php echo e($product->orders_count); ?> <?php echo e(__('Sold')); ?>

                    </span>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\manufacturing\analytics.blade.php ENDPATH**/ ?>