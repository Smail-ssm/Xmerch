

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('POD Pricing Options')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-pod-pricing-create')); ?>">
                        <i class="fas fa-plus"></i> <?php echo e(__('Add New')); ?>

                    </a>
                </h4>
                <ul class="links">
                    <li><a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                    <li><a href="javascript:;"><?php echo e(__('POD Settings')); ?></a></li>
                    <li><a href="<?php echo e(route('admin-pod-pricing-index')); ?>"><?php echo e(__('Pricing Options')); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catKey => $catName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-4" style="border-left: 4px solid #007bff;">
                            <div class="card-header" style="background: #f8f9fa;">
                                <h5 style="margin: 0;"><i class="fas fa-tag"></i> <?php echo e($catName); ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if(isset($options[$catKey]) && count($options[$catKey]) > 0): ?>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Name')); ?></th>
                                            <th><?php echo e(__('Value')); ?></th>
                                            <th><?php echo e(__('Price')); ?></th>
                                            <th><?php echo e(__('Order')); ?></th>
                                            <th><?php echo e(__('Status')); ?></th>
                                            <th class="text-right"><?php echo e(__('Actions')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $options[$catKey]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($option->name); ?></td>
                                            <td><code><?php echo e($option->value); ?></code></td>
                                            <td><strong>$<?php echo e(number_format($option->price, 2)); ?></strong></td>
                                            <td><?php echo e($option->sort_order); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('admin-pod-pricing-status', $option->id)); ?>" 
                                                   class="badge badge-<?php echo e($option->is_active ? 'success' : 'danger'); ?>">
                                                    <?php echo e($option->is_active ? 'Active' : 'Inactive'); ?>

                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo e(route('admin-pod-pricing-edit', $option->id)); ?>" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo e(route('admin-pod-pricing-delete', $option->id)); ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Delete this option?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <?php else: ?>
                                <div class="alert alert-info">
                                    No options in this category yet. 
                                    <a href="<?php echo e(route('admin-pod-pricing-create')); ?>">Add one now</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\pod-pricing\index.blade.php ENDPATH**/ ?>