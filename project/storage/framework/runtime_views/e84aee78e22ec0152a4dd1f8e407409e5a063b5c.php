
<div class="mockup-preview-wrapper" style="margin-top: 20px; padding: 20px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h4 class="heading" style="margin-bottom: 15px; color: #333;">
        <i class="fas fa-eye"></i> <?php echo e(__('Preview on Product')); ?>

    </h4>
    
    
    <div class="row mb-3">
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label><i class="fas fa-tshirt"></i> <?php echo e(__('Product Template')); ?></label>
                <select id="mockup-template" class="form-control">
                    <option value=""><?php echo e(__('Loading...')); ?></option>
                </select>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label><i class="fas fa-sync-alt"></i> <?php echo e(__('Rotation')); ?>: <span id="rotation-value">0</span>°</label>
                <input type="range" id="mockup-rotation" min="-180" max="180" value="0" class="form-control-range">
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="form-group">
                <label><i class="fas fa-expand-arrows-alt"></i> <?php echo e(__('Scale')); ?>: <span id="scale-value">1.0</span>x</label>
                <input type="range" id="mockup-scale" min="0.25" max="2" value="1" step="0.1" class="form-control-range">
            </div>
        </div>
    </div>
    
    
    <div class="mockup-canvas-container" style="text-align: center; margin: 20px 0; position: relative;">
        <canvas id="mockup-canvas" width="500" height="500" 
                style="max-width: 100%; border: 3px solid #dee2e6; border-radius: 12px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.15);"></canvas>
    </div>
    
    
    <div class="text-overlay-section" style="background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
        <h5 style="margin-bottom: 15px;"><i class="fas fa-font"></i> <?php echo e(__('Add Text to Design')); ?></h5>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="form-group">
                    <label><?php echo e(__('Text')); ?></label>
                    <input type="text" id="mockup-text-input" class="form-control" placeholder="<?php echo e(__('Enter your text...')); ?>">
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <div class="form-group">
                    <label><?php echo e(__('Color')); ?></label>
                    <input type="color" id="mockup-text-color" class="form-control" value="#000000" style="height: 38px;">
                </div>
            </div>
            <div class="col-lg-2 col-md-3">
                <div class="form-group">
                    <label><?php echo e(__('Size')); ?></label>
                    <select id="mockup-text-size" class="form-control">
                        <option value="16">16px</option>
                        <option value="20">20px</option>
                        <option value="24" selected>24px</option>
                        <option value="32">32px</option>
                        <option value="48">48px</option>
                        <option value="64">64px</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" id="mockup-add-text" class="btn btn-primary btn-block">
                        <i class="fas fa-plus"></i> <?php echo e(__('Add Text')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="mockup-actions" style="text-align: center;">
        <button type="button" id="mockup-reset" class="btn btn-secondary">
            <i class="fas fa-undo"></i> <?php echo e(__('Reset Position')); ?>

        </button>
        <small class="d-block mt-2 text-muted">
            <i class="fas fa-info-circle"></i> <?php echo e(__('Drag design to move • Drag corners to resize • Drag orange handle to rotate')); ?>

        </small>
    </div>
</div>

<style>
.mockup-preview-wrapper {
    border: 1px solid #dee2e6;
}
.mockup-canvas-container canvas {
    cursor: default;
    transition: box-shadow 0.3s ease;
}
.mockup-canvas-container canvas:hover {
    box-shadow: 0 6px 25px rgba(0,0,0,0.2);
}
#mockup-rotation, #mockup-scale {
    width: 100%;
    cursor: pointer;
}
.text-overlay-section {
    border: 1px solid #dee2e6;
}
</style>

<script>
// Update display values for sliders
$(document).on('input', '#mockup-rotation', function() {
    $('#rotation-value').text($(this).val());
});
$(document).on('input', '#mockup-scale', function() {
    $('#scale-value').text(parseFloat($(this).val()).toFixed(1));
});
</script>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\partials\mockup-preview.blade.php ENDPATH**/ ?>