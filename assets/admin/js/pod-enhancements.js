/**
 * POD Form Enhancements
 * Auto-save, Canvas export, Progress tracking, Smart defaults
 */

$(document).ready(function() {
    
    // ============================================
    // 1. CANVAS EXPORT TO FEATURE IMAGE
    // ============================================
    
    let canvasDataUrl = null;
    let hasDesignChanged = false;
    
    // Export canvas as feature image before form submit
    $('#geniusform').on('submit', function(e) {
        if (mockupPreview && mockupPreview.getCurrentLayers().length > 0) {
            // Export front view
            mockupPreview.switchView('front');
            canvasDataUrl = mockupPreview.canvas.toDataURL('image/png');
            
            // Create hidden input with canvas data
            if (!$('#canvas-export').length) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'canvas-export',
                    name: 'canvas_export',
                    value: canvasDataUrl
                }).appendTo('#geniusform');
            } else {
                $('#canvas-export').val(canvasDataUrl);
            }
            
            hasDesignChanged = false;
        }
    });
    
    // ============================================
    // 2. AUTO-SAVE DRAFT
    // ============================================
    
    let autoSaveTimer = null;
    let lastSaved = null;
    
    function autoSaveDraft() {
        if (!mockupPreview || mockupPreview.getCurrentLayers().length === 0) return;
        
        const draftData = {
            name: $('input[name="name"]').val(),
            price: $('input[name="price"]').val(),
            canvasState: mockupPreview.getSettings ? mockupPreview.getSettings() : {},
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('pod_draft_' + (new Date().getTime()), JSON.stringify(draftData));
        lastSaved = new Date();
        updateAutoSaveStatus('Saved at ' + lastSaved.toLocaleTimeString());
    }
    
    function updateAutoSaveStatus(message) {
        if (!$('#autosave-status').length) {
            $('<div id="autosave-status" style="position:fixed;top:70px;right:20px;background:#28a745;color:#fff;padding:8px 15px;border-radius:4px;font-size:12px;z-index:9999;"></div>')
                .appendTo('body');
        }
        $('#autosave-status').text(message).fadeIn().delay(2000).fadeOut();
    }
    
    // Auto-save every 30 seconds
    setInterval(function() {
        if (hasDesignChanged) {
            autoSaveDraft();
            hasDesignChanged = false;
        }
    }, 30000);
    
    // Track changes
    $('input, textarea, select').on('change', function() {
        hasDesignChanged = true;
    });
    
    // ============================================
    // 3. PROGRESS INDICATOR
    // ============================================
    
    function updateProgress() {
        const requiredFields = [
            'name',
            'category_id',
            'price'
        ];
        
        let completed = 0;
        requiredFields.forEach(function(field) {
            const val = $('[name="' + field + '"]').val();
            if (val && val.trim() !== '') completed++;
        });
        
        // Check if design exists
        if (mockupPreview && mockupPreview.getCurrentLayers().length > 0) {
            completed++;
        }
        
        const total = requiredFields.length + 1; // +1 for design
        const percentage = Math.round((completed / total) * 100);
        
        if (!$('#progress-bar').length) {
            const progressHTML = `
                <div id="progress-indicator" style="position:sticky;top:60px;background:#fff;padding:15px 20px;box-shadow:0 2px 10px rgba(0,0,0,0.1);z-index:999;margin-bottom:20px;border-radius:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                        <span style="font-weight:600;font-size:14px;">Design Upload Progress</span>
                        <span id="progress-percentage" style="color:#007bff;font-weight:bold;">${percentage}%</span>
                    </div>
                    <div style="background:#e9ecef;height:8px;border-radius:4px;overflow:hidden;">
                        <div id="progress-bar" style="background:linear-gradient(90deg,#007bff,#0056b3);height:100%;width:${percentage}%;transition:width 0.3s;"></div>
                    </div>
                    <div id="progress-hints" style="margin-top:10px;font-size:12px;color:#666;"></div>
                </div>
            `;
            $('.product-description').prepend(progressHTML);
        } else {
            $('#progress-bar').css('width', percentage + '%');
            $('#progress-percentage').text(percentage + '%');
        }
        
        // Update hints
        let hints = [];
        if (!$('[name="name"]').val()) hints.push('Add design name');
        if (!$('[name="category_id"]').val()) hints.push('Select category');
        if (!$('[name="price"]').val()) hints.push('Set price');
        if (!mockupPreview || mockupPreview.getCurrentLayers().length === 0) hints.push('Upload design');
        
        $('#progress-hints').html(hints.length ? '⚠️ ' + hints.join(' • ') : '✓ Ready to publish!');
    }
    
    // Update progress on changes
    $('input[name="name"], input[name="price"], select[name="category_id"]').on('input change', updateProgress);
    
    // Initial progress
    setTimeout(updateProgress, 500);
    
    // ============================================
    // 4. SMART DEFAULTS
    // ============================================
    
    // Auto-generate SKU from product name
    $('input[name="name"]').on('blur', function() {
        const name = $(this).val();
        if (name && !$('input[name="sku"]').val()) {
            const sku = 'POD-' + name.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 10) + '-' + Date.now().toString().substr(-4);
            $('input[name="sku"]').val(sku);
        }
    });
    
    // Default description template
    if (!$('textarea[name="details"]').val()) {
        const template = `<h3>Premium Print-on-Demand Product</h3>
<p>High-quality design printed on premium fabric.</p>
<ul>
<li>100% Cotton</li>
<li>Machine washable</li>
<li>Vibrant colors that won't fade</li>
<li>Fast production and shipping</li>
</ul>`;
        setTimeout(function() {
            if (typeof nicEditors !== 'undefined') {
                $('.nic-edit-p').val(template);
            }
        }, 1000);
    }
    
    // ============================================
    // 5. PROFIT CALCULATOR
    // ============================================
    
    function addProfitCalculator() {
        const priceField = $('input[name="price"]');
        if (!priceField.length) return;
        
        const calcHTML = `
            <div class="profit-calculator" style="margin-top:10px;padding:15px;background:#f8f9fa;border-radius:6px;border-left:4px solid #28a745;">
                <div style="font-weight:600;margin-bottom:10px;font-size:13px;">💰 Profit Calculator</div>
                <div style="display:flex;gap:15px;margin-bottom:10px;">
                    <div style="flex:1;">
                        <label style="font-size:11px;color:#666;display:block;margin-bottom:4px;">Base Cost</label>
                        <input type="number" id="base-cost" value="10" min="0" step="0.5" style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;">
                    </div>
                    <div style="flex:1;">
                        <label style="font-size:11px;color:#666;display:block;margin-bottom:4px;">Retail Price</label>
                        <input type="number" id="retail-price" value="0" readonly style="width:100%;padding:6px;border:1px solid #ddd;border-radius:4px;background:#fff;">
                    </div>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px;background:#fff;border-radius:4px;">
                    <span style="font-size:12px;color:#666;">Your Profit:</span>
                    <span id="profit-amount" style="font-weight:bold;color:#28a745;font-size:14px;">$0.00</span>
                </div>
            </div>
        `;
        
        if (!$('.profit-calculator').length) {
            priceField.closest('.row').after(calcHTML);
        }
        
        function updateProfit() {
            const retailPrice = parseFloat(priceField.val()) || 0;
            const baseCost = parseFloat($('#base-cost').val()) || 10;
            const profit = retailPrice - baseCost;
            
            $('#retail-price').val(retailPrice.toFixed(2));
            $('#profit-amount').text('$' + profit.toFixed(2));
            $('#profit-amount').css('color', profit > 0 ? '#28a745' : '#dc3545');
        }
        
        priceField.on('input', updateProfit);
        $('#base-cost').on('input', updateProfit);
        updateProfit();
    }
    
    setTimeout(addProfitCalculator, 500);
    
    // ============================================
    // 6. VALIDATION & HINTS
    // ============================================
    
    // Real-time validation
    $('input[name="name"]').on('blur', function() {
        const val = $(this).val();
        if (val && val.length < 3) {
            showFieldHint($(this), 'Name should be at least 3 characters', 'warning');
        } else if (val) {
            showFieldHint($(this), '✓ Good name', 'success');
        }
    });
    
    $('input[name="price"]').on('blur', function() {
        const val = parseFloat($(this).val());
        if (val && val < 5) {
            showFieldHint($(this), 'Price seems too low for POD', 'warning');
        } else if (val && val > 100) {
            showFieldHint($(this), 'High price - make sure it\'s competitive', 'info');
        } else if (val) {
            showFieldHint($(this), '✓ Price set', 'success');
        }
    });
    
    function showFieldHint(field, message, type) {
        const colors = {
            success: '#28a745',
            warning: '#ffc107',
            error: '#dc3545',
            info: '#17a2b8'
        };
        
        field.next('.field-hint').remove();
        $('<div class="field-hint" style="font-size:11px;margin-top:4px;color:' + colors[type] + ';">' + message + '</div>')
            .insertAfter(field);
    }
    
    // ============================================
    // 7. SAVE AS DRAFT BUTTON
    // ============================================
    
    if (!$('#save-draft-btn').length) {
        const draftBtnHTML = `
            <button type="button" id="save-draft-btn" class="mybtn1" style="background:#6c757d;margin-right:10px;">
                <i class="fas fa-save"></i> Save as Draft
            </button>
        `;
        $('.submit-area .mybtn1').before(draftBtnHTML);
    }
    
    $('#save-draft-btn').on('click', function() {
        autoSaveDraft();
        updateAutoSaveStatus('Draft saved successfully!');
    });
});
