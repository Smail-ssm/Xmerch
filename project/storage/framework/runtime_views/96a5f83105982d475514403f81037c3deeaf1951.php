<div class="heading-area">
    <h4 class="title">
      <?php echo e(__('Ratings & Reviews')); ?>

    </h4>
    <div class="reating-area">
      <div class="stars"><span id="star-rating"><?php echo e(App\Models\Rating::normalRating($productt->id)); ?></span> <i class="fas fa-star"></i></div>
    </div>
  </div>

  <ul class="all-comments">
    <?php $__currentLoopData = $productt->ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <li>
        <div class="single-comment">
          <div class="left-area">
            <img src="<?php echo e($review->user->photo ? asset('assets/images/users/'.$review->user->photo):asset('assets/images/'.$gs->user_image)); ?>" alt="">
              <h5 class="name"><?php echo e($review->user->name); ?></h5>
              <p class="date"><?php echo e(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$review->review_date)->diffForHumans()); ?></p>
          </div>
          <div class="right-area">
            <div class="header-area">
              <div class="stars-area">
                <ul class="stars">
                  <div class="ratings">
                    <div class="empty-stars"></div>
                    <div class="full-stars" style="width:<?php echo e($review->rating*20); ?>%"></div>
                  </div>
                </ul>
              </div>
            </div>
            <div class="comment-body">
              <p>
                <?php echo e($review->review); ?>

              </p>
            </div>
          </div>
        </div>
      </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </ul>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\load\reviews.blade.php ENDPATH**/ ?>