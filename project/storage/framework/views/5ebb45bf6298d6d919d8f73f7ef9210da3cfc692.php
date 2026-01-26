<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="XMerch-New - Multivendor Ecommerce system">
    <meta name="author" content="XMerchGroup">

    <?php if(isset($page->meta_tag) && isset($page->meta_description)): ?>

		<meta name="keywords" content="<?php echo e($page->meta_tag); ?>">
		<meta name="description" content="<?php echo e($page->meta_description); ?>">
		<title><?php echo e($gs->title); ?></title>

	<?php elseif(isset($blog->meta_tag) && isset($blog->meta_description)): ?>

		<meta property="og:title" content="<?php echo e($blog->title); ?>" />
		<meta property="og:description" content="<?php echo e($blog->meta_description != null ? $blog->meta_description : strip_tags($blog->meta_description)); ?>" />
		<meta property="og:image" content="<?php echo e(asset('assets/images/blogs/'.$blog->photo)); ?>" />
		<meta name="keywords" content="<?php echo e($blog->meta_tag); ?>">
		<meta name="description" content="<?php echo e($blog->meta_description); ?>">
		<title><?php echo e($gs->title); ?></title>

	<?php elseif(isset($productt)): ?>

		<meta name="keywords" content="<?php echo e(!empty($productt->meta_tag) ? implode(',', $productt->meta_tag ): ''); ?>">
		<meta name="description" content="<?php echo e($productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description)); ?>">
		<meta property="og:title" content="<?php echo e($productt->name); ?>" />
		<meta property="og:description" content="<?php echo e($productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description)); ?>" />
		<meta property="og:image" content="<?php echo e(asset('assets/images/thumbnails/'.$productt->thumbnail)); ?>" />
		<meta name="author" content="XMerchGroup">
		<title><?php echo e(substr($productt->name, 0,11)."-"); ?><?php echo e($gs->title); ?></title>

	<?php else: ?>

		<meta property="og:title" content="<?php echo e($gs->title); ?>" />
		<meta property="og:image" content="<?php echo e(asset('assets/images/'.$gs->logo)); ?>" />
		<meta name="keywords" content="<?php echo e($seo->meta_keys); ?>">
		<meta name="author" content="XMerchGroup">
		<title><?php echo e($gs->title); ?></title>

	<?php endif; ?>

    <link rel="icon"  type="image/x-icon" href="<?php echo e(asset('assets/images/'.$gs->favicon)); ?>"/>
    <!-- Google Font -->
    <?php if($default_font->font_value): ?>
		<link href="https://fonts.googleapis.com/css?family=<?php echo e($default_font->font_value); ?>:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<?php else: ?>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/styles.php?color='.str_replace('#','', $gs->colors).'&header_color='.$gs->header_color)); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/all.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/plugin.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/animate.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/webfonts/flaticon/flaticon.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/template.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/style.css')); ?>">
     <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/category/default.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/toastr.min.css')); ?>">
    <?php if($default_font->font_family): ?>
			<link rel="stylesheet" id="colorr" href="<?php echo e(asset('assets/front/css/font.php?font_familly='.$default_font->font_family)); ?>">
	<?php else: ?>
			<link rel="stylesheet" id="colorr" href="<?php echo e(asset('assets/front/css/font.php?font_familly='."Open Sans")); ?>">
	<?php endif; ?>

    <?php if(!empty($seo->google_analytics)): ?>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag() {
				dataLayer.push(arguments);
		}
		gtag('js', new Date());
		gtag('config', '<?php echo e($seo->google_analytics); ?>');
	</script>
	<?php endif; ?>
    <?php if(!empty($seo->facebook_pixel)): ?>
	    <script>
			!function(f,b,e,v,n,t,s)
			{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};
			if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
			n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t,s)}(window, document,'script',
			'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?php echo e($seo->facebook_pixel); ?>');
			fbq('track', 'PageView');
		</script>
		<noscript>
			<img height="1" width="1" style="display:none"
				 src="https://www.facebook.com/tr?id=<?php echo e($seo->facebook_pixel); ?>&ev=PageView&noscript=1"/>
		</noscript>
	<?php endif; ?>


    <?php echo $__env->yieldContent('css'); ?>
</head>
<body>
    <div id="page_wrapper" class="bg-white">

        <div class="loader">
            <div class="spinner"></div>
        </div>

        <?php echo $__env->yieldContent('content'); ?>



    </div>
    <script>


    var mainurl = "<?php echo e(url('/')); ?>";
    var gs      = <?php echo json_encode(DB::table('generalsettings')->where('id','=',1)->first(['is_loader','decimal_separator','thousand_separator','is_cookie','is_talkto','talkto'])); ?>;
    var ps_category = <?php echo e($ps->category); ?>;

    var lang = {
        'days': '<?php echo e(__('Days')); ?>',
        'hrs': '<?php echo e(__('Hrs')); ?>',
        'min': '<?php echo e(__('Min')); ?>',
        'sec': '<?php echo e(__('Sec')); ?>',
        'cart_already': '<?php echo e(__('Already Added To Card.')); ?>',
        'cart_out': '<?php echo e(__('Out Of Stock')); ?>',
        'cart_success': '<?php echo e(__('Successfully Added To Cart.')); ?>',
        'cart_empty': '<?php echo e(__('Cart is empty.')); ?>',
        'coupon_found': '<?php echo e(__('Coupon Found.')); ?>',
        'no_coupon': '<?php echo e(__('No Coupon Found.')); ?>',
        'already_coupon': '<?php echo e(__('Coupon Already Applied.')); ?>',
        'enter_coupon': '<?php echo e(__('Enter Coupon First')); ?>',
        'minimum_qty_error': '<?php echo e(__('Minimum Quantity is:')); ?>',
        'affiliate_link_copy': '<?php echo e(__('Affiliate Link Copied Successfully')); ?>'
    };

    </script>
     <!-- Include Scripts -->
     <script src="<?php echo e(asset('assets/front/js/jquery.min.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/jquery-ui.min.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/popper.min.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/bootstrap.min.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/plugin.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/waypoint.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/owl.carousel.min.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/wow.js')); ?>"></script>
     <script type="text/javascript" src="<?php echo e(asset('assets/front/js/lazy.min.js')); ?>"></script>
     <script type="text/javascript" src="<?php echo e(asset('assets/front/js/lazy.plugin.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/jquery.countdown.js')); ?>"></script>
     <?php echo $__env->yieldContent('zoom'); ?>
     <script src="<?php echo e(asset('assets/front/js/paraxify.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/toastr.min.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/custom.js')); ?>"></script>
     <script src="<?php echo e(asset('assets/front/js/main.js')); ?>"></script>

<script>
    lazy();
function lazy (){
    $(".lazy").Lazy({
        scrollDirection: 'vertical',
        effect: "fadeIn",
        effectTime:1000,
        threshold: 0,
        visibleOnly: false,
        onError: function(element) {
            console.log('error loading ' + element.data('src'));
        }
    });
}

</script>




     <?php
     echo Toastr::message();
     ?>
     <?php echo $__env->yieldContent('script'); ?>



</body>
</html>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views/layouts/front.blade.php ENDPATH**/ ?>