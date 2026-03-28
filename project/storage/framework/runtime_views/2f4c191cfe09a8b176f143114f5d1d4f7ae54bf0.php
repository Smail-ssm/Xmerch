

<?php $__env->startSection('content'); ?>
            <div class="content-area">

              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area" id="modalEdit">
                        <?php echo $__env->make('includes.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                      <form id="geniusformdata" action="<?php echo e(route('admin-featuredlink-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Name')); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="name" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e($data->name); ?>" required="">
                          </div>
                        </div>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Link')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="link" placeholder="<?php echo e(__('Link')); ?>" value="<?php echo e($data->link); ?>" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Current Icon')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <div class="img-upload">
                                <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->photo ? asset('assets/images/featuredlink/'.$data->photo):asset('assets/images/noimage.png')); ?>);">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Icon')); ?></label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                  </div>

                            </div>

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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\featuredlink\edit.blade.php ENDPATH**/ ?>