

<?php $__env->startSection('content'); ?>

    <div class="content-area">

        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__("Install New Addon")); ?> <a class="add-btn" href="<?php echo e(route('admin-addon-index')); ?>"><?php echo e(__('Back')); ?></a> </h4>
                        <ul class="links">
                            <li>
                                <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin-addon-index')); ?>"><?php echo e(__("Manage Addons")); ?> </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin-addon-create')); ?>"><?php echo e(__("Install New Addon")); ?></a>
                            </li>
                        </ul>
                </div>
            </div>
        </div>
        
        <div class="add-product-content">
            <div class="row">
                <div class="col-lg-12 p-5">

                        <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        
                        <form  action="<?php echo e(route('admin-addon-install')); ?>" method="POST" enctype="multipart/form-data">
                        
                        <?php echo e(csrf_field()); ?>


                        <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <?php echo $__env->make('alerts.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <div class="row justify-content-center">

                                <div class="col-lg-12 d-flex justify-content-center text-center">
                                    <div class="csv-icon">
                                        <i class="fas fa-download"></i>
                                    </div>
                                </div>
                                
                                <div class="col-lg-12 d-flex justify-content-center text-center">
                                    <div class="left-area mr-4">
                                        <h4 class="heading"><?php echo e(__("Upload File")); ?> *</h4>
                                    </div>
                                    <span class="file-btn">
                                        <input type="file" id="file" name="file" accept=".zip" required>
                                    </span>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12 mt-4 text-center">
                                    <button class="mybtn1 mr-5" type="submit"><?php echo e(__("Install")); ?></button>
                                </div>
                            </div>
                            
                        </form>
                </div>
            </div>
        </div>
        
    </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\addon\create.blade.php ENDPATH**/ ?>