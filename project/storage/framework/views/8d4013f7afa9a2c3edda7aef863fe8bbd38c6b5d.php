    <li class="nav-header">
        <i class="fas fa-chart-line"></i> <?php echo e(__('Sales & Revenue')); ?>

    </li>

    <li>
        <a href="#order" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-hand-holding-usd"></i><?php echo e(__('Orders')); ?></a>
        <ul class="collapse list-unstyled" id="order" data-parent="#accordion" >
            <li>
                <a href="<?php echo e(route('admin-orders-all')); ?>"> <?php echo e(__('All Orders')); ?></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-orders-all')); ?>?status=pending"> <?php echo e(__('Pending Orders')); ?></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-orders-all')); ?>?status=processing"> <?php echo e(__('Processing Orders')); ?></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-orders-all')); ?>?status=completed"> <?php echo e(__('Completed Orders')); ?></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-orders-all')); ?>?status=declined"> <?php echo e(__('Declined Orders')); ?></a>
            </li>

        </ul>
    </li>

    <li>
        <a href="#income" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-file-invoice-dollar"></i><?php echo e(__('Revenue Reports')); ?></a>
        <ul class="collapse list-unstyled" id="income" data-parent="#accordion" >
            <li>
                <a href="<?php echo e(route('admin-tax-calculate-income')); ?>"> <?php echo e(__('Tax Calculate')); ?></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-subscription-income')); ?>"> <?php echo e(__('Subscription Earning')); ?></a>
            </li>

            <li>
                <a href="<?php echo e(route('admin-withdraw-income')); ?>"> <?php echo e(__('Withdraw Earning')); ?></a>
            </li>

            <li>
                <a href="<?php echo e(route('admin-commission-income')); ?>"> <?php echo e(__('Commission Earning')); ?></a>
            </li>

        </ul>
    </li>

    <li>
        <a href="#customerDeposit" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-money"></i><?php echo e(__('Customer Deposits')); ?>

        </a>
        <ul class="collapse list-unstyled" id="customerDeposit" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-user-deposits','all')); ?>"><span><?php echo e(__('Completed Deposits')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-user-deposits','pending')); ?>"><span><?php echo e(__('Pending Deposits')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-trans-index')); ?>"><span><?php echo e(__('Transactions')); ?></span></a>
            </li>
        </ul>
    </li>

    <li class="nav-header">
        <i class="fas fa-boxes"></i> <?php echo e(__('Inventory & Designs')); ?>

    </li>

    <li>
            <a href="#menu5" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-sitemap"></i><?php echo e(__('Manage Categories')); ?></a>
            <ul class="collapse list-unstyled
            <?php if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category'): ?>
              show
            <?php elseif(request()->is('admin/attribute/*/manage') && request()->input('type')=='subcategory'): ?>
              show
            <?php elseif(request()->is('admin/attribute/*/manage') && request()->input('type')=='childcategory'): ?>
              show
            <?php endif; ?>" id="menu5" data-parent="#accordion" >
                    <li class="<?php if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category'): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin-cat-index')); ?>"><span><?php echo e(__('Main Category')); ?></span></a>
                    </li>
                    <li class="<?php if(request()->is('admin/attribute/*/manage') && request()->input('type')=='subcategory'): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin-subcat-index')); ?>"><span><?php echo e(__('Sub Category')); ?></span></a>
                    </li>
                    <li class="<?php if(request()->is('admin/attribute/*/manage') && request()->input('type')=='childcategory'): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin-childcat-index')); ?>"><span><?php echo e(__('Child Category')); ?></span></a>
                    </li>
            </ul>
    </li>

    <li>
        <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-cart"></i><?php echo e(__('Design Management')); ?>

        </a>
        <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-prod-types')); ?>"><span><?php echo e(__('Add New Design')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-prod-index')); ?>"><span><?php echo e(__('All Designs')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-prod-deactive')); ?>"><span><?php echo e(__('Deactivated Designs')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-prod-catalog-index')); ?>"><span><?php echo e(__('Design Catalogs')); ?></span></a>
            </li>

            <li>
                <a href="<?php echo e(route('admin-gs-prod-settings')); ?>"><span><?php echo e(__('Design Settings')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-mockup-index')); ?>"><span><?php echo e(__('Mockup Templates')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-pod-pricing-index')); ?>"><span><?php echo e(__('POD Pricing')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#affiliateprod" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-opencart"></i><?php echo e(__('Affiliate Products')); ?>

        </a>
        <ul class="collapse list-unstyled" id="affiliateprod" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-import-create')); ?>"><span><?php echo e(__('Add Affiliate Product')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-import-index')); ?>"><span><?php echo e(__('All Affiliate Products')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="<?php echo e(route('admin-prod-import')); ?>"><i class="fas fa-upload"></i><?php echo e(__('Bulk Design Upload')); ?></a>
    </li>

    <li>
        <a href="<?php echo e(route('admin-coupon-index')); ?>" class=" wave-effect"><i class="fas fa-percentage"></i><?php echo e(__('Discount Coupons')); ?></a>
    </li>

    <li class="nav-header">
        <i class="fas fa-tools"></i> <?php echo e(__('Manufacturing')); ?>

    </li>

    <?php if(Auth::guard('admin')->user()->IsSuper() || Auth::guard('admin')->user()->sectionCheck('print_production') || Auth::guard('admin')->user()->sectionCheck('manufacturing')): ?>
    <li>
        <a href="#printer" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-print"></i><?php echo e(__('Print Production')); ?>

        </a>
        <ul class="collapse list-unstyled" id="printer" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-printer-dashboard')); ?>"><span><?php echo e(__('Dashboard')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-printer-queue')); ?>"><span><?php echo e(__('Print Queue')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-printer-printing')); ?>"><span><?php echo e(__('Currently Printing')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-printer-ready')); ?>"><span><?php echo e(__('Ready to Ship')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-printer-shipped')); ?>"><span><?php echo e(__('Shipped')); ?></span></a>
            </li>
            <?php if(Auth::guard('admin')->user()->IsSuper() || Auth::guard('admin')->user()->sectionCheck('manage_staffs')): ?>
            <li>
                <a href="<?php echo e(route('admin-printer-accounts')); ?>"><span><?php echo e(__('Manage Accounts')); ?></span></a>
            </li>
            <?php endif; ?>
        </ul>
    </li>
    <?php endif; ?>

    <li class="nav-header">
        <i class="fas fa-users"></i> <?php echo e(__('CRM & Community')); ?>

    </li>

    <li>
        <a href="#menu3" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-user"></i><?php echo e(__('Customers')); ?>

        </a>
        <ul class="collapse list-unstyled" id="menu3" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-user-index')); ?>"><span><?php echo e(__('Customers List')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-withdraw-index')); ?>"><span><?php echo e(__('Withdraws')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-user-image')); ?>"><span><?php echo e(__('Customer Default Image')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#vendor" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-ui-user-group"></i><?php echo e(__('Designers')); ?>

        </a>
        <ul class="collapse list-unstyled" id="vendor" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-vendor-index')); ?>"><span><?php echo e(__('Designers List')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-vendor-withdraw-index')); ?>"><span><?php echo e(__('Withdraws')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-vendor-color')); ?>"><span><?php echo e(__('Default Background')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#vendorSubs" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-user-suited"></i><?php echo e(__('Designer Subscriptions')); ?>

        </a>
        <ul class="collapse list-unstyled" id="vendorSubs" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-vendor-subs','completed')); ?>"><span><?php echo e(__('Completed Subscriptions')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-vendor-subs','pending')); ?>"><span><?php echo e(__('Pending Subscriptions')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#vendor1" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
                <i class="icofont-verification-check"></i><?php echo e(__('Designer Verifications')); ?>

        </a>
        <ul class="collapse list-unstyled" id="vendor1" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-vr-index','all')); ?>"><span><?php echo e(__('All Verifications')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-vr-index','pending')); ?>"><span><?php echo e(__('Pending Verifications')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="<?php echo e(route('admin-subscription-index')); ?>" class=" wave-effect"><i class="fas fa-dollar-sign"></i><?php echo e(__('Designer Subscription Plans')); ?></a>
    </li>

    <li>
        <a href="#msg" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-newspaper"></i><?php echo e(__('Messages')); ?>

        </a>
        <ul class="collapse list-unstyled" id="msg" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-message-index')); ?>"><span><?php echo e(__('Tickets')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-message-dispute')); ?>"><span><?php echo e(__('Disputes')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-speech-comments"></i><?php echo e(__('Design Discussion')); ?>

        </a>
        <ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-rating-index')); ?>"><span><?php echo e(__('Design Reviews')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-comment-index')); ?>"><span><?php echo e(__('Comments')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-report-index')); ?>"><span><?php echo e(__('Reports')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="<?php echo e(route('admin-subs-index')); ?>" class=" wave-effect"><i class="fas fa-users-cog mr-2"></i><?php echo e(__('Subscribers')); ?></a>
    </li>

    <li class="nav-header">
        <i class="fas fa-desktop"></i> <?php echo e(__('Content Management')); ?>

    </li>

    <li>
        <a href="#blog" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-fw fa-newspaper"></i><?php echo e(__('Blog')); ?>

        </a>
        <ul class="collapse list-unstyled" id="blog" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-cblog-index')); ?>"><span><?php echo e(__('Categories')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-blog-index')); ?>"><span><?php echo e(__('Posts')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-blog-settings')); ?>"><span><?php echo e(__('Blog Settings')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#homepage" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-edit"></i><?php echo e(__('Home Page Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="homepage" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-sl-index')); ?>"><span><?php echo e(__('Sliders')); ?></span></a>
            </li>

            <li>
                <a href="<?php echo e(route('admin-arrival-index')); ?>"><span><?php echo e(__('Arrival Section')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-deal')); ?>"><span><?php echo e(__('Deal of the day')); ?></span></a>
            </li>


            <li>
                <a href="<?php echo e(route('admin-partner-index')); ?>"><span><?php echo e(__('Partners')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-ps-customize')); ?>"><span><?php echo e(__('Home Page Customization')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#menu" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-file-code"></i><?php echo e(__('Menu Page Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="menu" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-faq-index')); ?>"><span><?php echo e(__('FAQ Page')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-ps-contact')); ?>"><span><?php echo e(__('Contact Us Page')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-page-index')); ?>"><span><?php echo e(__('Other Pages')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-ps-page-banner')); ?>"><span><?php echo e(__('Other Page Banner')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-ps-menu-links')); ?>"><span><?php echo e(__('Customize Menu Links')); ?></span></a>
            </li>
        </ul>
    </li>

    <li class="nav-header">
        <i class="fas fa-sliders-h"></i> <?php echo e(__('System Settings')); ?>

    </li>

    <li>
        <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-cogs"></i><?php echo e(__('General Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-gs-logo')); ?>"><span><?php echo e(__('Logo')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-fav')); ?>"><span><?php echo e(__('Favicon')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-load')); ?>"><span><?php echo e(__('Loader')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-shipping-index')); ?>"><span><?php echo e(__('Shipping Methods')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-package-index')); ?>"><span><?php echo e(__('Packagings')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-pick-index')); ?>"><span><?php echo e(__('Pickup Locations')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-contents')); ?>"><span><?php echo e(__('Website Contents')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-affilate')); ?>"><span><?php echo e(__('Affiliate Program')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-popup')); ?>"><span><?php echo e(__('Popup Banner')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-bread')); ?>"><span><?php echo e(__('Breadcrumb Banner')); ?></span></a>
            </li>

            <li>
                <a href="<?php echo e(route('admin-gs-error-banner')); ?>"><span><?php echo e(__('Error Banner')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-gs-maintenance')); ?>"><span><?php echo e(__('Website Maintenance')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#emails" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-at"></i><?php echo e(__('Email Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="emails" data-parent="#accordion">
            <li><a href="<?php echo e(route('admin-mail-index')); ?>"><span><?php echo e(__('Email Template')); ?></span></a></li>
            <li><a href="<?php echo e(route('admin-mail-config')); ?>"><span><?php echo e(__('Email Configurations')); ?></span></a></li>
            <li><a href="<?php echo e(route('admin-group-show')); ?>"><span><?php echo e(__('Group Email')); ?></span></a></li>
        </ul>
    </li>

    <li>
        <a href="#payments" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-file-invoice"></i><?php echo e(__('Payment Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="payments" data-parent="#accordion">
            <li><a href="<?php echo e(route('admin-gs-payments')); ?>"><span><?php echo e(__('Payment Information')); ?></span></a></li>
            <li><a href="<?php echo e(route('admin-payment-index')); ?>"><span><?php echo e(__('Payment Gateways')); ?></span></a></li>
            <li><a href="<?php echo e(route('admin-currency-index')); ?>"><span><?php echo e(__('Currencies')); ?></span></a></li>
            <li><a href="<?php echo e(route('admin-reward-index')); ?>"><span><?php echo e(__('Reward Information')); ?></span></a></li>
        </ul>
    </li>

    <li>
        <a href="#socials" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-paper-plane"></i><?php echo e(__('Social Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="socials" data-parent="#accordion">
                <li><a href="<?php echo e(route('admin-sociallink-index')); ?>"><span><?php echo e(__('Social Links')); ?></span></a></li>
                <li><a href="<?php echo e(route('admin-social-facebook')); ?>"><span><?php echo e(__('Facebook Login')); ?></span></a></li>
                <li><a href="<?php echo e(route('admin-social-google')); ?>"><span><?php echo e(__('Google Login')); ?></span></a></li>
        </ul>
    </li>

    <li>
        <a href="#langs" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-language"></i><?php echo e(__('Language Settings')); ?>

        </a>
        <ul class="collapse list-unstyled" id="langs" data-parent="#accordion">
                <li><a href="<?php echo e(route('admin-lang-index')); ?>"><span><?php echo e(__('Website Language')); ?></span></a></li>
                <li><a href="<?php echo e(route('admin-tlang-index')); ?>"><span><?php echo e(__('Admin Panel Language')); ?></span></a></li>

        </ul>
    </li>

    <li>
        <a href="<?php echo e(route('admin.fonts.index')); ?>" class=" wave-effect"><i class="fa fa-font"></i><?php echo e(__('Font Option')); ?></a>
    </li>

    <li>
        <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-wrench"></i><?php echo e(__('SEO Tools')); ?>

        </a>
        <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-prod-popular',30)); ?>"><span><?php echo e(__('Popular Products')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-seotool-analytics')); ?>"><span><?php echo e(__('Google Analytics')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-seotool-keywords')); ?>"><span><?php echo e(__('Website Meta Keywords')); ?></span></a>
            </li>
        </ul>
    </li>

    <li>
        <a href="#menu1" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-flag"></i><?php echo e(__('Manage Country')); ?>

        </a>
        <ul class="collapse list-unstyled" id="menu1" data-parent="#accordion">
            <li>
                <a href="<?php echo e(route('admin-country-index')); ?>"><span><?php echo e(__('Country')); ?></span></a>
            </li>
            <li>
                <a href="<?php echo e(route('admin-country-tax')); ?>"><span><?php echo e(__('Manage Tax')); ?></span></a>
            </li>
        </ul>
    </li>

    <li class="nav-header">
        <i class="fas fa-user-lock"></i> <?php echo e(__('Access Control')); ?>

    </li>

    <li>
        <a href="<?php echo e(route('admin-staff-index')); ?>" class=" wave-effect"><i class="fas fa-user-secret"></i><?php echo e(__('Manage Staffs')); ?></a>
    </li>

    <li>
        <a href="<?php echo e(route('admin-role-index')); ?>" class=" wave-effect"><i class="fas fa-user-tag"></i><?php echo e(__('Manage Roles')); ?></a>
    </li>

    <li>
        <a href="<?php echo e(route('admin-cache-clear')); ?>" class=" wave-effect"><i class="fas fa-sync"></i><?php echo e(__('Clear Cache')); ?></a>
    </li>

    <li>
        <a href="<?php echo e(route('admin-cache-clear')); ?>" class=" wave-effect"><i class="fas fa-sync"></i><?php echo e(__('Clear Cache')); ?></a>
    </li>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views/partials/admin-role/super.blade.php ENDPATH**/ ?>