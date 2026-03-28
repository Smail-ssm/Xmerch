

<?php $__env->startSection('styles'); ?>
	<link href="<?php echo e(asset('assets/admin/css/product.css')); ?>" rel="stylesheet"/>
	<link href="<?php echo e(asset('assets/admin/css/jquery.Jcrop.css')); ?>" rel="stylesheet"/>
	<link href="<?php echo e(asset('assets/admin/css/Jcrop-style.css')); ?>" rel="stylesheet"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
					<h4 class="heading"> <?php echo e(__("Edit Product")); ?><a class="add-btn ml-2" href="<?php echo e(url()->previous()); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__("Back")); ?></a></h4>
					<ul class="links">
						<li>
							<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(("Dashboard")); ?> </a>
						</li>
					<li>
						<a href="javascript:;"><?php echo e(__("Affiliate Products")); ?> </a>
					</li>
					<li>
						<a href="<?php echo e(route('admin-import-index')); ?>"><?php echo e(__("All Products")); ?></a>
					</li>
						<li>
							<a href="javascript:;"><?php echo e(__("Edit")); ?></a>
						</li>
					</ul>
			</div>
		</div>
	</div>

	<div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
	<form id="geniusform" action="<?php echo e(route('admin-import-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
		<?php echo e(csrf_field()); ?>


		<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
	<div class="row">
		<div class="col-lg-8">
			<div class="add-product-content">
				<div class="row">
					<div class="col-lg-12">
						<div class="product-description">
							<div class="body-area">
								<div class="row">
									<div class="col-lg-12">
									  <div class="left-area">
										  <h4 class="heading"><?php echo e(__('Select Language')); ?>*</h4>
									  </div>
									</div>
									<div class="col-lg-12">
										<select name="language_id" required="">
											  <?php $__currentLoopData = DB::table('languages')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ldata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($ldata->id); ?>" <?php echo e($ldata->id == $data->language_id ? 'selected' : ''); ?>><?php echo e($ldata->language); ?></option>
											  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										  </select>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__('Product Name')); ?>* </h4>
											<p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
										</div>
									</div>
									<div class="col-lg-12">
										<input type="text" class="input-field" placeholder="<?php echo e(__('Enter Product Name')); ?>" name="name" required="" value="<?php echo e($data->name); ?>">
									</div>
								</div>
		
								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__('Product Sku')); ?>* </h4>
										</div>
									</div>
									<div class="col-lg-12">
										<input type="text" class="input-field" placeholder="<?php echo e(__('Enter Product Sku')); ?>" name="sku" required="" value="<?php echo e($data->sku); ?>">
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
												<h4 class="heading"><?php echo e(__("Product Affiliate Link")); ?>* </h4>
												<p class="sub-heading"><?php echo e(__("(External Link)")); ?></p>
										</div>
									</div>
									<div class="col-lg-12">
										<input type="text" class="input-field" placeholder="<?php echo e(__("Enter Product Link")); ?>" name="affiliate_link" required="" value="<?php echo e($data->affiliate_link); ?>">
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
												<h4 class="heading"><?php echo e(__("Category")); ?>*</h4>
										</div>
									</div>
									<div class="col-lg-12">
										<select id="cat" name="category_id" required="">
												<option><?php echo e(__("Select Category")); ?></option>
												<?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option data-href="<?php echo e(route('admin-subcat-load',$cat->id)); ?>" value="<?php echo e($cat->id); ?>" <?php echo e($cat->id == $data->category_id ? "selected":""); ?> ><?php echo e($cat->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__("Sub Category")); ?>*</h4>
										</div>
									</div>
									<div class="col-lg-12">
											<select id="subcat" name="subcategory_id">
												<option value=""><?php echo e(__("Select Sub Category")); ?></option>
												<?php if($data->subcategory_id == null): ?>
													<?php $__currentLoopData = $data->category->subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option data-href="<?php echo e(route('admin-childcat-load',$sub->id)); ?>" value="<?php echo e($sub->id); ?>" ><?php echo e($sub->name); ?></option>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php else: ?>
													<?php $__currentLoopData = $data->category->subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option data-href="<?php echo e(route('admin-childcat-load',$sub->id)); ?>" value="<?php echo e($sub->id); ?>" <?php echo e($sub->id == $data->subcategory_id ? "selected":""); ?> ><?php echo e($sub->name); ?></option>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                                  
												<?php endif; ?>
											</select>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__("Child Category")); ?>*</h4>
										</div>
									</div>
									<div class="col-lg-12">
											<select id="childcat" name="childcategory_id" <?php echo e($data->subcategory_id == null ? "disabled":""); ?>>
												  <option value=""><?php echo e(__("Select Child Category")); ?></option>
													<?php if($data->subcategory_id != null): ?>
														<?php if($data->childcategory_id == null): ?>
															<?php $__currentLoopData = $data->subcategory->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
															<option value="<?php echo e($child->id); ?>" ><?php echo e($child->name); ?></option>
															<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														<?php else: ?>
															<?php $__currentLoopData = $data->subcategory->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
															<option value="<?php echo e($child->id); ?> " <?php echo e($child->id == $data->childcategory_id ? "selected":""); ?>><?php echo e($child->name); ?></option>
															<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														<?php endif; ?>                                               
													<?php endif; ?>
											</select>
									</div>
								</div>

						
									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__("Product Stock")); ?>*</h4>
												<p class="sub-heading"><?php echo e(__("(Leave Empty will Show Always Available)")); ?></p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="stock" type="text" class="input-field" placeholder="<?php echo e(__("e.g 20")); ?>" value="<?php echo e($data->stock); ?>">
											<div class="checkbox-wrapper">
												<input type="checkbox" name="measure_check" class="checkclick" id="allowProductMeasurement" value="1" <?php echo e($data->measure == null ? '' : 'checked'); ?>>
												<label for="allowProductMeasurement"><?php echo e(__("Allow Product Measurement")); ?></label>
											</div>
										</div>
									</div>
							

								<div class="<?php echo e($data->measure == null ? 'showbox' : ''); ?>">
									<div class="row">
										<div class="col-lg-6">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__("Product Measurement")); ?>*</h4>
											</div>
										</div>
										<div class="col-lg-12">
												<select id="product_measure">
												  <option value="" <?php echo e($data->measure == null ? 'selected':''); ?>><?php echo e(__("None")); ?></option>
												  <option value="Gram" <?php echo e($data->measure == 'Gram' ? 'selected':''); ?>><?php echo e(__("Gram")); ?></option>
												  <option value="Kilogram" <?php echo e($data->measure == 'Kilogram' ? 'selected':''); ?>><?php echo e(__("Kilogram")); ?></option>
												  <option value="Litre" <?php echo e($data->measure == 'Litre' ? 'selected':''); ?>><?php echo e(__("Litre")); ?></option>
												  <option value="Pound" <?php echo e($data->measure == 'Pound' ? 'selected':''); ?>><?php echo e(__("Pound")); ?></option>
												  <option value="Custom" <?php echo e(in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? '' : 'selected'); ?>><?php echo e(__("Custom")); ?></option>
												</select>
										</div>
										<div class="col-lg-12 <?php echo e(in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? 'hidden' : ''); ?>" id="measure">
											<input name="measure" type="text" id="measurement" class="input-field" placeholder="Enter Unit" value="<?php echo e($data->measure); ?>">
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">

										</div>
									</div>
									<div class="col-lg-12">
										<ul class="list">
											<li>
												<input class="checkclick1" name="product_condition_check" type="checkbox" id="conditionCheck" value="1" <?php echo e($data->product_condition != 0 ? "checked":""); ?>>
												<label for="conditionCheck"><?php echo e(__('Allow Product Condition')); ?></label>
											</li>
										</ul>
									</div>
								</div>


								<div class="<?php echo e($data->product_condition == 0 ? "showbox":""); ?>">
									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__('Product Condition')); ?>*</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<select name="product_condition">
												<option value="2" <?php echo e($data->product_condition == 2 ? "selected":""); ?>><?php echo e(__('New')); ?></option>
												<option value="1" <?php echo e($data->product_condition == 1 ? "selected":""); ?>><?php echo e(__('Used')); ?></option>
											</select>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">

										</div>
									</div>
									<div class="col-lg-12">
										<ul class="list">
											<li>
												<input class="checkclick1" name="shipping_time_check" type="checkbox" id="check1" value="1" <?php echo e($data->ship != null ? "checked":""); ?>>
												<label for="check1"><?php echo e(__("Allow Estimated Shipping Time")); ?></label>
											</li>
										</ul>
									</div>
								</div>


								<div class="<?php echo e($data->ship != null ? "":"showbox"); ?>">
									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__("Product Estimated Shipping Time")); ?>* </h4>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="text" class="input-field" placeholder="Estimated Shipping Time" name="ship" value="<?php echo e($data->ship == null ? "" : $data->ship); ?>">
										</div>
									</div>
								</div>

								
								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">

										</div>
									</div>
									<div class="col-lg-12">
										<ul class="list">
											<li>
												<input class="checkclickc" name="color_check" type="checkbox" id="check3" value="1" <?php echo e(!empty($data->color) ? "checked":""); ?>>
												<label for="check3"><?php echo e(__('Allow Product Colors')); ?></label>
											</li>
										</ul>
									</div>
								</div>

								<div class="<?php echo e(!empty($data->color) ? "":"showbox"); ?>">
									<div class="row">
										<?php if(!empty($data->color)): ?> 
										<div  class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">
														<?php echo e(__('Product Colors')); ?>*
													</h4>
													<p class="sub-heading">
														<?php echo e(__('(Choose Your Favorite Colors)')); ?>

													</p>
												</div>
											</div>
											<div  class="col-lg-12">
													<div class="select-input-color" id="color-section">
														<?php $__currentLoopData = array_unique($data->color); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<div class="color-area">
															<span class="remove color-remove"><i class="fas fa-times"></i></span>
															<div class="input-group colorpicker-component cp">
															  <input type="text" value="<?php echo e($ct); ?>"  class="input-field cp tcolor"/>
															  <span class="input-group-addon"><i></i></span>
															</div>
														 </div>
														 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</div>
												<a href="javascript:;" id="color-btn" class="add-more mt-4 mb-3"><i class="fas fa-plus"></i><?php echo e(__('Add More Color')); ?> </a>
											</div>

										<?php else: ?>

											<div  class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">
														<?php echo e(__('Product Colors')); ?>*
													</h4>
													<p class="sub-heading">
														<?php echo e(__('(Choose Your Favorite Colors)')); ?>

													</p>
												</div>
											</div>
											<div  class="col-lg-12">
													<div class="select-input-color" id="color-section">
														<div class="color-area">
															<span class="remove color-remove"><i class="fas fa-times"></i></span>
															<div class="input-group colorpicker-component cp">
															  <input type="text"  class="input-field cp tcolor"/>
															  <span class="input-group-addon"><i></i></span>
															</div>
														 </div>
													</div>
												<a href="javascript:;" id="color-btn" class="add-more mt-4 mb-3"><i class="fas fa-plus"></i><?php echo e(__('Add More Color')); ?> </a>
											</div>
										<?php endif; ?>
									</div>
								</div>


								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">

										</div>
									</div>
									<div class="col-lg-12">
										<ul class="list">
											<li>
												<input class="checkclicks" name="color_check" type="checkbox" id="tcheck" value="1" <?php echo e(!empty($data->size) ? "checked":""); ?>>
												<label for="tcheck"><?php echo e(__('Allow Product Sizes')); ?></label>
											</li>
										</ul>
									</div>
								</div>

								<div class="<?php echo e(!empty($data->size) ? "":"showbox"); ?>">
									<?php if(!empty($data->size)): ?>
									<div class="row">
											<div  class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">
														<?php echo e(__('Product Size')); ?>*
													</h4>
													<p class="sub-heading">
														<?php echo e(__('(eg. S,M,L,XL,XXL,3XL,4XL)')); ?>

													</p>
												</div>
											</div>
											<div  class="col-lg-12">
													<div class="select-input-tsize" id="tsize-section">
														<?php $__currentLoopData = array_unique($data->size); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<div class="tsize-area">
															<span class="remove tsize-remove"><i class="fas fa-times"></i></span>
															<input  type="text" class="input-field tsize" placeholder="<?php echo e(__('Enter Product Size')); ?>" value="<?php echo e($dt); ?>"  required="" >
														 </div>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</div>
												<a href="javascript:;" id="tsize-btn" class="add-more mt-4 mb-3"><i class="fas fa-plus"></i><?php echo e(__('Add More Size')); ?> </a>
											</div>
									</div>
									<?php else: ?>
									<div class="row">
										<div  class="col-lg-4">
											<div class="left-area">
												<h4 class="heading">
													<?php echo e(__('Product Size')); ?>*
												</h4>
												<p class="sub-heading">
													<?php echo e(__('(eg. S,M,L,XL,XXL,3XL,4XL)')); ?>

												</p>
											</div>
										</div>
										<div  class="col-lg-7">
												<div class="select-input-tsize" id="tsize-section">
													<div class="tsize-area">
														<span class="remove tsize-remove"><i class="fas fa-times"></i></span>
														<input  type="text" class="input-field tsize" placeholder="<?php echo e(__('Enter Product Size')); ?>"  >
													</div>
												</div>
											<a href="javascript:;" id="tsize-btn" class="add-more mt-4 mb-3"><i class="fas fa-plus"></i><?php echo e(__('Add More Size')); ?> </a>
										</div>
									</div>
									<?php endif; ?>
								</div>


							

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading">
												<?php echo e(__("Product Description")); ?>*
											</h4>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="text-editor">
											<textarea name="details" class="nic-edit-p"><?php echo e($data->details); ?></textarea> 
										</div>
									</div>
								</div>
								
								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading">
												<?php echo e(__("Product Buy/Return Policy")); ?>*
											</h4>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="text-editor">
											<textarea name="policy" class="nic-edit-p"><?php echo e($data->policy); ?></textarea> 
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="checkbox-wrapper">
											<input type="checkbox" name="seo_check" value="1" class="checkclick" id="allowProductSEO" <?php echo e(($data->meta_tag != null || strip_tags($data->meta_description) != null) ? 'checked':''); ?>>
						                    <label for="allowProductSEO"><?php echo e(__("Allow Product SEO")); ?></label>
										</div>
									</div>
								</div>


								<div class="<?php echo e(($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "showbox":""); ?>">
									<div class="row">
									  <div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__("Meta Tags")); ?> *</h4>
										</div>
									  </div>
									  <div class="col-lg-12">
										<ul id="metatags" class="myTags">
											<?php if(!empty($data->meta_tag)): ?>
											  <?php $__currentLoopData = $data->meta_tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li><?php echo e($element); ?></li>
											  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										  <?php endif; ?>
										</ul>
									  </div>
									</div>  

									<div class="row">
									  <div class="col-lg-12">
										<div class="left-area">
										  <h4 class="heading">
											  <?php echo e(__("Meta Description")); ?> *
										  </h4>
										</div>
									  </div>
									  <div class="col-lg-12">
										<div class="text-editor">
										  <textarea name="meta_description" class="input-field" placeholder="<?php echo e(__("Meta Description")); ?>"><?php echo e($data->meta_description); ?></textarea> 
										</div>
									  </div>
									</div>
								  </div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="add-product-content">
				<div class="row">
					<div class="col-lg-12">
						<div class="product-description">
							<div class="body-area">
								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__("Feature Image Source")); ?>*</h4>
										</div>
									</div>
									<div class="col-lg-12">
										<select id="imageSource" name="image_source">
											<option value="file" <?php echo e(!filter_var($data->photo,FILTER_VALIDATE_URL) ? 'selected' : ''); ?>><?php echo e(__("File")); ?></option>
											<option value="link" <?php echo e(filter_var($data->photo,FILTER_VALIDATE_URL) ? 'selected' : ''); ?>><?php echo e(__("Link")); ?></option>
										</select>
									</div>
								</div>


							<div id="f-file" style="<?php echo e(filter_var($data->photo,FILTER_VALIDATE_URL) ? 'display:none' : ''); ?>">
								 <div class="row">
									<div class="col-lg-12">
									  <div class="left-area">
										  <h4 class="heading"><?php echo e(__("Feature Image")); ?> *</h4>
									  </div>
									</div>

									<div class="col-lg-12">
										<div class="panel panel-body">
											<div class="span4 cropme text-center" id="landscape" style="width: 100%; height: 285px; border: 1px dashed #ddd; background: #f1f1f1;">
												<a href="javascript:;" id="crop-image" class=" mybtn1" style="">
													<i class="icofont-upload-alt"></i> <?php echo e(__('Upload Image Here')); ?>

												</a>
											</div>
										</div>
									</div>
								  </div>

								  <input type="hidden" id="feature_photo" name="photo" value="<?php echo e($data->photo); ?>" accept="image/*">
							 </div>

							  <div id="f-link" style="<?php echo e(!filter_var($data->photo,FILTER_VALIDATE_URL) ? 'display:none' : ''); ?>">
								  <div class="row">
									  <div class="col-lg-12">
										  <div class="left-area">
											  <h4 class="heading"><?php echo e(__("Feature Image Link ")); ?>*</h4>
										  </div>
									  </div>
									  <div class="col-lg-12">
										  <input type="text" name="photolink" value="<?php echo e($data->photo); ?>" class="input-field">
									  </div>
								  </div>
							  </div>
							 
								<div class="row">
									<div class="col-lg-5">
										<div class="left-area">
											<h4 class="heading">
												<?php echo e(__("Product Gallery Images")); ?> *
											</h4>
										</div>
									</div>
									<div class="col-lg-7">
										<a href="javascript" class="set-gallery"  data-toggle="modal" data-target="#setgallery">
											<input type="hidden" value="<?php echo e($data->id); ?>">
											<i class="icofont-plus"></i> <?php echo e(__("Set Gallery")); ?>

										</a>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading">
												<?php echo e(__("Product Current Price")); ?>*
											</h4>
											<p class="sub-heading">
												(<?php echo e(__("In")); ?> <?php echo e($sign->name); ?>)
											</p>
										</div>
									</div>
									<div class="col-lg-12">
										<input name="price" step="0.1" type="number" class="input-field" placeholder="<?php echo e(__("e.g 20")); ?>" value="<?php echo e(round($data->price * $sign->value , 2)); ?>" required="" min="0">
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__("Product Discount Price")); ?>*</h4>
											<p class="sub-heading"><?php echo e(__("(Optional)")); ?></p>
										</div>
									</div>
									<div class="col-lg-12">
										<input name="previous_price" step="0.1" type="number" class="input-field" placeholder="<?php echo e(__("e.g 20")); ?>" value="<?php echo e(round($data->previous_price * $sign->value , 2)); ?>" min="0">
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">
											<h4 class="heading"><?php echo e(__('Youtube Video URL')); ?>*</h4>
											<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
										</div>
									</div>
									<div class="col-lg-12">
										<input  name="youtube" type="text" class="input-field" placeholder="<?php echo e(__("Enter Youtube Video URL")); ?>" value="<?php echo e($data->youtube); ?>">
									</div>
								</div>


								
								<div class="row">
									<div class="col-lg-12">
										<div class="left-area">

										</div>
									</div>
									<div class="col-lg-12">
										<div class="featured-keyword-area">
											<div class="heading-area">
												<h4 class="title"><?php echo e(__("Feature Tags")); ?></h4>
											</div>

											<div class="feature-tag-top-filds" id="feature-section">
												<?php if(!empty($data->features)): ?>
													 <?php $__currentLoopData = $data->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														<div class="feature-area">
															<span class="remove feature-remove"><i class="fas fa-times"></i></span>
															<div class="row">
																<div class="col-lg-6">
																<input type="text" name="features[]" class="input-field" placeholder="<?php echo e(__("Enter Your Keyword")); ?>" value="<?php echo e($data->features[$key]); ?>">
																</div>

																<div class="col-lg-6">
																	<div class="input-group colorpicker-component cp">
																	<input type="text" name="colors[]" value="<?php echo e($data->colors[$key]); ?>" class="input-field cp"/>
																	<span class="input-group-addon"><i></i></span>
																	</div>
																</div>
															</div>
														</div>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php else: ?> 
													<div class="feature-area">
														<span class="remove feature-remove"><i class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-lg-6">
															<input type="text" name="features[]" class="input-field" placeholder="<?php echo e(__("Enter Your Keyword")); ?>">
															</div>

															<div class="col-lg-6">
																<div class="input-group colorpicker-component cp">
																<input type="text" name="colors[]" value="#000000" class="input-field cp"/>
																<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
													</div>
												<?php endif; ?>
											</div>
											<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i class="icofont-plus"></i> <?php echo e(__("Add More Field")); ?></a>
										</div>
									</div>
								</div>


								<div class="row">
								  <div class="col-lg-12">
									<div class="left-area">
										<h4 class="heading"><?php echo e(__("Tags")); ?> *</h4>
									</div>
								  </div>
								  <div class="col-lg-12">
									<ul id="tags" class="myTags">
										<?php if(!empty($data->tags)): ?>
											<?php $__currentLoopData = $data->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											  <li><?php echo e($element); ?></li>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
									</ul>
								  </div>
								</div>

								<div class="row">
									<div class="col-lg-3">
										<div class="left-area">
											
										</div>
									</div>
									<div class="col-lg-8 text-center">
										<button class="addProductSubmit-btn" type="submit"><?php echo e(__("Save")); ?></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
