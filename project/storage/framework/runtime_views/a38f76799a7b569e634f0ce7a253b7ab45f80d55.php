<tbody class="wishlist-items-wrapper">
    <?php $__currentLoopData = $wishlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wishlist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <tr id="yith-wcwl-row-103" data-row-id="103">
        <td class="product-remove">
            <div>
                <a href="<?php echo e(route('user-wishlist-remove', App\Models\Wishlist::where('user_id','=',$user->id)->where('product_id','=',$wishlist->id)->first()->id )); ?>" class="remove wishlist-remove remove_from_wishlist" title="Remove this product">×</a>
            </div>
        </td>
        <td class="product-thumbnail">
            <a href="<?php echo e(route('front.product', $wishlist->slug)); ?>"> <img src="<?php echo e($wishlist->photo ? asset('assets/images/products/'.$wishlist->photo):asset('assets/images/noimage.png')); ?>" alt=""> </a>
        </td>
        <td class="product-name"> <a href="<?php echo e(route('front.product', $wishlist->slug)); ?>"><?php echo e(mb_strlen($wishlist->name,'UTF-8') > 35 ? mb_substr($wishlist->name,0,35,'UTF-8').'...' : $wishlist->name); ?></a></td>
        <td class="product-price"> <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php echo e($wishlist->showPrice()); ?>  <small>
            <del>
                <?php echo e($wishlist->showPreviousPrice()); ?>

            </del>
        </small></bdi>
            </span>
        </td>
        <td class="product-stock-status">
            <?php if($wishlist->type == 'Physical'): ?>
            <?php if($wishlist->emptyStock()): ?>
            <div class="stock-availability out-stock"><?php echo e(('Out Of Stock')); ?></div>
            <?php else: ?>
            <div class="stock-availability in-stock text-bold"><?php echo e(('In Stock')); ?></div>
            <?php endif; ?>
            <?php endif; ?>
        </td>
        <td class="product-add-to-cart">
            <!-- Date added -->
            <button type="submit" id="addcrt" class="single_add_to_cart_button button alt single_add_to_cart_ajax_button"><?php echo e(__('Add to cart')); ?></button>
            <!-- Remove from wishlist -->
        </td>
    <input type="hidden" id="product_price" value="<?php echo e(round($wishlist->vendorPrice() * $curr->value,2)); ?>">
    <input type="hidden" id="product_id" value="<?php echo e($wishlist->id); ?>">
    <input type="hidden" id="curr_pos" value="<?php echo e($gs->currency_format); ?>">
    <input type="hidden" id="curr_sign" value="<?php echo e($curr->sign); ?>">
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>

<script>

    // Tooltip Section

    $('[data-toggle="tooltip"]').tooltip({});

    $('[rel-toggle="tooltip"]').tooltip();

    $('[data-toggle="tooltip"]').on('click', function () {
      $(this).tooltip('hide');
    })


    $('[rel-toggle="tooltip"]').on('click', function () {
      $(this).tooltip('hide');
    })

    // Tooltip Section Ends
  </script>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\frontend\ajax\wishlist.blade.php ENDPATH**/ ?>