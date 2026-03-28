<section class="product-details-area">
    <div id="quick-section">
    <div class="left-area-top-info">
        <div class="row">
          <div class="col-lg-5">
              <div class="xzoom-container">
                  <img class="xzoom5" id="xzoom-magnific"
                    src="<?php echo e(filter_var($product->photo, FILTER_VALIDATE_URL) ?$product->photo:asset('assets/images/products/'.$product->photo)); ?>"
                    xoriginal="<?php echo e(filter_var($product->photo, FILTER_VALIDATE_URL) ?$product->photo:asset('assets/images/products/'.$product->photo)); ?>" />
                  <div class="xzoom-thumbs">
                    <div class="all-slider">

                      <a href="<?php echo e(filter_var($product->photo, FILTER_VALIDATE_URL) ?$product->photo:asset('assets/images/products/'.$product->photo)); ?>">
                        <img class="xzoom-gallery5" width="80" src="<?php echo e(filter_var($product->photo, FILTER_VALIDATE_URL) ? $product->photo:asset('assets/images/products/'.$product->photo)); ?>">
                      </a>

                      <?php $__currentLoopData = $product->galleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                      <a href="<?php echo e(asset('assets/images/galleries/'.$gal->photo)); ?>">
                        <img class="xzoom-gallery5" width="80" src="<?php echo e(asset('assets/images/galleries/'.$gal->photo)); ?>" >
                      </a>

                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </div>
                  </div>
                </div>



          </div>
          <div class="col-lg-7">
            <div class="product-info">
              <h4 class="item-name">
                <?php echo e($product->name); ?>

              </h4>

              <div class="top-meta">

                  

                  <?php if($product->type == 'Physical'): ?>
                      <?php if($product->emptyStock()): ?>
                      <li class="outStock">
                        <p>
                          <i class="icofont-close-circled"></i>
                          <?php echo e(__('Out Of Stock')); ?>

                        </p>
                      </li>
                      <?php else: ?>
                      <div class="isStock">
                          <span>
                            <i class="far fa-check-circle"></i>
                            <?php echo e($gs->show_stock == 0 ? '' : $product->stock); ?> <?php echo e(__('In Stock')); ?>

                          </span>
                      </div>
                      <?php endif; ?>
                  <?php endif; ?>

                  

                  

                    <div class="stars">
                        <div class="ratings">
                            <div class="empty-stars"></div>
                            <div class="full-stars" style="width:<?php echo e(App\Models\Rating::ratings($product->id)); ?>%"></div>
                          </div>
                    </div>

                    <div class="review">
                      <i class="far fa-comments"></i> <?php echo e(App\Models\Rating::ratingCount($product->id)); ?> <?php echo e(__('Review')); ?>

                    </div>

                  

                  

                  <?php if($product->product_condition != 0): ?>

                    <div class="<?php echo e($product->product_condition == 2 ? 'condition' : 'no-condition'); ?>">
                      <span><?php echo e($product->product_condition == 2 ?  __('New')  :  __('Used')); ?></span>
                    </div>

                  <?php endif; ?>

                  

                  

                    <div class="wish">

                      <?php if(Auth::check()): ?>

                      <a class="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$product->id)); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Wish')); ?>">
                        <i class="far fa-heart"></i>
                      </a>

                      <?php else: ?>

                      <a rel-toggle="tooltip" href="javascript:;" title="<?php echo e(__('Wish')); ?>" data-placement="top" class="add-to-wish" data-toggle="modal" data-target="#user-login">
                        <i class="far fa-heart"></i>
                      </a>

                      <?php endif; ?>

                    </div>

                  

                  

                    <div class="compear">

                      <a class="add-to-compare" href="javascript:;" data-href="<?php echo e(route('product.compare.add',$product->id)); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Compare')); ?>">
                        <i class="fas fa-random"></i>
                      </a>

                    </div>

                  

                  

                    <?php if($product->youtube != null): ?>
                      <div class="play-video">
                        <a href="<?php echo e($product->youtube); ?>" class="video-play-btn mfp-iframe"
                          data-toggle="tooltip" data-placement="top" title="<?php echo e(__('Play Video')); ?>">
                          <i class="fas fa-play"></i>
                        </a>
                      </div>
                    <?php endif; ?>

                  

              </div>

              

              <div class="price-and-discount">
                <div class="price">
                  <div class="current-price" id="msizeprice">
                    <?php echo e($product->showPrice()); ?>

                  </div>
                  <small>
                    <del>
                      <?php echo e($product->showPreviousPrice()); ?>

                    </del>
                  </small>
                </div>
              </div>

              

              

              <?php if($product->stock_check == 1): ?>

                  

                  <?php if(!empty($product->size)): ?>
                  <div class="mproduct-size">
                    <p class="title"><?php echo e(__('Size :')); ?></p>
                    <ul class="siz-list">
                      <?php $__currentLoopData = array_unique($product->size); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="<?php echo e($loop->first ? 'active' : ''); ?>" data-key="<?php echo e(str_replace(' ','',$data1)); ?>">
                          <span class="box">
                            <?php echo e($data1); ?>


                            <input type="hidden" class="msize" value="<?php echo e($data1); ?>">
                            <input type="hidden" class="msize_key" value="<?php echo e($key); ?>">
                          </span>
                        </li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                  </div>

                  <?php endif; ?>

                  

                  

                  <?php if(!empty($product->color)): ?>

                  <div class="mproduct-color">
                    <div class="title"><?php echo e(__('Color :')); ?></div>
                    <ul class="color-list">

                      <?php $__currentLoopData = $product->color; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <li class="<?php echo e($loop->first ? 'active' : ''); ?> <?php echo e($product->IsSizeColor($product->size[$key]) ? str_replace(' ','',$product->size[$key]) : ''); ?> <?php echo e($product->size[$key] == $product->size[0] ? 'show-colors' : ''); ?>">
                          <span class="box" data-color="<?php echo e($product->color[$key]); ?>" style="background-color: <?php echo e($product->color[$key]); ?>">
                            <input type="hidden" class="msize" value="<?php echo e($product->size[$key]); ?>">
                            <input type="hidden" class="msize_qty" value="<?php echo e($product->size_qty[$key]); ?>">
                            <input type="hidden" class="msize_key" value="<?php echo e($key); ?>">
                            <input type="hidden" class="msize_price" value="<?php echo e(round($product->size_price[$key] * $curr->value,2)); ?>">

                          </span>
                        </li>

                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>
                  </div>

                  <?php endif; ?>

                  

                  <?php else: ?>
                  <?php if(!empty($product->size_all)): ?>
                  <div class="mproduct-size" data-key="false">
                    <p class="title"><?php echo e(__('Size :')); ?></p>
                    <ul class="siz-list">
                      <?php $__currentLoopData = array_unique(explode(',',$product->size_all)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="<?php echo e($loop->first ? 'active' : ''); ?>" data-key="<?php echo e(str_replace(' ','',$data1)); ?>">
                          <span class="box">
                            <?php echo e($data1); ?>

                            <input type="hidden" class="msize" value="<?php echo e($data1); ?>">
                            <input type="hidden" class="msize_key" value="<?php echo e($key); ?>">
                          </span>
                        </li>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                  </div>
                  <?php endif; ?>
                  <?php if(!empty($product->color_all)): ?>

                  <div class="mproduct-color" data-key="false">
                    <div class="title"><?php echo e(__('Color :')); ?></div>
                    <ul class="color-list">

                      <?php $__currentLoopData = explode(',',$product->color_all); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $color1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <li class="<?php echo e($loop->first ? 'active' : ''); ?> show-colors">
                          <span class="box" data-color="<?php echo e($color1); ?>" style="background-color: <?php echo e($color1); ?>">
                            <input type="hidden" class="msize_price" value="0">

                          </span>
                        </li>

                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </ul>
                  </div>

                  <?php endif; ?>
                  <?php endif; ?>

              

              

              <?php if(!empty($product->size)): ?>

                <input type="hidden" class="product-stock" value="<?php echo e($product->size_qty[0]); ?>">

                <?php else: ?>

                <?php if(!$product->emptyStock()): ?>
                  <input type="hidden" class="product-stock" value="<?php echo e($product->stock); ?>">
                <?php elseif($product->type != 'Physical'): ?>
                  <input type="hidden" class="product-stock" value="0">
                <?php else: ?>
                  <input type="hidden" class="product-stock" value="">

                <?php endif; ?>

              <?php endif; ?>

              

              

              <?php if(!empty($product->attributes)): ?>
                <?php
                  $attrArr = json_decode($product->attributes, true);
                ?>
              <?php endif; ?>
              <?php if(!empty($attrArr)): ?>
                <div class="product-attributes">
                  <div class="row">
                  <?php $__currentLoopData = $attrArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attrKey => $attrVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1): ?>

                  <div class="col-lg-6">
                    <div class="form-group mb-2">
                      <strong for="" class="text-capitalize"><?php echo e(str_replace("_", " ", $attrKey)); ?> :</strong>
                        <div class="">
                        <?php $__currentLoopData = $attrVal['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $optionVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <div class="custom-control custom-radio">
                            <input type="hidden" class="keys" value="">
                            <input type="hidden" class="values" value="">
                            <input type="radio" id="<?php echo e($attrKey); ?><?php echo e($optionKey); ?>" name="<?php echo e($attrKey); ?>" class="custom-control-input mproduct-attr"  data-key="<?php echo e($attrKey); ?>" data-price = "<?php echo e($attrVal['prices'][$optionKey] * $curr->value); ?>" value="<?php echo e($optionVal); ?>" <?php echo e($loop->first ? 'checked' : ''); ?>>
                            <label class="custom-control-label" for="<?php echo e($attrKey); ?><?php echo e($optionKey); ?>"><?php echo e($optionVal); ?>


                            <?php if(!empty($attrVal['prices'][$optionKey])): ?>
                              +
                              <?php echo e($curr->sign); ?> <?php echo e($attrVal['prices'][$optionKey] * $curr->value); ?>

                            <?php endif; ?>
                            </label>
                          </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                  </div>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                </div>
              <?php endif; ?>

              

              

              <input type="hidden" id="mproduct_price" value="<?php echo e(round($product->vendorPrice() * $curr->value,2)); ?>">
              <input type="hidden" id="mproduct_id" value="<?php echo e($product->id); ?>">
              <input type="hidden" id="mcurr_pos" value="<?php echo e($gs->currency_format); ?>">
              <input type="hidden" id="mcurr_sign" value="<?php echo e($curr->sign); ?>">

              <?php if(!$product->emptyStock()): ?>

                <div class="inner-box">
                  <div class="cart-btn">
                    <ul class="btn-list">

                      

                      <?php if($product->product_type != "affiliate" && $product->type == 'Physical'): ?>

                          <li>
                            <div class="multiple-item-price">
                              <div class="qty">
                                <span class="modal-plus">
                                  <i class="fas fa-plus"></i>
                                </span>
                                <input class="modal-total" type="text" id="order-qty1" value="<?php echo e($product->minimum_qty == null ? '1' : (int)$product->minimum_qty); ?>">
                                <input type="hidden" id="mproduct_minimum_qty" value="<?php echo e($product->minimum_qty == null ? '0' : $product->minimum_qty); ?>">
                                <span class="modal-minus">
                                  <i class="fas fa-minus"></i>
                                </span>
                              </div>
                            </div>
                          </li>

                      <?php endif; ?>

                      

                      <?php if($product->product_type == "affiliate"): ?>

                      <li>
                        <a href="<?php echo e(route('affiliate.product', $product->slug)); ?>" target="_blank">
                          <i class="icofont-cart"></i>
                          <?php echo e(__('Purchase Now')); ?>

                        </a>
                      </li>

                      <?php else: ?>

                      <li>
                        <a href="javascript:;" id="maddcrt">
                          <i class="icofont-cart"></i>
                          <?php echo e(__('Add To Cart')); ?>

                        </a>
                      </li>

                      <li>
                        <a id="mqaddcrt" href="javascript:;">
                          <i class="icofont-cart"></i>
                          <?php echo e(__('Purchase Now')); ?>

                        </a>
                      </li>

                      <?php endif; ?>

                    </ul>
                  </div>
                </div>

              <?php endif; ?>

              

              

              <?php if($product->ship != null): ?>

              <div class="shipping-time">
                <?php echo e(__('Estimated Shipping Time:')); ?>

                <span><?php echo e($product->ship); ?></span>
              </div>

              <?php endif; ?>

              <?php if( $product->sku != null ): ?>

              <div class="product-id">
                <?php echo e(__('Product SKU:')); ?>

                <span><?php echo e($product->sku); ?></span>
              </div>

              <?php endif; ?>

              

              

              <?php if($product->type == 'License'): ?>

                <?php if($product->platform != null): ?>
                  <div class="license-id">
                      <?php echo e(__('Platform:')); ?>

                      <span><?php echo e($product->platform); ?></span>
                  </div>
                <?php endif; ?>

                <?php if($product->region != null): ?>
                  <div class="license-id">
                      <?php echo e(__('Region:')); ?>

                      <span><?php echo e($product->region); ?></span>
                  </div>
                <?php endif; ?>

                <?php if($product->licence_type != null): ?>
                <div class="license-id">
                    <?php echo e(__('License Type:')); ?>

                    <span><?php echo e($product->licence_type); ?></span>
                </div>
                <?php endif; ?>

              <?php endif; ?>

              <div class="mt-2">
                <a class="view_more_btn" href="<?php echo e(route('front.product',$product->slug)); ?>"><?php echo e(__('Get More Details')); ?> <i class="fas fa-arrow-right"></i></a>
              </div>


              


            </div>
          </div>
        </div>
      </div>
    </div>

    </section>

    <script src="<?php echo e(asset('assets/front/js/setup.js')); ?>"></script>

    <script type="text/javascript">

    (function($) {
        "use strict";

      function number_format (number, decimals, dec_point, thousands_sep) {
          // Strip all characters but numerical ones.
          number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
          var n = !isFinite(+number) ? 0 : +number,
              prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
              sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
              dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
              s = '',
              toFixedFix = function (n, prec) {
                  var k = Math.pow(10, prec);
                  return '' + Math.round(n * k) / k;
              };
          // Fix for IE parseFloat(0.55).toFixed(0) = 0;
          s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
          if (s[0].length > 3) {
              s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
          }
          if ((s[1] || '').length < prec) {
              s[1] = s[1] || '';
              s[1] += new Array(prec - s[1].length + 1).join('0');
          }
          return s.join(dec);
      }

        //   magnific popup activation
        $('.video-play-btn').magnificPopup({
            type: 'video'
        });

        var sizes = "";
        var size_qty = ($('.mproduct-color .color-list li.active').length > 0) ? parseFloat($('.mproduct-color .color-list li.active').find('.msize_qty').val()) : '';
        var size_price = "";
        var size_key = "";
        var colors = "";
        var total = "";
        var mstock = $('.product-stock').val();
        var keys = "";
        var values = "";
        var prices = "";

        $('.mproduct-attr').on('change',function(){

                var total;
                total = mgetAmount()+mgetSizePrice();
                total = total.toFixed(2);
                var pos = $('#mcurr_pos').val();
                var sign = $('#mcurr_sign').val();
                if(pos == '0')
                {
                $('#msizeprice').html(sign+total);
                }
                else {
                $('#msizeprice').html(total+sign);
                }
        });


        function mgetSizePrice()
          {
            var total = 0;
            if($('.mproduct-color .color-list li.active').length > 0)
            {
              total = parseFloat($('.mproduct-color .color-list li.active').find('.msize_price').val());
            }
            return total;
          }


        function mgetAmount()
        {
          var total = 0;
          var value = parseFloat($('#mproduct_price').val());
          var datas = $(".mproduct-attr:checked").map(function() {
            return $(this).data('price');
          }).get();

          var data;
          for (data in datas) {
            total += parseFloat(datas[data]);
          }
          total += value;
          return total;
        }

        // Product Details Product Size Active Js Code
        $('.mproduct-size .siz-list .box').on('click', function () {

            var parent = $(this).parent();
            $('.mproduct-size .siz-list li').removeClass('active');
            parent.addClass('active');

            sizes = $(this).find('input.msize').val();
            size_key = $(this).find('input.msize_key').val();
            $('.modal-total').val('1');

            if ($(this).parent().parent().parent().attr('data-key') != 'false') {
              $('.mproduct-color .color-list li').removeClass('show-colors');

            var size_color = $('.mproduct-color .color-list li.'+parent.data('key'));
            size_color.addClass('show-colors').first().addClass('active');
            colors = size_color.find('span.box').data('color');
            sizes = size_color.find('.msize').val();
            size_qty = size_color.find('.msize_qty').val();
            size_price = size_color.find('.msize_price').val();
            size_key = size_color.find('.msize_key').val();

            total = mgetAmount()+parseFloat(size_price);
            mstock = size_qty;
            total = total.toFixed(2);
            total = number_format(total, 2, gs.decimal_separator, gs.thousand_separator);
            var pos = $('#mcurr_pos').val();
            var sign = $('#mcurr_sign').val();
            if(pos == '0')
            {
             $('#msizeprice').html(sign+total);
            }
            else {
             $('#msizeprice').html(total+sign);
            }

          }



        });



        // Product Details Product Color Active Js Code
        $('.mproduct-color .color-list .box').on('click', function () {
            colors = $(this).data('color');
            var parent = $(this).parent();
            $('.mproduct-color .color-list li').removeClass('active');
            parent.addClass('active');

            $('.modal-total').html('1');

            if ($(this).parent().parent().parent().attr('data-key') != 'false') {

             size_qty = $(this).find('.msize_qty').val();
             size_price = $(this).find('.msize_price').val();
             size_key = $(this).find('.msize_key').val();
             sizes = $(this).find('.msize').val();
             total = mgetAmount()+parseFloat(size_price);
             mstock = size_qty;
             total = total.toFixed(2);
             total = number_format(total, 2, gs.decimal_separator, gs.thousand_separator);
             var pos = $('#mcurr_pos').val();
             var sign = $('#mcurr_sign').val();
             if(pos == '0')
             {
             $('#msizeprice').html(sign+total);
             }
             else {
             $('#msizeprice').html(total+sign);
             }
            }
        });


        $('.modal-total').keypress(function(e){
          if (this.value.length == 0 && e.which == 48 ){
            return false;
         }
          if(e.which != 8 && e.which != 32){
            if(isNaN(String.fromCharCode(e.which))){
              e.preventDefault();
            }
          }
        });

        $('.modal-minus').on('click', function () {
            var el = $(this);
            var $tselector = el.parent().parent().find('.modal-total');
            total = $($tselector).val();
            if (total > 1) {
                total--;
            }
            $($tselector).val(total);
        });

        $('.modal-plus').on('click', function () {
            var el = $(this);
            var $tselector = el.parent().parent().find('.modal-total');
            total = $($tselector).val();
            if(mstock != "")
            {
                var stk = parseInt(mstock);
                if(total < stk)
                {
                    total++;
                    $($tselector).val(total);
                }
            }
            else {
                total++;
            }
            $($tselector).val(total);
        });

        $("#maddcrt").on("click", function(){
            var qty = $('.modal-total').val() ? $('.modal-total').val() : 1;
            var pid = $(this).parent().parent().parent().parent().parent().find("#mproduct_id").val();

          if($('.mproduct-attr').length > 0)
          {
            values = $(".mproduct-attr:checked").map(function() {
            return $(this).val();
          }).get();

          keys = $(".mproduct-attr:checked").map(function() {
            return $(this).data('key');
          }).get();


          prices = $(".mproduct-attr:checked").map(function() {
            return $(this).data('price');
          }).get();

          }

          if (!isNaN(size_qty)) {
          if(size_qty == '0'){
            toastr.error(lang.cart_out);
            return false;
          }
        } else {
          size_qty = null;
        }


            $.ajax({
                type: "GET",
                url:mainurl+"/addnumcart",
                data:{id:pid,qty:qty,size:sizes,color:colors,size_qty:size_qty,size_price:size_price,size_key:size_key,keys:keys,values:values,prices:prices},
                success:function(data){
                    if(data == 'digital') {
                        toastr.error("<?php echo e(__('Already Added To Cart.')); ?>");
                    }
                    else if(data == 0) {
                        toastr.error("<?php echo e(__('Out Of Stock.')); ?>");
                    }
                    else if(data[3]) {
                      toastr.error("<?php echo e(__('Minimum Quantity is:')); ?>"+' '+data[4]);
                    }
                    else {
                        $("#cart-count").html(data[0]);
                        $("#total-cost").html(data[1]);
                        $("#cart-items").load(mainurl+'/carts/view');
                        toastr.success("<?php echo e(__('Successfully Added To Cart.')); ?>");
                    }
                }
            });
        });


        $(document).on("click", "#mqaddcrt" , function(){
          var qty = $('.modal-total').val();
          var minimum_qty = $('#mproduct_minimum_qty').val();
          var pid = $(this).parent().parent().parent().parent().parent().find("#mproduct_id").val();

          if($('.mproduct-attr').length > 0)
          {
          values = $(".mproduct-attr:checked").map(function() {
          return $(this).val();
          }).get();

          keys = $(".mproduct-attr:checked").map(function() {
          return $(this).data('key');
          }).get();


          prices = $(".mproduct-attr:checked").map(function() {
          return $(this).data('price');
          }).get();

          }

          qty = parseInt(qty);
          minimum_qty = parseInt(minimum_qty);

          if(qty < minimum_qty){
            toastr.error("<?php echo e(__('Minimum Quantity is:')); ?>"+' '+minimum_qty);
            return false;
          }

          if(size_qty == '0'){
            toastr.error("<?php echo e(__('Out Of Stock')); ?>");
            return false;
          }

         window.location = mainurl+"/addtonumcart?id="+pid+"&qty="+qty+"&size="+sizes+"&color="+colors.substring(1, colors.length)+"&size_qty="+size_qty+"&size_price="+size_price+"&size_key="+size_key+"&keys="+keys+"&values="+values+"&prices="+prices;

         });

    })(jQuery);

    </script>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\load\quick.blade.php ENDPATH**/ ?>