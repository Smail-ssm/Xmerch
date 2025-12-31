@if(Auth::guard('admin')->user()->role_id != 0)

@php
    $user = Auth::guard('admin')->user();
    $hasSalesAccess = $user->sectionCheck('orders') || $user->sectionCheck('earning') || $user->sectionCheck('customer_deposits');
    $hasInventoryAccess = $user->sectionCheck('categories') || $user->sectionCheck('products') || $user->sectionCheck('affilate_products') || $user->sectionCheck('bulk_product_upload') || $user->sectionCheck('set_coupons');
    $hasManufacturingAccess = $user->sectionCheck('print_production');
@endphp

@if($hasSalesAccess)
    <li class="nav-header">
        <i class="fas fa-chart-line"></i> {{ __('Sales & Revenue') }}
    </li>

    @if($user->sectionCheck('orders'))
    <li>
        <a href="#order" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-hand-holding-usd"></i>{{ __('Orders') }}</a>
        <ul class="collapse list-unstyled" id="order" data-parent="#accordion" >
            <li>
                <a href="{{ route('admin-orders-all') }}"> {{ __('All Orders') }}</a>
            </li>
            <li>
                <a href="{{ route('admin-orders-all') }}?status=pending"> {{ __('Pending Orders') }}</a>
            </li>
            <li>
                <a href="{{ route('admin-orders-all') }}?status=processing"> {{ __('Processing Orders') }}</a>
            </li>
            <li>
                <a href="{{ route('admin-orders-all') }}?status=completed"> {{ __('Completed Orders') }}</a>
            </li>
            <li>
                <a href="{{ route('admin-orders-all') }}?status=declined"> {{ __('Declined Orders') }}</a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('earning'))
    <li>
        <a href="#income" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-file-invoice-dollar"></i>{{ __('Total Earning') }}</a>
        <ul class="collapse list-unstyled" id="income" data-parent="#accordion" >
            <li>
                <a href="{{route('admin-tax-calculate-income')}}"> {{ __('Tax Calculate') }}</a>
            </li>
            <li>
                <a href="{{route('admin-subscription-income')}}"> {{ __('Subscription Earning') }}</a>
            </li>
            <li>
                <a href="{{route('admin-withdraw-income')}}"> {{ __('Withdraw Earning') }}</a>
            </li>
            <li>
                <a href="{{route('admin-commission-income')}}"> {{ __('Commission Earning') }}</a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('customer_deposits'))
    <li>
        <a href="#customerDeposit" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-money"></i>{{ __('Customer Deposits') }}
        </a>
        <ul class="collapse list-unstyled" id="customerDeposit" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-user-deposits','all') }}"><span>{{ __('Completed Deposits') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-user-deposits','pending') }}"><span>{{ __('Pending Deposits') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-trans-index') }}"><span>{{ __('Transactions') }}</span></a>
            </li>
        </ul>
    </li>
    @endif
@endif

