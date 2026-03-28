<div class="product type-product">
    <div class="product-wrapper">
       <div class="product-image">
          <a href="<?php echo e(route('front.product', $prod->slug)); ?>" class="woocommerce-LoopProduct-link"><img class="lazy" data-src="<?php echo e($prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
          <?php if(!empty($prod->features)): ?>
          <div class="product-variations">
             <?php $__currentLoopData = $prod->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <span class="active sale"><a href="#" style="background-color: <?php echo e($prod->colors[$key]); ?>"><?php echo e($prod->features[$key]); ?></a></span>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          <?php if(round($prod->offPercentage() )>0): ?>
          <div class="on-sale">- <?php echo e(round($prod->offPercentage() )); ?>%</div>
          <?php endif; ?>

          <div class="hover-area">
            <?php if($prod->product_type == "affiliate"): ?>
            <div class="cart-button">
               <a href="javascript:;" data-href="<?php echo e($product->affiliate_link); ?>" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
            </div>
            <?php else: ?>
            <?php if($prod->emptyStock()): ?>
            <div class="closed">
               <a class="cart-out-of-stock button add_to_cart_button" href="#" title="<?php echo e(__('Out Of Stock')); ?>" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
            </div>
            <?php else: ?>
            <div class="cart-button">
               <a href="javascript:;" data-href="<?php echo e(route('product.cart.add',$prod->id)); ?>" class="add-cart button add_to_cart_button" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
            </div>
            <div class="cart-button buynow">
                <a  class="button add_to_cart_button add-to-cart-quick" href="javascript:;" data-bs-toggle="tooltip" data-href="<?php echo e(route('product.cart.quickadd',$prod->id)); ?>" data-bs-placement="right" title="<?php echo e(__('Buy Now')); ?>" data-bs-original-title="<?php echo e(__('Buy Now')); ?>"></a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            <?php if(Auth::check()): ?>
            <div class="wishlist-button">
               <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$prod->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
            </div>
            <?php else: ?>
            <div class="wishlist-button">
               <a class="add_to_wishlist button add_to_cart_button" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
            </div>
            <?php endif; ?>
            <div class="compare-button">
               <a class="compare button add_to_cart_button" data-href="<?php echo e(route('product.compare.add',$prod->id)); ?>" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Compare" aria-label="Compare"><?php echo e(__('Compare')); ?></a>
            </div>
         </div>
       </div>
       <div class="product-info">
          <h3 class="product-title"><a href="<?php echo e(route('front.product', $prod->slug)); ?>"><?php echo e($prod->showName()); ?></a></h3>
          <div class="product-price">
             <div class="price">
                <ins><?php echo e($prod->showPrice()); ?> </ins>
                <del><?php echo e($prod->showPreviousPrice()); ?></del>
             </div>
          </div>
          <div class="shipping-feed-back">
             <div class="star-rating">
                <div class="rating-wrap">
                   <p><i class="fas fa-star"></i><span> <?php echo e(App\Models\Rating::ratings($prod->id)); ?> (<?php echo e(App\Models\Rating::ratingCount($prod->id)); ?>)</span></p>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>

<?php /**PATH C:\laragon\www\xmerch\project\resources\views\partials\product\home-product.blade.php ENDPATH**/ ?>