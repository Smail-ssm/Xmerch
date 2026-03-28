

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><i class="fas fa-chart-bar"></i> <?php echo e(__('Capacity Planning')); ?></h4>
                <ul class="links">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                    <li><a href="<?php echo e(route('admin-manufacturing-dashboard')); ?>"><?php echo e(__('Manufacturing')); ?></a></li>
                    <li><a href="#"><?php echo e(__('Capacity')); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <h5><i class="fas fa-cog"></i> <?php echo e(__('Product Capacity Management')); ?></h5>
                    <p class="text-muted"><?php echo e(__('Set daily production limits for each product')); ?></p>
                    
                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Product')); ?></th>
                                    <th><?php echo e(__('Daily Capacity')); ?></th>
                                    <th><?php echo e(__('Today\'s Orders')); ?></th>
                                    <th><?php echo e(__('Remaining')); ?></th>
                                    <th><?php echo e(__('Utilization')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $podProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><strong><?php echo e($product->name); ?></strong></td>
                                    <td><?php echo e($product->production_cap); ?> units/day</td>
                                    <td><?php echo e($product->daily_orders); ?></td>
                                    <td><?php echo e($product->remaining_capacity); ?></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar <?php echo e($product->utilization_percentage >= 100 ? 'bg-danger' : ($product->utilization_percentage >= 80 ? 'bg-warning' : 'bg-success')); ?>" 
                                                 style="width: <?php echo e(min(100, $product->utilization_percentage)); ?>%;">
                                                <?php echo e($product->utilization_percentage); ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin-prod-edit', $product->id)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Adjust
                                        </a>
                                    </td>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\manufacturing\capacity.blade.php ENDPATH**/ ?>