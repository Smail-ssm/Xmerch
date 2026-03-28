<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Reward')); ?>


                </h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Reward ')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->



<!--==================== Blog Section Start ====================-->
<div class="full-row">
    <div class="container">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <?php echo $__env->make('partials.user.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-40 widget_categories bg-light account-info">

                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Reward Point')); ?>

                                <a class="mybtn1" href="<?php echo e(url()->previous()); ?>"> <i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a>
                            </h4>
                            <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                            <form id="userform" action="<?php echo e(route('user-reward-convert-submit')); ?>" class="pay-form" class="form-horizontal" action="" method="POST" enctype="multipart/form-data">

                                   <?php echo e(csrf_field()); ?>


                                   <?php echo $__env->make('includes.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                   <div class="form-group mb-3">
                                       <label class="control-label col-sm-4"><?php echo e(__('Current Point')); ?> : <?php echo e(Auth::user()->reward); ?></label>
                                   </div>
                                   <div class="form-group mb-4">
                                       <label class="control-label col-sm-4"><?php echo e($gs->reward_point); ?> <?php echo e(__('Reward Point To (USD)')); ?> $<?php echo e($gs->reward_dolar); ?></label>
                                   </div>

                                     <div class="form-group mt-2">
                                       <div class="row">
                                         <div class="col-md-6">
                                           <label class="control-label col-sm-12" for="reward"><?php echo e(__('Reward Point')); ?> *  </label>
                                             <div class="input-group mb-3">
                                               <input type="text" id="reward" name="reward_point" class="form-control border" placeholder="<?php echo e(__('Reward Point')); ?>" value="<?php echo e(old('reward_point')); ?>" required>

                                             </div>
                                         </div>
                                       </div>
                                     </div>

                                     <div class="form-group mt-2">
                                       <div class="row">
                                         <div class="col-md-6">
                                           <label class="control-label col-sm-12" for="name"><?php echo e(__('Convert Total')); ?> *  </label>
                                             <div class="input-group mb-3">
                                               <input type="text" id="convert_total" class="form-control border" placeholder="<?php echo e(__('Convert Total')); ?>" value="" readonly>
                                               <div class="input-group-append d-flex">
                                                 <span class="input-group-text" id="basic-addon2"><?php echo e($curr->name); ?></span>
                                               </div>
                                             </div>
                                         </div>
                                       </div>
                                     </div>
                               <hr>
                               <div class="add-product-footer">
                                   <button type="button" id="check" class="mybtn1"><?php echo e(__('Check')); ?> </button>
                                   <button id="final-btn" type="submit" class="mybtn1"><?php echo e(__('Convert')); ?> </button>
                               </div>
                           </form>



                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--==================== Blog Section End ====================-->

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


<script type="text/javascript">

  $(document).on('click','#check',function(){
    let point = parseInt($('#reward').val());
    if(!isNaN(point)) {
      if(point <'<?php echo e($gs->reward_point); ?>'){
        toastr.error('Minimum Convert Point is <?php echo e($gs->reward_point); ?>');
    }else if(point >'<?php echo e($user->reward); ?>'){
        toastr.error('Your reward point is ' + '<?php echo e($user->reward); ?>');
    }else{
        let amount = (point / '<?php echo e($gs->reward_point); ?>' )* '<?php echo e($gs->reward_dolar); ?>';
        $('#convert_total').val(amount);
    }
    }
  })

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\user\reward\convert.blade.php ENDPATH**/ ?>