@extends('layouts.admin')

@section('styles')
<style>
/* Print Area Editor */
.print-area-editor {
    position: relative;
    display: inline-block;
    margin: 20px 0;
    background: #f5f5f5;
    border-radius: 8px;
    padding: 10px;
}

.print-area-editor img {
    max-width: 500px;
    max-height: 500px;
    display: block;
}

.print-area-box {
    position: absolute;
    border: 3px dashed #007bff;
    background: rgba(0, 123, 255, 0.1);
    cursor: move;
    box-sizing: border-box;
}

.print-area-box::after {
    content: 'PRINT AREA';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #007bff;
    font-weight: bold;
    font-size: 14px;
    pointer-events: none;
}

.resize-handle {
    position: absolute;
    width: 12px;
    height: 12px;
    background: #007bff;
    border: 2px solid #fff;
    border-radius: 50%;
}

.resize-handle.nw { top: -6px; left: -6px; cursor: nw-resize; }
.resize-handle.ne { top: -6px; right: -6px; cursor: ne-resize; }
.resize-handle.sw { bottom: -6px; left: -6px; cursor: sw-resize; }
.resize-handle.se { bottom: -6px; right: -6px; cursor: se-resize; }

.print-area-info {
    margin-top: 10px;
    padding: 10px;
    background: #e9ecef;
    border-radius: 4px;
    font-size: 13px;
}

