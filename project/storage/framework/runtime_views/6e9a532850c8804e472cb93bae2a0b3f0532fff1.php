
<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('assets/admin/css/product.css')); ?>" rel="stylesheet"/>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="mr-breadcrumb">
								<div class="row">
									<div class="col-lg-12">
											<h4 class="heading"><?php echo e(__("Import CSV Product")); ?> <a class="add-btn" href="<?php echo e(route('admin-prod-types')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__("Back")); ?></a></h4>
											<ul class="links">
												<li>
													<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
												</li>
											<li>
												<a href="javascript:;"><?php echo e(__("Import CSV Product")); ?> </a>
											</li>
											<li>
												<a href="<?php echo e(route('admin-import-index')); ?>"><?php echo e(__("All Products")); ?></a>
											</li>
												<li>
													<a href="<?php echo e(route('admin-import-csv')); ?>"><?php echo e(__("Import CSV Product")); ?></a>
												</li>
											</ul>
									</div>
								</div>
							</div>
							<div class="add-product-content">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area" id="modalEdit">

					                      <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
					                      <form id="geniusform" action="<?php echo e(route('admin-import-csv-store')); ?>" method="POST" enctype="multipart/form-data">
					                        <?php echo e(csrf_field()); ?>


                        <?php echo $__env->make('includes.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  



												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Category")); ?>*</h4>
														</div>
													</div>
													<div class="col-lg-7">
															<select id="cat" name="category_id" required="">
																	<option value=""><?php echo e(__("Select Category")); ?></option>
						                                              <?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						                                                  <option data-href="<?php echo e(route('admin-subcat-load',$cat->id)); ?>" value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
						                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						                                     </select>
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Sub Category")); ?>*</h4>
														</div>
													</div>
													<div class="col-lg-7">
															<select id="subcat" name="subcategory_id" disabled="">
                                                  				<option value=""><?php echo e(__("Select Sub Category")); ?></option>
															</select>
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Child Category")); ?>*</h4>
														</div>
													</div>
													<div class="col-lg-7">
															<select id="childcat" name="childcategory_id" disabled="">
                                                  				<option value=""><?php echo e(__("Select Child Category")); ?></option>
															</select>
													</div>
												</div>

											  <div class="row">
												  <div class="col-lg-4">
													  <div class="left-area">
														  <h4 class="heading"><?php echo e(__("Import File")); ?> *</h4>
													  </div>
												  </div>
												  <div class="col-lg-7">
													  <span class="file-btn">
														  <input type="file" id="csvfile" name="csvfile" accept=".csv">
													  </span>
												  </div>

											  </div>
												
	
						                        <input type="hidden" name="type" value="Physical">
												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
															
														</div>
													</div>
													<div class="col-lg-7 text-center">
														<button class="addProductSubmit-btn" type="submit"><?php echo e(__("Start Import")); ?></button>
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

<?php $__env->startSection('scripts'); ?>

<script src="<?php echo e(asset('assets/admin/js/product.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\productimport\importcsv.blade.php ENDPATH**/ ?>