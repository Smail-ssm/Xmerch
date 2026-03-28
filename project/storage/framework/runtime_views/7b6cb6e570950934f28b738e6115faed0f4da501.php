

<?php $__env->startSection('content'); ?>
            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                      <form id="geniusformdata" action="<?php echo e(route('admin-subscription-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__("Title")); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="<?php echo e(__("Enter Subscription Title")); ?>" required="" value="<?php echo e($data->title); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__("Cost")); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="price" placeholder="<?php echo e(__("Enter Subscription Cost")); ?>" required="" value="<?php echo e($data->price * $sign->value); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__("Days")); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="days" placeholder="<?php echo e(__("Enter Subscription Days")); ?>" required="" value="<?php echo e($data->days); ?>">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__("Product Limitations")); ?>*</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <select id="limit" name="limit" required="">
                                  <option value=""><?php echo e(__("Select an Option")); ?></option>
                                  <option <?php echo e($data->allowed_products == 0 ? "selected" : ""); ?> value="0"><?php echo e(__("Unlimited")); ?></option>
                                  <option <?php echo e($data->allowed_products != 0 ? "selected" : ""); ?> value="1"><?php echo e(__("Limited")); ?></option>
                              </select>
                          </div>
                        </div>

                        <div class="<?php echo e($data->allowed_products == 0 ? 'showbox' : ''); ?>" id="limits">
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="left-area">
                                  <h4 class="heading"><?php echo e(__("Allowed Products")); ?> *</h4>
                              </div>
                            </div>
                            <div class="col-lg-7">
                              <input type="number" min="1" class="input-field" id="allowed_products" name="allowed_products" placeholder="<?php echo e(__("Enter Allowed Products")); ?>" <?php echo e($data->allowed_products != 0 ? "required" : ""); ?> value="<?php echo e($data->allowed_products != 0 ? $data->allowed_products : '1'); ?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading">
                                   <?php echo e(__("Details")); ?> *
                              </h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="nic-edit" name="details" placeholder="<?php echo e(__("Details")); ?>"><?php echo e($data->details); ?></textarea> 
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__("Save")); ?></button>
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

<script type="text/javascript">

(function($) {
		"use strict";

$("#limit").on('change',function() {
  val = $(this).val();
    if(val == 1) {
        $("#limits").show();
        $("#allowed_products").prop("required", true);
    }
    else
    {
        $("#limits").hide();
        $("#allowed_products").prop("required", false);

    }
});

})(jQuery);

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\subscription\edit.blade.php ENDPATH**/ ?>