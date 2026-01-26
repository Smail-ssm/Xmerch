/**
 * Advanced POD Mockup Preview Tool v3.0
 * Features: Front/Back views, Layer management, Enhanced text, Drag & drop
 */

class MockupPreview {
    constructor(options = {}) {
        this.canvasId = options.canvasId || 'mockup-canvas';
        this.canvas = null;
        this.ctx = null;
        this.currentView = 'front';
        
        // Separate data for all views
        this.views = {
            front: {
                templateImage: null,
                layers: []
            },
            back: {
                templateImage: null,
                layers: []
            },
            left: {
                templateImage: null,
                layers: []
            },
            right: {
                templateImage: null,
                layers: []
            }
        };
        
        // Interaction state
        this.selectedLayerIndex = -1;
        this.isDragging = false;
        this.isResizing = false;
        this.isRotating = false;
        this.activeHandle = null;
        this.dragStart = { x: 0, y: 0 };
        this.initialState = null;
        
        // Templates
        this.templates = {};
        this.currentTemplateId = null;
        this.productColor = null; // Hex color for tinting
        
        this.init();
    }
    
    setProductColor(hex) {
        this.productColor = hex;
        this.render();
    }
    
    init() {
        this.canvas = document.getElementById(this.canvasId);
        if (!this.canvas) return;
        
        this.ctx = this.canvas.getContext('2d');
        this.setupEventListeners();
        this.loadTemplatesFromAPI();
    }
    
    getCurrentLayers() {
        return this.views[this.currentView].layers;
    }
    
    getCurrentTemplate() {
        return this.views[this.currentView].templateImage;
    }
    
