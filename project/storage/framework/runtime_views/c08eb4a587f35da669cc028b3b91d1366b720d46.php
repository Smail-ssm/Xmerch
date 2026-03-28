    <div class="row">
        <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blogg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <div class="col-lg-6 col-md-6 mycol">
            <div class="single-blog">
                <div class="img">
                <img src="<?php echo e($blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png')); ?>" alt="">
                <div class="date">
                <?php echo e(date('d M, Y',strtotime($blogg->created_at))); ?>

                </div>
                </div>
                <div class="content">
                <a href="<?php echo e(route('front.blogshow',$blogg->id)); ?>">
                    <h4 class="title">
                        <?php echo e(mb_strlen($blogg->title,'UTF-8') > 200 ? mb_substr($blogg->title,0,200,'UTF-8')."...":$blogg->title); ?>

                    </h4>
                </a>
                <ul class="top-meta">
                    <li>
                    <a href="javascript:;"><i class="far fa-comments"></i> <?php echo e($blogg->source); ?> </a>
                    </li>
                    <li>
                    <a href="javascript:;">
                        <i class="far fa-eye"></i> <?php echo e($blogg->views); ?> 
                    </a>
                    </li>
                </ul>
                </div>
            </div>
        </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>

    <div class="page-center">

        <?php echo $blogs->links(); ?>   
            
    </div><?php /**PATH C:\laragon\www\xmerch\project\resources\views\frontend\ajax\blog.blade.php ENDPATH**/ ?>