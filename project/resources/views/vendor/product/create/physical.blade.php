@extends('layouts.vendor')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet"/>

<style>
/* ============================================
   POD DESIGN UPLOAD - MODERN LAYOUT
   ============================================ */

.pod-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.pod-section {
    background: #fff;
    border-radius: 16px;
    margin-bottom: 25px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    overflow: hidden;
}

.pod-section-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 25px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.pod-section-header.info { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.pod-section-header.pricing { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.pod-section-header i {
    font-size: 24px;
}

.pod-section-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.pod-section-header span {
    font-size: 13px;
    opacity: 0.9;
}

.pod-section-body {
    padding: 25px;
}

/* ============================================
   SECTION 1: DESIGNER TOOL
   ============================================ */

.designer-container {
    display: flex;
    min-height: 450px;
    background: #2d2d2d;
    border-radius: 12px;
    overflow: hidden;
}

.designer-toolbar {
    width: 60px;
    background: #1a1a1a;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 15px 0;
    gap: 5px;
}

.tool-btn {
    width: 44px;
    height: 44px;
    border: none;
    border-radius: 8px;
    background: transparent;
    color: #888;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tool-btn:hover, .tool-btn.active {
    background: #3d3d3d;
    color: #fff;
}

.tool-divider {
    width: 30px;
    height: 1px;
    background: #444;
    margin: 10px 0;
}

.color-swatches {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    padding: 5px;
    max-width: 50px;
}

.color-swatch {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent;
    transition: transform 0.2s;
}

.color-swatch:hover, .color-swatch.active {
    transform: scale(1.2);
    border-color: #fff;
}

.designer-canvas {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: 
        linear-gradient(45deg, #333 25%, transparent 25%),
        linear-gradient(-45deg, #333 25%, transparent 25%),
        linear-gradient(45deg, transparent 75%, #333 75%),
        linear-gradient(-45deg, transparent 75%, #333 75%);
    background-size: 20px 20px;
    background-color: #3a3a3a;
    position: relative;
}

.canvas-wrapper {
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.4);
}

#mockup-canvas {
    display: block;
    max-height: 400px;
}

.upload-design-btn {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #007bff;
    color: #fff;
    padding: 12px 30px;
    border-radius: 25px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(0,123,255,0.4);
}

.designer-views {
    width: 90px;
    background: #1a1a1a;
    padding: 15px 8px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.view-thumb {
    background: #333;
    border-radius: 8px;
    padding: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    text-align: center;
}

.view-thumb:hover, .view-thumb.active {
    border-color: #007bff;
}

.view-thumb i { font-size: 24px; color: #888; }
.view-thumb span { display: block; color: #888; font-size: 11px; margin-top: 5px; }

/* Text Panel */
.text-tool-panel {
    position: absolute;
    top: 20px;
    left: 80px;
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.3);
    width: 260px;
    display: none;
    z-index: 100;
}

.text-tool-panel.show { display: block; }
.text-tool-panel h5 { margin: 0 0 12px; font-size: 14px; }
.text-tool-panel .form-row { display: flex; gap: 8px; margin-bottom: 8px; }
.text-tool-panel input, .text-tool-panel select { flex: 1; padding: 6px; border: 1px solid #ddd; border-radius: 4px; }
.text-tool-panel textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; resize: none; height: 50px; }
.text-tool-panel .btn-apply { width: 100%; padding: 8px; background: #007bff; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-top: 8px; }

/* Layers Panel */
.layers-panel {
    position: absolute;
    bottom: 20px;
    right: 110px;
    width: 180px;
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    max-height: 250px;
    overflow-y: auto;
}

.layers-panel h6 { margin: 0 0 10px; font-size: 12px; padding-bottom: 8px; border-bottom: 1px solid #eee; }

.layer-item {
    padding: 6px 8px;
    background: #f5f5f5;
    border-radius: 4px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    border: 2px solid transparent;
}

.layer-item.selected { background: #e3f2fd; border-color: #2196f3; }
.layer-item i { color: #666; font-size: 12px; }
.layer-name { flex: 1; font-size: 11px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.layer-delete { background: none; border: none; color: #dc3545; cursor: pointer; padding: 2px; opacity: 0; }
.layer-item:hover .layer-delete { opacity: 1; }

/* ============================================
   SECTION 2: PRODUCT INFO
   ============================================ */

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    margin-bottom: 0;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 14px;
    color: #333;
}

.form-group label span {
    color: #dc3545;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

/* ============================================
   SECTION 3: PRICING
   ============================================ */

.pricing-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.pricing-option {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    border: 2px solid transparent;
    transition: all 0.3s;
}

.pricing-option:hover {
    border-color: #667eea;
}

.pricing-option label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    margin-bottom: 10px;
    color: #333;
}

.pricing-option label i {
    margin-right: 6px;
    color: #667eea;
}

.pricing-option select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #fff;
}

.pricing-summary {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 20px;
    color: #fff;
}

.pricing-item {
    text-align: center;
    padding: 10px;
}

.pricing-item.highlight {
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
}

.pricing-item .label {
    font-size: 12px;
    opacity: 0.9;
    margin-bottom: 5px;
}

.pricing-item .value {
    font-size: 24px;
    font-weight: bold;
}

.pricing-item.highlight .value {
    font-size: 32px;
}

/* Margin Slider */
.margin-slider {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
}

.margin-slider label {
    display: flex;
    justify-content: space-between;
    font-weight: 600;
    margin-bottom: 10px;
}

.margin-slider input[type="range"] {
    width: 100%;
}

.margin-display {
    display: inline-block;
    background: #667eea;
    color: #fff;
    padding: 4px 12px;
    border-radius: 20px;
    font-weight: bold;
}

/* ============================================
   SUBMIT SECTION
   ============================================ */

.submit-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #f8f9fa;
    border-radius: 12px;
}

.submit-btn {
    padding: 15px 40px;
    font-size: 16px;
    font-weight: 600;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.submit-btn.primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
}

.submit-btn.secondary {
    background: #6c757d;
    color: #fff;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.2);
}

/* Responsive */
@media (max-width: 991px) {
    .designer-container { flex-direction: column; }
    .designer-toolbar { flex-direction: row; width: 100%; justify-content: center; }
    .designer-views { flex-direction: row; width: 100%; justify-content: center; }
    .info-grid, .pricing-grid { grid-template-columns: 1fr; }
    .pricing-summary { grid-template-columns: repeat(2, 1fr); }
}

/* Hide old elements */
.pod-hide { display: none !important; }
</style>

@endsection

@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Upload Design') }} 
                    <a class="add-btn" href="{{ route('vendor-prod-index') }}">
                        <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                    </a>
                </h4>
            </div>
        </div>
    </div>

    <form id="geniusform" action="{{route('vendor-prod-store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('alerts.admin.form-both')
        <input type="hidden" name="product_type" value="physical">
        <input type="hidden" name="is_pod" value="1">

        <div class="pod-page">
            
            {{-- ============================================
                 SECTION 1: DESIGNER TOOL
                 ============================================ --}}
            <div class="pod-section">
                <div class="pod-section-header">
                    <i class="fas fa-palette"></i>
                    <div>
                        <h3>{{ __('Design Your Product') }}</h3>
                        <span>Upload artwork, add text, and customize your product mockup</span>
                    </div>
                </div>
                <div class="pod-section-body" style="padding: 0;">
                    <div class="designer-container">
                        {{-- Toolbar --}}
                        <div class="designer-toolbar">
                            <button type="button" class="tool-btn active" id="tool-product" title="Product Type">
                                <i class="fas fa-tshirt"></i>
                            </button>
                            <div class="tool-divider"></div>
                            <div class="color-swatches">
                                <div class="color-swatch active" style="background:#fff" data-color="white"></div>
                                <div class="color-swatch" style="background:#000" data-color="black"></div>
                                <div class="color-swatch" style="background:#dc3545" data-color="red"></div>
                                <div class="color-swatch" style="background:#007bff" data-color="blue"></div>
                                <div class="color-swatch" style="background:#28a745" data-color="green"></div>
                                <div class="color-swatch" style="background:#ffc107" data-color="yellow"></div>
                            </div>
                            <div class="tool-divider"></div>
                            <button type="button" class="tool-btn" id="tool-upload" title="Upload Design">
                                <i class="fas fa-upload"></i>
                            </button>
                            <button type="button" class="tool-btn" id="tool-text" title="Add Text">
                                <i class="fas fa-font"></i>
                            </button>
                            <div class="tool-divider"></div>
                            <button type="button" class="tool-btn" id="tool-rotate" title="Rotate">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button type="button" class="tool-btn" id="tool-reset" title="Delete Selected">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        {{-- Canvas --}}
                        <div class="designer-canvas">
                            <div class="canvas-wrapper">
                                <canvas id="mockup-canvas" width="400" height="420"></canvas>
                            </div>
                            <label for="design-upload-input" class="upload-design-btn">
                                <i class="fas fa-cloud-upload-alt"></i> Upload Your Design
                            </label>
                            <input type="file" id="design-upload-input" accept="image/*" style="display:none;">
                            
                            {{-- Text Panel --}}
                            <div class="text-tool-panel" id="text-panel">
                                <h5><i class="fas fa-font"></i> Add Text</h5>
                                <div class="form-row">
                                    <select id="text-font">
                                        <option value="Arial">Arial</option>
                                        <option value="Impact">Impact</option>
                                        <option value="Georgia">Georgia</option>
                                    </select>
                                    <select id="text-size">
                                        <option value="24">24px</option>
                                        <option value="32" selected>32px</option>
                                        <option value="48">48px</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <input type="color" id="text-color" value="#000000">
                                    <button type="button" id="text-bold" style="font-weight:bold;">B</button>
                                    <button type="button" id="text-italic" style="font-style:italic;">I</button>
                                </div>
                                <textarea id="text-input" placeholder="Enter your text..."></textarea>
                                <button type="button" class="btn-apply" id="apply-text">Add Text</button>
                            </div>
                            
                            {{-- Layers Panel --}}
                            <div class="layers-panel">
                                <h6>Layers</h6>
                                <div id="layers-panel"></div>
                            </div>
                        </div>

                        {{-- Views --}}
                        <div class="designer-views">
                            <div class="view-thumb active" data-view="front">
                                <i class="fas fa-tshirt"></i>
                                <span>Front</span>
                            </div>
                            <div class="view-thumb" data-view="back">
                                <i class="fas fa-tshirt"></i>
                                <span>Back</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================
                 SECTION 2: PRODUCT INFO
                 ============================================ --}}
            <div class="pod-section">
                <div class="pod-section-header info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h3>{{ __('Product Information') }}</h3>
                        <span>Name, category, and description for your design</span>
                    </div>
                </div>
                <div class="pod-section-body">
                    <div class="info-grid">
                        <div class="form-group">
                            <label>{{ __('Design Name') }} <span>*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter design name" required>
                        </div>
                        <input type="hidden" name="is_pod" value="1">
                        <div class="form-group">
                            <label>{{ __('Production Cap (Minimum Batch)') }} <i class="fas fa-question-circle" title="Production will only start once this many orders are reached for this design."></i></label>
                            <input type="number" name="production_cap" class="form-control" value="1" min="1" required>
                            <small class="text-muted">Orders will queue until this total is reached.</small>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Category') }} <span>*</span></label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach(App\Models\Category::where('status', 1)->get() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        {{-- Hidden design fields --}}
                        <input type="hidden" name="photo" id="mockup-image-hidden">
                        <input type="hidden" name="print_image" id="print-image-hidden">
                        <input type="hidden" name="design_data" id="design-json-hidden">
                        <div class="form-group full-width">
                            <label>{{ __('Description') }}</label>
                            <textarea name="details" class="form-control" rows="4" placeholder="Describe your design..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ __('Stock Quantity') }}</label>
                            <input type="number" name="stock" class="form-control" value="999" min="0">
                        </div>
                        <div class="form-group">
                            <label>{{ __('SKU') }}</label>
                            <input type="text" name="sku" class="form-control" placeholder="Auto-generated">
                        </div>
                    </div>
                    <input type="hidden" name="language_id" value="1">
                </div>
            </div>

            {{-- ============================================
                 SECTION 3: PRICING
                 ============================================ --}}
            <div class="pod-section">
                <div class="pod-section-header pricing">
                    <i class="fas fa-dollar-sign"></i>
                    <div>
                        <h3>{{ __('Quality & Pricing') }}</h3>
                        <span>Select quality options and set your profit margin</span>
                    </div>
                </div>
                <div class="pod-section-body">
                    <div class="pricing-grid">
                        <div class="pricing-option">
                            <label><i class="fas fa-print"></i> Print Quality</label>
                            <select id="print-quality">
                                <option value="standard" data-price="0">Standard (+$0)</option>
                                <option value="hd" data-price="3">HD Print (+$3)</option>
                                <option value="premium" data-price="5">Premium (+$5)</option>
                                <option value="dtg" data-price="8">DTG (+$8)</option>
                            </select>
                        </div>
                        <div class="pricing-option">
                            <label><i class="fas fa-tshirt"></i> Clothing Quality</label>
                            <select id="clothing-quality">
                                <option value="basic" data-price="8">Basic Cotton ($8)</option>
                                <option value="standard" data-price="12" selected>Standard ($12)</option>
                                <option value="premium" data-price="18">Premium ($18)</option>
                                <option value="organic" data-price="25">Organic ($25)</option>
                            </select>
                        </div>
                        <div class="pricing-option">
                            <label><i class="fas fa-shipping-fast"></i> Production Speed</label>
                            <select id="production-speed">
                                <option value="standard" data-price="0">Standard 5-7 days (+$0)</option>
                                <option value="fast" data-price="5">Fast 2-3 days (+$5)</option>
                                <option value="express" data-price="10">Express 1 day (+$10)</option>
                            </select>
                        </div>
                        <div class="pricing-option">
                            <label><i class="fas fa-tag"></i> Tag Options</label>
                            <select id="tag-option">
                                <option value="standard" data-price="0">Standard Tag (+$0)</option>
                                <option value="custom" data-price="2">Custom Brand (+$2)</option>
                                <option value="tagless" data-price="1">Tagless (+$1)</option>
                            </select>
                        </div>
                        <div class="pricing-option">
                            <label><i class="fas fa-box"></i> Packaging</label>
                            <select id="packaging">
                                <option value="standard" data-price="0">Standard Bag (+$0)</option>
                                <option value="premium" data-price="2">Premium Box (+$2)</option>
                                <option value="custom" data-price="5">Custom Branded (+$5)</option>
                            </select>
                        </div>
                        <div class="pricing-option">
                            <label><i class="fas fa-percent"></i> Your Profit Margin</label>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <input type="range" id="profit-margin" min="10" max="200" value="50" style="flex:1;">
                                <span class="margin-display" id="margin-display">50%</span>
                            </div>
                        </div>
                    </div>

                    <div class="pricing-summary">
                        <div class="pricing-item">
                            <div class="label">Base Cost</div>
                            <div class="value" id="base-cost-display">$12.00</div>
                        </div>
                        <div class="pricing-item">
                            <div class="label">Add-ons</div>
                            <div class="value" id="features-cost-display">$0.00</div>
                        </div>
                        <div class="pricing-item">
                            <div class="label">Your Profit</div>
                            <div class="value" id="margin-amount-display">$6.00</div>
                        </div>
                        <div class="pricing-item highlight">
                            <div class="label">Retail Price</div>
                            <div class="value" id="final-price-display">$18.00</div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="price" id="final-price-input" value="18.00">
                    <input type="hidden" name="base_cost" id="base-cost-input" value="12.00">
                </div>
            </div>

            {{-- ============================================
                 SUBMIT SECTION
                 ============================================ --}}
            <div class="submit-section">
                <div>
                    <span style="color:#666;">Estimated monthly profit (50 sales): </span>
                    <strong id="monthly-profit" style="color:#28a745;font-size:18px;">$300</strong>
                </div>
                <div>
                    <button type="button" id="save-draft-btn" class="submit-btn secondary">
                        <i class="fas fa-save"></i> Save Draft
                    </button>
                    <button type="submit" class="submit-btn primary">
                        <i class="fas fa-rocket"></i> Publish Design
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')

<script src="{{asset('assets/admin/js/jquery.Jcrop.js')}}"></script>
<script src="{{asset('assets/admin/js/jquery.SimpleCropper.js')}}"></script>
<script src="{{asset('assets/admin/js/mockup-preview.js')}}"></script>
<script src="{{asset('assets/admin/js/mockup-events.js')}}"></script>
<script src="{{asset('assets/admin/js/quality-pricing.js')}}"></script>

<script>
$(document).ready(function() {
    // Auto-generate SKU from name
    $('input[name="name"]').on('blur', function() {
        const name = $(this).val();
        if (name && !$('input[name="sku"]').val()) {
            const sku = 'POD-' + name.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().substring(0, 8) + '-' + Date.now().toString().substr(-4);
            $('input[name="sku"]').val(sku);
        }
    });
    
    // Save draft
    $('#save-draft-btn').on('click', function() {
        alert('Draft saved!');
    });
});
</script>

@endsection
