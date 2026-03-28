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
    <?php if(isset($active_theme) && isset($active_theme->font_value)): ?>
        <link href="https://fonts.googleapis.com/css?family=<?php echo e($active_theme->font_value); ?>:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <?php elseif($default_font->font_value): ?>
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


    <style>
        :root {
            /* Premium Light Model */
            --bg-main: #FFFFFF;
            --bg-surface: #F8F9FA;
            --bg-card: #FFFFFF;
            --text-main: #212529;
            --text-dark: #111111;
            --text-muted: #6C757D;
            --border-color: rgba(0,0,0,0.1);
            --card-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            
            <?php if(isset($active_theme)): ?>
                <?php if(isset($active_theme->primary_color)): ?> --theme-primary: <?php echo e($active_theme->primary_color); ?>; <?php endif; ?>
                <?php if(isset($active_theme->bg_color)): ?> --theme-bg: <?php echo e($active_theme->bg_color); ?>; <?php endif; ?>
                <?php if(isset($active_theme->font_family)): ?> --theme-font: <?php echo $active_theme->font_family; ?>; <?php endif; ?>
            <?php else: ?>
                --theme-primary: <?php echo e($gs->colors); ?>;
            <?php endif; ?>
        }

        [data-theme="dark"] {
            /* Premium Dark Model */
            --bg-main: #0F172A;
            --bg-surface: #1E293B;
            --bg-card: #1E293B;
            --text-main: #F1F5F9;
            --text-dark: #FFFFFF;
            --text-muted: #94A3B8;
            --border-color: rgba(255,255,255,0.08);
            --card-shadow: 0 20px 25px -5px rgba(0,0,0,0.4);
        }

        body { 
            background-color: var(--bg-main) !important; 
            color: var(--text-main) !important;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* Essential Overrides */
        [data-theme="dark"] .bg-white, 
        [data-theme="dark"] .bg-light,
        [data-theme="dark"] .card, 
        [data-theme="dark"] .modal-content, 
        [data-theme="dark"] .dropdown-menu,
        [data-theme="dark"] .account-info,
        [data-theme="dark"] .widget,
        [data-theme="dark"] .product-wrapper,
        [data-theme="dark"] .order-box,
        [data-theme="dark"] .my-account-popup,
        [data-theme="dark"] .cart-popup,
        [data-theme="dark"] .main-nav,
        [data-theme="dark"] .header-sticky,
        [data-theme="dark"] .responsive-menubar,
        [data-theme="dark"] .header-cart-1 .cart-popup { 
            background-color: var(--bg-surface) !important; 
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] p, 
        [data-theme="dark"] h1, 
        [data-theme="dark"] h2, 
        [data-theme="dark"] h3, 
        [data-theme="dark"] h4, 
        [data-theme="dark"] h5, 
        [data-theme="dark"] h6, 
        [data-theme="dark"] span:not(.header-cart-count), 
        [data-theme="dark"] label, 
        [data-theme="dark"] b, 
        [data-theme="dark"] strong,
        [data-theme="dark"] a:not(.btn):not(.nav-link):not(.dropdown-item) {
            color: var(--text-main) !important;
        }

        /* Handle specific dark backgrounds and white text */
        [data-theme="dark"] .text-dark,
        [data-theme="dark"] .text-black,
        [data-theme="dark"] .font-600.text-uppercase.text-secondary {
            color: var(--text-dark) !important;
        }

        [data-theme="dark"] .text-muted, 
        [data-theme="dark"] .breadcrumb-item, 
        [data-theme="dark"] .post-admin ul li, 
        [data-theme="dark"] .sub-heading,
        [data-theme="dark"] .text-general i {
            color: var(--text-muted) !important;
        }

        [data-theme="dark"] .border, 
        [data-theme="dark"] .border-bottom, 
        [data-theme="dark"] .border-top,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .input-field,
        [data-theme="dark"] .nice-select,
        [data-theme="dark"] hr {
            border-color: var(--border-color) !important;
        }

        /* Form Controls */
        [data-theme="dark"] .form-control, 
        [data-theme="dark"] .input-field, 
        [data-theme="dark"] select, 
        [data-theme="dark"] textarea,
        [data-theme="dark"] .nice-select {
            background-color: rgba(255,255,255,0.03) !important;
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .nice-select .list {
            background-color: var(--bg-surface) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .nice-select .option:hover, 
        [data-theme="dark"] .nice-select .option.focus, 
        [data-theme="dark"] .nice-select .option.selected.focus {
            background-color: var(--bg-main) !important;
        }

        [data-theme="dark"] .nice-select::after {
            border-color: var(--text-muted) !important;
        }

        [data-theme="dark"] svg {
            fill: var(--text-main) !important;
        }

        [data-theme="dark"] .ecommerce-header svg,
        [data-theme="dark"] .top-header svg,
        [data-theme="dark"] .responsive-menubar svg {
            fill: var(--text-dark) !important;
        }

        /* Pagination Fixes */
        [data-theme="dark"] .page-item .page-link,
        [data-theme="dark"] .pagination .page-link {
            background-color: var(--bg-surface) !important;
            border-color: var(--border-color) !important;
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .page-item.active .page-link,
        [data-theme="dark"] .pagination .page-item.active .page-link {
            background-color: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
            color: #fff !important;
        }

        [data-theme="dark"] .page-item.disabled .page-link,
        [data-theme="dark"] .pagination .page-item.disabled .page-link {
            background-color: rgba(255,255,255,0.02) !important;
            color: var(--text-muted) !important;
            opacity: 0.6;
        }

        /* Table Text Clarification */
        [data-theme="dark"] .table td, 
        [data-theme="dark"] .table th,
        [data-theme="dark"] .table span,
        [data-theme="dark"] .table p,
        [data-theme="dark"] .table small {
            color: var(--text-main) !important;
            opacity: 1 !important;
        }
        
        [data-theme="dark"] .table .text-muted,
        [data-theme="dark"] .table .text-gray {
            color: var(--text-muted) !important;
        }

        /* Dashboard & Table Fixes */
        [data-theme="dark"] .table,
        [data-theme="dark"] .order-table,
        [data-theme="dark"] .c-table {
            color: var(--text-main) !important;
            background-color: var(--bg-surface) !important;
        }

        [data-theme="dark"] .table thead th,
        [data-theme="dark"] .order-table thead th,
        [data-theme="dark"] .c-table thead th {
             color: var(--text-main) !important;
             border-color: var(--border-color) !important;
             background-color: rgba(255,255,255,0.02) !important;
        }

        [data-theme="dark"] .table tbody td,
        [data-theme="dark"] .order-table tbody td,
        [data-theme="dark"] .c-table tbody td {
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .table-hover tbody tr:hover {
            color: var(--text-main) !important;
            background-color: rgba(255,255,255,0.05) !important;
        }

        [data-theme="dark"] .user-info h5,
        [data-theme="dark"] .user-info p,
        [data-theme="dark"] .widget-title {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .c-info-box-content h6,
        [data-theme="dark"] .c-info-box-content p {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .alert-success {
            background-color: rgba(40, 167, 69, 0.2) !important;
            color: #28a745 !important;
            border-color: rgba(40, 167, 69, 0.3) !important;
        }

        /* Sidebar & Navigation Drawer Fixes */
        [data-theme="dark"] .sidebar-blog, 
        [data-theme="dark"] .sidebar-blog#sidebar,
        [data-theme="dark"] .navbar-slide-push, 
        [data-theme="dark"] .menu-and-category,
        [data-theme="dark"] .tab-content,
        [data-theme="dark"] .woocommerce-product-categories,
        [data-theme="dark"] .dashboard-overlay,
        [data-theme="dark"] .my-account-popup {
            background-color: var(--bg-surface) !important;
            color: var(--text-main) !important;
            border-color: var(--border-color) !important;
        }

        [data-theme="dark"] .sidebar-blog ul li a, 
        [data-theme="dark"] .navbar-slide-push .nav-link,
        [data-theme="dark"] .menu-and-category .nav-link:not(.active),
        [data-theme="dark"] .product-categories a,
        [data-theme="dark"] .cat-item a,
        [data-theme="dark"] .sidebar-blog .widget-title {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .sidebar-blog ul li,
        [data-theme="dark"] .cat-item {
            border-bottom: 1px solid var(--border-color) !important;
        }

        [data-theme="dark"] .sidebar-blog ul li a:hover, 
        [data-theme="dark"] .sidebar-blog ul li a.active,
        [data-theme="dark"] .navbar-slide-push .nav-link:hover,
        [data-theme="dark"] .product-categories a:hover {
            color: var(--theme-primary) !important;
            background-color: rgba(255,255,255,0.03) !important;
        }
        
        [data-theme="dark"] .navbar-slide-push .login-signup {
            background-color: var(--bg-main) !important;
            border-bottom: 1px solid var(--border-color);
        }

        [data-theme="dark"] .slide-nav-close i {
            color: var(--text-main) !important;
        }

        /* Dashboard Specific Hardcoded Fixes */
        [data-theme="dark"] .rounded.bg-white.shadow-sm[style*="border: 2px dashed"] {
            background-color: rgba(255,255,255,0.03) !important;
            border-color: var(--theme-primary) !important;
        }

        [data-theme="dark"] .widget-title.down-line::after {
            background-color: var(--theme-primary);
        }

        [data-theme="dark"] .dashboard-sidebar-btn {
            background-color: var(--theme-primary) !important;
        }
        
        [data-theme="dark"] .user-title {
            color: var(--theme-primary) !important;
        }
        [data-theme="dark"] .top-header,
        [data-theme="dark"] .ecommerce-header {
            background-color: var(--bg-main) !important;
            border-bottom: 1px solid var(--border-color);
        }

        [data-theme="dark"] .my-account-popup li a:hover {
            background: rgba(255,255,255,0.05);
        }

        /* Icon & Header Text Fixes */
        [data-theme="dark"] i, 
        [data-theme="dark"] [class^="flaticon-"], 
        [data-theme="dark"] [class*=" flaticon-"],
        [data-theme="dark"] .fas, 
        [data-theme="dark"] .far, 
        [data-theme="dark"] .fab,
        [data-theme="dark"] svg:not(.product-svg) {
            color: var(--text-main) !important;
            fill: var(--text-main) !important;
        }

        [data-theme="dark"] .ecommerce-header i,
        [data-theme="dark"] .top-header i,
        [data-theme="dark"] .responsive-menubar i,
        [data-theme="dark"] .header-sticky i,
        [data-theme="dark"] .ecommerce-header svg,
        [data-theme="dark"] .top-header svg {
            color: var(--text-dark) !important;
            fill: var(--text-dark) !important;
        }

        [data-theme="dark"] .nav-link, 
        [data-theme="dark"] .menu-item-text,
        [data-theme="dark"] .dropdown-item,
        [data-theme="dark"] .navbar-brand,
        [data-theme="dark"] .category-link,
        [data-theme="dark"] .nice-select .option,
        [data-theme="dark"] .cart-item-name a,
        [data-theme="dark"] .cart-item-price {
            color: var(--text-main) !important;
        }

        /* Specific Header Hardcoded Fixes */
        [data-theme="dark"] .text-white i,
        [data-theme="dark"] .btn i,
        [data-theme="dark"] .search-submit i,
        [data-theme="dark"] .text-white svg {
            color: #FFFFFF !important;
            fill: #FFFFFF !important;
        }

        /* Alignment Fixes */
        .top-header {
            min-height: 40px;
            display: flex;
            align-items: center;
        }
        
        .top-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .top-links li {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .language-selector, .currency-selector, .theme-chooser-header {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Search Bar & Action Icons Fixes */
        .header-cart-1, .wishlist-view, .refresh-view, .sign-in {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .cart-icon i, .sign-in i, .search-pop i {
            transition: color 0.3s ease;
        }

        [data-theme="dark"] .cart-icon i, 
        [data-theme="dark"] .sign-in i, 
        [data-theme="dark"] .search-pop i,
        [data-theme="dark"] .top-header span,
        [data-theme="dark"] .top-header i {
            color: var(--text-main) !important;
        }

        [data-theme="dark"] .header-sticky {
            background-color: var(--bg-surface) !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3) !important;
        }

        /* Niche Theme Priority Integration */
        <?php if(isset($active_theme)): ?>
            <?php if(isset($active_theme->font_family)): ?>
            body { font-family: var(--theme-font) !important; }
            <?php endif; ?>
            
            <?php if(isset($active_theme->bg_color)): ?>
            body { background-color: var(--theme-bg) !important; }
            <?php endif; ?>

            <?php if(isset($active_theme->global_css)): ?> <?php echo $active_theme->global_css; ?> <?php endif; ?>
            <?php if(isset($active_theme->header_css)): ?> <?php echo $active_theme->header_css; ?> <?php endif; ?>
            <?php if(isset($active_theme->product_card_css)): ?> <?php echo $active_theme->product_card_css; ?> <?php endif; ?>
            <?php if(isset($active_theme->banner_css)): ?> <?php echo $active_theme->banner_css; ?> <?php endif; ?>
            <?php if(isset($active_theme->footer_css)): ?> <?php echo $active_theme->footer_css; ?> <?php endif; ?>
        <?php endif; ?>

        /* Utility */
        .theme-toggle-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--theme-primary);
            color: white;
            border: none;
            cursor: pointer;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            transition: all 0.3s;
        }
        .theme-toggle-btn:hover { transform: scale(1.1) rotate(15deg); }
        /* Search Bar Outline */
        .product-search-one .search-form,
        .search-mobile.search-form {
            border: 1px solid var(--border-color) !important;
            transition: all 0.3s ease;
        }

        .product-search-one .search-form:hover,
        .product-search-one .search-form:focus-within,
        .search-mobile.search-form:hover,
        .search-mobile.search-form:focus-within {
            border-color: var(--theme-primary) !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }

        /* Desktop Header Cleanup */
        .main-nav {
            position: relative;
            z-index: 20;
        }

        .main-nav .main-nav-shell {
            width: 100%;
        }

        .main-nav .header-brand {
            margin-right: 20px;
            display: inline-flex;
            align-items: center;
        }

        .main-nav .header-brand img {
            max-height: 88px;
            width: auto;
            object-fit: contain;
        }

        .main-nav .main-menu-list {
            gap: 6px;
        }

        .main-nav .main-menu-list .nav-link {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            padding: 10px 11px;
            border-radius: 10px;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .main-nav .main-menu-list .nav-item.active > .nav-link,
        .main-nav .main-menu-list .nav-link:hover {
            background: rgba(255, 255, 255, 0.45);
        }

        .main-nav .header-right-cluster {
            gap: 12px;
        }

        .main-nav .top-search-wrap {
            max-width: 680px;
            min-width: 320px;
        }

        .main-nav .top-search-wrap .search-form {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(16, 24, 40, 0.08);
            box-shadow: 0 10px 24px rgba(16, 24, 40, 0.08);
        }

        .main-nav .top-search-wrap input.search-field {
            padding-left: 20px;
        }

        .main-nav .top-search-wrap .search-submit {
            background: #111827;
            border-top-right-radius: 999px;
            border-bottom-right-radius: 999px;
        }

        .main-nav .top-search-wrap .categori-container {
            min-width: 185px;
            border-left: 1px solid rgba(16, 24, 40, 0.1);
        }

        .main-nav .header-integrated-items {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(16, 24, 40, 0.08);
            border-radius: 999px;
            padding: 5px 10px;
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            margin-left: 8px !important;
            margin-right: 2px !important;
        }

        .main-nav .header-language-chip {
            min-height: auto;
            height: auto;
            display: flex;
            align-items: center;
            border-radius: 999px;
            padding: 0 4px;
        }

        .main-nav .top-sell-btn {
            background: #1f2937;
            color: #fff;
            border: 1px solid transparent;
            font-size: 12px;
        }

        .main-nav .top-sell-btn:hover {
            background: #111827;
            color: #fff;
        }

        .main-nav .header-icon-actions {
            gap: 8px;
        }

        .main-nav .header-icon-actions .sign-in > a,
        .main-nav .header-icon-actions .search-view > a,
        .main-nav .header-icon-actions .header-cart-1 .cart-icon {
            width: 44px;
            height: 44px;
            line-height: 44px;
            border: 1px solid rgba(16, 24, 40, 0.1);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 6px 14px rgba(16, 24, 40, 0.08);
        }

        .main-nav .header-icon-actions .header-cart-1 .cart .cart-wrap {
            display: none !important;
        }

        .main-nav .header-icon-actions [class*="header-cart-"] .cart .cart-icon .header-cart-count {
            width: 18px;
            height: 18px;
            line-height: 18px;
            font-size: 10px;
            top: -4px;
            right: -4px;
            left: auto;
        }

        .main-nav .header-icon-actions .sign-in > a i {
            font-size: 28px !important;
        }

        @media (max-width: 1599px) {
            .main-nav .header-brand img {
                max-height: 76px;
            }

            .main-nav .main-menu-list .nav-link {
                font-size: 13px;
                padding: 9px 9px;
            }

            .main-nav .top-search-wrap {
                min-width: 280px;
            }
        }

        @media (max-width: 1399px) {
            .main-nav .header-integrated-items {
                display: none !important;
            }

            .main-nav .top-search-wrap {
                max-width: 560px;
            }
        }
    </style>

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




<script>
    // Force light mode only
    const html = document.documentElement;
    html.setAttribute('data-theme', 'light');
    localStorage.setItem('theme', 'light');
</script>

     <?php
     echo Toastr::message();
     ?>
     <?php echo $__env->yieldContent('script'); ?>



</body>
</html>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\layouts\front.blade.php ENDPATH**/ ?>