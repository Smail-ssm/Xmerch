<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Register')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Vendor Register')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->

        <!--==================== Registration Form Start ====================-->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="woocommerce">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-12 mx-auto">
                                    <div class="registration-form border">
                                        <?php echo $__env->make('includes.admin.form-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <h3><?php echo e(__('Vendor Registration')); ?></h3>
                                        <form id="registerform" action="<?php echo e(route('user-register-submit')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <p>

                                                <input type="text" name="name" class="form-control" placeholder="<?php echo e(__('Full Name')); ?>"  >
                                            </p>
                                            <p>

                                                <input type="email" name="email" class="form-control" required=""  placeholder="<?php echo e(__('Email Address')); ?>" >
                                            </p>
                                            <p>

                                                <input type="text" name="phone" class="form-control" required=""  placeholder="<?php echo e(__('Phone Number')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="address" class="form-control" required=""  placeholder="<?php echo e(__('Address')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_name" class="form-control" required=""  placeholder="<?php echo e(__('Shop Name')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="owner_name" class="form-control" required=""  placeholder="<?php echo e(__('Shop Owner Name')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_number" class="form-control" required=""  placeholder="<?php echo e(__('Shop Number')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_address" class="form-control" required=""  placeholder="<?php echo e(__('Shop Address')); ?>" >
                                            </p>

                                            <p>
                                                <input type="text" name="reg_number" class="form-control" required=""  placeholder="<?php echo e(__('Registration Number')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_message" class="form-control" required=""  placeholder="<?php echo e(__('Shop Message')); ?>" >
                                            </p>

                                            <p>
                                                <input type="password" name="password" class="form-control" required=""  placeholder="<?php echo e(__('Password')); ?>" >
                                            </p>
                                            <p>
                                                <input type="password" name="password_confirmation" class="form-control" required=""  placeholder="<?php echo e(__('Confirm Password')); ?>" >
                                            </p>

                                            <?php if($gs->is_capcha == 1): ?>
                                            <div class="form-input mb-3">
                                                 <?php echo NoCaptcha::display(); ?>

                                                 <?php echo NoCaptcha::renderJs(); ?>

                                                 <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                 <p class="my-2"><?php echo e($message); ?></p>
                                                 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                             </div>
                                             <?php endif; ?>
                                             <input type="hidden" name="vendor"  value="1">
                                            <input id="processdata" type="hidden" value="<?php echo e(__('Processing...')); ?>">
                                                <button type="submit" class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register"><?php echo e(__('Register')); ?></button>
                                            </p>
                                        </form>
                                        <p>
                                                <?php echo e(__("Do have any account?")); ?><a href="<?php echo e(route('user.login')); ?>"  class="text-secondary"><?php echo e(__(' Login')); ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--==================== Registration Form Start ====================-->


<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\frontend\vendor-register.blade.php ENDPATH**/ ?>