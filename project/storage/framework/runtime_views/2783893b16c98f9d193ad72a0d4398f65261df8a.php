<?php $__env->startSection('content'); ?>

            <div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Add Arrival')); ?> <a class="add-btn" href="<?php echo e(route('admin-arrival-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                      <ul class="links">
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Home Page Settings')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-arrival-index')); ?>"><?php echo e(__('Arrival')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-arrival-create')); ?>"><?php echo e(__('Add Arrival')); ?></a>
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
                      <form id="geniusform" action="<?php echo e(route('admin-arrival-store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                      <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                      
                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                    <label class="control-label" for="subtitle_text"><?php echo e(__('Text')); ?>*</label>
                                                  <input type="text" class="form-control" name="title" placeholder="Add title here">
                                                </div>
                                              </div>

                                              <div class="form-group">
                                                <div class="col-sm-12">
                                                  <label class="control-label" for="subtitle_text"><?php echo e(__('Header')); ?>*</label>
                                                <input type="text" class="form-control" name="header" placeholder="Add header here">
                                              </div>
                                            </div>
                                      
                        <div class="row mt-5">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Current Featured Image')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <div class="img-upload full-width-img">
                                <div id="image-preview" class="img-preview" style="background: url(<?php echo e(asset('assets/admin/images/upload.png')); ?>);">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                  </div>
                                  <p class="text"><?php echo e(__('Prefered Size: (600x770) or Square Sized Image')); ?></p>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Create Arrival')); ?></button>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\arrival\create.blade.php ENDPATH**/ ?>