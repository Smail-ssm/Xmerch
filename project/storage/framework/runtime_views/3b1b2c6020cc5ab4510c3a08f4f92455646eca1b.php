

<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('assets/admin/css/jquery-ui.css')); ?>" rel="stylesheet" type="text/css">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="social-links-area">
							<div class="add-product-content">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">
											<?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
<form id="geniusformdata" action="<?php echo e(route('admin-prod-feature-submit',$data->id)); ?>" method="POST" enctype="multipart/form-data">
												<?php echo e(csrf_field()); ?>



												<div class="row">
														<div class="col-lg-8">
															<div class="left-area">
																	<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('Flash Deal')); ?> *</h4>
															</div>
														</div>
														  <div class="col-sm-3">
															<label class="switch">
															  <input type="checkbox" name="is_discount" id="is_discount" value="1" <?php echo e($data->is_discount == 1 ? "checked":""); ?>>
															  <span class="slider round"></span>
															</label>
														  </div>
													</div>
	
													<div class="<?php echo e($data->is_discount == 0 ? "showbox":""); ?>">
	
														<div class="row">
															<div class="col-lg-3">
																<div class="left-area">
																		<h4 class="heading"><?php echo e(__("Discount Date")); ?> *</h4>
																</div>
															</div>
															<div class="col-sm-8">
																<input type="text" class="input-field" name="discount_date"  placeholder="<?php echo e(__("Enter Date")); ?>" autocomplete="off" id="discount_date" value="<?php echo e($data->discount_date); ?>">
	
															</div>
														</div>
													</div>

													<div class="row">
														<div class="col-lg-8">
															<div class="left-area">
																<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('Hot')); ?> *</h4>
															</div>
														</div>
														<div class="col-sm-3">
															<label class="switch">
																<input type="checkbox" name="hot" value="1" <?php echo e($data->hot == 1 ? "checked":""); ?>>
																<span class="slider round"></span>
															</label>
														</div>
													</div>
		
														<div class="row">
															<div class="col-lg-8">
																<div class="left-area">
																	<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('New')); ?> *</h4>
																</div>
															</div>
															<div class="col-sm-3">
																<label class="switch">
																  <input type="checkbox" name="latest" value="1" <?php echo e($data->latest == 1 ? "checked":""); ?>>
																  <span class="slider round"></span>
																</label>
															</div>
														</div>
		
														<div class="row">
															<div class="col-lg-8">
																<div class="left-area">
																	<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('Sale')); ?> *</h4>
																</div>
															</div>
															<div class="col-sm-3">
																<label class="switch">
																	<input type="checkbox" name="sale" value="1" <?php echo e($data->sale == 1 ? "checked":""); ?>>
																	<span class="slider round"></span>
																</label>
															</div>
														</div>

														<div class="row">
															<div class="col-lg-8">
																<div class="left-area">
																	<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('Best Seller')); ?> *</h4>
																</div>
															</div>
															  <div class="col-sm-3">
																<label class="switch">
																  <input type="checkbox" name="best" value="1" <?php echo e($data->best == 1 ? "checked":""); ?>>
																  <span class="slider round"></span>
																</label>
															  </div>
														</div>



												<div class="row">
													<div class="col-lg-8">
														<div class="left-area">
															<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('Featured')); ?> *</h4>
														</div>
													</div>
									                  <div class="col-sm-3">
									                    <label class="switch">
									                      <input type="checkbox" name="featured" value="1" <?php echo e($data->featured == 1 ?"checked":""); ?>>
									                      <span class="slider round"></span>
									                    </label>
									                  </div>
												</div>



												<div class="row">
													<div class="col-lg-8">
														<div class="left-area">
															<h4 class="heading"><?php echo e(__("Highlight in")); ?>  <?php echo e(__('Top Rated')); ?> *</h4>
														</div>
													</div>
									                  <div class="col-sm-3">
									                    <label class="switch">
									                      <input type="checkbox" name="top" value="1" <?php echo e($data->top == 1 ? "checked":""); ?>>
									                      <span class="slider round"></span>
									                    </label>
									                  </div>
												</div>

												<div class="row">
													<div class="col-lg-8">
														<div class="left-area">
															<h4 class="heading"><?php echo e(__("Highlight in")); ?>  <?php echo e(__('Big Save')); ?> *</h4>
														</div>
													</div>
									                  <div class="col-sm-3">
									                    <label class="switch">
									                      <input type="checkbox" name="big" value="1" <?php echo e($data->big == 1 ? "checked":""); ?>>
									                      <span class="slider round"></span>
									                    </label>
									                  </div>
												</div>

												<div class="row">
													<div class="col-lg-8">
														<div class="left-area">
															<h4 class="heading"><?php echo e(__("Highlight in")); ?> <?php echo e(__('Trending')); ?> *</h4>
														</div>
													</div>
									                  <div class="col-sm-3">
									                    <label class="switch">
									                      <input type="checkbox" name="trending" value="1" <?php echo e($data->trending == 1 ?"checked":""); ?>>
									                      <span class="slider round"></span>
									                    </label>
									                  </div>
												</div>

												<div class="row">
													<div class="col-lg-5">
														<div class="left-area">
															
														</div>
													</div>
													<div class="col-lg-7">
														<button class="addProductSubmit-btn" type="submit"><?php echo e(__("Submit")); ?></button>
													</div>
												</div>


											</form>


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

$('#is_discount').on('change',function(){

if(this.checked)
{
	$(this).parent().parent().parent().next().removeClass('showbox');
	$('#discount').prop('required',true);
	$('#discount_date').prop('required',true);
}

else {
	$(this).parent().parent().parent().next().addClass('showbox');
	$('#discount').prop('required',false);
	$('#discount_date').prop('required',false);
}

});

})(jQuery);

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\product\highlight.blade.php ENDPATH**/ ?>