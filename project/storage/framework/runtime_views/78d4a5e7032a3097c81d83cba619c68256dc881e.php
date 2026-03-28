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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-40 widget_categories bg-light account-info">

                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Package Details')); ?>


                            </h4>
                            <div class="pack-details">
                                <div class="row">

                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            <?php echo e(__('Plan:')); ?>

                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            <?php echo e($subs->title); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            <?php echo e(__('Price:')); ?>

                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            <?php echo e(round($subs->price * $curr->value ,2)); ?><?php echo e($curr->sign); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            <?php echo e(__('Durations:')); ?>

                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            <?php echo e($subs->days); ?> <?php echo e(__('Day(s)')); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h5 class="title">
                                            <?php echo e(__('Product(s) Allowed:')); ?>

                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                            <?php echo e($subs->allowed_products == 0 ? 'Unlimited':  $subs->allowed_products); ?>

                                        </p>
                                    </div>
                                </div>

                                <?php if(!empty($package)): ?>
                                <?php if($package->subscription_id != $subs->id): ?>
                                <div class="row">
                                    <div class="col-lg-4">
                                    </div>
                                    <div class="col-lg-8">
                                        <span class="notic"><b><?php echo e(__('Note:')); ?></b>
                                            <?php echo e(__('Your Previous Plan will be deactivated!')); ?></span>
                                    </div>
                                </div>

                                <br>
                                <?php else: ?>
                                <br>

                                <?php endif; ?>
                                <?php else: ?>
                                <br>
                                <?php endif; ?>

                                <form id="subscribe-form" class="pay-form" action="<?php echo e($subs->price == 0 ? route('user-vendor-request-submit') : ''); ?>" method="POST">

                                    <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('alerts.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    <?php echo csrf_field(); ?>

                                    <?php if($user->is_vendor == 0): ?>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Shop Name')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" id="shop-name" class="option" name="shop_name"
                                                placeholder="<?php echo e(__('Shop Name')); ?>" required>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Owner Name')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="option" name="owner_name"
                                                placeholder="<?php echo e(__('Owner Name')); ?>" required>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Shop Number')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="option" name="shop_number"
                                                placeholder="<?php echo e(__('Shop Number')); ?>" required>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Shop Address')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="option" name="shop_address"
                                                placeholder="<?php echo e(__('Shop Address')); ?>" required>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Registration Number')); ?> <small><?php echo e(__('(Optional)')); ?></small>
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <input type="text" class="option" name="reg_number"
                                                placeholder="<?php echo e(__('Registration Number')); ?>">
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Message')); ?> <small><?php echo e(__('(Optional)')); ?></small>
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <textarea class="option" name="shop_message" placeholder="<?php echo e(__('Message')); ?>" rows="5"></textarea>
                                        </div>
                                    </div>

                                    <br>

                                    <?php endif; ?>
                                    <input type="hidden" name="subs_id" value="<?php echo e($subs->id); ?>">

                                    <?php if($subs->price != 0): ?>

                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Select Payment Method')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">

                                            <select name="method" id="method" class="option form-control border mb-3" required="">
                                                <option value="" data-form="" data-show="no" data-val="" data-href=""><?php echo e(__('Select an option')); ?></option>
                                                <?php $__currentLoopData = $gateway; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paydata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                    <?php if($paydata->type == 'manual'): ?>

                                                    <option value="<?php echo e($paydata->title); ?>" data-form="<?php echo e($paydata->showSubscriptionLink()); ?>" data-show="<?php echo e($paydata->showForm()); ?>" data-href="<?php echo e(route('user.load.payment',['slug1' => $paydata->showKeyword(),'slug2' => $paydata->id])); ?>" data-val="<?php echo e($paydata->title); ?>">
                                                        <?php echo e($paydata->title); ?>

                                                      </option>

                                                    <?php else: ?>

                                                    <option value="<?php echo e($paydata->name); ?>" data-form="<?php echo e($paydata->showSubscriptionLink()); ?>" data-show="<?php echo e($paydata->showForm()); ?>" data-href="<?php echo e(route('user.load.payment',['slug1' => $paydata->showKeyword(),'slug2' => $paydata->id])); ?>" data-val="<?php echo e($paydata->keyword); ?>">
                                                        <?php echo e($paydata->name); ?>

                                                    </option>

                                                    <?php endif; ?>

                                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                 <option value="" data-form="" data-show="no" data-val="" data-href=""><?php echo e(__('Select an option')); ?></option>
                                            </select>

                                        </div>
                                    </div>

                                    <div id="payments" class="d-none">




                                    </div>
                                    <?php endif; ?>

                                    <input type="hidden" id="ck" value="0">
                                    <input type="hidden" name="sub" id="sub" value="0">
                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="col-lg-8">
                                            <button type="submit" id="final-btn" class="mybtn1"><?php echo e(__('Submit')); ?></button>
                                        </div>
                                    </div>

                                </form>

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
<?php $__env->startSection('script'); ?>
<script type="text/javascript" src="<?php echo e(asset('assets/front/js/payvalid.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/front/js/paymin.js')); ?>"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/front/js/payform.js')); ?>"></script>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script type="text/javascript">

(function($) {
		"use strict";

$('#method').on('change',function(){
    var val  = $(this).find(':selected').attr('data-val');
    var form = $(this).find(':selected').attr('data-form');
    var show = $(this).find(':selected').attr('data-show');
    var href = $(this).find(':selected').attr('data-href');

    if(show == "yes"){
        $('#payments').removeClass('d-none');
    }else{
        $('#payments').addClass('d-none');
    }

    if(val == 'paystack'){
			$('.pay-form').prop('id','paystack');
		}
		else if(val == 'voguepay'){
			$('.pay-form').prop('id','voguepay');
		}
		else if(val == 'mercadopago'){
			$('.pay-form').prop('id','mercadopago');
		}
		else if(val == '2checkout'){
			$('.pay-form').prop('id','twocheckout');
		}
		else {
			$('.pay-form').prop('id','subscribe-form');
		}


    $('#payments').load(href);
    $('.pay-form').attr('action',form);
});


    $(document).on('submit','#paystack',function(){
            var val = $('#sub').val();
            if(val == 0)
            {
                if($('#shop-name').length > 0){

                    $.get('<?php echo e(route('user.shop.check').'?shop_name='); ?>'+$('#shop-name').val(), function(data, status){
                        if ((data.errors)) {

                            $('.alert-danger').show();
                            $('.alert-danger ul').html('');
                            for(var error in data.errors)
                            {
                                $('.alert-danger ul').append('<li>'+ data.errors[error] +'</li>');
                                $('#sub').val('0');
                                $('#ck').val('1');
                            }
                        }
                        else {
                            $('#ck').val('0');
                        }
                    });

                }

                setTimeout(function(){
                    if($('#ck').val() == '0') {

                        var total = <?php echo e($subs->price); ?>;
                        total = Math.round(total);

                        var handler = PaystackPop.setup({
                        key: '<?php echo e($paystack["key"]); ?>',
                        email: '<?php echo e(Auth::user()->email); ?>',
                        amount: total * 100,
                        currency: "<?php echo e($curr->name); ?>",
                        ref: ''+Math.floor((Math.random() * 1000000000) + 1),
                        callback: function(response){
                            $('#ref_id').val(response.reference);
                            $('#sub').val('1');
                            $('#final-btn').click();
                        },
                        onClose: function(){
                            window.location.reload();
                        }
                        });
                        handler.openIframe();
                        return false;
                    }

                }, 1000);
            return false;
            }
            else {
                return true;
            }
		});

})(jQuery);

</script>

<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\user\package\details.blade.php ENDPATH**/ ?>