    /**
     * Add image layer
     */
    addImageLayer(imageUrl) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            img.crossOrigin = 'anonymous';
            img.onload = () => {
                const layer = {
                    type: 'image',
                    image: img,
                    x: 150,
                    y: 150,
                    width: 200,
                    height: (200 / img.width) * img.height,
                    rotation: 0,
                    opacity: 1
                };
                this.getCurrentLayers().push(layer);
                this.selectedLayerIndex = this.getCurrentLayers().length - 1;
                this.render();
                this.updateLayersPanel();
                resolve(layer);
            };
            img.onerror = () => reject('Failed to load image');
            img.src = imageUrl;
        });
    }
    
    /**
     * Add text layer with advanced options
     */
    addTextLayer(options = {}) {
        const layer = {
            type: 'text',
            text: options.text || 'Your Text',
            x: options.x || 200,
            y: options.y || 250,
            fontSize: options.fontSize || 32,
            fontFamily: options.fontFamily || 'Arial',
            color: options.color || '#000000',
            rotation: options.rotation || 0,
            bold: options.bold || false,
            italic: options.italic || false,
            underline: options.underline || false,
            // New advanced options
            strokeColor: options.strokeColor || '',
            strokeWidth: options.strokeWidth || 0,
            shadowColor: options.shadowColor || '',
            shadowBlur: options.shadowBlur || 0,
            shadowOffsetX: options.shadowOffsetX || 0,
            shadowOffsetY: options.shadowOffsetY || 0,
            letterSpacing: options.letterSpacing || 0,
            opacity: options.opacity || 1,
            textAlign: options.textAlign || 'center'
        };
        
        this.getCurrentLayers().push(layer);
        this.selectedLayerIndex = this.getCurrentLayers().length - 1;
        this.render();
        this.updateLayersPanel();
        return this.getCurrentLayers().length - 1;
    }
    
    /**
     * Update selected layer
     */
    updateSelectedLayer(properties) {
        const layers = this.getCurrentLayers();
        if (this.selectedLayerIndex >= 0 && this.selectedLayerIndex < layers.length) {
            Object.assign(layers[this.selectedLayerIndex], properties);
            this.render();
            this.updateLayersPanel();
        }
    }
    
    /**
     * Delete selected layer
     */
    deleteSelectedLayer() {
        const layers = this.getCurrentLayers();
        if (this.selectedLayerIndex >= 0 && this.selectedLayerIndex < layers.length) {
            layers.splice(this.selectedLayerIndex, 1);
            this.selectedLayerIndex = -1;
            this.render();
            this.updateLayersPanel();
        }
    }
    
    /**
     * Duplicate selected layer
     */
    duplicateSelectedLayer() {
        const layers = this.getCurrentLayers();
        if (this.selectedLayerIndex >= 0 && this.selectedLayerIndex < layers.length) {
            const original = layers[this.selectedLayerIndex];
            const duplicate = JSON.parse(JSON.stringify(original));
            if (duplicate.type === 'image') {
                duplicate.image = original.image; // Restore image reference
            }
            duplicate.x += 20;
            duplicate.y += 20;
            layers.push(duplicate);
            this.selectedLayerIndex = layers.length - 1;
            this.render();
            this.updateLayersPanel();
        }
    }
    
    /**
     * Switch view (front/back/left/right)
     */
    switchView(view) {
        if (!this.views[view]) return; // Guard
        
        // Lazy Load: If image is missing, reload it for this specific view
        if (!this.views[view].templateImage && this.currentTemplateId) {
            console.warn(`View ${view} missing, lazy loading...`);
            this.loadTemplate(this.currentTemplateId, view);
        }

        this.currentView = view;
        this.selectedLayerIndex = -1;
        
        // Ensure the canvas size matches the template image
        if(this.canvas && this.views[view].templateImage) {
            const img = this.views[view].templateImage;
            this.canvas.width = img.width;
            this.canvas.height = img.height;
        } else if (this.canvas) {
             // Fallback default
             this.canvas.width = 500;
             this.canvas.height = 600;
        }
        
        this.render();
        this.updateLayersPanel();
        
        // Update UI buttons active state
        const buttons = document.querySelectorAll('.view-btn');
        if(buttons) {
            buttons.forEach(btn => {
                if(btn.dataset.view === view) btn.classList.add('active');
                else btn.classList.remove('active');
            });
        }
    }
    
    /**
     * Load templates from API
     */
    loadTemplatesFromAPI() {
        const apiUrl = (typeof mainurl !== 'undefined' ? mainurl : '') + '/admin/api/mockup-templates';
        
        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                this.templates = {};
                data.forEach(t => {
                    const key = `${t.product_type}_${t.color}`;
                    this.templates[key] = {
                        id: t.id,
                        name: t.name,
                        url: t.image_url,
                        productType: t.product_type,
                        color: t.color,
                        designArea: t.design_area
                    };
                });
                this.loadDefaultTemplate();
            })
            .catch(err => {
                console.log('Loading fallback templates');
                this.loadFallbackTemplates();
            });
    }
    
    loadFallbackTemplates() {
        const baseUrl = (typeof mainurl !== 'undefined' ? mainurl : '') + '/assets/images/mockups/';
        this.templates = {
            'tshirt_white': {
                name: 'White T-Shirt',
                url: baseUrl + 'tshirt_white.png',
                productType: 'tshirt',
                color: 'white',
                designArea: { x: 150, y: 150, maxWidth: 200, maxHeight: 250 }
            }
        };
        this.loadDefaultTemplate();
    }
    
    loadDefaultTemplate() {
        const keys = Object.keys(this.templates);
        if (keys.length > 0) {
            // Load all views (no second arg)
            this.loadTemplate(keys[0]);
        }
    }
    
    loadTemplate(templateKey, view = null) {
        const template = this.templates[templateKey];
        if (!template) return Promise.reject('Template not found');
        
        this.currentTemplateId = templateKey;
        
        // Return a promise that resolves when ALL required views are loaded
        const viewsToLoad = view ? [view] : ['front', 'back', 'left', 'right'];
        const promises = viewsToLoad.map(v => {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.crossOrigin = 'anonymous';
                
                // Determine URL for this view
                let url = template.url; // Default fallback
                if (v === 'front' && template.frontUrl) url = template.frontUrl;
                if (v === 'back' && template.backUrl) url = template.backUrl;
                if (v === 'left' && template.leftUrl) url = template.leftUrl;
                if (v === 'right' && template.rightUrl) url = template.rightUrl;
                
                // If no specific url found for this view (and it's not the default one), skip
                // Actually, if we try to load 'left' but have no url, we should probably handle it gracefully.
                // But for now, let's assume if it's in the list, we try to load it. 
                // Using a placeholder or the default url might be confusing if it's the wrong image.
                // Our template config has specific keys, so checking keys is good.
                
                img.onload = () => {
                    this.views[v].templateImage = img;
                    
                    // If this is the current view, update canvas size immediately
                    if(v === this.currentView) {
                        this.canvas.width = img.width;
                        this.canvas.height = img.height;
                        this.render();
                    }
                    resolve();
                };
                img.onerror = () => {
                    console.warn(`Failed to load ${v} template for ${templateKey}`);
                    // Don't reject entire chain, just resolve (maybe missing image)
                    resolve(); 
                };
                img.src = url;
            });
        });
        
        return Promise.all(promises);
    }
    
    /**
     * Setup event listeners
     */
    setupEventListeners() {
        if (!this.canvas) return;
        
        this.canvas.addEventListener('mousedown', (e) => this.handleMouseDown(e));
        this.canvas.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        this.canvas.addEventListener('mouseup', () => this.handleMouseUp());
        this.canvas.addEventListener('mouseleave', () => this.handleMouseUp());
        
        this.canvas.addEventListener('touchstart', (e) => this.handleTouchStart(e), { passive: false });
        this.canvas.addEventListener('touchmove', (e) => this.handleTouchMove(e), { passive: false });
        this.canvas.addEventListener('touchend', () => this.handleMouseUp());
    }
    
    /**
     * Render all layers
     */
    render() {
        if (!this.ctx) return;
        
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Draw template
        const template = this.getCurrentTemplate();
        if (template) {
            // 1. Draw the base template image
            this.ctx.drawImage(template, 0, 0, this.canvas.width, this.canvas.height);
            
            // 2. Apply Product Color Tinting (if set)
            // Fix: Explicitly skip tinting if color is white (#ffffff), 'white', or transparent
            // This prevents the 'multiply' blend mode from darkening the white shirt into grey.
            const isWhite = !this.productColor || 
                           this.productColor === '#ffffff' || 
                           this.productColor === 'white' || 
                           this.productColor === 'transparent';

            if (!isWhite) {
                this.ctx.save();
                
                // A. 'multiply' blend mode:Colors the white parts, preserves black shadows
                this.ctx.globalCompositeOperation = 'multiply';
                this.ctx.fillStyle = this.productColor;
                this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                
                // B. 'destination-in' blend mode: 
                // Uses the original image alpha channel to clip the result,
                // ensuring we don't color the transparent background.
                this.ctx.globalCompositeOperation = 'destination-in';
                this.ctx.drawImage(template, 0, 0, this.canvas.width, this.canvas.height);
                
                this.ctx.restore();
            }
        }
        
        // Draw all layers
        const layers = this.getCurrentLayers();
        layers.forEach((layer, index) => {
            const isSelected = index === this.selectedLayerIndex;
            if (layer.type === 'image') {
                this.drawImageLayer(layer, isSelected);
            } else if (layer.type === 'text') {
                this.drawTextLayer(layer, isSelected);
            }
        });
    }
    
    drawImageLayer(layer, isSelected) {
        const { x, y, width, height, rotation, opacity } = layer;
        const centerX = x + width / 2;
        const centerY = y + height / 2;
        
        this.ctx.save();
        this.ctx.globalAlpha = opacity;
        this.ctx.translate(centerX, centerY);
        this.ctx.rotate(rotation * Math.PI / 180);
        this.ctx.translate(-centerX, -centerY);
        this.ctx.drawImage(layer.image, x, y, width, height);
        this.ctx.restore();
        
        if (isSelected) {
            this.drawHandles(x, y, width, height);
        }
    }
    
    drawTextLayer(layer, isSelected) {
        this.ctx.save();
        
        const { x, y, rotation, opacity } = layer;
        
        this.ctx.globalAlpha = opacity;
        this.ctx.translate(x, y);
        this.ctx.rotate(rotation * Math.PI / 180);
        
        // Font style
        let fontStyle = '';
        if (layer.bold) fontStyle += 'bold ';
        if (layer.italic) fontStyle += 'italic ';
        this.ctx.font = `${fontStyle}${layer.fontSize}px ${layer.fontFamily}`;
        this.ctx.textAlign = layer.textAlign;
        this.ctx.textBaseline = 'middle';
        
        // Shadow
        if (layer.shadowColor) {
            this.ctx.shadowColor = layer.shadowColor;
            this.ctx.shadowBlur = layer.shadowBlur;
            this.ctx.shadowOffsetX = layer.shadowOffsetX;
            this.ctx.shadowOffsetY = layer.shadowOffsetY;
        }
        
        // Stroke
        if (layer.strokeColor && layer.strokeWidth > 0) {
            this.ctx.strokeStyle = layer.strokeColor;
            this.ctx.lineWidth = layer.strokeWidth;
            this.ctx.strokeText(layer.text, 0, 0);
        }
        
        // Fill text
        this.ctx.fillStyle = layer.color;
        this.ctx.fillText(layer.text, 0, 0);
        
        // Underline
        if (layer.underline) {
            const metrics = this.ctx.measureText(layer.text);
            this.ctx.beginPath();
            this.ctx.moveTo(-metrics.width/2, layer.fontSize/2);
            this.ctx.lineTo(metrics.width/2, layer.fontSize/2);
            this.ctx.strokeStyle = layer.color;
            this.ctx.lineWidth = 2;
            this.ctx.stroke();
        }
        
        this.ctx.restore();
        
        if (isSelected) {
            const metrics = this.ctx.measureText(layer.text);
            const textWidth = metrics.width;
            const textHeight = layer.fontSize;
            this.drawHandles(x - textWidth/2, y - textHeight/2, textWidth, textHeight);
        }
    }
    
    drawHandles(x, y, width, height) {
        this.ctx.save();
        
        // Selection border
        this.ctx.strokeStyle = '#00aaff';
        this.ctx.lineWidth = 2;
        this.ctx.setLineDash([5, 5]);
        this.ctx.strokeRect(x, y, width, height);
        
        // Corner handles
        const handleSize = 10;
        this.ctx.fillStyle = '#00aaff';
        this.ctx.setLineDash([]);
        
        this.ctx.fillRect(x - handleSize/2, y - handleSize/2, handleSize, handleSize);
        this.ctx.fillRect(x + width - handleSize/2, y - handleSize/2, handleSize, handleSize);
        this.ctx.fillRect(x - handleSize/2, y + height - handleSize/2, handleSize, handleSize);
        this.ctx.fillRect(x + width - handleSize/2, y + height - handleSize/2, handleSize, handleSize);
        
        // Rotation handle
        this.ctx.beginPath();
        this.ctx.arc(x + width/2, y - 20, 8, 0, Math.PI * 2);
        this.ctx.fillStyle = '#ff6600';
        this.ctx.fill();
        this.ctx.strokeStyle = '#fff';
        this.ctx.lineWidth = 2;
        this.ctx.stroke();
        
        this.ctx.restore();
    }
    
    getHandle(mx, my) {
        const layers = this.getCurrentLayers();
        if (this.selectedLayerIndex < 0 || this.selectedLayerIndex >= layers.length) return null;
        
        const layer = layers[this.selectedLayerIndex];
        let x, y, width, height;
        
        if (layer.type === 'image') {
            ({ x, y, width, height } = layer);
        } else if (layer.type === 'text') {
            const metrics = this.ctx.measureText(layer.text);
            width = metrics.width;
            height = layer.fontSize;
            x = layer.x - width/2;
            y = layer.y - height/2;
        }
        
        const handleSize = 12;
        
        // Rotation handle
        if (Math.hypot(mx - (x + width/2), my - (y - 20)) < 12) {
            return 'rotate';
        }
        
        // Corner handles
        if (Math.abs(mx - (x + width)) < handleSize && Math.abs(my - (y + height)) < handleSize) {
            return 'resize-br';
        }
        
        // Inside layer
        if (mx >= x && mx <= x + width && my >= y && my <= y + height) {
            return 'drag';
        }
        
        return null;
    }
    
    handleMouseDown(e) {
        const rect = this.canvas.getBoundingClientRect();
        const mx = e.clientX - rect.left;
        const my = e.clientY - rect.top;
        
        // Check if clicking on a layer
        const layers = this.getCurrentLayers();
        for (let i = layers.length - 1; i >= 0; i--) {
            const layer = layers[i];
            let x, y, width, height;
            
            if (layer.type === 'image') {
                ({ x, y, width, height } = layer);
            } else if (layer.type === 'text') {
                const metrics = this.ctx.measureText(layer.text);
                width = metrics.width;
                height = layer.fontSize;
                x = layer.x - width/2;
                y = layer.y - height/2;
            }
            
            if (mx >= x && mx <= x + width && my >= y && my <= y + height) {
                this.selectedLayerIndex = i;
                this.render();
                this.updateLayersPanel();
                this.updateTextPanel();
                break;
            }
        }
        
        this.activeHandle = this.getHandle(mx, my);
        
        if (this.activeHandle === 'drag') {
            this.isDragging = true;
        } else if (this.activeHandle && this.activeHandle.startsWith('resize')) {
            this.isResizing = true;
        } else if (this.activeHandle === 'rotate') {
            this.isRotating = true;
        }
        
        this.dragStart = { x: mx, y: my };
        if (this.selectedLayerIndex >= 0) {
            this.initialState = { ...layers[this.selectedLayerIndex] };
        }
    }
    
    handleMouseMove(e) {
        const rect = this.canvas.getBoundingClientRect();
        const mx = e.clientX - rect.left;
        const my = e.clientY - rect.top;
        
        if (!this.isDragging && !this.isResizing && !this.isRotating) {
            const handle = this.getHandle(mx, my);
            if (handle === 'rotate') this.canvas.style.cursor = 'crosshair';
            else if (handle && handle.startsWith('resize')) this.canvas.style.cursor = 'nwse-resize';
            else if (handle === 'drag') this.canvas.style.cursor = 'move';
            else this.canvas.style.cursor = 'default';
            return;
        }
        
        const layers = this.getCurrentLayers();
        if (this.selectedLayerIndex < 0) return;
        
        const layer = layers[this.selectedLayerIndex];
        const dx = mx - this.dragStart.x;
        const dy = my - this.dragStart.y;
        
        if (this.isDragging) {
            if (layer.type === 'image') {
                layer.x = this.initialState.x + dx;
                layer.y = this.initialState.y + dy;
            } else if (layer.type === 'text') {
                layer.x = this.initialState.x + dx;
                layer.y = this.initialState.y + dy;
            }
        } else if (this.isResizing && layer.type === 'image') {
            const aspectRatio = layer.image.width / layer.image.height;
            let newWidth = this.initialState.width + dx;
            let newHeight = newWidth / aspectRatio;
            if (newWidth < 50) {
                newWidth = 50;
                newHeight = 50 / aspectRatio;
            }
            layer.width = newWidth;
            layer.height = newHeight;
        } else if (this.isResizing && layer.type === 'text') {
            layer.fontSize = Math.max(12, this.initialState.fontSize + dx / 2);
        } else if (this.isRotating) {
            const centerX = layer.x + (layer.width || 0) / 2;
            const centerY = layer.y + (layer.height || 0) / 2;
            const angle = Math.atan2(my - centerY, mx - centerX) * 180 / Math.PI;
            layer.rotation = angle + 90;
        }
        
        this.render();
    }
    
    handleMouseUp() {
        this.isDragging = false;
        this.isResizing = false;
        this.isRotating = false;
        this.activeHandle = null;
    }
    
    handleTouchStart(e) {
        e.preventDefault();
        const touch = e.touches[0];
        this.handleMouseDown({ clientX: touch.clientX, clientY: touch.clientY });
    }
    
    handleTouchMove(e) {
        e.preventDefault();
        const touch = e.touches[0];
        this.handleMouseMove({ clientX: touch.clientX, clientY: touch.clientY });
    }
    
    /**
     * Update layers panel UI
     */
    updateLayersPanel() {
        const panel = $('#layers-panel');
        if (!panel.length) return;
        
        const layers = this.getCurrentLayers();
        panel.html('');
        
        // Add Base Product Layer (Static)
        const baseLayer = $('<div>')
            .addClass('layer-entry')
            .removeClass('active'); // Base is usually background, not selectable in same way
        
        baseLayer.html(`
            <i class="fas fa-tshirt layer-icon"></i>
            <span style="flex:1">Base Product</span>
            <i class="fas fa-lock" style="font-size:10px; opacity:0.5;"></i>
        `);
        panel.append(baseLayer);

        if (layers.length === 0) {
            // panel.append('<div class="no-layers" style="padding:10px; font-size:12px; color:#999; text-align:center;">No custom layers</div>');
            // return;
        }
        
        layers.forEach((layer, index) => {
            const isSelected = index === this.selectedLayerIndex;
            const layerDiv = $('<div>')
                .addClass('layer-entry')
                .toggleClass('active', isSelected)
                .attr('data-index', index);
            
            const icon = layer.type === 'image' ? 'fa-image' : 'fa-font';
            const name = layer.type === 'image' ? 'Image Layer' : (layer.text ? layer.text.substring(0, 15) : 'Text Layer');
            
            layerDiv.html(`
                <i class="fas ${icon} layer-icon"></i>
                <span style="flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">${name}</span>
                <button class="layer-delete-btn" style="border:none; background:transparent; color:#ff4444; cursor:pointer;" data-index="${index}"><i class="fas fa-trash"></i></button>
            `);
            
            panel.append(layerDiv);
        });

        // Re-attach listeners to these specific elements? 
        // Better to use delegated listeners once in init, but let's check if we did.
        // We will assume jQuery delegated listeners are set up in the main file or we should add them here if possible.
        // Actually, let's just add them here to be safe and self-contained
        
        $('.layer-entry[data-index]').off('click').on('click', (e) => {
            if($(e.target).closest('.layer-delete-btn').length) return; // Ignore delete
            const idx = parseInt($(e.currentTarget).data('index'));
            this.selectedLayerIndex = idx;
            this.render();
            this.updateLayersPanel();
            this.updateTextPanel();
        });

        $('.layer-delete-btn').off('click').on('click', (e) => {
            e.stopPropagation();
            const idx = parseInt($(e.currentTarget).data('index'));
            this.deleteLayer(idx);
        });
    }

    deleteLayer(index) {
        const layers = this.getCurrentLayers();
        if (index >= 0 && index < layers.length) {
            layers.splice(index, 1);
            this.selectedLayerIndex = -1;
            this.render();
            this.updateLayersPanel();
        }
    }
    
    /**
     * Update text panel with selected layer properties
     */
    updateTextPanel() {
        const layers = this.getCurrentLayers();
        if (this.selectedLayerIndex < 0 || this.selectedLayerIndex >= layers.length) return;
        
        const layer = layers[this.selectedLayerIndex];
        if (layer.type !== 'text') return;
        
        $('#text-input').val(layer.text);
        $('#text-font').val(layer.fontFamily);
        $('#text-size').val(layer.fontSize);
        $('#text-color').val(layer.color);
        $('#text-stroke-color').val(layer.strokeColor || '#000000');
        $('#text-stroke-width').val(layer.strokeWidth || 0);
    }
    
    /**
     * Export the design as images
     * @param {number} scale - Multiplier for high resolution (default 5x = 2000px width)
     * @returns {Promise<{print: string, mockup: string}>}
     */
    async exportDesign(scale = 5) {
        const originalWidth = this.canvas.width;
        const originalHeight = this.canvas.height;
        const exportWidth = originalWidth * scale;
        const exportHeight = originalHeight * scale;
        
        // Create an off-screen canvas for high-res export
        const expCanvas = document.createElement('canvas');
        expCanvas.width = exportWidth;
        expCanvas.height = exportHeight;
        const expCtx = expCanvas.getContext('2d');
        
        // 1. Generate Print File (Design only, transparent background)
        expCtx.clearRect(0, 0, exportWidth, exportHeight);
        
        const layers = this.getCurrentLayers();
        layers.forEach(layer => {
            expCtx.save();
            expCtx.globalAlpha = layer.opacity || 1;
            
            // Adjust coordinates and size for scale
            const x = layer.x * scale;
            const y = layer.y * scale;
            const width = (layer.width || 0) * scale;
            const height = (layer.height || 0) * scale;
            
            const centerX = x + width / 2;
            const centerY = y + height / 2;
            
            if (layer.type === 'image') {
                expCtx.translate(centerX, centerY);
                expCtx.rotate((layer.rotation || 0) * Math.PI / 180);
                expCtx.translate(-centerX, -centerY);
                expCtx.drawImage(layer.image, x, y, width, height);
            } else if (layer.type === 'text') {
                // For text, layer.x and layer.y are already the anchor
                expCtx.translate(x * scale / layer.x, y * scale / layer.y); // Scale translation
                // Wait, it's easier:
                expCtx.setTransform(1, 0, 0, 1, 0, 0); // Reset
                const tx = layer.x * scale;
                const ty = layer.y * scale;
                expCtx.translate(tx, ty);
                expCtx.rotate((layer.rotation || 0) * Math.PI / 180);
                
                let fontStyle = '';
                if (layer.bold) fontStyle += 'bold ';
                if (layer.italic) fontStyle += 'italic ';
                const fontSize = layer.fontSize * scale;
                expCtx.font = `${fontStyle}${fontSize}px ${layer.fontFamily}`;
                expCtx.textAlign = layer.textAlign;
                expCtx.textBaseline = 'middle';
                
                if (layer.shadowColor) {
                    expCtx.shadowColor = layer.shadowColor;
                    expCtx.shadowBlur = layer.shadowBlur * scale;
                    expCtx.shadowOffsetX = layer.shadowOffsetX * scale;
                    expCtx.shadowOffsetY = layer.shadowOffsetY * scale;
                }
                
                if (layer.strokeColor && layer.strokeWidth > 0) {
                    expCtx.strokeStyle = layer.strokeColor;
                    expCtx.lineWidth = layer.strokeWidth * scale;
                    expCtx.strokeText(layer.text, 0, 0);
                }
                
                expCtx.fillStyle = layer.color;
                expCtx.fillText(layer.text, 0, 0);
            }
            expCtx.restore();
        });
        
        const printData = expCanvas.toDataURL('image/png');
        
        // 2. Generate Mockup (Template + Design)
        expCtx.clearRect(0, 0, exportWidth, exportHeight);
        const template = this.getCurrentTemplate();
        if (template) {
            expCtx.drawImage(template, 0, 0, exportWidth, exportHeight);
        }
        
        // Re-draw layers on top of template
        layers.forEach(layer => {
            expCtx.save();
            expCtx.globalAlpha = layer.opacity || 1;
            const x = layer.x * scale;
            const y = layer.y * scale;
            const width = (layer.width || 0) * scale;
            const height = (layer.height || 0) * scale;
            const centerX = x + width / 2;
            const centerY = y + height / 2;
            
            if (layer.type === 'image') {
                expCtx.translate(centerX, centerY);
                expCtx.rotate((layer.rotation || 0) * Math.PI / 180);
                expCtx.translate(-centerX, -centerY);
                expCtx.drawImage(layer.image, x, y, width, height);
            } else if (layer.type === 'text') {
                expCtx.translate(x, y);
                expCtx.rotate((layer.rotation || 0) * Math.PI / 180);
                let fontStyle = '';
                if (layer.bold) fontStyle += 'bold ';
                if (layer.italic) fontStyle += 'italic ';
                expCtx.font = `${fontStyle}${layer.fontSize * scale}px ${layer.fontFamily}`;
                expCtx.textAlign = layer.textAlign;
                expCtx.textBaseline = 'middle';
                
                if (layer.strokeColor && layer.strokeWidth > 0) {
                    expCtx.strokeStyle = layer.strokeColor;
                    expCtx.lineWidth = layer.strokeWidth * scale;
                    expCtx.strokeText(layer.text, 0, 0);
                }
                expCtx.fillStyle = layer.color;
                expCtx.fillText(layer.text, 0, 0);
            }
            expCtx.restore();
        });
        
        const mockupData = expCanvas.toDataURL('image/png');
        
        return {
            print: printData,
            mockup: mockupData,
            json: JSON.stringify(this.getSettings())
        };
    }

    /**
     * Get current settings for export/save
     */
    getSettings() {
        return {
            currentView: this.currentView,
            views: {
                front: {
                    layers: this.views.front.layers.map(layer => {
                        if (layer.type === 'image') {
                            return { ...layer, image: layer.image.src };
                        }
                        return { ...layer };
                    })
                },
                back: {
                    layers: this.views.back.layers.map(layer => {
                        if (layer.type === 'image') {
                            return { ...layer, image: layer.image.src };
                        }
                        return { ...layer };
                    })
                }
            },
            currentTemplateId: this.currentTemplateId
        };
    }
}

// Global instance
var mockupPreview = null;
