@extends('layouts.vendor')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet"/>

<style>
/* ============================================
   POD DESIGN UPLOAD - MODERN LAYOUT
   ============================================ */

/* ============================================
   PRO EDITOR THEME (IMG.LY INSPIRED)
   ============================================ */

:root {
    --editor-bg: #f3f4f6;
    --panel-bg: #ffffff;
    --border-color: #e5e7eb;
    --accent-color: #4f46e5;
    --accent-hover: #4338ca;
    --text-main: #111827;
    --text-sub: #6b7280;
    --surface-hover: #f9fafb;
    --header-height: 60px;
}

.pod-app-container {
    display: flex;
    height: 80vh;
    min-height: 600px;
    background: var(--editor-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    overflow: hidden;
    font-family: 'Inter', sans-serif;
    color: var(--text-main);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

/* --- LEFT PANEL: LAYERS --- */
.editor-sidebar-left {
    width: 240px;
    background: var(--panel-bg);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    z-index: 20;
}

.sidebar-header {
    height: 50px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    padding: 0 16px;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-main);
    justify-content: space-between;
}

.layers-list {
    flex: 1;
    overflow-y: auto;
    padding: 12px;
}

.layer-entry {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 13px;
}

.layer-entry:hover {
    background: var(--surface-hover);
    border-color: var(--accent-color);
}

.layer-entry.active {
    background: #eef2ff;
    border-color: var(--accent-color);
    color: var(--accent-color);
    font-weight: 500;
}

.layer-icon {
    color: var(--text-sub);
    font-size: 14px;
}

.layer-entry.active .layer-icon { color: var(--accent-color); }

/* --- CENTER: CANVAS --- */
.editor-canvas-stage {
    flex: 1;
    background: var(--editor-bg);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.stage-toolbar {
    height: 50px;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: center; /* Center the view switcher */
    pointer-events: none; /* Let clicks pass through if needed, but buttons need pointer-events:auto */
}

.view-switcher-pill {
    pointer-events: auto;
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 100px;
    padding: 4px;
    display: flex;
    gap: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.view-btn {
    padding: 6px 16px;
    border-radius: 100px;
    border: none;
    background: transparent;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-sub);
    cursor: pointer;
    transition: all 0.2s;
}

.view-btn:hover { background: var(--surface-hover); color: var(--text-main); }
.view-btn.active { background: var(--text-main); color: #fff; }

.canvas-viewport {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    background-image: radial-gradient(#d1d5db 1px, transparent 1px);
    background-size: 20px 20px;
}

.canvas-frame {
    /* The literal mockup canvas */
    box-shadow: 0 20px 50px -10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

/* Floating Actions (Center Bottom) */
.stage-actions {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 12px;
    pointer-events: auto;
}

.action-btn-pill {
    background: var(--text-main);
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: 100px;
    font-weight: 600;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 10px 20px -5px rgba(0,0,0,0.2);
    transition: transform 0.2s;
}
.action-btn-pill:hover { transform: translateY(-3px); box-shadow: 0 15px 30px -5px rgba(0,0,0,0.3); }

/* --- RIGHT PANEL: INSPECTOR --- */
.editor-sidebar-right {
    width: 320px;
    background: var(--panel-bg);
    border-left: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    z-index: 20;
    overflow-y: auto;
}

.inspector-section {
    padding: 20px;
    border-bottom: 1px solid var(--border-color);
}

.section-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-sub);
    margin-bottom: 16px;
    display: block;
}

/* Grid Tools */
.asset-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.asset-card {
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
    text-align: center;
}

.asset-card:hover { border-color: var(--accent-color); background: #eef2ff; color: var(--accent-color); }
.asset-card i { font-size: 20px; }
.asset-card span { font-size: 13px; font-weight: 500; }

/* Config Inputs */
.config-group { margin-bottom: 15px; }
.config-input {
    width: 100%;
    padding: 10px 12px;
    background: var(--surface-hover);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 14px;
    color: var(--text-main);
}
.config-input:focus { outline: none; border-color: var(--accent-color); background: #fff; }

/* Color Circles */
.color-options { display: flex; flex-wrap: wrap; gap: 8px; }
.color-dot {
    width: 28px; height: 28px;
    border-radius: 50%;
    cursor: pointer;
    border: 2px solid transparent; /* Ring */
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.1);
}
.color-dot.active { border-color: var(--accent-color); transform: scale(1.1); }

/* --- RESPONSIVE --- */
@media (max-width: 1024px) {
    .pod-app-container { flex-direction: column; height: auto; }
    .editor-sidebar-left, .editor-sidebar-right { width: 100%; height: auto; max-height: 200px; }
    .editor-canvas-stage { height: 500px; }
    .stage-actions { bottom: 20px; }
}

/* Hide legacy */
.pod-hide { display: none; }

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
                    <!-- PRO EDITOR UI -->
                    <div class="pod-app-container">
                        
                        <!-- LEFT SIDEBAR: LAYERS -->
                        <div class="editor-sidebar-left">
                            <div class="sidebar-header">
                                <span>LAYERS</span>
                                <small style="color:var(--text-sub)">Drag to reorder</small>
                            </div>
                            <div id="layers-panel" class="layers-list">
                                 <!-- Layers injected via JS -->
                                 <div class="layer-entry active">
                                    <i class="fas fa-tshirt layer-icon"></i>
                                    <span style="flex:1">Base Product</span>
                                    <i class="fas fa-lock" style="font-size:10px; opacity:0.5;"></i>
                                </div>
                            </div>
                        </div>

                        <!-- CENTER: CANVAS STAGE -->
                        <div class="editor-canvas-stage">
                            
                            <!-- Top Toolbar (View Switcher) -->
                            <div class="stage-toolbar">
                                <div class="view-switcher-pill">
                                    <button type="button" class="view-btn active" data-view="front">Front</button>
                                    <button type="button" class="view-btn" data-view="back">Back</button>
                                    <button type="button" class="view-btn" data-view="left">Left</button>
                                    <button type="button" class="view-btn" data-view="right">Right</button>
                                </div>
                            </div>

                            <!-- Canvas Area -->
                            <div class="canvas-viewport">
                                <div class="canvas-frame">
                                    <canvas id="mockup-canvas" width="500" height="550"></canvas>
                                </div>
                            </div>

                            <!-- Bottom Actions -->
                            <div class="stage-actions">
                                 <button type="button" class="action-btn-pill" id="tool-zoom-in" title="Zoom In"><i class="fas fa-search-plus"></i></button>
                                 <button type="button" class="action-btn-pill" id="tool-zoom-out" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
                            </div>

                        </div>

                        <!-- RIGHT SIDEBAR: INSPECTOR & TOOLS -->
                        <div class="editor-sidebar-right">
                            
                            <!-- ADD ASSETS SECTION -->
                            <div class="inspector-section">
                                <span class="section-label">Add Assets</span>
                                <div class="asset-grid">
                                    <div class="asset-card" id="tool-text">
                                        <i class="fas fa-font" style="color:#6366f1"></i>
                                        <span>Add Text</span>
                                    </div>
                                    <div class="asset-card" id="tool-upload">
                                        <i class="fas fa-cloud-upload-alt" style="color:#10b981"></i>
                                        <span>Upload Image</span>
                                    </div>
                                </div>
                                <input type="file" id="design-upload-input" accept="image/*" style="display:none;">
                            </div>

                            <!-- PRODUCT CONFIG SECTION -->
                            <div class="inspector-section">
                                <span class="section-label">Product Configuration</span>
                                <div class="config-group">
                                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block;">Base Product</label>
                                    <button type="button" class="config-input" id="tool-product" style="text-align:left; display:flex; justify-content:space-between; align-items:center;">
                                        <span>Change Product</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="config-group">
                                    <label style="font-size:13px; font-weight:600; margin-bottom:8px; display:block;">Colors</label>
                                    <div class="color-options">
                                        <div class="color-dot active" style="background:#ffffff; border:1px solid #ddd;" data-color="white" data-hex="#ffffff"></div>
                                        <div class="color-dot" style="background:#0f172a" data-color="black" data-hex="#0f172a"></div>
                                        <div class="color-dot" style="background:#ef4444" data-color="red" data-hex="#ef4444"></div>
                                        <div class="color-dot" style="background:#3b82f6" data-color="blue" data-hex="#3b82f6"></div>
                                        <div class="color-dot" style="background:#10b981" data-color="green" data-hex="#10b981"></div>
                                        <div class="color-dot" style="background:#f59e0b" data-color="yellow" data-hex="#f59e0b"></div>
                                        <div class="color-dot" style="background:#8b5cf6" data-color="purple" data-hex="#8b5cf6"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- TEXT PROPERTIES (Hidden by default or shown when text selected - keeping static for now) -->
                            <div class="inspector-section tool-properties-panel" id="text-properties-panel">
                                <span class="section-label">Text Properties</span>
                                <div class="config-group">
                                    <textarea id="text-input" class="config-input" rows="2" placeholder="Edit text..."></textarea>
                                </div>
                                <div class="config-group">
                                    <div style="display:flex; gap:8px;">
                                        <select id="text-font" class="config-input">
                                            <option value="Arial">Arial</option>
                                            <option value="Helvetica">Helvetica</option>
                                            <option value="Times New Roman">Times</option>
                                            <option value="Impact">Impact</option>
                                        </select>
                                        <input type="number" id="text-size" class="config-input" value="32" style="width:70px;">
                                    </div>
                                </div>
                                <div class="config-group">
                                    <div style="display:flex; gap:8px;">
                                        <input type="color" id="text-color" value="#000000" style="height:38px; width:100%; padding:0; border:none; border-radius:6px; cursor:pointer;">
                                        <button type="button" class="config-input" id="add-text-btn" style="background:var(--accent-color); color:#fff; border:none;">Update</button>
                                    </div>
                                </div>
                                <div class="config-group">
                                    <div style="display:flex; gap:8px;">
                                        <button type="button" id="text-bold" class="config-input" style="flex:1; font-weight:bold;">B</button>
                                        <button type="button" id="text-italic" class="config-input" style="flex:1; font-style:italic;">I</button>
                                    </div>
                                </div>
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
                            <label>{{ __('Est. Print Time (Minutes)') }}</label>
                            <input type="number" name="print_time_minutes" class="form-control" value="30" min="15">
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
                            <select id="print-quality" name="quality_tier">
                                <option value="standard" data-price="0">Standard (+$0)</option>
                                <option value="premium" data-price="5">Premium (+$5)</option>
                                <option value="deluxe" data-price="8">Deluxe (+$8)</option>
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
<script src="{{asset('assets/admin/js/mockup-preview.js')}}?v=3.2"></script>
<!-- <script src="{{asset('assets/admin/js/mockup-events.js')}}"></script> -->
<script src="{{asset('assets/admin/js/quality-pricing.js')}}"></script>
<script>
    // Pro Editor Logic
    $(document).ready(function() {
        
        // Initialize Mockup Preview
        try {
            mockupPreview = new MockupPreview({
                canvasId: 'mockup-canvas'
            });
            
            // Define Templates with Front/Back/Left/Right URLs
            mockupPreview.templates = {
                'tshirt_white': {
                    name: 'White T-Shirt',
                    frontUrl: "{{ asset('assets/images/mockups/tshirt/front.png') }}",
                    backUrl: "{{ asset('assets/images/mockups/tshirt/back.png') }}",
                    leftUrl: "{{ asset('assets/images/mockups/tshirt/left.png') }}",
                    rightUrl: "{{ asset('assets/images/mockups/tshirt/right.png') }}",
                    productType: 'tshirt',
                    color: 'white',
                },
                'tshirt_black': {
                    name: 'Black T-Shirt',
                    frontUrl: "{{ asset('assets/images/mockups/tshirt/front.svg') }}",
                    backUrl: "{{ asset('assets/images/mockups/tshirt/back.svg') }}",
                    leftUrl: "{{ asset('assets/images/mockups/tshirt/left.svg') }}",
                    rightUrl: "{{ asset('assets/images/mockups/tshirt/right.svg') }}",
                    productType: 'tshirt',
                    color: 'black',
                },
                'hoodie_white': {
                    name: 'White Hoodie',
                    frontUrl: "{{ asset('assets/images/mockups/hoodie/front.svg') }}",
                    backUrl: "{{ asset('assets/images/mockups/hoodie/back.svg') }}",
                    leftUrl: "{{ asset('assets/images/mockups/hoodie/left.svg') }}",
                    rightUrl: "{{ asset('assets/images/mockups/hoodie/right.svg') }}",
                    productType: 'hoodie',
                    color: 'white',
                },
                'shirt_white': {
                    name: 'White Shirt',
                    frontUrl: "{{ asset('assets/images/mockups/shirt/front.svg') }}",
                    backUrl: "{{ asset('assets/images/mockups/shirt/back.svg') }}",
                    leftUrl: "{{ asset('assets/images/mockups/shirt/left.svg') }}",
                    rightUrl: "{{ asset('assets/images/mockups/shirt/right.svg') }}",
                    productType: 'shirt',
                    color: 'white',
                }
            };
            
            mockupPreview.loadDefaultTemplate();
            
        } catch(e) {
            console.error("Mockup Init Error:", e);
        }

        // --- ASSET TOOLS ---
        
        // Add Text Focus
        $('#tool-text').on('click', function() {
            $('#text-input').focus();
            // Highlight properties panel
            $('#text-properties-panel').addClass('highlight-panel');
            setTimeout(() => $('#text-properties-panel').removeClass('highlight-panel'), 800);
        });
        
        // Add/Update Text Button
        $('#add-text-btn').on('click', function() {
              const text = $('#text-input').val() || 'New Text';
              const font = $('#text-font').val();
              const size = parseInt($('#text-size').val());
              const color = $('#text-color').val();
              const isBold = $('#text-bold').hasClass('active');
              const isItalic = $('#text-italic').hasClass('active');
              
              if(mockupPreview) {
                  // If layer selected, update it? Or always add new?
                  // For now, let's always add new for simplicity, or update if we had a "selected" state tracking
                  mockupPreview.addTextLayer({
                      text: text,
                      fontFamily: font,
                      fontSize: size,
                      color: color,
                      bold: isBold,
                      italic: isItalic
                  });
              }
        });

        // Upload Trigger
        $('#tool-upload').on('click', function() {
            $('#design-upload-input').click();
        });
        
        // File Input Change
        $('#design-upload-input').on('change', function(e) {
            if(e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                     if(mockupPreview) mockupPreview.addImageLayer(evt.target.result);
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // --- CONFIGURATION TOOLS ---

        // Product Switcher
        $('#tool-product').on('click', function() {
            if(mockupPreview) {
                 const current = mockupPreview.currentTemplateId;
                 let next = 'tshirt_black';
                 if(current === 'tshirt_black') next = 'hoodie_white';
                 else if(current === 'hoodie_white') next = 'tshirt_white';
                 else next = 'tshirt_black';
                 
                 mockupPreview.loadTemplate(next);
            }
        });
        
        // View Switcher (Front/Back)
        $('.view-btn').on('click', function() {
            if($(this).attr('disabled')) return;
            $('.view-btn').removeClass('active');
            $(this).addClass('active');
            
            const view = $(this).data('view');
            if(mockupPreview) mockupPreview.switchView(view);
        });

        // Color Dots (Product Color)
        $('.color-dot').on('click', function() {
            $('.color-dot').removeClass('active');
            $(this).addClass('active');
            
            const colorHex = $(this).data('hex');
            
            // For tinting to work best, we usually want the White template
            // If the user selects 'Black' specifically, we might want to load the dedicated Black template if it exists,
            // otherwise we tint the white one (which results in a dark grey usually, not true black).
            // For now, let's try to stick to the 'Base' White template for all colors except specific ones if we wanted.
            
            // Ensure we are using a "tintable" base (White)
            // If current template is 'tshirt_black', switch to 'tshirt_white' then tint
            if (mockupPreview) {
                 if (mockupPreview.currentTemplateId && mockupPreview.currentTemplateId.includes('black')) {
                     // Switch to white equivalent for better tinting
                     const newId = mockupPreview.currentTemplateId.replace('black', 'white');
                     if (mockupPreview.templates[newId]) {
                         mockupPreview.loadTemplate(newId).then(() => {
                             mockupPreview.setProductColor(colorHex);
                         });
                     } else {
                         mockupPreview.setProductColor(colorHex);
                     }
                 } else {
                     mockupPreview.setProductColor(colorHex);
                 }
            }
        });

        // Zoom Tools
        $('#tool-zoom-in').on('click', function() {
            // Implement zoom logic using CSS transform on canvas-frame or internal canvas scale
            const currentScale = parseFloat($('#mockup-canvas').data('scale') || 1);
            // Limit max zoom to 2.0 (200%) to preserve PNG quality
            const newScale = Math.min(currentScale + 0.1, 2.0);
            $('#mockup-canvas').css('transform', `scale(${newScale})`).data('scale', newScale);
        });
        
        $('#tool-zoom-out').on('click', function() {
            const currentScale = parseFloat($('#mockup-canvas').data('scale') || 1);
            const newScale = Math.max(currentScale - 0.1, 0.5);
            $('#mockup-canvas').css('transform', `scale(${newScale})`).data('scale', newScale);
        });
        
        // Text Enhancements
        $('#text-bold, #text-italic').on('click', function() {
            $(this).toggleClass('active');
        });

    });
</script>

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
