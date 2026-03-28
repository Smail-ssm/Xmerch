

<?php $__env->startPush('styles'); ?>
<style media="screen">
.special-box {
  box-shadow: 0px 1px 6px 0px rgba(208, 208, 208, 0.61);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
  <div class="content-area">
    <div class="mr-breadcrumb">
      <div class="row align-items-center">
        <div class="col-lg-12">
            <h4 class="heading d-inline-block">
               <?php echo e(__('Manage Attribute')); ?>

              <a
              <?php if(request()->input('type') == 'category'): ?>
              href="<?php echo e(route('admin-cat-index')); ?>"
              <?php elseif(request()->input('type') == 'subcategory'): ?>
              href="<?php echo e(route('admin-subcat-index')); ?>"
              <?php elseif(request()->input('type') == 'childcategory'): ?>
              href="<?php echo e(route('admin-childcat-index')); ?>"
              <?php endif; ?>
              class="add-btn"><i class="fas fa-angle-left"></i> <?php echo e(__('Back')); ?></a>
            </h4>
            <ul class="links d-inline-block">
              <li>
                <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
              </li>
              <li>
                <a href="javascript:;"><?php echo e(__('Manage Attribute')); ?>

                </a>
              </li>
              <li><a href="javascript:;"><?php echo e(__('Edit')); ?></a></li>
            </ul>

        </div>
      </div>
    </div>

    <div class="product-area">
      <div class="row">
        <div class="col-lg-12">
          <div class="py-5" id="app">

            <div class="add-product-content">
              <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php if(session()->has('success')): ?>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                        </div>
                      </div>
                    <?php endif; ?>

                    
                    <?php if($type == 'subcategory' || $type == 'childcategory'): ?>
                      <?php
                        if ($type == 'subcategory') {
                          $category = $data->category;
                        } elseif ($type == 'childcategory') {
                          $category = $data->subcategory->category;
                        }
                      ?>

                      <?php if($category->attributes()->count() > 0): ?>
                        <?php $__currentLoopData = $category->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div class="row">
                            <div class="col-lg-3">
                              <div class="left-area">
                                  <h4 class="heading"><?php echo e(__($attribute->name)); ?> *</h4>
                                  <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                              </div>
                            </div>
                            <div class="col-lg-9">
                              <?php $__currentLoopData = $attribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="customRadioInline<?php echo e($option->id); ?>" name="<?php echo e($attribute->id); ?>" class="custom-control-input">
                                  <label class="custom-control-label" for="customRadioInline<?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
                                </div>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                          </div>
                          <br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                    


                    
                    <?php if($type == 'childcategory'): ?>
                      <?php if($data->subcategory->attributes()->count() > 0): ?>
                        <?php $__currentLoopData = $data->subcategory->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div class="row">
                            <div class="col-lg-3">
                              <div class="left-area">
                                  <h4 class="heading"><?php echo e(__($attribute->name)); ?> *</h4>
                                  <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                              </div>
                            </div>
                            <div class="col-lg-9">
                              <?php $__currentLoopData = $attribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="custom-control custom-radio custom-control-inline">
                                  <input type="radio" id="customRadioInline<?php echo e($option->id); ?>" name="<?php echo e($attribute->id); ?>" class="custom-control-input">
                                  <label class="custom-control-label" for="customRadioInline<?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
                                </div>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                          </div>
                          <br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    <?php endif; ?>
                    


                    <?php $__currentLoopData = $data->attributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="row">
                        <div class="col-lg-3">
                          <div class="left-area">
                              <h4 class="heading"><?php echo e(__($attribute->name)); ?> *</h4>
                              <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <?php $__currentLoopData = $attribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" id="customRadioInline<?php echo e($option->id); ?>" name="<?php echo e($attribute->id); ?>" class="custom-control-input">
                              <label class="custom-control-label" for="customRadioInline<?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
                            </div>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="col-md-3">
                          <a href="<?php echo e(route('admin-attr-edit', $attribute->id)); ?>" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                          <form class="d-inline-block" action="<?php echo e(route('admin-attr-delete', $attribute->id)); ?>" method="get">
                            <button type="submit" class="btn btn-danger" data-target="#confirm-delete" data-toggle="modal"><i class="fas fa-trash-alt"></i></button>
                          </form>
                        </div>
                      </div>
                      <br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
           <div class="submit-loader">
              <img  src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
           </div>
           <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
           </div>
           <div class="modal-body">
           </div>
           <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
           </div>
        </div>
     </div>
  </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\attribute\manage.blade.php ENDPATH**/ ?>