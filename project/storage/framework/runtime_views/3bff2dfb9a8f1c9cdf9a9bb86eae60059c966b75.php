

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Add Pricing Option')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-pod-pricing-index')); ?>">
                        <i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-8">
                <div class="product-description">
                    <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        
                        <form action="<?php echo e(route('admin-pod-pricing-store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label"><?php echo e(__('Category')); ?> *</label>
                                    <select name="category" class="form-control" required>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label"><?php echo e(__('Sort Order')); ?></label>
                                    <input type="number" name="sort_order" class="form-control" value="0">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label"><?php echo e(__('Display Name')); ?> *</label>
                                    <input type="text" name="name" class="form-control" required
                                           placeholder="e.g. Premium DTG Print">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label"><?php echo e(__('Value/Slug')); ?> *</label>
                                    <input type="text" name="value" class="form-control" required
                                           placeholder="e.g. premium_dtg">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <label class="form-label"><?php echo e(__('Price ($)')); ?> *</label>
                                    <input type="number" name="price" class="form-control" required
                                           step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label"><?php echo e(__('Status')); ?></label>
                                    <select name="is_active" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label"><?php echo e(__('Description')); ?></label>
                                    <textarea name="description" class="form-control" rows="2"
                                              placeholder="Brief description for designers"></textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <button type="submit" class="mybtn1">
                                        <i class="fas fa-save"></i> <?php echo e(__('Save Option')); ?>

                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\pod-pricing\create.blade.php ENDPATH**/ ?>