

<?php $__currentLoopData = $vprods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="col-lg-12">
    <?php echo $__env->make('partials.product.product-different-view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<div class="col-lg-12">
    <div class="page-center category">
        <?php echo $vprods->appends(['sort' => request()->input('sort'), 'min' => request()->input('min'), 'max' =>
        request()->input('max')])->links(); ?>

    </div>
</div>


<script>
    // Lozad Section
    const observer = lozad(); // lazy loads elements with default selector as '.lozad'
    observer.observe();
    // Lozad Section Ends

    // Tooltip Section

    $('[data-toggle="tooltip"]').tooltip({});

    $('[rel-toggle="tooltip"]').tooltip();

    $('[data-toggle="tooltip"]').on('click', function () {
        $(this).tooltip('hide');
    })


    $('[rel-toggle="tooltip"]').on('click', function () {
        $(this).tooltip('hide');
    })

    // Tooltip Section Ends
</script>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\frontend\ajax\vendor.blade.php ENDPATH**/ ?>