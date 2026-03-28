

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Mockup Templates')); ?>

                    <a class="add-btn" href="<?php echo e(route('admin-mockup-create')); ?>">
                        <i class="fas fa-plus"></i> <?php echo e(__('Add New Template')); ?>

                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table">
                    <?php echo $__env->make('alerts.admin.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
                    <div class="table-responsive">
                        <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Image')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Type')); ?></th>
                                    <th><?php echo e(__('Style')); ?></th>
                                    <th><?php echo e(__('Color')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo e($template->image_url); ?>" alt="<?php echo e($template->name); ?>" 
                                             style="width: 60px; height: 60px; object-fit: contain; background: #f5f5f5; border-radius: 4px;">
                                    </td>
                                    <td><?php echo e($template->name); ?></td>
                                    <td><?php echo e($template->product_type_name); ?></td>
                                    <td><?php echo e($template->style_name); ?></td>
                                    <td>
                                        <span style="display:inline-block; width:20px; height:20px; background:<?php echo e($template->color); ?>; border-radius:50%; border:1px solid #ddd;"></span>
                                        <?php echo e($template->color_name); ?>

                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin-mockup-status', $template->id)); ?>" class="btn btn-sm <?php echo e($template->status == 1 ? 'btn-success' : 'btn-danger'); ?>">
                                            <?php echo e($template->status == 1 ? __('Active') : __('Inactive')); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <div class="action-list">
                                            <a href="<?php echo e(route('admin-mockup-edit', $template->id)); ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:;" data-href="<?php echo e(route('admin-mockup-delete', $template->id)); ?>" 
                                               data-toggle="modal" data-target="#confirm-delete" class="btn btn-sm btn-danger delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title"><?php echo e(__('Confirm Delete')); ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="text-center"><?php echo e(__('Are you sure you want to delete this mockup template?')); ?></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                <a href="" class="btn btn-danger btn-ok"><?php echo e(__('Delete')); ?></a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\xmerch\project\resources\views\admin\mockup\index.blade.php ENDPATH**/ ?>