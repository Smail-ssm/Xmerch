<a href="<?php echo e(route('front.product', $prod->slug)); ?>" class="single-product-flas">
    <div class="img">
       <img class="lazy" data-src="<?php echo e($prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png')); ?>" alt="">
       <?php if(!empty($prod->features)): ?>
       <div class="sell-area">
          <?php $__currentLoopData = $prod->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <span class="sale" style="background-color:<?php echo e($prod->colors[$key]); ?>">
          <?php echo e($prod->features[$key]); ?>

          </span>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       </div>
       <?php endif; ?>
    </div>
    <div class="content">
       <h4 class="name">
          <?php echo e($prod->showName()); ?>

       </h4>
       <ul class="stars d-flex">
          <div class="ratings">
             <div class="empty-stars"></div>
             <div class="full-stars" style="width:<?php echo e(App\Models\Rating::ratings($prod->id)); ?>%"></div>
          </div>
          <li class="ml-2">
             <span>(<?php echo e(App\Models\Rating::ratingCount($prod->id)); ?>)</span>
          </li>
       </ul>
       <div class="price">
          <span class="new-price"><?php echo e($prod->showPrice()); ?></span>
          <small class="old-price"><del><?php echo e($prod->showPreviousPrice()); ?></del></small>
       </div>
       <ul class="action-meta">
          
          <?php if(Auth::check()): ?>
          <li>
             <span class="wish add-to-wish" data-href="<?php echo e(route('user-wishlist-add',$prod->id)); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Wish')); ?>">
             <i class="far fa-heart"></i>
             </span>
          </li>
          <?php else: ?>
          <li>
             <span rel-toggle="tooltip" title="<?php echo e(__('Wish')); ?>" data-placement="top" class="wish add-to-wish" data-toggle="modal" data-target="#user-login">
             <i class="far fa-heart"></i>
             </span>
          </li>
          <?php endif; ?>
          
          
          <?php if($prod->product_type == "affiliate"): ?>
          <li>
             <span class="cart-btn affilate-btn" data-href="<?php echo e($prod->affiliate_link); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Buy Now')); ?>">
             <i class="icofont-cart"></i>
             </span>
          </li>
          <?php else: ?>
          <?php if($prod->emptyStock()): ?>
          <li>
             <span class="cart-btn cart-out-of-stock" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Out Of Stock')); ?>">
             <i class="icofont-close-circled"></i>
             </span>
          </li>
          <?php else: ?>
          <li>
             <span class="cart-btn add-to-cart add-to-cart-btn" data-href="<?php echo e(route('product.cart.add',$prod->id)); ?>"  title="<?php echo e(__('Add To Cart')); ?>">
             <i class="icofont-cart"></i>
             </span>
          </li>
          <li>
             <span class="cart-btn quick-view" data-href="<?php echo e(route('product.quick',$prod->id)); ?>" rel-toggle="tooltip" data-placement="top" title="<?php echo e(__('Quick View')); ?>" data-toggle="modal" data-target="#quickview">
             <i class="fas fa-eye"></i>
             </span>
          </li>
          <?php endif; ?>
          <?php endif; ?>
          
          
          <li>
             <span class="compear add-to-compare" data-href="<?php echo e(route('product.compare.add',$prod->id)); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Compare')); ?>">
             <i class="fas fa-random"></i>
             </span>
          </li>
          
       </ul>
       <div class="deal-counter">
          <div data-countdown="<?php echo e($prod->discount_date); ?>"></div>
       </div>
    </div>
 </a>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\partials\product\flash-product.blade.php ENDPATH**/ ?>