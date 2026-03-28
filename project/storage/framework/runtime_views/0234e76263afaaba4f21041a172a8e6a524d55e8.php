
	<option value=""><?php echo e(__('Select Country')); ?></option>

	<?php $__currentLoopData = DB::table('countries')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cdata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
		<option value="<?php echo e($cdata->country_name); ?>" <?php echo e($data->country == $cdata->country_name ? 'selected' : ''); ?>><?php echo e($cdata->country_name); ?></option>		
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\includes\admin\countries.blade.php ENDPATH**/ ?>