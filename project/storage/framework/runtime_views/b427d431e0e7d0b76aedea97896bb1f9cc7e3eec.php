<?php $__currentLoopData = $conv->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($message->user_id != 0): ?>
<div class="single-reply-area user">
    <div class="row">
        <div class="col-lg-12">
            <div class="reply-area">
                <div class="left">
                    <p><?php echo e($message->message); ?></p>
                </div>
                <div class="right">
                    <?php if($message->conversation->user->is_provider == 1): ?>
                    <img class="img-circle" src="<?php echo e($message->conversation->user->photo != null ? $message->conversation->user->photo : asset('assets/images/noimage.png')); ?>" alt="">
                    <?php else: ?>

                    <img class="img-circle" src="<?php echo e($message->conversation->user->photo != null ? asset('assets/images/users/'.$message->conversation->user->photo) : asset('assets/images/noimage.png')); ?>" alt="">

                    <?php endif; ?>
                    <p class="ticket-date"><?php echo e($message->conversation->user->name); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<?php else: ?>
<div class="single-reply-area admin">
    <div class="row">
        <div class="col-lg-12">
            <div class="reply-area">
                <div class="left">
                    <img class="img-circle" src="<?php echo e(asset('assets/images/admin.jpg')); ?>" alt="">
                    <p class="ticket-date">Admin</p>
                </div>
                <div class="right">
                    <p><?php echo e($message->message); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\load\usermessage.blade.php ENDPATH**/ ?>