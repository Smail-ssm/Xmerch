<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/front/css/category/classic.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<?php echo $__env->make('partials.global.subscription-popup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<header class="ecommerce-header nav-on-banner">
    
    <?php echo $__env->make('partials.global.top-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('partials.global.responsive-menubar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</header>
<?php if($ps->slider == 1): ?>
    <div class="position-relative">
        <span class="nextBtn"></span>
        <span class="prevBtn"></span>
        <section class="home-slider owl-theme owl-carousel">
            <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="banner-slide-item" style="background: url('<?php echo e(asset('assets/images/sliders/'.$data->photo)); ?>') no-repeat center center / cover ;">
                <div class="container">
                    <div class="banner-wrapper-item text-<?php echo e($data->position); ?>">
                        <div class="banner-content text-dark ">
                            <h5 class="subtitle text-dark slide-h5"><?php echo e($data->subtitle_text); ?></h5>

                            <h2 class="title text-dark slide-h5"><?php echo e($data->title_text); ?></h2>

                            <p class="slide-h5"><?php echo e($data->details_text); ?></p>

                            <a href="<?php echo e($data->link); ?>" class="cmn--btn "><?php echo e(__('SHOP NOW')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </section>
    </div>
    <?php endif; ?>
<?php if($ps->arrival_section == 1): ?>
        <!--==================== Fashion Banner Section Start ====================-->
        <div class="full-row">
            <div class="container">
                <div class="fashion-banner-wrapper">
                <?php $__currentLoopData = $arrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$arrival): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="row row-cols-lg-2 row-cols-1 justify-content-between">
                    <div class="col">
                        <div class="banner-wrapper hover-img-zoom custom-class-121">
                            <div class="banner-image overflow-hidden transation">
                                <a href="<?php echo e(route('front.category')); ?>"><img class="lazy" data-src="<?php echo e($arrival->photo ?  asset('assets/images/arrival/'.$arrival->photo): ""); ?>" alt="Banner Image"></a>
                            </div>
                            <div class="banner-content position-absolute">
                                <div class="product-tag" style="font-size: 15px;text-transform: uppercase; color: var(--theme-secondary-color); letter-spacing: 3px;"><span><?php echo e(__('Men Collection')); ?></span></div>
                                <h2 style="margin: 10px 0 20px;"><a href="<?php echo e(route('front.category')); ?>" class="text-dark mb-10 d-block"><?php echo e(__('New Autumn Arrival 2021')); ?></a></h2>
                                <a href="<?php echo e(route('front.category')); ?>" class="btn-link-left-line"><?php echo e(__('Shop Now')); ?></a>
                            </div>
                        </div>

                    </div>
                    <div class="col hide1">
                        <div class="products-avilable-number fact-counter">
                            <?php if($loop->first): ?>
                            <div class="mb-30 count wow fadeIn" data-wow-duration="300ms">
                                <div class="counting d-table">
                                    <div>
                                        <span class="count-num" data-speed="3000" data-stop="<?php echo e($products->count()); ?>">0</span>
                                        <span>+</span>
                                        <span class="title"><?php echo app('translator')->get('Products For You'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php elseif($loop->last): ?>
                            <div class="mb-30 count wow fadeIn counting-bottom" data-wow-duration="300ms">
                                <div class="counting d-table">
                                    <div>
                                        <span class="count-num" data-speed="3000" data-stop="<?php echo e($ratings->count()>0 ? $ratings->count() : '2156'); ?>">0</span>
                                        <span>+</span>
                                        <span class="title"><?php echo app('translator')->get('Feedback Given By Customer'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>



                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            </div>
        </div>
        <!--==================== Fashion Banner Section End ====================-->
<?php endif; ?>


<div id="extraData">
    <div class="text-center">
        <img  src="<?php echo e(asset('assets/images/'.$gs->loader)); ?>">
    </div>
</div>



    <?php if(isset($visited)): ?>
    <?php if($gs->is_cookie == 1): ?>
        <div class="cookie-bar-wrap show">
            <div class="container d-flex justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="row justify-content-center">
                        <div class="cookie-bar">
                            <div class="cookie-bar-text">
                                <?php echo e(__('The website uses cookies to ensure you get the best experience on our website.')); ?>

                            </div>
                            <div class="cookie-bar-action">
                                <button class="btn btn-primary btn-accept">
                                <?php echo e(__('GOT IT!')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php endif; ?>
<!-- Scroll to top -->
<a href="#" class="scroller text-white" id="scroll"><i class="fa fa-angle-up"></i></a>
<!-- End Scroll To top -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
	<script>
		let checkTrur = 0;
		$(window).on('scroll', function(){

		if(checkTrur == 0){
			$('#extraData').load('<?php echo e(route('front.extraIndex')); ?>');
			checkTrur = 1;
		}
		});
        var owl = $('.home-slider').owlCarousel({
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        autoplay: true,
        margin: 0,
        animateIn: 'fadeInDown',
        animateOut: 'fadeOutUp',
        mouseDrag: false,
    })
    $('.nextBtn').click(function() {
        owl.trigger('next.owl.carousel', [300]);
    })
    $('.prevBtn').click(function() {
        owl.trigger('prev.owl.carousel', [300]);
    })
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views/partials/theme/theme1.blade.php ENDPATH**/ ?>