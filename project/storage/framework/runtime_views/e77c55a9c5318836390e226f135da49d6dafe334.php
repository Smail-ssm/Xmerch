

<?php $__env->startSection('content'); ?>
            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                      <form id="geniusformdata" action="<?php echo e(route('admin-service-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


												<div class="row">
													<div class="col-lg-4">
													  <div class="left-area">
														  <h4 class="heading"><?php echo e(__('Select Language')); ?>*</h4>
													  </div>
													</div>
													<div class="col-lg-7">
														<select name="language_id" required="">
															  <?php $__currentLoopData = DB::table('languages')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ldata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																<option value="<?php echo e($ldata->id); ?>" <?php echo e($ldata->id == $data->language_id ? 'selected' : ''); ?>><?php echo e($ldata->language); ?></option>
															  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
														  </select>
													</div>
												</div>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Title')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="<?php echo e(__('Title')); ?>" value="<?php echo e($data->title); ?>" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Current Featured Image')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <div class="img-upload">
                                <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->photo ? asset('assets/images/services/'.$data->photo):asset('assets/images/noimage.png')); ?>);">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                  </div>
                                  <p class="text"><?php echo e(__('Prefered Size: (600x600) or Square Sized Image')); ?></p>
                            </div>

                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading">
                                   <?php echo e(__('Description')); ?> *
                              </h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="input-field" name="details" placeholder="<?php echo e(__('Description')); ?>"><?php echo e($data->details); ?></textarea> 
                          </div>
                        </div>



                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Save')); ?></button>
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\service\edit.blade.php ENDPATH**/ ?>