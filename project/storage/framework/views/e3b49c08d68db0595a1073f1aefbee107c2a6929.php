<div class="top-header font-400 d-none d-lg-block py-1 text-general">
    <div class="container">
       <div class="row align-items-center">
          <div class="col-lg-4 sm-mx-none">
             <div class="d-flex align-items-center text-general">
                <i class="flaticon-phone-call flat-mini me-2 text-general"></i>
                <span class="text-dark"> <?php echo e($ps->phone); ?></span>
             </div>
          </div>
          <div class="col-lg-8 ">
             <ul class="top-links text-general ms-auto  d-flex justify-content-end">
                <li class="my-account-dropdown">
                   <div class="language-selector nice-select">
                      <i class="fas fa-globe-americas text-dark"></i>
                      <select name="language" class="language selectors nice">
                      <?php $__currentLoopData = DB::table('languages')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e(route('front.language',$language->id)); ?>" <?php echo e(Session::has('language') ? ( Session::get('language') == $language->id ? 'selected' : '' ) : (DB::table('languages')->where('is_default','=',1)->first()->id == $language->id ? 'selected' : '')); ?> >
                      <?php echo e($language->language); ?>

                      </option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                   </div>
                </li>
                <li class="my-account-dropdown">
                   <div class="currency-selector nice-select">
                      <span class="text-dark"><?php echo e(Session::has('currency') ? DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign); ?></span>
                      <select name="currency" class="currency selectors nice">
                      <?php $__currentLoopData = DB::table('currencies')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e(route('front.currency',$currency->id)); ?>" <?php echo e(Session::has('currency') ? ( Session::get('currency') == $currency->id ? 'selected' : '' ) : (DB::table('currencies')->where('is_default','=',1)->first()->id == $currency->id ? 'selected' : '')); ?>>
                      <?php echo e($currency->name); ?>

                      </option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                   </div>
                </li>
                <?php if($gs->reg_vendor == 1): ?>
                <div class=" align-items-center text-general sell">
                   <?php if(Auth::check()): ?>
                   <?php if(Auth::guard('web')->user()->is_vendor == 2): ?>
                   <a href="<?php echo e(route('vendor.dashboard')); ?>" class="sell-btn "> <?php echo e(__('Sell')); ?></a>
                   <?php else: ?>
                   <a href="<?php echo e(route('user-package')); ?>" class="sell-btn "> <?php echo e(__('Sell')); ?></a>
                   <?php endif; ?>
                </div>
                <?php else: ?>
                <div class=" align-items-center text-general">
                   <a href="<?php echo e(route('vendor.login')); ?>" class="sell-btn "> <?php echo e(__('Sell')); ?></a>
                </div>
                <?php endif; ?>
                <?php endif; ?>
             </ul>
          </div>
       </div>
    </div>
 </div>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views/partials/global/top-header.blade.php ENDPATH**/ ?>