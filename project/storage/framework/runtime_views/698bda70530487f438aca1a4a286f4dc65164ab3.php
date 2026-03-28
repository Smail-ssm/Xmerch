
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
											<h4 class="heading"> <?php echo e(__('Edit Product')); ?><a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
											<ul class="links">
												<li>
													<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
												</li>
												<li>
													<a href="<?php echo e(route('admin-prod-index')); ?>"><?php echo e(__('Products')); ?> </a>
												</li>
												<li>
													<a href="javascript:;"><?php echo e(__('Physical Product')); ?></a>
												</li>
												<li>
													<a href="javascript:;"><?php echo e(__('Edit')); ?></a>
												</li>
											</ul>
									</div>
								</div>
							</div>
							<form id="geniusform" action="<?php echo e(route('admin-prod-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
								<?php echo e(csrf_field()); ?>

								<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
								<div class="row">
									<div class="col-lg-8">
										<div class="add-product-content">
											<div class="row">
												<div class="col-lg-12">
													<div class="product-description">
														<div class="body-area">
															<div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
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
																			<h4 class="heading"><?php echo e(__("Product Name")); ?>* </h4>
																			<p class="sub-heading"><?php echo e(__("(In Any Language)")); ?></p>
																	</div>
																</div>
																<div class="col-lg-12">
																	<input type="text" class="input-field" placeholder="<?php echo e(__("Enter Product Name")); ?>" name="name" required="" value="<?php echo e($data->name); ?>">
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
																		<h4 class="heading"><?php echo e(__('Category')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<select id="cat" name="category_id" required="">
																		<option><?php echo e(__('Select Category')); ?></option>
																		<?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																			<option data-href="<?php echo e(route('admin-subcat-load',$cat->id)); ?>" value="<?php echo e($cat->id); ?>" <?php echo e($cat->id == $data->category_id ? "selected":""); ?> ><?php echo e($cat->name); ?></option>
																		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																	</select>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Sub Category')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																		<select id="subcat" name="subcategory_id">
																			<option value=""><?php echo e(__('Select Sub Category')); ?></option>
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
																		<h4 class="heading"><?php echo e(__('Child Category')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<select id="childcat" name="childcategory_id" <?php echo e($data->subcategory_id == null ? "disabled":""); ?>>
																			<option value=""><?php echo e(__('Select Child Category')); ?></option>
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

														<?php
															$selectedAttrs = json_decode($data->attributes, true);
															// dd($selectedAttrs);
														?>
		
														
														<div id="catAttributes">
															<?php
																$catAttributes = !empty($data->category->attributes) ? $data->category->attributes : '';
															?>
															<?php if(!empty($catAttributes)): ?>
																<?php $__currentLoopData = $catAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catAttribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<div class="row">
																		 <div class="col-lg-12">
																			<div class="left-area">
																				<h4 class="heading"><?php echo e($catAttribute->name); ?> *</h4>
																			</div>
																		 </div>
																		 <div class="col-lg-12">
																			 <?php
																				 $i = 0;
																			 ?>
																			 <?php $__currentLoopData = $catAttribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																				 <?php
																					$inName = $catAttribute->input_name;
																					$checked = 0;
																				 ?>
																				 <div class="row">
																					 <div class="col-lg-5">
																						 <div class="custom-control custom-checkbox">
																							  <input type="checkbox" id="<?php echo e($catAttribute->input_name); ?><?php echo e($option->id); ?>" name="<?php echo e($catAttribute->input_name); ?>[]" value="<?php echo e($option->name); ?>" class="custom-control-input attr-checkbox"
																							  <?php if(is_array($selectedAttrs) && array_key_exists($catAttribute->input_name,$selectedAttrs)): ?>
																							  <?php if(is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"])): ?>
																								  checked
																								 <?php
																									 $checked = 1;
																								 ?>
																							  <?php endif; ?>
																						  <?php endif; ?>
																							  >
																							  <label class="custom-control-label" for="<?php echo e($catAttribute->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
																						 </div>
																					 </div>
		
																					 <div class="col-lg-7 <?php echo e($catAttribute->price_status == 0 ? 'd-none' : ''); ?>">
																							<div class="row">
																								 <div class="col-2">
																										+
																								 </div>
																								 <div class="col-10">
																									<div class="price-container">
																											<span class="price-curr"><?php echo e($sign->sign); ?></span>
																											<input type="text" class="input-field price-input" id="<?php echo e($catAttribute->input_name); ?><?php echo e($option->id); ?>_price" data-name="<?php echo e($catAttribute->input_name); ?>_price[]" placeholder="0.00 (Additional Price)" value="<?php echo e(!empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? $selectedAttrs["$inName"]['prices'][$i] : ''); ?>">
																									</div>
																								 </div>
																							</div>
																					 </div>
																				 </div>
																				 <?php
																					 if ($checked == 1) {
																						 $i++;
																					 }
																				 ?>
																				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																		 </div>
																	</div>
																<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
															<?php endif; ?>
														</div>
														

														
														<div id="subcatAttributes">
															<?php
																$subAttributes = !empty($data->subcategory->attributes) ? $data->subcategory->attributes : '';
															?>
															<?php if(!empty($subAttributes)): ?>
																<?php $__currentLoopData = $subAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAttribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<div class="row">
																		<div class="col-lg-12 mb-2">
																				<div class="left-area">
																					<h4 class="heading"><?php echo e($subAttribute->name); ?> *</h4>
																				</div>
																		</div>
																		<div class="col-lg-12">
																				<?php
																					$i = 0;
																				?>
																				<?php $__currentLoopData = $subAttribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																					<?php
																						$inName = $subAttribute->input_name;
																						$checked = 0;
																					?>

																					<div class="row">
																						<div class="col-lg-5">
																						<div class="custom-control custom-checkbox">
																							<input type="checkbox" id="<?php echo e($subAttribute->input_name); ?><?php echo e($option->id); ?>" name="<?php echo e($subAttribute->input_name); ?>[]" value="<?php echo e($option->name); ?>" class="custom-control-input attr-checkbox"
																							<?php if(is_array($selectedAttrs) && array_key_exists($subAttribute->input_name,$selectedAttrs)): ?>
																							<?php
																							$inName = $subAttribute->input_name;
																							?>
																							<?php if(is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"])): ?>
																							checked
																							<?php
																								$checked = 1;
																							?>
																							<?php endif; ?>
																							<?php endif; ?>
																							>

																							<label class="custom-control-label" for="<?php echo e($subAttribute->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
																						</div>
																						</div>
																						<div class="col-lg-7 <?php echo e($subAttribute->price_status == 0 ? 'd-none' : ''); ?>">
																						<div class="row">
																							<div class="col-2">
																								+
																							</div>
																							<div class="col-10">
																								<div class="price-container">
																									<span class="price-curr"><?php echo e($sign->sign); ?></span>
																									<input type="text" class="input-field price-input" id="<?php echo e($subAttribute->input_name); ?><?php echo e($option->id); ?>_price" data-name="<?php echo e($subAttribute->input_name); ?>_price[]" placeholder="0.00 (Additional Price)" value="<?php echo e(!empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? $selectedAttrs["$inName"]['prices'][$i] : ''); ?>">
																								</div>
																							</div>
																						</div>
																						</div>
																					</div>
																					<?php
																						if ($checked == 1) {
																							$i++;
																						}
																					?>
																					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																					</div>
																				</div>
																			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																		<?php endif; ?>
																	</div>
															

															
																<div id="childcatAttributes">
																	<?php
																		$childAttributes = !empty($data->childcategory->attributes) ? $data->childcategory->attributes : '';
																	?>
																	<?php if(!empty($childAttributes)): ?>
																		<?php $__currentLoopData = $childAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childAttribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																			<div class="row">
																				<div class="col-lg-12 mb-2">
																						<div class="left-area">
																							<h4 class="heading"><?php echo e($childAttribute->name); ?> *</h4>
																						</div>
																				</div>
																				<div class="col-lg-12">
																					<?php
																						$i = 0;
																					?>
																					<?php $__currentLoopData = $childAttribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																						<?php
																							$inName = $childAttribute->input_name;
																							$checked = 0;
																						?>
																						<div class="row">
																								<div class="col-lg-5">
																									<div class="custom-control custom-checkbox">
																										<input type="checkbox" id="<?php echo e($childAttribute->input_name); ?><?php echo e($option->id); ?>" name="<?php echo e($childAttribute->input_name); ?>[]" value="<?php echo e($option->name); ?>" class="custom-control-input attr-checkbox"
																										<?php if(is_array($selectedAttrs) && array_key_exists($childAttribute->input_name,$selectedAttrs)): ?>
																											<?php
																												$inName = $childAttribute->input_name;
																											?>
																											<?php if(is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"])): ?>
																												checked
																												<?php
																													$checked = 1;
																												?>
																											<?php endif; ?>
																										<?php endif; ?>																										
																										
																										>

																										<label class="custom-control-label" for="<?php echo e($childAttribute->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
																									</div>
																							</div>


																								<div class="col-lg-7 <?php echo e($childAttribute->price_status == 0 ? 'd-none' : ''); ?>">
																									<div class="row">
																											<div class="col-2">
																												+
																											</div>
																											<div class="col-10">
																												<div class="price-container">
																														<span class="price-curr"><?php echo e($sign->sign); ?></span>
																														<input type="text" class="input-field price-input" id="<?php echo e($childAttribute->input_name); ?><?php echo e($option->id); ?>_price" data-name="<?php echo e($childAttribute->input_name); ?>_price[]" placeholder="0.00 (Additional Price)" value="<?php echo e(!empty($selectedAttrs["$inName"]['prices'][$i]) && $checked == 1 ? $selectedAttrs["$inName"]['prices'][$i] : ''); ?>">
																												</div>
																											</div>
																									</div>
																								</div>
																						</div>
																						<?php
																							if ($checked == 1) {
																								$i++;
																							}
																						?>
																						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																				</div>

																			</div>
																		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																	<?php endif; ?>
																</div>
															

															<div class="<?php echo e(!empty($data->size) ? "showbox":""); ?>" id="stckprod">
																<div class="row">
																
																	<div class="col-lg-12">
																		<div class="checkbox-wrapper">
																			<input type="checkbox" name="measure_check" class="checkclick1" id="allowProductMeasurement" value="1" <?php echo e($data->measure == null ? '' : 'checked'); ?>>
																			<label for="allowProductMeasurement"><?php echo e(__('Allow Product Measurement')); ?></label>
																		</div>
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
																				<option value="2" <?php echo e($data->product_condition == 2
																	? "selected":""); ?>><?php echo e(__('New')); ?></option>
																				<option value="1" <?php echo e($data->product_condition == 1
																	? "selected":""); ?>><?php echo e(__('Used')); ?></option>
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
																			<input class="checkclick1" name="preordered_check" type="checkbox" id="preorderedCheck" value="1" <?php echo e($data->preordered != 0 ? "checked":""); ?>>
																			<label for="preorderedCheck"><?php echo e(__('Allow Product Preorder')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>
			
															<div class="<?php echo e($data->preordered == 0 ? "showbox":""); ?>">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Product Preorder')); ?>*</h4>
																		</div>
																	</div>
																	<div class="col-lg-12">
																			<select name="preordered">
																				<option value="1" <?php echo e($data->preordered == 1
																	? "selected":""); ?>><?php echo e(__('Sale')); ?></option>
																				<option value="2" <?php echo e($data->preordered == 2
																	? "selected":""); ?>><?php echo e(__('Preordered')); ?></option>
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
																			<input class="checkclick1" name="minimum_qty_check" type="checkbox" id="check111" value="1" <?php echo e($data->minimum_qty != null ? "checked":""); ?>>
																			<label for="check111"><?php echo e(__('Allow Minimum Order Qty')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>
			
															<div class="<?php echo e($data->minimum_qty != null ? "":"showbox"); ?>">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Product Minimum Order Qty')); ?>* </h4>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<input type="number" class="input-field" min="1"
																			placeholder="<?php echo e(__('Minimum Order Qty')); ?>" name="minimum_qty" value="<?php echo e($data->minimum_qty == null ? '' : $data->minimum_qty); ?>">
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
																			<label for="check1"><?php echo e(__('Allow Estimated Shipping Time')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>
			
			
															<div class="<?php echo e($data->ship != null ? "":"showbox"); ?>">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Product Estimated Shipping Time')); ?>* </h4>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<input type="text" class="input-field"
																			placeholder="<?php echo e(__('Estimated Shipping Time')); ?>" name="ship"
																			value="<?php echo e($data->ship == null ? "" : $data->ship); ?>">
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
																			<input class="checkclickc" name="color_check" type="checkbox" id="check3" value="1" <?php echo e(!empty($data->color_all) ? "checked":""); ?>>
																			<label for="check3"><?php echo e(__('Allow Product Colors')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>
			
															<div class="<?php echo e(!empty($data->color_all) ? "":"showbox"); ?>">
																<div class="row">
																	<?php if(!empty($data->color_all)): ?> 
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
																					<?php $__currentLoopData = array_unique(explode(',',$data->color_all)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																					<div class="color-area">
																						<span class="remove color-remove"><i class="fas fa-times"></i></span>
																						<div class="input-group colorpicker-component cp">
																						  <input type="text" name="color_all[]" value="<?php echo e($ct); ?>"  class="input-field cp tcolor"/>
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
																						  <input type="text"  name="color_all[]"  class="input-field cp tcolor"/>
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
																			<input class="checkclicks" name="size_check" type="checkbox" id="tcheck" value="1" <?php echo e(!empty($data->size_all) ? "checked":""); ?>>
																			<label for="tcheck"><?php echo e(__('Allow Product Sizes')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>
			
															<div class="<?php echo e(!empty($data->size_all) ? "":"showbox"); ?>">
																<?php if(!empty($data->size_all)): ?>
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
																					<?php $__currentLoopData = array_unique(explode(',',$data->size_all)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																					<div class="tsize-area">
																						<span class="remove tsize-remove"><i class="fas fa-times"></i></span>
																						<input  type="text" name="size_all[]" class="input-field tsize" placeholder="<?php echo e(__('Enter Product Size')); ?>" value="<?php echo e($dt); ?>"  required="" >
																					 </div>
																					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																				</div>
																			<a href="javascript:;" id="tsize-btn" class="add-more mt-4 mb-3"><i class="fas fa-plus"></i><?php echo e(__('Add More Size')); ?> </a>
																		</div>
																</div>
																<?php else: ?>
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
																					<div class="tsize-area">
																						<span class="remove tsize-remove"><i class="fas fa-times"></i></span>
																						<input  type="text" name="size_all[]" class="input-field tsize" placeholder="<?php echo e(__('Enter Product Size')); ?>"  >
																						
																					 </div>
																				</div>
																			<a href="javascript:;" id="tsize-btn" class="add-more mt-4 mb-3"><i class="fas fa-plus"></i><?php echo e(__('Add More Size')); ?> </a>
																		</div>
																</div>
																<?php endif; ?>
															</div>
			
			
			
			
															<div class="<?php echo e($data->measure == null ? 'showbox' : ''); ?>">
																<div class="row">
																	<div class="col-lg-6">
																		<div class="left-area">
																				<h4 class="heading"><?php echo e(__('Product Measurement')); ?>*</h4>
																		</div>
																	</div>
																	<div class="col-lg-6">
																			<select id="product_measure">
																			  <option value="" <?php echo e($data->measure == null ? 'selected':''); ?>><?php echo e(__('None')); ?></option>
																			  <option value="Gram" <?php echo e($data->measure == 'Gram' ? 'selected':''); ?>><?php echo e(__('Gram')); ?></option>
																			  <option value="Kilogram" <?php echo e($data->measure == 'Kilogram' ? 'selected':''); ?>><?php echo e(__('Kilogram')); ?></option>
																			  <option value="Litre" <?php echo e($data->measure == 'Litre' ? 'selected':''); ?>><?php echo e(__('Litre')); ?></option>
																			  <option value="Pound" <?php echo e($data->measure == 'Pound' ? 'selected':''); ?>><?php echo e(__('Pound')); ?></option>
																			  <option value="Custom" <?php echo e(in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? '' : 'selected'); ?>><?php echo e(__('Custom')); ?></option>
																			 </select>
																	</div>
																	
																	<div class="col-lg-6 <?php echo e(in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? 'hidden' : ''); ?>" id="measure">
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
																			<input name="stock_check" class="stock-check" type="checkbox" id="size-check" value="1" <?php echo e(!empty($data->size) ? "checked":""); ?>>
																			<label for="size-check" class="stock-text"><?php echo e(__('Manage Stock')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('POD Configuration')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<div class="checkbox-wrapper">
																		<input type="checkbox" name="is_pod" value="1" class="checkclick" id="is_pod" <?php echo e($data->is_pod == 1 ? 'checked' : ''); ?>>
																		<label for="is_pod"><?php echo e(__('This is a Print-On-Demand Product')); ?></label>
																	</div>
																</div>
															</div>

															<div class="showbox" id="pod-section" style="display: <?php echo e($data->is_pod == 1 ? 'block' : 'none'); ?>">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Upload Design File')); ?> *</h4>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<input type="file" class="input-field" name="print_file" accept=".png,.jpg,.jpeg,.ai,.psd,.pdf">
																		<p class="sub-heading"><?php echo e(__('Supported formats: PNG, JPG, AI, PSD, PDF')); ?></p>
                                                                        <?php if($data->print_file): ?>
                                                                            <p class="text-success"><?php echo e(__('Current File:')); ?> <a href="<?php echo e(asset('assets/files/designs/'.$data->print_file)); ?>" target="_blank"><?php echo e($data->print_file); ?></a></p>
                                                                        <?php endif; ?>
																	</div>
																</div>

																<div class="row">
																	<div class="col-lg-6">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Daily Production Capacity')); ?> *</h4>
																		</div>
																		<input type="number" class="input-field" name="production_cap" placeholder="<?php echo e(__('e.g. 50')); ?>" min="1" value="<?php echo e($data->production_cap); ?>">
																	</div>
																	<div class="col-lg-6">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Est. Print Time (Minutes)')); ?> *</h4>
																		</div>
																		<input type="number" class="input-field" name="print_time_minutes" placeholder="<?php echo e(__('e.g. 30')); ?>" min="1" value="<?php echo e($data->print_time_minutes ?? 30); ?>">
																	</div>
																</div>

                                                                <div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Quality Tier')); ?> *</h4>
																		</div>
                                                                        <select name="quality_tier">
                                                                            <option value="standard" <?php echo e($data->quality_tier == 'standard' ? 'selected' : ''); ?>><?php echo e(__('Standard')); ?></option>
                                                                            <option value="premium" <?php echo e($data->quality_tier == 'premium' ? 'selected' : ''); ?>><?php echo e(__('Premium')); ?></option>
                                                                            <option value="deluxe" <?php echo e($data->quality_tier == 'deluxe' ? 'selected' : ''); ?>><?php echo e(__('Deluxe')); ?></option>
                                                                        </select>
																	</div>
																</div>
															</div>

															<div class="row <?php echo e(!empty($data->size) ? "d-none":""); ?>" id="default_stock">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Product Stock')); ?>*</h4>
																		<p class="sub-heading"><?php echo e(__('(Leave Empty will Show Always Available)')); ?></p>
																	</div>
																</div>
																<div class="col-lg-12">
																	<input name="stock"  type="number" class="input-field" placeholder="e.g 20" value="<?php echo e($data->stock); ?>" min="0">
																</div>
														</div>

			
													


													

															<div class="<?php echo e(!empty($data->size) ? "":"showbox"); ?>" id="size-display">
																<div class="row">
																		<div  class="col-lg-12">
																		</div>
																		<div  class="col-lg-12">
																			<div class="product-size-details" id="size-section">
																			  <?php if(!empty($data->size)): ?>
																				<?php $__currentLoopData = $data->size; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																				<div class="size-area">
																					<span class="remove size-remove"><i class="fas fa-times"></i></span>
																					<div  class="row">
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Name')); ?> :
																								<span>
																									<?php echo e(__('(eg. S,M,L,XL,3XL,4XL)')); ?>

																								</span>
																							</label>
																							<select name="size[]" class="input-field size-name">
																								<?php $__currentLoopData = $data->size; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																								<option value="<?php echo e($dt); ?>" <?php echo e($dt == $data1 ? 'selected' : ''); ?>><?php echo e($dt); ?></option>
																								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																							</select>
																						</div>
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Qty')); ?> :
																								<span>
																									<?php echo e(__('(Quantity of this size)')); ?>

																								</span>
																							</label>
																							<input type="number" name="size_qty[]" required class="input-field" placeholder="<?php echo e(__('Size Qty')); ?>" value="<?php echo e($data->size_qty[$key]); ?>" min="1">
																						</div>
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Price')); ?> :
																								<span>
																									<?php echo e(__('(Added with base price)')); ?>

																								</span>
																							</label>
																							<input type="number" name="size_price[]" required class="input-field" placeholder="<?php echo e(__('Size Price')); ?>" value="<?php echo e(round($data->size_price[$key] * $sign->value , 2)); ?>" min="0">
																						</div>
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Color')); ?> :
																								<span>
																									<?php echo e(__('(Select color of this size)')); ?>

																								</span>
																							</label>
																							<select name="color[]" class="input-field color-name">
																								<?php $__currentLoopData = $data->color; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																								<option value="<?php echo e($ct); ?>" style="background-color:<?php echo e($ct); ?>" <?php echo e($ct == $data->color[$key] ? 'selected' : ''); ?>></option>
																								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																							</select>
																						</div>
			
																					</div>
																				</div>
			
																				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			
																				<?php else: ?>
			
																				<div class="size-area">
																					<span class="remove size-remove"><i class="fas fa-times"></i></span>
																					<div  class="row">
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Name')); ?> :
																								<span>
																									<?php echo e(__('(eg. S,M,L,XL,3XL,4XL)')); ?>

																								</span>
																							</label>
																							<select name="size[]" class="input-field size-name"></select>
																						</div>
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Qty')); ?> :
																								<span>
																									<?php echo e(__('(Quantity of this size)')); ?>

																								</span>
																							</label>
																							<input type="number" name="size_qty[]" class="input-field" placeholder="<?php echo e(__('Size Qty')); ?>" value="1" min="1">
																						</div>
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Price')); ?> :
																								<span>
																									<?php echo e(__('(Added with base price)')); ?>

																								</span>
																							</label>
																							<input type="number" name="size_price[]" class="input-field" placeholder="<?php echo e(__('Size Price')); ?>" value="0" min="0">
																						</div>
																						<div class="col-md-3 col-sm-6">
																							<label>
																								<?php echo e(__('Size Color')); ?> :
																								<span>
																									<?php echo e(__('(Select color of this size)')); ?>

																								</span>
																							</label>
																							<select name="color[]" class="input-field color-name"></select>
																						</div>
			
																					</div>
																				</div>
			
																				<?php endif; ?>
			
																			</div>
			
																			<a href="javascript:;" id="size-btn" class="add-more"><i class="fas fa-plus"></i><?php echo e(__('Add More')); ?> </a>
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
																			<input class="checkclick1" name="whole_check" type="checkbox" id="whole_check" value="1" <?php echo e(!empty($data->whole_sell_qty) ? "checked":""); ?>>
																			<label for="whole_check"><?php echo e(__('Allow Product Whole Sell')); ?></label>
																		</li>
																	</ul>
																</div>
															</div>
			
															<div class="<?php echo e(!empty($data->whole_sell_qty) ? "":"showbox"); ?>">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
				
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<div class="featured-keyword-area">
																			<div class="feature-tag-top-filds" id="whole-section">
																				<?php if(!empty($data->whole_sell_qty)): ?>
				
																					 <?php $__currentLoopData = $data->whole_sell_qty; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				
																				<div class="feature-area">
																					<span class="remove whole-remove"><i class="fas fa-times"></i></span>
																					<div class="row">
																						<div class="col-lg-6">
																						<input type="number" name="whole_sell_qty[]" class="input-field" placeholder="<?php echo e(__('Enter Quantity')); ?>" min="0" value="<?php echo e($data->whole_sell_qty[$key]); ?>" required="">
																						</div>
				
																						<div class="col-lg-6">
																						<input type="number" name="whole_sell_discount[]" class="input-field" placeholder="<?php echo e(__('Enter Discount Percentage')); ?>" min="0" value="<?php echo e($data->whole_sell_discount[$key]); ?>" required="">
																						</div>
																					</div>
																				</div>
				
				
																						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																				<?php else: ?>
				
				
																				<div class="feature-area">
																					<span class="remove whole-remove"><i class="fas fa-times"></i></span>
																					<div class="row">
																						<div class="col-lg-6">
																						<input type="number" name="whole_sell_qty[]" class="input-field" placeholder="<?php echo e(__('Enter Quantity')); ?>" min="0">
																						</div>
				
																						<div class="col-lg-6">
																						<input type="number" name="whole_sell_discount[]" class="input-field" placeholder="<?php echo e(__('Enter Discount Percentage')); ?>" min="0" />
																						</div>
																					</div>
																				</div>
				
																				<?php endif; ?>
																			</div>
				
																			<a href="javascript:;" id="whole-btn" class="add-fild-btn"><i class="icofont-plus"></i> <?php echo e(__('Add More Field')); ?></a>
																		</div>
																	</div>
																</div>
															</div>

														<div class="row">
															<div class="col-lg-12">
																<div class="left-area">
																	<h4 class="heading">
																		<?php echo e(__('Product Description')); ?>*
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
																			<?php echo e(__('Product Buy/Return Policy')); ?>*
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
																	<label for="allowProductSEO"><?php echo e(__('Allow Product SEO')); ?></label>
																  </div>
															</div>
														</div>

														<div class="<?php echo e(($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "showbox":""); ?>">
															<div class="row">
															  <div class="col-lg-12">
																<div class="left-area">
																	<h4 class="heading"><?php echo e(__('Meta Tags')); ?> *</h4>
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
																	  <?php echo e(__('Meta Description')); ?> *
																  </h4>
																</div>
															  </div>
															  <div class="col-lg-12">
																<div class="text-editor">
																  <textarea name="meta_description" class="input-field" placeholder="<?php echo e(__('Details')); ?>"><?php echo e($data->meta_description); ?></textarea>
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
																	  <h4 class="heading"><?php echo e(__('Feature Image')); ?> *</h4>
																  </div>
																</div>
																<div class="col-lg-12">
																	<div class="panel panel-body">
																		<div class="span4 cropme text-center" id="landscape" style="width: 100%; height: 285px; border: 1px dashed #ddd; background: #f1f1f1;">
																			<a href="javascript:;" id="crop-image" class="d-inline-block mybtn1">
																				<i class="icofont-upload-alt"></i> <?php echo e(__('Upload Image Here')); ?>

																			</a>
																		</div>
																	</div>
																</div>
															  </div>
			
															  <input type="hidden" id="feature_photo" name="photo" value="<?php echo e($data->photo); ?>" accept="image/*">
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading">
																				<?php echo e(__('Product Gallery Images')); ?> *
																			</h4>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<a href="javascript" class="set-gallery"  data-toggle="modal" data-target="#setgallery">
																			<input type="hidden" value="<?php echo e($data->id); ?>">
																				<i class="icofont-plus"></i> <?php echo e(__('Set Gallery')); ?>

																		</a>
																	</div>
																</div>

																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																			<h4 class="heading">
																				<?php echo e(__('Product Current Price')); ?>*
																			</h4>
																			<p class="sub-heading">
																				(<?php echo e(__('In')); ?> <?php echo e($sign->name); ?>)
																			</p>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<input name="price" type="number" class="input-field" placeholder="e.g 20" step="0.1" min="0" value="<?php echo e(round($data->price * $sign->value , 2)); ?>" required="">
																	</div>
																</div>
				
																<div class="row">
																	<div class="col-lg-12">
																		<div class="left-area">
																				<h4 class="heading"><?php echo e(__('Product Discount Price')); ?>*</h4>
																				<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
																		</div>
																	</div>
																	<div class="col-lg-12">
																		<input name="previous_price" step="0.1" type="number" class="input-field" placeholder="e.g 20" value="<?php echo e(round($data->previous_price * $sign->value , 2)); ?>" min="0">
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
																		<input  name="youtube" type="text" class="input-field" placeholder="Enter Youtube Video URL" value="<?php echo e($data->youtube); ?>">
																</div>
														</div>

														<div class="row">
															<div class="col-lg-12">
																<div class="left-area">
		
																</div>
															</div>
															<div class="col-lg-12">
																<div class="featured-keyword-area">
																	<div class="left-area">
																		<h4 class="title"><?php echo e(__('Feature Tags')); ?></h4>
																	</div>
		
																	<div class="feature-tag-top-filds" id="feature-section">
																		<?php if(!empty($data->features)): ?>
		
																			 <?php $__currentLoopData = $data->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		
																		<div class="feature-area">
																			<span class="remove feature-remove"><i class="fas fa-times"></i></span>
																			<div class="row">
																				<div class="col-lg-6">
																				<input type="text" name="features[]" class="input-field" placeholder="<?php echo e(__('Enter Your Keyword')); ?>" value="<?php echo e($data->features[$key]); ?>">
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
																				<input type="text" name="features[]" class="input-field" placeholder="<?php echo e(__('Enter Your Keyword')); ?>">
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
		
																	<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i class="icofont-plus"></i> <?php echo e(__('Add More Field')); ?></a>
																</div>
															</div>
														</div>

														<div class="row">
															<div class="col-lg-12">
															  <div class="left-area">
																  <h4 class="heading"><?php echo e(__('Tags')); ?> *</h4>
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

														  <div class="row text-center">
															<div class="col-6 offset-3">
																<button class="addProductSubmit-btn" type="submit"><?php echo e(__('Save')); ?></button>
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
					<h5 class="modal-title" id="exampleModalCenterTitle"><?php echo e(__('Image Gallery')); ?></h5>
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
											<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i><?php echo e(__('Upload File')); ?></label>
									</form>
								</div>
							</div>
							<div class="col-sm-6">
								<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> <?php echo e(__('Done')); ?></a>
							</div>
							<div class="col-sm-12 text-center">( <small><?php echo e(__('You can upload multiple Images.')); ?></small> )</div>
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

// Gallery Section Update

$(function($) {
		"use strict";

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
	      				$('.selected-image .row').html('<h3><?php echo e(__('No Images Found.')); ?></h3>');
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
    
     // POD Section Toggle
    $(document).on('click', '#is_pod', function() {
        if($(this).is(':checked')){
            $('#pod-section').show();
        } else {
            $('#pod-section').hide();
        }
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


})(jQuery);

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

(function($) {
		"use strict";

  $(document).ready(function() {

    let html = `<img src="<?php echo e(empty($data->photo) ? asset('assets/images/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('assets/images/products/'.$data->photo))); ?>" alt="">`;
    $(".span4.cropme").html(html);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

  });


  $('.ok').on('click', function () {

 setTimeout(
    function() {


  	var img = $('#feature_photo').val();

      $.ajax({
        url: "<?php echo e(route('admin-prod-upload-update',$data->id)); ?>",
        type: "POST",
        data: {"image":img},
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



    });

})(jQuery);

  </script>

  <script type="text/javascript">

(function($) {
		"use strict";

  $('#imageSource').on('change', function () {
    var file = this.value;
      if (file == "file"){
          $('#f-file').show();
          $('#f-link').hide();
      }
      if (file == "link"){
          $('#f-file').hide();
          $('#f-link').show();
      }
  });



$(document).on('click','#size-check',function(){
	if($(this).is(':checked')){
		$('#default_stock').addClass('d-none')
	}else{
		$('#default_stock').removeClass('d-none');
	}
})



})(jQuery);

  </script>


<?php echo $__env->make('partials.admin.product.product-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\product\edit\physical.blade.php ENDPATH**/ ?>