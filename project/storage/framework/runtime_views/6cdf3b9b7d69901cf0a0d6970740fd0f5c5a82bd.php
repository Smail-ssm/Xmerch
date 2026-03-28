<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/datatables.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Dashboard')); ?></h3>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Blog Section Start ====================-->
   <div class="container">
      <?php if(Auth::user()->is_vendor == 1): ?>
      <div class="row mb-4">
          <div class="col-12 text-center">
              <div class="p-4 rounded bg-white shadow-sm" style="border: 2px dashed #667eea;">
                  <h3 class="mb-3" style="color: #667eea;"><?php echo e(__('Ready to create something new?')); ?></h3>
                  <p class="mb-4"><?php echo e(__('Head over to your Designer Studio to upload new artwork and create products.')); ?></p>
                  <a href="<?php echo e(route('vendor-prod-create', 'physical')); ?>" class="btn btn-lg btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border:none;">
                      <i class="fas fa-palette mr-2"></i> <?php echo e(__('Open Designer Studio')); ?>

                  </a>
                  <a href="<?php echo e(route('vendor.dashboard')); ?>" class="btn btn-lg btn-outline-primary ml-2">
                       <?php echo e(__('Go to Vendor Dashboard')); ?>

                  </a>
              </div>
          </div>
      </div>
      <?php endif; ?>
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
            <?php if(Session::has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong><?php echo e(__('Success')); ?></strong> <?php echo e(Session::get('success')); ?>

               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <?php endif; ?>
            <div class="row">
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Account Information')); ?></h4>
                     <div class="user-info">
                        <h5 class="title"><?php echo e($user->name); ?></h5>
                        <p><span class="user-title"><?php echo e(__('Email')); ?>:</span> <?php echo e($user->email); ?></p>
                        <?php if($user->phone != null): ?>
                        <p><span class="user-title"><?php echo e(__('Phone')); ?>:</span> <?php echo e($user->phone); ?></p>
                        <?php endif; ?>
                        <?php if($user->fax != null): ?>
                        <p><span class="user-title"><?php echo e(__('Fax')); ?>:</span> <?php echo e($user->fax); ?></p>
                        <?php endif; ?>
                        <?php if($user->city != null): ?>
                        <p><span class="user-title"><?php echo e(__('City')); ?>:</span> <?php echo e($user->city); ?></p>
                        <?php endif; ?>
                        <?php if($user->zip != null): ?>
                        <p><span class="user-title"><?php echo e(__('Zip')); ?>:</span> <?php echo e($user->zip); ?></p>
                        <?php endif; ?>
                        <?php if($user->address != null): ?>
                        <p><span class="user-title"><?php echo e(__('Address')); ?>:</span> <?php echo e($user->address); ?></p>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('My Wallet')); ?></h4>
                     <div class="user-info">
                        <h5 class="title"><?php echo e(__('Affiliate Bonus')); ?>:</h5>
                        <h5 class="title w-price"><?php echo e(App\Models\Product::vendorConvertPrice($user->affilate_income)); ?></h5>
                        <hr>
                        <h5 class="title w-title"><?php echo e(__('Wallet Balance')); ?></h5>
                        <h5 class="title w-price mb-3"><?php echo e(App\Models\Product::vendorConvertPrice(Auth::user()->balance)); ?></h5>
                        <a href="<?php echo e(route('user-deposit-create')); ?>" class="mybtn1 sm "> <i class="fas fa-plus"></i> <?php echo e(__('Add Deposit')); ?></a>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row mt-3">
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info card c-info-box-area">
                     <div class="c-info-box box2">
                        <p><?php echo e(Auth::user()->orders()->count()); ?></p>
                     </div>
                     <div class="c-info-box-content">
                        <h6 class="title"><?php echo e(__('Total Orders')); ?></h6>
                        <p class="text"><?php echo e(__('All Time')); ?></p>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info card c-info-box-area">
                     <div class="c-info-box box1">
                        <p><?php echo e(Auth::user()->orders()->where('status','pending')->count()); ?></p>
                     </div>
                     <div class="c-info-box-content">
                        <h6 class="title"><?php echo e(__('Pending Orders')); ?></h6>
                        <p class="text"><?php echo e(__('All Time')); ?></p>
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row table-responsive-lg mt-3">
               <div class="col-lg-12">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Recent Orders')); ?></h4>
                     <div class="table-responsive">
                        <table class="table order-table" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th><?php echo e(__('#Order')); ?></th>
                                 <th><?php echo e(__('Date')); ?></th>
                                 <th><?php echo e(__('Order Total')); ?></th>
                                 <th><?php echo e(__('Order Status')); ?></th>
                                 <th><?php echo e(__('View')); ?></th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php $__currentLoopData = Auth::user()->orders()->latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                 <td data-label="<?php echo e(__('#Order')); ?>">
                                    <div>
                                       <?php echo e($order->order_number); ?>

                                    </div>
                                 </td>
                                 <td data-label="<?php echo e(__('Date')); ?>">
                                    <div>
                                       <?php echo e(date('d M Y',strtotime($order->created_at))); ?>

                                    </div>
                                 </td>
                                 <td data-label="<?php echo e(__('Order Total')); ?>">
                                    <div>
                                       <?php echo e(\PriceHelper::showAdminCurrencyPrice(($order->pay_amount  * $order->currency_value),$order->currency_sign)); ?>

                                    </div>
                                 </td>
                                 <td data-label="<?php echo e(__('Order Status')); ?>">
                                    <div class="order-status <?php echo e($order->status); ?>">
                                       <?php echo e(ucwords($order->status)); ?>

                                    </div>
                                 </td>
                                 <td data-label="<?php echo e(__('View')); ?>">
                                    <div>
                                       <a class="mybtn1 sm1" href="<?php echo e(route('user-order',$order->id)); ?>">
                                          <?php echo e(__('View Order')); ?>

                                       </a>
                                    </div>
                                 </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tbody>
                        </table>
                     </div>
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

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\user\dashboard.blade.php ENDPATH**/ ?>