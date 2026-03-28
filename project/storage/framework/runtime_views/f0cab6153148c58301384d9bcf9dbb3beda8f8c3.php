                            
                        <div class="col-lg-3 col-md-3 col-sm-4">
                             <div class="patient__linksArea">
                             	<a href="<?php echo e(route('user-dashboard')); ?>" class="patient-btn"><?php echo e($lang->lang78); ?></a>
                                <a href="<?php echo e(route('user-appointments')); ?>" class="patient-btn"><?php echo e($lang->lang79); ?></a>
                                <a href="<?php echo e(route('user-messages')); ?>" class="patient-btn"><?php echo e($lang->lang80); ?></a>   
                                <a href="<?php echo e(route('user-message-index')); ?>" class="patient-btn"><?php echo e($lang->lang81); ?></a>  
                             
                                <a href="<?php echo e(route('user-profile')); ?>" class="patient-btn"><?php echo e($lang->lang82); ?></a>
                                <a href="<?php echo e(route('user-reset')); ?>" class="patient-btn"><?php echo e($lang->lang83); ?></a>
                                <a href="<?php echo e(route('user-logout')); ?>" class="patient-btn"><?php echo e($lang->lang84); ?></a>
                            </div>
                            <div class="patient__socialArea mt_30">
                                <ul>
                                    <li><a href=""><i class="fa fa-map-marker"></i> <span><?php echo e($lang->lang85); ?> <?php echo e($user->address); ?></span></a></li>
                                    <li><a href=""><i class="fa fa-phone"></i> <span><?php echo e($lang->lang86); ?> <?php echo e($user->phone); ?></span></a></li>
                                    <li><a href=""><i class="fa fa-envelope"></i> <span><?php echo e($lang->lang87); ?> <?php echo e($user->email); ?></span></a></li>
                                </ul>
                            </div>
                        </div>


<?php /**PATH C:\laragon\www\xmerch\project\resources\views\includes\user-profile.blade.php ENDPATH**/ ?>