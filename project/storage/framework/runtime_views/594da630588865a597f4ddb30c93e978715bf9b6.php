<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Pricing Plans')); ?>


                </h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Pricing Plans')); ?></li>
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
                <div class="user-profile-details">
                    <div class="row">
                        <?php $__currentLoopData = $subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-lg-6">
                                <div class="elegant-pricing-tables style-2 text-center">
                                    <div class="pricing-head">
                                        <h3><?php echo e($sub->title); ?></h3>
                                        <?php if($sub->price  == 0): ?>
                                        <span class="price">
                                        <span class="price-digit"><?php echo e(__('Free')); ?></span>
                                        </span>
                                        <?php else: ?>
                                        <span class="price">
                                            <sup><?php echo e($curr->sign); ?></sup>
                                            <span class="price-digit"><?php echo e(round($sub->price * $curr->value,2)); ?></span><br>
                                            <span class="price-month"><?php echo e($sub->days); ?> <?php echo e(__('Day(s)')); ?></span>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pricing-detail">
                                        <?php echo clean($sub->details , array('Attr.EnableID' => true)); ?>

                                    </div>
                                <?php if(!empty($package)): ?>
                                    <?php if($package->subscription_id == $sub->id): ?>
                                        <a href="javascript:;" class="btn btn-default"><?php echo e(__('Current Plan')); ?></a>
                                        <br>
                                        <?php if(Carbon\Carbon::now()->format('Y-m-d') > $user->date): ?>
                                        <small class="hover-white"><?php echo e(__('Expired on:')); ?> <?php echo e(date('d/m/Y',strtotime($user->date))); ?></small>
                                        <?php else: ?>
                                        <small class="hover-white"><?php echo e(__('Ends on:')); ?> <?php echo e(date('d/m/Y',strtotime($user->date))); ?></small>
                                        <?php endif; ?>
                                         <a href="<?php echo e(route('user-vendor-request',$sub->id)); ?>" class="hover-white"><u><?php echo e(__('Renew')); ?></u></a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('user-vendor-request',$sub->id)); ?>" class="btn btn-default"><?php echo e(__('Get Started')); ?></a>
                                        <br><small>&nbsp;</small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="<?php echo e(route('user-vendor-request',$sub->id)); ?>" class="btn btn-default"><?php echo e(__('Get Started')); ?></a>
                                    <br><small>&nbsp;</small>
                                <?php endif; ?>

                                </div>
                            </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--==================== Blog Section End ====================-->

<!-- Order Tracking modal Start-->
<div class="modal fade" id="order-tracking-modal" role="dialog"  data-bs-backdrop="static" data-bs-keyboard="false"  aria-labelledby="order-tracking-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content text-center">
        <div class="modal-header">
            <h5 class="modal-title pt-3 pl-3 mx-auto"> <b><?php echo e(__('Order Tracking')); ?></b> </h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="order-track">

        </div>
        </div>
    </div>
</div>
<!-- Order Tracking modal End -->

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>


<script type="text/javascript">



</script>

<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\user\package\index.blade.php ENDPATH**/ ?>