@if($hasInventoryAccess)
    <li class="nav-header">
        <i class="fas fa-boxes"></i> {{ __('Inventory & Designs') }}
    </li>

    @if($user->sectionCheck('categories'))
    <li>
        <a href="#menu5" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false"><i class="fas fa-sitemap"></i>{{ __('Manage Categories') }}</a>
        <ul class="collapse list-unstyled
        @if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category')
          show
        @elseif(request()->is('admin/attribute/*/manage') && request()->input('type')=='subcategory')
          show
        @elseif(request()->is('admin/attribute/*/manage') && request()->input('type')=='childcategory')
          show
        @endif" id="menu5" data-parent="#accordion" >
                <li class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='category') active @endif">
                    <a href="{{ route('admin-cat-index') }}"><span>{{ __('Main Category') }}</span></a>
                </li>
                <li class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='subcategory') active @endif">
                    <a href="{{ route('admin-subcat-index') }}"><span>{{ __('Sub Category') }}</span></a>
                </li>
                <li class="@if(request()->is('admin/attribute/*/manage') && request()->input('type')=='childcategory') active @endif">
                    <a href="{{ route('admin-childcat-index') }}"><span>{{ __('Child Category') }}</span></a>
                </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('products'))
    <li>
        <a href="#menu2" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-cart"></i>{{ __('Design Management') }}
        </a>
        <ul class="collapse list-unstyled" id="menu2" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-prod-types') }}"><span>{{ __('Add New Design') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-prod-index') }}"><span>{{ __('All Designs') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-prod-deactive') }}"><span>{{ __('Deactivated Designs') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-prod-catalog-index') }}"><span>{{ __('Design Catalogs') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-prod-settings') }}"><span>{{ __('Design Settings') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('affilate_products'))
    <li>
        <a href="#affiliateprod" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-opencart"></i>{{ __('Affiliate Products') }}
        </a>
        <ul class="collapse list-unstyled" id="affiliateprod" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-import-create') }}"><span>{{ __('Add Affiliate Product') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-import-index') }}"><span>{{ __('All Affiliate Products') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('bulk_product_upload'))
    <li>
        <a href="{{ route('admin-prod-import') }}"><i class="fas fa-upload"></i>{{ __('Bulk Design Upload') }}</a>
    </li>
    @endif

    @if($user->sectionCheck('set_coupons'))
    <li>
        <a href="{{ route('admin-coupon-index') }}" class=" wave-effect"><i class="fas fa-percentage"></i>{{ __('Discount Coupons') }}</a>
    </li>
    @endif
@endif

@if($hasManufacturingAccess)
    <li class="nav-header">
        <i class="fas fa-tools"></i> {{ __('Manufacturing') }}
    </li>

    <li>
        <a href="#printer" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-print"></i>{{ __('Print Production') }}
        </a>
        <ul class="collapse list-unstyled" id="printer" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-printer-dashboard') }}"><span>{{ __('Dashboard') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-printer-queue') }}"><span>{{ __('Print Queue') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-printer-printing') }}"><span>{{ __('Currently Printing') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-printer-ready') }}"><span>{{ __('Ready to Ship') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-printer-shipped') }}"><span>{{ __('Shipped') }}</span></a>
            </li>
        </ul>
    </li>
@endif

@php
    $hasCRMAccess = $user->sectionCheck('customers') || $user->sectionCheck('vendors') || $user->sectionCheck('vendor_subscriptions') || $user->sectionCheck('vendor_verifications') || $user->sectionCheck('vendor_subscription_plans') || $user->sectionCheck('messages') || $user->sectionCheck('product_discussion') || $user->sectionCheck('subscribers');
    $hasContentAccess = $user->sectionCheck('blog') || $user->sectionCheck('home_page_settings') || $user->sectionCheck('menu_page_settings');
    $hasSystemAccess = $user->sectionCheck('general_settings') || $user->sectionCheck('emails_settings') || $user->sectionCheck('payment_settings') || $user->sectionCheck('social_settings') || $user->sectionCheck('language_settings') || $user->sectionCheck('seo_tools') || $user->sectionCheck('manage_country');
    $hasAccessControl = $user->sectionCheck('manage_staffs') || $user->sectionCheck('manage_roles') || $user->sectionCheck('cache_clear');
@endphp

