

<?php $__env->startSection('content'); ?>
  <div class="content-area">
    <div class="mr-breadcrumb">
      <div class="row align-items-center">
        <div class="col-lg-12">
            <h4 class="heading d-inline-block">
              <span class="text-capitalize"></span> <?php echo e(__('Manage Attribute')); ?>

              <a href="<?php echo e(url()->previous()); ?>" class="add-btn"><i class="fas fa-angle-left"></i> <?php echo e(__('Back')); ?></a>
            </h4>
            <ul class="links d-inline-block">
              <li>
                <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
              </li>
              <li><a href="javascript:;"><?php echo e(__('Manage Attribute')); ?></a></li>
              <li>
                <a href="#"><span class="text-capitalize"></span> <?php echo e(__('Attribute')); ?></a>
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

            <div class="add-product-content1">
              <div class="row">
                <div class="col-md-6 offset-md-3">
                  <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                  <form id="geniusform" action="<?php echo e(route('admin-attr-update', $attr->id)); ?>" method="post" enctype="multipart/form-data">
                      <?php echo e(csrf_field()); ?>


                      <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                               <label for=""><strong><?php echo e(__('Name')); ?></strong></label>
                               <div class="">
                                 <input type="text" class="input-field" name="name" value="<?php echo e($attr->name); ?>" placeholder="<?php echo e(__('Enter Name')); ?>" required>
                               </div>
                               <?php if($errors->has('name')): ?>
                                 <p class="text-danger mb-0"><?php echo e($errors->first('name')); ?></p>
                               <?php endif; ?>
                          </div>
                        </div>
                      </div>

                      <div class="row" id="optionarea">
                        <div class="col-md-12">
                          <div class="form-group">
                               <label for=""><strong><?php echo e(__('Options')); ?></strong></label>
                               <div class="row mb-2 counterrow" v-for="option in options" :key="option.id">
                                 <div class="col-md-11">
                                   <input class="input-field optionin" type="text" name="options[]" :value="option.name" placeholder="<?php echo e(__('Option label')); ?>" required>
                                 </div>

                                 <div class="col-md-1">
                                   <button type="button" class="btn btn-danger text-white" @click="removeExistingOption(option.id)"><i class="fa fa-times"></i></button>
                                 </div>
                               </div>
                               <div class="row mb-2 counterrow" v-for="n in counter" :id="'newOption'+n">
                                 <div class="col-md-11">
                                   <input class="input-field optionin" type="text" name="options[]" value="" placeholder="<?php echo e(__('Option label')); ?>" required>
                                 </div>

                                 <div class="col-md-1">
                                   <button type="button" class="btn btn-danger text-white" @click="removeOption(n)"><i class="fa fa-times"></i></button>
                                 </div>
                               </div>
                               <button type="button" class="btn btn-success text-white" @click="addOption()"><i class="fa fa-plus"></i> <?php echo e(__('Add Option')); ?></button>
                               <?php if($errors->has('options.*') || $errors->has('options')): ?>
                                 <p class="text-danger mb-0"><?php echo e($errors->first('options.*')); ?></p>
                                 <p class="text-danger mb-0"><?php echo e($errors->first('options')); ?></p>
                               <?php endif; ?>
                          </div>
                        </div>
                      </div>


                      <div class="row mt-1">
                        <div class="col-lg-12">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="priceStatus1" name="price_status" class="custom-control-input" <?php echo e($attr->price_status == 1 ? 'checked' : ''); ?> value="1">
                            <label class="custom-control-label" for="priceStatus1">Allow Price Field</label>
                          </div>
                        </div>
                      </div>

                      <div class="row mb-4">
                        <div class="col-lg-12">
                          <div class="custom-control custom-checkbox">
                            <input type="checkbox" id="detailsStatus1" name="details_status" class="custom-control-input" <?php echo e($attr->details_status == 1 ? 'checked' : ''); ?> value="1">
                            <label class="custom-control-label" for="detailsStatus1">Show on Details Page</label>
                          </div>
                        </div>
                      </div>


                      <div class="text-left">
                        <button type="submit" class="btn btn-primary addProductSubmit-btn"><?php echo e(__('UPDATE FIELD')); ?></button>
                      </div>
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
  <script>
    var app = new Vue({
      el: '#app',
      data: {
        options: [],
        counter: 0
      },
      created() {
        $.get("<?php echo e(route('admin-attr-options', $attr->id)); ?>", (data) => {
          for (var i = 0; i < data.length; i++) {
            this.options.push(data[i]);
          }
        });
      },
      methods: {
        addOption() {
          this.counter++;
        },
        removeExistingOption(optionid) {
          for (var i = 0; i < this.options.length; i++) {
            if (this.options[i].id == optionid) {
              this.options.splice(i, 1);
            }
          }
        },
        removeOption(n) {
          $("#newOption"+n).remove();
        }
      }
    })
  </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\attribute\edit.blade.php ENDPATH**/ ?>