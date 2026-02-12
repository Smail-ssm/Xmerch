
 
 <?php if($ps->category==1): ?>
 <div class="full-row">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-lg-5">
                 <span class="text-secondary pb-2 d-table tagline mx-auto text-uppercase text-center"><?php echo e(__('Featured Products')); ?></span>
                 <h2 class="main-title mb-4 text-center text-secondary"><?php echo e(__('Our Featured Products')); ?></h2>
             </div>
         </div>
         <div class="products product-style-1">
            <div class="row g-4 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">

                <?php $__currentLoopData = $popular_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col">
                    <?php echo $__env->make('partials.product.home-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
     </div>
 </div>
 <!--==================== Top Products Section End ====================-->
 <?php endif; ?>
 
 <?php if($ps->deal_of_the_day==1): ?>

<!--==================== Deal of the day Section Start ====================-->
<div class="full-row bg-light">
    <div class="container">
        <div class="row offer-product align-items-center">
            <div class="col-xl-5 col-lg-7">
                <h1 class="down-line-secondary text-dark text-uppercase mb-30"><?php echo e(__('Deal')); ?> <br> <?php echo e(__('of the Day')); ?></h1>
                <div class="product type-product">
                    <div class="product-wrapper">
                        <div class="product-info">

                            <h3 class="product-title"><?php echo e($gs->deal_title); ?></h3>
                            <div class="product-price">

                                <div class="on-sale"><span>50</span><span>% off</span></div>
                            </div>
                            <div class="font-fifteen">
                                <p><?php echo e($gs->deal_details); ?></p>
                            </div>
                            <div class="time-count time-box text-center my-30 flex-between w-75" data-countdown="<?php echo e($gs->deal_time); ?>"></div>
                            <a href="<?php echo e(route('front.category').'?type=flash'); ?>" class="btn btn-dark text-uppercase rounded-0"><?php echo e(__('Shop Now')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-5 offset-xl-1">
               
                <div class="xs-mt-30"><img src="<?php echo e($gs->deal_background ? asset('assets/images/'.$gs->deal_background):asset('assets/images/noimage.png')); ?>" alt=""></div>

            </div>
        </div>
    </div>
</div>
<!--==================== Deal of the day Section End ====================-->

 <?php endif; ?>
        <!--==================== Deal of the day Section End ====================-->


<?php if($ps->top_big_trending==1): ?>
        <!--==================== Top Collection Section Start ====================-->
        <div class="full-row bg-white">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="top-collection-tab nav-tab-active-secondary">
                            <ul class="nav nav-pills list-color-general justify-content-center mb-5">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="pill" href="#pills-new-arrival-two"><?php echo e(__('New Arrival')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#pills-Trending-two"><?php echo e(__('Trending')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#pills-best-selling-two"><?php echo e(__('Best Selling')); ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="pill" href="#pills-featured-two"><?php echo e(__('Hot Sale')); ?></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="pills-new-arrival-two">
									<div class="products product-style-1">
										<div class="row g-4 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">

                                            <?php $__currentLoopData = $latest_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<div class="col">
                                                <?php echo $__env->make('partials.product.home-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
											</div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</div>
									</div>
                                </div>
                                <div class="tab-pane fade" id="pills-Trending-two">
									<div class="products product-style-1">
										<div class="row g-4 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
											<?php $__currentLoopData = $trending_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<div class="col">
                                                <?php echo $__env->make('partials.product.home-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
											</div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

										</div>
									</div>
                                </div>
                                <div class="tab-pane fade" id="pills-best-selling-two">
									<div class="products product-style-1">
										<div class="row g-4 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
											<?php $__currentLoopData = $sale_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<div class="col">
                                                <?php echo $__env->make('partials.product.home-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
											</div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

										</div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-featured-two">
									<div class="products product-style-1">
										<div class="row g-4 row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
                                            <?php $__currentLoopData = $hot_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<div class="col">
                                                <?php echo $__env->make('partials.product.home-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
											</div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--==================== Top Collection Section End ====================-->
<?php endif; ?>
<!--==================== Service Section Start ====================-->
<?php if($ps->partner==1): ?>
<div class="full-row bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">

                <h2 class="main-title mb-4 text-center text-secondary"><?php echo e($gs->partner_title); ?></h2>
                <span class="mb-30 sub-title text-general font-medium ordenery-font font-400 text-center"><?php echo e($gs->partner_text); ?></span>
            </div>
        </div>
        <div class="row g-3">
            <?php $__currentLoopData = DB::table('partners')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                <div class="simple-service">
                    <img class="lazy" data-src="<?php echo e(asset('assets/images/partner/'.$data->photo)); ?>" alt="">

                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<?php endif; ?>

<!--==================== Service Section End ====================-->

<!--==================== Top Products Section Start ====================-->
<?php if($ps->best_sellers==1): ?>
<div class="full-row">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <span class="text-secondary pb-2 d-table tagline mx-auto text-uppercase text-center"><?php echo e(__('Top Products')); ?></span>
                <h2 class="main-title mb-4 text-center text-secondary"><?php echo e(__('Best Selling Products')); ?></h2>

            </div>
        </div>

        <div class="row">
            <div class="col-12">

                 <div class="products product-style-1 owl-mx-15">
                    <div class="four-carousel owl-carousel dot-disable nav-arrow-middle-show e-title-general e-title-hover-primary e-image-bg-light  e-info-center e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
                    <?php $__currentLoopData = $best_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item">
                            <?php echo $__env->make('partials.product.home-product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--==================== Top Products Section End ====================-->
<?php endif; ?>
 <!--==================== Our Blog Section Start ====================-->
<?php if($ps->blog==1): ?>
    <div class="full-row pt-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5">

                        <h2 class="main-title mb-4 text-center text-secondary"><?php echo e(__('Latest Post')); ?></h2>
                        <span class="mb-30 sub-title text-general font-medium ordenery-font font-400 text-center"><?php echo e(__('Cillum eu id enim aliquip aute ullamco anim. Culpa deserunt nostrud excepteur voluptate velit ipsum esse enim.')); ?></span>
                    </div>
                </div>
                <div class="row row-cols-lg-2 row-cols-1">
                    <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col">
                        <div class="thumb-latest-blog text-center transation hover-img-zoom mb-3">
                            <div class="post-image overflow-hidden">
                                <a href="<?php echo e(route('front.blogshow',$blog->slug)); ?>">
                                    <img class="lazy" data-src="<?php echo e(asset('assets/images/blogs/'.$blog->photo)); ?>" alt="Image not found!">
                                </a>

                            </div>
                            <div class="post-content">
                                <h3><a href="<?php echo e(route('front.blogshow',$blog->slug)); ?>" class="transation text-dark hover-text-primary d-table my-10 mx-sm-auto"><?php echo e(mb_strlen($blog->title,'UTF-8') > 200 ? mb_substr($blog->title,0,200,'UTF-8')."...":$blog->title); ?></a></h3>
                                <div class="post-meta font-small text-uppercase list-color-general my-3">
                                    <p class="post-date"><?php echo e(date('d M, Y',strtotime($blog->created_at))); ?></p>
                                </div>
                                <a href="<?php echo e(route('front.blogshow',$blog->slug)); ?>" class="btn-link-left-line"><?php echo e(__('Read More')); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </div>
            </div>
        </div>
        <!--==================== Our Blog Section End ====================-->
<?php endif; ?>
<?php if($ps->third_left_banner==1): ?>
    <!--==================== Newsleter Section Start ====================-->
    <div class="full-row bg-dark py-30">
        <div class="container">
            <div class="row mx-auto">
                <div class="col-lg-5 col-md-6 mx-auto">
                    <div class="d-flex align-items-center h-100">
                        <h4 class="text-white mb-0 text-uppercase"><?php echo e(__('Sign up to newslatter')); ?></h4>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12">
                    <form action="<?php echo e(route('front.subscribe')); ?>" class="subscribe-form subscribeform  position-relative md-mt-20" method="POST">
                        <?php echo csrf_field(); ?>
                        <input class="form-control rounded-pill mb-0" type="text" placeholder="Enter your email" aria-label="Address" name="email">
                        <button type="submit" class="btn btn-secondary rounded-right-pill text-white"><?php echo e(__('Send')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Newsleter Section End ====================-->
<?php endif; ?>
    <!--==================== Footer Section Start ====================-->
    <footer class="full-row bg-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-widget mb-5">
                        <div class="footer-logo mb-4">
                            <a href="<?php echo e(route('front.index')); ?>"><img class="lazy" data-src="<?php echo e(asset('assets/images/'.$gs->footer_logo)); ?>" alt="Image not found!" /></a>
                        </div>
                        <div class="widget-ecommerce-contact">
                            <?php if($ps->phone != null): ?>
                            <span class="font-medium font-500 text-dark"><?php echo e(__('Got Questions ? Call us 24/7!')); ?></span>
                            <div class="text-dark h4 font-400 "><?php echo e($ps->phone); ?></div>
                            <?php endif; ?>
                            <?php if($ps->street != null): ?>
                            <span class="h6 text-secondary mt-2"><?php echo e(__('Address :')); ?></span>
                            <div class="text-general"><?php echo e($ps->street); ?></div>
                            <?php endif; ?>
                            <?php if($ps->email != null): ?>
                            <span class="h6 text-secondary mt-2"><?php echo e(__('Email :')); ?></span>
                            <div class="text-general"><?php echo e($ps->email); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="footer-widget media-widget mb-5">
                        <?php $__currentLoopData = DB::table('social_links')->where('user_id',0)->where('status',1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($link->link); ?>"><i class="<?php echo e($link->icon); ?>"></i></a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget category-widget mb-5">
                        <h3 class="widget-title mb-4"><?php echo e(__('Product Category')); ?></h3>
                        <ul>
                            <?php $__currentLoopData = DB::table('categories')->where('language_id',$langg->id)->get()->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('front.category', $cate->slug)); ?><?php echo e(!empty(request()->input('search')) ? '?search='.request()->input('search') : ''); ?>"><?php echo e($cate->name); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget category-widget mb-5">
                        <h3 class="widget-title mb-4 xs-mx-none"><?php echo e(__('Footer Links')); ?></h3>
                        <ul>
                            <?php if($ps->home == 1): ?>
                            <li>
                                <a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if($ps->blog == 1): ?>
                            <li>
                                <a href="<?php echo e(route('front.blog')); ?>"><?php echo e(__('Blog')); ?></a>
                            </li>
                                <?php endif; ?>
                                <?php if($ps->faq == 1): ?>
                            <li>
                                <a href="<?php echo e(route('front.faq')); ?>"><?php echo e(__('Faq')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php $__currentLoopData = DB::table('pages')->where('language_id',$langg->id)->where('footer','=',1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('front.vendor',$data->slug)); ?>"><?php echo e($data->title); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($ps->contact == 1): ?>
                                <li>
                                <a href="<?php echo e(route('front.contact')); ?>"><?php echo e(__('Contact Us')); ?></a>
                            </li>
                                <?php endif; ?>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget widget-nav mb-5">
                        <h3 class="widget-title mb-4"><?php echo e(__('Recent Post')); ?></h3>
                        <ul>
                            <?php $__currentLoopData = DB::table('blogs')->where('language_id',$langg->id)->latest()->limit(3)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $footer_blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="post">
                                    <div class="post-img">
                                        <img class="lozad lazy" data-src="<?php echo e(asset('assets/images/blogs/'.$footer_blog->photo)); ?>" alt="">
                                        </div>
                                        <div class="post-details">
                                        <a href="<?php echo e(route('front.blogshow',$footer_blog->slug)); ?>">
                                            <h4 class="post-title">
                                                <?php echo e(mb_strlen($footer_blog->title,'UTF-8') > 45 ? mb_substr($footer_blog->title,0,45,'UTF-8')." .." : $footer_blog->title); ?>

                                            </h4>
                                        </a>
                                        <p class="date">
                                            <?php echo e(date('M d - Y',(strtotime($footer_blog->created_at)))); ?>

                                        </p>
                                    </div>
                                </div>
                            </li>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--==================== Footer Section End ====================-->

<!--==================== Copyright Section Start ====================-->

        <div class="container">

                <div class="mx-auto text-center py-3">
                    <span class="sm-mb-10 d-block"><?php echo e($gs->copyright); ?></span>
                </div>


        </div>
<!--==================== Copyright Section End ====================-->



<script src="<?php echo e(asset('assets/front/js/extraindex.js')); ?>"></script>

<script>

    $(".lazy").Lazy();
</script>

<?php /**PATH C:\laragon\www\xmerch\project\resources\views/partials/theme/extraindex.blade.php ENDPATH**/ ?>