@if($hasCRMAccess)
    <li class="nav-header">
        <i class="fas fa-users"></i> {{ __('CRM & Community') }}
    </li>

    @if($user->sectionCheck('customers'))
    <li>
        <a href="#menu3" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-user"></i>{{ __('Customers') }}
        </a>
        <ul class="collapse list-unstyled" id="menu3" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-user-index') }}"><span>{{ __('Customers List') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-withdraw-index') }}"><span>{{ __('Withdraws') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-user-image') }}"><span>{{ __('Customer Default Image') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('vendors'))
    <li>
        <a href="#vendor" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-ui-user-group"></i>{{ __('Designers') }}
        </a>
        <ul class="collapse list-unstyled" id="vendor" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-vendor-index') }}"><span>{{ __('Designers List') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-vendor-withdraw-index') }}"><span>{{ __('Withdraws') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-vendor-color') }}"><span>{{ __('Default Background') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('vendor_subscriptions'))
    <li>
        <a href="#vendorSubs" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-user-suited"></i>{{ __('Designer Subscriptions') }}
        </a>
        <ul class="collapse list-unstyled" id="vendorSubs" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-vendor-subs','completed') }}"><span>{{ __('Completed Subscriptions') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-vendor-subs','pending') }}"><span>{{ __('Pending Subscriptions') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('vendor_verifications'))
    <li>
        <a href="#vendor1" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
                <i class="icofont-verification-check"></i>{{ __('Designer Verifications') }}
        </a>
        <ul class="collapse list-unstyled" id="vendor1" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-vr-index','all') }}"><span>{{ __('All Verifications') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-vr-index','pending') }}"><span>{{ __('Pending Verifications') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('vendor_subscription_plans'))
    <li>
        <a href="{{ route('admin-subscription-index') }}" class=" wave-effect"><i class="fas fa-dollar-sign"></i>{{ __('Designer Subscription Plans') }}</a>
    </li>
    @endif

    @if($user->sectionCheck('messages'))
    <li>
        <a href="#msg" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-newspaper"></i>{{ __('Messages') }}
        </a>
        <ul class="collapse list-unstyled" id="msg" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-message-index') }}"><span>{{ __('Tickets') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-message-dispute') }}"><span>{{ __('Disputes') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('product_discussion'))
    <li>
        <a href="#menu4" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="icofont-speech-comments"></i>{{ __('Design Discussion') }}
        </a>
        <ul class="collapse list-unstyled" id="menu4" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-rating-index') }}"><span>{{ __('Design Reviews') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-comment-index') }}"><span>{{ __('Comments') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-report-index') }}"><span>{{ __('Reports') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('subscribers'))
    <li>
        <a href="{{ route('admin-subs-index') }}" class=" wave-effect"><i class="fas fa-users-cog mr-2"></i>{{ __('Subscribers') }}</a>
    </li>
    @endif
@endif

@if($hasContentAccess)
    <li class="nav-header">
        <i class="fas fa-desktop"></i> {{ __('Content Management') }}
    </li>

    @if($user->sectionCheck('blog'))
    <li>
        <a href="#blog" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-fw fa-newspaper"></i>{{ __('Blog') }}
        </a>
        <ul class="collapse list-unstyled" id="blog" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-cblog-index') }}"><span>{{ __('Categories') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-blog-index') }}"><span>{{ __('Posts') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-blog-settings') }}"><span>{{ __('Blog Settings') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('home_page_settings'))
    <li>
        <a href="#homepage" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-edit"></i>{{ __('Home Page Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="homepage" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-sl-index') }}"><span>{{ __('Sliders') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-arrival-index') }}"><span>{{ __('Arrival Section') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-deal') }}"><span>{{ __('Deal of the day') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-partner-index') }}"><span>{{ __('Partners') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-ps-customize') }}"><span>{{ __('Home Page Customization') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('menu_page_settings'))
    <li>
        <a href="#menu" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-file-code"></i>{{ __('Menu Page Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="menu" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-faq-index') }}"><span>{{ __('FAQ Page') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-ps-contact') }}"><span>{{ __('Contact Us Page') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-page-index') }}"><span>{{ __('Other Pages') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-ps-page-banner') }}"><span>{{ __('Other Page Banner') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-ps-menu-links') }}"><span>{{ __('Customize Menu Links') }}</span></a>
            </li>
        </ul>
    </li>
    @endif
@endif

@if($hasSystemAccess)
    <li class="nav-header">
        <i class="fas fa-sliders-h"></i> {{ __('System Settings') }}
    </li>

    @if($user->sectionCheck('general_settings'))
    <li>
        <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-cogs"></i>{{ __('General Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-gs-logo') }}"><span>{{ __('Logo') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-fav') }}"><span>{{ __('Favicon') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-load') }}"><span>{{ __('Loader') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-shipping-index') }}"><span>{{ __('Shipping Methods') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-package-index') }}"><span>{{ __('Packagings') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-pick-index') }}"><span>{{ __('Pickup Locations') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-contents') }}"><span>{{ __('Website Contents') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-affilate') }}"><span>{{__('Affiliate Program')}}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-popup') }}"><span>{{ __('Popup Banner') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-bread') }}"><span>{{ __('Breadcrumb Banner') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-error-banner') }}"><span>{{ __('Error Banner') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-gs-maintenance') }}"><span>{{ __('Website Maintenance') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('emails_settings'))
    <li>
        <a href="#emails" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-at"></i>{{ __('Email Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="emails" data-parent="#accordion">
            <li><a href="{{route('admin-mail-index')}}"><span>{{ __('Email Template') }}</span></a></li>
            <li><a href="{{route('admin-mail-config')}}"><span>{{ __('Email Configurations') }}</span></a></li>
            <li><a href="{{route('admin-group-show')}}"><span>{{ __('Group Email') }}</span></a></li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('payment_settings'))
    <li>
        <a href="#payments" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-file-invoice"></i>{{ __('Payment Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="payments" data-parent="#accordion">
            <li><a href="{{route('admin-gs-payments')}}"><span>{{__('Payment Information')}}</span></a></li>
            <li><a href="{{route('admin-payment-index')}}"><span>{{ __('Payment Gateways') }}</span></a></li>
            <li><a href="{{route('admin-currency-index')}}"><span>{{ __('Currencies') }}</span></a></li>
            <li><a href="{{route('admin-reward-index')}}"><span>{{__('Reward Information')}}</span></a></li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('social_settings'))
    <li>
        <a href="#socials" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-paper-plane"></i>{{ __('Social Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="socials" data-parent="#accordion">
                <li><a href="{{route('admin-sociallink-index')}}"><span>{{ __('Social Links') }}</span></a></li>
                <li><a href="{{route('admin-social-facebook')}}"><span>{{ __('Facebook Login') }}</span></a></li>
                <li><a href="{{route('admin-social-google')}}"><span>{{ __('Google Login') }}</span></a></li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('language_settings'))
    <li>
        <a href="#langs" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-language"></i>{{ __('Language Settings') }}
        </a>
        <ul class="collapse list-unstyled" id="langs" data-parent="#accordion">
                <li><a href="{{route('admin-lang-index')}}"><span>{{ __('Website Language') }}</span></a></li>
                <li><a href="{{route('admin-tlang-index')}}"><span>{{ __('Admin Panel Language') }}</span></a></li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('seo_tools'))
    <li>
        <a href="#seoTools" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-wrench"></i>{{ __('SEO Tools') }}
        </a>
        <ul class="collapse list-unstyled" id="seoTools" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-prod-popular',30) }}"><span>{{ __('Popular Products') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-seotool-analytics') }}"><span>{{ __('Google Analytics') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-seotool-keywords') }}"><span>{{ __('Website Meta Keywords') }}</span></a>
            </li>
        </ul>
    </li>
    @endif

    @if($user->sectionCheck('manage_country'))
    <li>
        <a href="#menu1" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
            <i class="fas fa-flag"></i>{{ __('Manage Country') }}
        </a>
        <ul class="collapse list-unstyled" id="menu1" data-parent="#accordion">
            <li>
                <a href="{{ route('admin-country-index') }}"><span>{{ __('Country') }}</span></a>
            </li>
            <li>
                <a href="{{ route('admin-country-tax') }}"><span>{{ __('Manage Tax') }}</span></a>
            </li>
        </ul>
    </li>
    @endif
@endif

@if($hasAccessControl)
    <li class="nav-header">
        <i class="fas fa-user-lock"></i> {{ __('Access Control') }}
    </li>

    @if($user->sectionCheck('manage_staffs'))
    <li>
        <a href="{{ route('admin-staff-index') }}" class=" wave-effect"><i class="fas fa-user-secret"></i>{{ __('Manage Staffs') }}</a>
    </li>
    @endif

    @if($user->sectionCheck('manage_roles'))
    <li>
        <a href="{{ route('admin-role-index') }}" class=" wave-effect"><i class="fas fa-user-tag"></i>{{ __('Manage Roles') }}</a>
    </li>
    @endif

    @if($user->sectionCheck('cache_clear'))
    <li>
        <a href="{{ route('admin-cache-clear') }}" class=" wave-effect"><i class="fas fa-sync"></i>{{ __('Clear Cache') }}</a>
    </li>
    @endif
@endif

@endif
