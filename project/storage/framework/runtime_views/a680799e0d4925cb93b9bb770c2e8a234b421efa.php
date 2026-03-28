<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Product Details')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Product Details')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<?php echo $__env->make('partials.product-details.top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!--==================== Product Description Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row justify-content-between">
         <div class="col-lg-8">
            <div class="section-head border-bottom">
               <div class="woocommerce-tabs wc-tabs-wrapper ps-0">
                  <ul class="nav nav-pills wc-tabs" id="pills-tab-one" role="tablist">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-description-one-tab" data-bs-toggle="pill" href="#pills-description-one" role="tab" aria-controls="pills-description-one" aria-selected="true"><?php echo e(__('Description')); ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-information-one-tab" data-bs-toggle="pill" href="#pills-information-one" role="tab" aria-controls="pills-information-one" aria-selected="true"><?php echo e(__('Buy / Return Policy')); ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-reviews-one-tab" data-bs-toggle="pill" href="#pills-reviews-one" role="tab" aria-controls="pills-reviews-one" aria-selected="true"><?php echo e(__('Reviews')); ?></a>
                     </li>
                     <?php if($gs->is_comment == 1): ?>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-comment-one-tab" data-bs-toggle="pill" href="#pills-comment-one" role="tab" aria-controls="pills-comment-one" aria-selected="true"><?php echo e(__('Comments')); ?></a>
                     </li>
                     <?php endif; ?>
                  </ul>
               </div>
            </div>
            <div class="woocommerce-tabs wc-tabs-wrapper ps-0 mt-0">
               <div class="tab-content" id="pills-tabContent-one">
                  <div class="tab-pane fade show active woocommerce-Tabs-panel woocommerce-Tabs-panel--description mb-5 mt-4" id="pills-description-one" role="tabpanel" aria-labelledby="pills-description-one-tab">
                     <?php echo clean($productt->details , array('Attr.EnableID' => true)); ?>

                  </div>
                  <div class="tab-pane fade mb-5" id="pills-information-one" role="tabpanel" aria-labelledby="pills-information-one-tab">
                     <div class="row">
                        <div class="col-8">
                           <?php echo clean($productt->policy , array('Attr.EnableID' => true)); ?>

                        </div>
                     </div>
                  </div>
                  
                  <?php if($gs->is_comment == 1): ?>
                  <div class="tab-pane fade" id="pills-comment-one" role="tabpanel" aria-labelledby="pills-comment-one-tab">
                     <?php echo $__env->make('partials.product-details.comment-replies', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  </div>
                  <?php endif; ?>
                  <div class="tab-pane fade" id="pills-reviews-one" role="tabpanel" aria-labelledby="pills-reviews-one-tab">
                     <?php echo $__env->make('partials.product-details.reviews', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                  </div>
               </div>
            </div>
         </div>
         </div>

         <?php if($productt->user_id != 0 && $vendors->count() > 0): ?>
         <div class="col-lg-3">

                    <div class="section-head border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex section-head-side-title">
                            <h5 class="font-700 text-dark mb-0"><?php echo e(__("Seller's Product")); ?></h5>
                        </div>
                    </div>

                    <div class="product-style-2 owl-carousel owl-nav-hover-primary nav-top-right single-carousel dot-disable product-list e-bg-white">

                        <?php $__currentLoopData = $vendors->take(9)->chunk(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <div class="item">
                            <div class="row row-cols-1">
                                <?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <div class="col mb-1">
                                    <div class="product type-product">
                                        <div class="product-wrapper">
                                            <div class="product-image">
                                                <a href="<?php echo e(route('front.product', $prod->slug)); ?>" class="woocommerce-LoopProduct-link"><img class="lazy" data-src="<?php echo e($prod->photo ? asset('assets/images/products/'.$prod->photo ):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
                                                <div class="wishlist-view">
                                                    <div class="quickview-button">
                                                        <a class="quickview-btn" href="<?php echo e(route('front.product', $prod->slug)); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Quick View" aria-label="Quick View"><?php echo e(__('Quick View')); ?></a>
                                                    </div>
                                                    <div class="whishlist-button">
                                                        <a class="add_to_wishlist" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                                                    </div>
                                            </div>
                                            </div>
                                            <div class="product-info">
                                                <h3 class="product-title"><a href="<?php echo e(route('front.product', $prod->slug)); ?>"><?php echo e($prod->showName()); ?></a></h3>
                                                <div class="product-price">
                                                    <div class="price">
                                                        <ins><?php echo e($prod->showPrice()); ?></ins>
                                                        <del><?php echo e($prod->showPreviousPrice()); ?></del>
                                                    </div>
                                                    <div class="on-sale"><span><?php echo e(round((double)$prod->offPercentage())); ?></span><span>% off</span></div>
                                                </div>
                                                <div class="shipping-feed-back">
                                                    <div class="star-rating">
                                                        <div class="rating-wrap">
                                                            <p><i class="fas fa-star"></i><span> <?php echo e(App\Models\Rating::ratings($prod->id)); ?></span></p>
                                                        </div>
                                                        <div class="rating-counts-wrap">
                                                            <p>(<?php echo e(App\Models\Rating::ratingCount($prod->id)); ?>)</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
         </div>
         <?php endif; ?>
      </div>
   </div>
</div>
<!--==================== Product Description Section End ====================-->
<!--==================== Related Products Section Start ====================-->
<div class="full-row pt-0">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="section-head border-bottom d-flex justify-content-between align-items-end mb-2">
               <div class="d-flex section-head-side-title">
                  <h4 class="font-600 text-dark mb-0"><?php echo e(__('Related Products')); ?></h4>
               </div>
            </div>
         </div>
         <div class="col-12">
            <div class="products product-style-1 owl-mx-5">
               <div class="five-carousel owl-carousel nav-top-right e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
                  <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="item">
                     <div class="product type-product">
                        <div class="product-wrapper">
                           <div class="product-image">
                              <a href="<?php echo e(route('front.product', $item->slug)); ?>" class="woocommerce-LoopProduct-link"><img class="lazy" data-src="<?php echo e($item->photo ? asset('assets/images/products/'.$item->photo):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
                              <div class="on-sale">-<?php echo e(round((double)$item->offPercentage())); ?>%</div>
                              <div class="hover-area">
                                 <?php if($item->product_type == "affiliate"): ?>
                                 <div class="cart-button">
                                    <a href="javascript:;" data-href="<?php echo e($item->affiliate_link); ?>" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                                 </div>
                                 <?php else: ?>
                                 <?php if($item->emptyStock()): ?>
                                 <div class="closed">
                                    <a class="cart-out-of-stock button add_to_cart_button" href="#" title="<?php echo e(__('Out Of Stock')); ?>" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                                 </div>
                                 <?php else: ?>
                                 <div class="cart-button">
                                    <a href="javascript:;" data-href="<?php echo e(route('product.cart.add',$item->id)); ?>" class="add-cart button add_to_cart_button" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                                 </div>
                                 <div class="closed">
                                    <a  class="button add_to_cart_button add-to-cart-quick" href="javascript:;" data-bs-toggle="tooltip" data-href="<?php echo e(route('product.cart.quickadd',$item->id)); ?>" data-bs-placement="right" title="<?php echo e(__('Buy Now')); ?>" data-bs-original-title="<?php echo e(__('Buy Now')); ?>"><i class="flaticon-shopping-cart-1 flat-mini mx-auto"></i></a>
                                 </div>
                                 <?php endif; ?>
                                 <?php endif; ?>
                                 <?php if(Auth::check()): ?>
                                 <div class="wishlist-button">
                                    <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$item->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                                 </div>
                                 <?php else: ?>
                                 <div class="wishlist-button">
                                    <a class="add_to_wishlist button add_to_cart_button" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                                 </div>
                                 <?php endif; ?>
                                 <div class="compare-button">
                                    <a class="compare button add_to_cart_button" data-href="<?php echo e(route('product.compare.add',$item->id)); ?>" href="javascript:;" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Compare" aria-label="Compare"><?php echo e(__('Compare')); ?></a>
                                 </div>
                              </div>
                           </div>
                           <div class="product-info">
                              <h3 class="product-title"><a href="<?php echo e(route('front.product', $item->slug)); ?>"><?php echo e($item->showName()); ?></a></h3>
                              <div class="product-price">
                                 <div class="price">
                                    <ins><?php echo e($item->showPrice()); ?></ins>
                                    <del><?php echo e($item->showPreviousPrice()); ?></del>
                                 </div>
                              </div>
                              <div class="shipping-feed-back">
                                 <div class="star-rating">
                                    <div class="rating-wrap">
                                       <p><i class="fas fa-star"></i><span> <?php echo e(App\Models\Rating::ratings($item->id)); ?> (<?php echo e(App\Models\Rating::ratingCount($item->id)); ?>)</span></p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Related Products Section End ====================-->
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php if($gs->is_report): ?>

<?php if(Auth::check()): ?>



<div class="modal fade report" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="report-modal-Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

 <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                    <div class="login-area">
                        <div class="header-area forgot-passwor-area">
                            <h4 class="title text-center"><?php echo e(__(('REPORT PRODUCT'))); ?></h4>
                            <p class="text"><?php echo e(__('Please give the following details')); ?></p>
                        </div>
                        <div class="login-form">

                            <form id="reportform" action="<?php echo e(route('product.report')); ?>" method="POST">

                              <?php echo $__env->make('includes.admin.form-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                <?php echo e(csrf_field()); ?>

                                <input type="hidden" name="user_id" value="<?php echo e(Auth::user()->id); ?>">
                                <input type="hidden" name="product_id" value="<?php echo e($productt->id); ?>">
                                <div class="form-input">
                                    <input type="text" name="title" class="User Name form-control border" placeholder="<?php echo e(__('Enter Report Title')); ?>" required="">

                                </div>
                                <br>

                                <div class="form-input">
                                  <textarea name="note" class="User Name form-control border" placeholder="<?php echo e(__('Enter Report Note')); ?>" required=""></textarea>
                                </div>

                                <button type="submit" class="submit-btn"><?php echo e(__('SUBMIT')); ?></button>
                            </form>
                        </div>
                    </div>
      </div>
    </div>
  </div>
</div>



<?php endif; ?>

<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="<?php echo e(asset('assets/front/js/jquery.elevatezoom.js')); ?>"></script>

<!-- Initializing the slider -->


<script type="text/javascript">
lazy();

    (function($) {
		"use strict";

         //initiate the plugin and pass the id of the div containing gallery images
      $("#single-image-zoom").elevateZoom({
         gallery: 'gallery_09',
         zoomType: "inner",
         cursor: "crosshair",
         galleryActiveClass: 'active',
         imageCrossfade: true,
         loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'
      });
      //pass the images to Fancybox
      $("#single-image-zoom").bind("click", function(e) {
         var ez = $('#single-image-zoom').data('elevateZoom');
         $.fancybox(ez.getGalleryList());
         return false;
      });

          $(document).on("submit", "#emailreply" , function(){
          var token = $(this).find('input[name=_token]').val();
          var subject = $(this).find('input[name=subject]').val();
          var message =  $(this).find('textarea[name=message]').val();
          var email = $(this).find('input[name=email]').val();
          var name = $(this).find('input[name=name]').val();
          var user_id = $(this).find('input[name=user_id]').val();
          $('#eml').prop('disabled', true);
          $('#subj').prop('disabled', true);
          $('#msg').prop('disabled', true);
          $('#emlsub').prop('disabled', true);
     $.ajax({
            type: 'post',
            url: "<?php echo e(URL::to('/user/user/contact')); ?>",
            data: {
                '_token': token,
                'subject'   : subject,
                'message'  : message,
                'email'   : email,
                'name'  : name,
                'user_id'   : user_id
                  },
            success: function( data) {
          $('#eml').prop('disabled', false);
          $('#subj').prop('disabled', false);
          $('#msg').prop('disabled', false);
          $('#subj').val('');
          $('#msg').val('');
          $('#emlsub').prop('disabled', false);
        if(data == 0)
          toastr.error("Email Not Found");
        else
          toastr.success("Message Sent");
          $('#vendorform').modal('hide');
            }
        });
          return false;
        });

})(jQuery);



</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\frontend\product.blade.php ENDPATH**/ ?>