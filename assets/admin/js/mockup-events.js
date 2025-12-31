$(document).ready(function() {
    // Initialize MockupPreview
    if ($('#mockup-canvas').length) {
        mockupPreview = new MockupPreview({ canvasId: 'mockup-canvas' });
    }
    
    // ============================================
    // TEE DESIGNER - EVENT HANDLERS
    // ============================================
    
    // Color swatch click - change product color
    $(document).on('click', '.color-swatch', function() {
        $('.color-swatch').removeClass('active');
        $(this).addClass('active');
        var color = $(this).data('color');
        if (mockupPreview) {
            var templates = mockupPreview.templates;
            for (var key in templates) {
                if (templates[key].color === color) {
                    mockupPreview.loadTemplate(key);
                    break;
                }
            }
        }
    });
    
    // View thumbs - front/back switching
    $(document).on('click', '.view-thumb', function() {
        $('.view-thumb').removeClass('active');
        $(this).addClass('active');
        var view = $(this).data('view');
        if (mockupPreview) {
            mockupPreview.switchView(view);
        }
    });
    
    // Tool buttons
    $(document).on('click', '#tool-text', function() {
        $('#text-panel').toggleClass('show');
    });
    
    $(document).on('click', '#tool-upload', function() {
        $('#design-upload-input').click();
    });
    
    $(document).on('click', '#tool-reset', function() {
        if (mockupPreview && mockupPreview.selectedLayerIndex >= 0) {
            mockupPreview.deleteSelectedLayer();
        }
    });
    
    $(document).on('click', '#tool-rotate', function() {
        if (mockupPreview && mockupPreview.selectedLayerIndex >= 0) {
            var layer = mockupPreview.getCurrentLayers()[mockupPreview.selectedLayerIndex];
            layer.rotation = (layer.rotation || 0) + 15;
            mockupPreview.render();
        }
    });
    
    // Design upload from TeeDesigner
    $(document).on('change', '#design-upload-input', function(e) {
        var file = e.target.files[0];
        if (file && mockupPreview) {
            var reader = new FileReader();
            reader.onload = function(evt) {
                mockupPreview.addImageLayer(evt.target.result).then(function() {
                    $('.upload-design-btn').hide();
                });
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Add text from panel
    $(document).on('click', '#apply-text', function() {
        if (mockupPreview) {
            var text = $('#text-input').val() || 'Your Text';
            var color = $('#text-color').val() || '#000000';
            var size = parseInt($('#text-size').val()) || 32;
            var font = $('#text-font').val() || 'Arial';
            var strokeColor = $('#text-stroke-color').val();
            var strokeWidth = parseInt($('#text-stroke-width').val()) || 0;
            
            var options = {
                text: text,
                color: color,
                fontSize: size,
                fontFamily: font,
                strokeColor: strokeColor,
                strokeWidth: strokeWidth
            };
            
            mockupPreview.addTextLayer(options);
            $('#text-panel').removeClass('show');
            $('#text-input').val('');
        }
    });
    
    // Layer panel interactions
    $(document).on('click', '.layer-item', function() {
        var index = parseInt($(this).attr('data-index'));
        if (mockupPreview) {
            mockupPreview.selectedLayerIndex = index;
            mockupPreview.render();
            mockupPreview.updateLayersPanel();
        }
    });
    
    $(document).on('click', '.layer-delete', function(e) {
        e.stopPropagation();
        var index = parseInt($(this).closest('.layer-item').attr('data-index'));
        if (mockupPreview) {
            mockupPreview.selectedLayerIndex = index;
            mockupPreview.deleteSelectedLayer();
        }
    });
    
    $(document).on('click', '#delete-selected-layer', function() {
        if (mockupPreview) {
            mockupPreview.deleteSelectedLayer();
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        if (!mockupPreview) return;
        
        // Delete key
        if (e.key === 'Delete' && mockupPreview.selectedLayerIndex >= 0) {
            mockupPreview.deleteSelectedLayer();
        }
        
        // Ctrl+D - Duplicate
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            mockupPreview.duplicateSelectedLayer();
        }
    });
    
    // ============================================
    // POD CLEANUP - Hide unnecessary fields
    // ============================================
    
    // List of headings to hide (POD doesn't need these)
    const hideHeadings = [
        'Feature Image',
        'Product Gallery Images',
        'Wholesale Price',
        'Product Dimension',
        'Measurement',
        'Estimated Shipping Time',
        'Allow Product Condition',
        'Meta Tags',
        'Meta Description',
        'Product Current Price',  // Will be replaced by quality pricing
        'Product Discount Price'
    ];
    
    // Find and hide sections by heading text
    $('.product-description h4.heading').each(function() {
        const headingText = $(this).text().trim();
        hideHeadings.forEach(function(hideText) {
            if (headingText.includes(hideText)) {
                $(this).closest('.row').addClass('pod-hide');
            }
        }.bind(this));
    });
    
    // Hide old mockup preview wrapper
    $('.mockup-preview-wrapper').closest('.row').addClass('pod-hide');
    
    // Inject quality pricing section after product description
    setTimeout(function() {
        const descriptionRow = $('.product-description').find('textarea[name="details"]').closest('.row');
        if (descriptionRow.length && !$('#quality-pricing-section').length) {
            $.get('/partials/quality-pricing', function(html) {
                descriptionRow.after('<div id="quality-pricing-section">' + html + '</div>');
            }).fail(function() {
                console.log('Quality pricing partial not loaded via AJAX');
            });
        }
    }, 1000);

    // ============================================
    // FORM SUBMISSION - Export Design
    // ============================================
    
    $('#geniusform').on('submit', async function(e) {
        if (!mockupPreview) return true;
        if ($(this).data('design-exported')) return true;

        // Prevent default only to handle the async export
        e.preventDefault();
        
        // Show loader
        $('.gocover').show();
        
        try {
            // Export design images (5x multiplier for high-res)
            const result = await mockupPreview.exportDesign(5);
            
            // Populate hidden fields
            $('#print-image-hidden').val(result.print);
            $('#mockup-image-hidden').val(result.mockup);
            $('#design-json-hidden').val(result.json);
            
            // Mark as exported to prevent loop
            $(this).data('design-exported', true);
            
            // Continue with standard form submission
            this.submit();
        } catch (err) {
            console.error('Design export failed:', err);
            $('.gocover').hide();
            alert('Failed to generate design files. Please try again.');
        }
    });
});