</div>

<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
		<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle"><?php echo e(__("Image Gallery")); ?></h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">×</span>
			</button>
		</div>
		<div class="modal-body">
			<div class="top-area">
				<div class="row">
					<div class="col-sm-6 text-right">
						<div class="upload-img-btn">
							<form  method="POST" enctype="multipart/form-data" id="form-gallery">
								<?php echo csrf_field(); ?>
							<input type="hidden" id="pid" name="product_id" value="">
							<input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*" multiple>
									<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i><?php echo e(__("Upload File")); ?></label>
							</form>
						</div>
					</div>
					<div class="col-sm-6">
						<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> <?php echo e(__("Done")); ?></a>
					</div>
					<div class="col-sm-12 text-center">( <small><?php echo e(__("You can upload multiple Images.")); ?></small> )</div>
				</div>
			</div>
			<div class="gallery-images">
				<div class="selected-image">
					<div class="row">


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
	
    $(function($) {
		"use strict";

// Gallery Section Update

    $(document).on("click", ".set-gallery" , function(){
        var pid = $(this).find('input[type=hidden]').val();
        $('#pid').val(pid);
        $('.selected-image .row').html('');
            $.ajax({
                    type: "GET",
                    url:"<?php echo e(route('admin-gallery-show')); ?>",
                    data:{id:pid},
                    success:function(data){
                      if(data[0] == 0)
                      {
	                    $('.selected-image .row').addClass('justify-content-center');
	      				$('.selected-image .row').html('<h3><?php echo e(__("No Images Found.")); ?></h3>');
     				  }
                      else {
	                    $('.selected-image .row').removeClass('justify-content-center');
	      				$('.selected-image .row h3').remove();      
                          var arr = $.map(data[1], function(el) {
                          return el });

                          for(var k in arr)
                          {
        				$('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+arr[k]['id']+'">'+
                                            '</span>'+
                                            '<a href="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  	'</div>');
                          }                         
                       }
 
                    }
                  });
      });


  $(document).on('click', '.remove-img' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $(this).parent().parent().remove();
	    $.ajax({
	        type: "GET",
	        url:"<?php echo e(route('admin-gallery-delete')); ?>",
	        data:{id:id}
	    });
  });

  $(document).on('click', '#prod_gallery' ,function() {
    $('#uploadgallery').click();
  });
                                        
                                
  $("#uploadgallery").change(function(){
    $("#form-gallery").submit();  
  });

  $(document).on('submit', '#form-gallery' ,function() {
		  $.ajax({
		   url:"<?php echo e(route('admin-gallery-store')); ?>",
		   method:"POST",
		   data:new FormData(this),
		   dataType:'JSON',
		   contentType: false,
		   cache: false,
		   processData: false,
		   success:function(data)
		   {
		    if(data != 0)
		    {
	                    $('.selected-image .row').removeClass('justify-content-center');
	      				$('.selected-image .row h3').remove();   
		        var arr = $.map(data, function(el) {
		        return el });
		        for(var k in arr)
		           {
        				$('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+arr[k]['id']+'">'+
                                            '</span>'+
                                            '<a href="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  	'</div>');
		            }          
		    }
		                     
		                       }

		  });
		  return false;

});

 }); 


// Gallery Section Update Ends	

</script>

<script src="<?php echo e(asset('assets/admin/js/jquery.Jcrop.js')); ?>"></script>

<script src="<?php echo e(asset('assets/admin/js/jquery.SimpleCropper.js')); ?>"></script>

<script type="text/javascript">
	
    (function($) {
		"use strict";

$('.cropme').simpleCropper();

	})(jQuery);

</script>

  <script type="text/javascript">

$(function($) {
		"use strict";


    let html = `<img src="<?php echo e(empty($data->photo) ? asset('assets/images/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('assets/images/products/'.$data->photo))); ?>" alt="">`;
	
    $(".span4.cropme").html(html);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



  });

  $('.ok').on('click', function () {

    (function($) {
		"use strict";

 setTimeout(
    function() {

  	var img = $('#feature_photo').val();

      $.ajax({
        url: "<?php echo e(route('admin-prod-upload-update',$data->id)); ?>",
        type: "POST",
        data: {"image":img,"_token": "<?php echo e(csrf_token()); ?>"},
        success: function (data) {
          if (data.status) {
            $('#feature_photo').val(data.file_name);
          }
          if ((data.errors)) {
            for(var error in data.errors)
            {
              $.notify(data.errors[error], "danger");
            }
          }
        }
      });

    }, 1000);

    })(jQuery);

});

  </script>

  <script type="text/javascript">

(function($) {
		"use strict";

  $('#imageSource').on('change', function () {
    var file = this.value;
      if (file == "file"){
          $('#f-file').show();
          $('#f-link').hide();
          $('#f-link').find('input').prop('required',false);
      }
      if (file == "link"){
          $('#f-file').hide();
          $('#f-link').show();
          $('#f-link').find('input').prop('required',true);
      }
  });
  
})(jQuery);

  </script>  

<?php echo $__env->make('partials.admin.product.product-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\productimport\editone.blade.php ENDPATH**/ ?>