.current-image {
    margin-bottom: 15px;
    padding: 10px;
    background: #fff3cd;
    border-radius: 4px;
}
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Edit Mockup Template') }}
                    <a class="add-btn" href="{{ route('admin-mockup-index') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="row">
            <div class="col-lg-7">
                <div class="product-description">
                    <div class="body-area">
                        @include('alerts.admin.form-error')
                        
                        <form action="{{ route('admin-mockup-update', $template->id) }}" method="POST" enctype="multipart/form-data" id="mockup-form">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Template Name') }} *</label>
                                        <input type="text" name="name" class="form-control" required 
                                               value="{{ old('name', $template->name) }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>{{ __('Product Type') }} *</label>
                                        <select name="product_type" class="form-control" required>
                                            @foreach($productTypes as $key => $name)
                                            <option value="{{ $key }}" {{ $template->product_type == $key ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Style') }} *</label>
                                        <select name="style" class="form-control" required>
                                            @foreach($styles as $key => $name)
                                            <option value="{{ $key }}" {{ $template->style == $key ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Color') }} *</label>
                                        <select name="color" class="form-control" required>
                                            @foreach($colors as $key => $name)
                                            <option value="{{ $key }}" {{ $template->color == $key ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>{{ __('Status') }}</label>
                                        <select name="status" class="form-control">
                                            <option value="1" {{ $template->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                            <option value="0" {{ $template->status == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>{{ __('Mockup Image') }} <small>(Leave empty to keep current)</small></label>
                                        <input type="file" name="image" id="mockup-image-input" class="form-control" accept="image/*">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden inputs for design area -->
                            <input type="hidden" name="design_x" id="design_x" value="{{ $template->design_x }}">
                            <input type="hidden" name="design_y" id="design_y" value="{{ $template->design_y }}">
                            <input type="hidden" name="design_width" id="design_width" value="{{ $template->design_width }}">
                            <input type="hidden" name="design_height" id="design_height" value="{{ $template->design_height }}">

                            <div class="row mt-4">
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> {{ __('Update Template') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Print Area Editor -->
            <div class="col-lg-5">
                <div class="product-description">
                    <div class="body-area">
                        <h5><i class="fas fa-crop-alt"></i> {{ __('Edit Print Area') }}</h5>
                        <p class="text-muted">{{ __('Drag and resize the blue box to adjust the print area') }}</p>
                        
                        <div class="print-area-editor" id="print-area-editor">
                            <img id="mockup-preview" src="{{ $template->image_url }}" 
                                 data-x="{{ $template->design_x }}"
                                 data-y="{{ $template->design_y }}"
                                 data-w="{{ $template->design_width }}"
                                 data-h="{{ $template->design_height }}">
                            <div class="print-area-box" id="print-area-box">
                                <div class="resize-handle nw" data-dir="nw"></div>
                                <div class="resize-handle ne" data-dir="ne"></div>
                                <div class="resize-handle sw" data-dir="sw"></div>
                                <div class="resize-handle se" data-dir="se"></div>
                            </div>
                        </div>
                        
                        <div class="print-area-info" id="print-area-info">
                            <strong>Print Area:</strong>
                            <span id="info-x">X: {{ $template->design_x }}</span> | 
                            <span id="info-y">Y: {{ $template->design_y }}</span> | 
                            <span id="info-w">W: {{ $template->design_width }}</span> | 
                            <span id="info-h">H: {{ $template->design_height }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    const editor = $('#print-area-editor');
    const preview = $('#mockup-preview');
    const box = $('#print-area-box');
    
    let isDragging = false;
    let isResizing = false;
    let resizeDir = '';
    let startX, startY, startLeft, startTop, startWidth, startHeight;
    
    // Initialize box position from existing data
    function initBox() {
        const x = parseInt(preview.data('x')) || 100;
        const y = parseInt(preview.data('y')) || 100;
        const w = parseInt(preview.data('w')) || 200;
        const h = parseInt(preview.data('h')) || 200;
        
        box.css({
            left: x + 'px',
            top: y + 'px',
            width: w + 'px',
            height: h + 'px'
        });
    }
    
    // Wait for existing image to load, then init
    if (preview.attr('src')) {
        if (preview[0].complete) {
            initBox();
        } else {
            preview.on('load', initBox);
        }
    }
    
    // Handle new image upload
    $('#mockup-image-input').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                preview.attr('src', evt.target.result);
                preview.on('load', function() {
                    // Center the box
                    const imgW = preview.width();
                    const imgH = preview.height();
                    const boxW = Math.min(200, imgW - 50);
                    const boxH = Math.min(200, imgH - 50);
                    const boxX = Math.round((imgW - boxW) / 2);
                    const boxY = Math.round((imgH - boxH) / 2);
                    
                    box.css({
                        left: boxX + 'px',
                        top: boxY + 'px',
                        width: boxW + 'px',
                        height: boxH + 'px'
                    });
                    updateValues();
                });
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Drag functionality
    box.on('mousedown', function(e) {
        if ($(e.target).hasClass('resize-handle')) return;
        isDragging = true;
        startX = e.clientX;
        startY = e.clientY;
        startLeft = parseInt(box.css('left'));
        startTop = parseInt(box.css('top'));
        e.preventDefault();
    });
    
    // Resize functionality
    $('.resize-handle').on('mousedown', function(e) {
        isResizing = true;
        resizeDir = $(this).data('dir');
        startX = e.clientX;
        startY = e.clientY;
        startLeft = parseInt(box.css('left'));
        startTop = parseInt(box.css('top'));
        startWidth = parseInt(box.css('width'));
        startHeight = parseInt(box.css('height'));
        e.preventDefault();
        e.stopPropagation();
    });
    
    $(document).on('mousemove', function(e) {
        if (isDragging) {
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            let newLeft = startLeft + dx;
            let newTop = startTop + dy;
            
            const maxLeft = preview.width() - parseInt(box.css('width'));
            const maxTop = preview.height() - parseInt(box.css('height'));
            newLeft = Math.max(0, Math.min(newLeft, maxLeft));
            newTop = Math.max(0, Math.min(newTop, maxTop));
            
            box.css({ left: newLeft + 'px', top: newTop + 'px' });
            updateValues();
        }
        
        if (isResizing) {
            const dx = e.clientX - startX;
            const dy = e.clientY - startY;
            let newWidth = startWidth;
            let newHeight = startHeight;
            let newLeft = startLeft;
            let newTop = startTop;
            
            if (resizeDir.includes('e')) newWidth = Math.max(50, startWidth + dx);
            if (resizeDir.includes('s')) newHeight = Math.max(50, startHeight + dy);
            if (resizeDir.includes('w')) {
                newWidth = Math.max(50, startWidth - dx);
                newLeft = startLeft + (startWidth - newWidth);
            }
            if (resizeDir.includes('n')) {
                newHeight = Math.max(50, startHeight - dy);
                newTop = startTop + (startHeight - newHeight);
            }
            
            newLeft = Math.max(0, newLeft);
            newTop = Math.max(0, newTop);
            if (newLeft + newWidth > preview.width()) newWidth = preview.width() - newLeft;
            if (newTop + newHeight > preview.height()) newHeight = preview.height() - newTop;
            
            box.css({
                left: newLeft + 'px',
                top: newTop + 'px',
                width: newWidth + 'px',
                height: newHeight + 'px'
            });
            updateValues();
        }
    });
    
    $(document).on('mouseup', function() {
        isDragging = false;
        isResizing = false;
    });
    
    function updateValues() {
        const x = parseInt(box.css('left'));
        const y = parseInt(box.css('top'));
        const w = parseInt(box.css('width'));
        const h = parseInt(box.css('height'));
        
        $('#design_x').val(x);
        $('#design_y').val(y);
        $('#design_width').val(w);
        $('#design_height').val(h);
        
        $('#info-x').text('X: ' + x);
        $('#info-y').text('Y: ' + y);
        $('#info-w').text('W: ' + w);
        $('#info-h').text('H: ' + h);
    }
});
</script>
@endsection
