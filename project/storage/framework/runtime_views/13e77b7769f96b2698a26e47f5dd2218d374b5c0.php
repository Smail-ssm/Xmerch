<?php
$tnOnly = filter_var(env('TN_ONLY_MODE', false), FILTER_VALIDATE_BOOLEAN);
$countriesQuery = App\Models\Country::where('status', 1);
if ($tnOnly) {
    $countriesQuery->whereIn('country_name', ['Tunisia', 'Tunisie']);
}
$countries = $countriesQuery->get();
?>

<option value="" disabled <?php echo e($countries->count() === 1 ? '' : 'selected'); ?>><?php echo e(__('Select Country')); ?></option>
<?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
$selected = Auth::check() && Auth::user()->country == $data->country_name;
if (!$selected && $tnOnly && $countries->count() === 1) {
    $selected = true;
}
?>
<option value="<?php echo e($data->country_name); ?>" data="<?php echo e($data->id); ?>" rel5="<?php echo e(Auth::check() && Auth::user()->country == $data->country_name ? 1 : 0); ?>" rel="<?php echo e($data->states->count() > 0 ? 1 : 0); ?>" rel1="<?php echo e(Auth::check() ? 1 : 0); ?>" rel2="<?php echo e(Auth::check() && Auth::user()->state ? Auth::user()->state : 0); ?>" <?php echo e($selected ? 'selected' : ''); ?> data-href="<?php echo e(route('country.wise.state', $data->id)); ?>"><?php echo e($data->country_name); ?></option>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\includes\countries.blade.php ENDPATH**/ ?>