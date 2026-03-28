
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="mr-breadcrumb">
								<div class="row">
									<div class="col-lg-12">
											<h4 class="heading"><?php echo e(__('System Purchase Activation')); ?></h4>
											<ul class="links">
												<li>
													<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
												</li>
												<li>
													<a href="<?php echo e(route('admin-generate-backup')); ?>"><?php echo e(__('Generate BackUp')); ?> </a>
												</li>
											</ul>
									</div>
								</div>
							</div>
							<div class="add-product-content1 add-product-content2">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">

				                        <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                        						<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

												<div style="padding: 10px;" class="text-center">
													<?php if($bkuplink == ""): ?>
														<span id="bkupData"><?php echo e(__('No Backup File Generated.')); ?></span>
													<?php else: ?>
														<span id="bkupData"><a href="<?php echo e($bkuplink); ?>"><?php echo e($chk); ?></a><a href="<?php echo e(route('admin-clear-backup')); ?>"> <i class="fa fa-times-circle"></i></a></span>
													<?php endif; ?>
												</div>
												<hr/>
												<div class="add-product-footer text-center">
													<button name="addProduct_btn" id="generateBkup" type="button" class="addProductSubmit-btn"><?php echo e(__('Generate Backup')); ?></button>
												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
	<script type="text/javascript">
	
    (function($) {
		"use strict";

        $("#generateBkup").click(function(){
            $('#bkupData').html('<img style="height:100px;" src="<?php echo e(asset("assets/images/".$gs->loader)); ?>"><br><?php echo e(__('Generating Backup... Please wait....')); ?>');
            $.ajax({
                url: "<?php echo e(url('admin/check/movescript')); ?>",
                success: function(result){
                    if(result.status == 'success'){
                        $("#bkupData").html('<a href="'+result.backupfile+'">'+result.filename+'</a>');
                    }
                }
            });
        });


	})(jQuery);

	</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\movetoserver.blade.php ENDPATH**/ ?>