@extends('layouts.vendor')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet"/>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto&family=Open+Sans&family=Lato&family=Montserrat&family=Oswald&family=Source+Sans+Pro&family=Slabo+27px&family=Raleway&family=PT+Sans&family=Merriweather&family=Nunito&family=Prompt&family=Work+Sans&family=Bebas+Neue&family=Anton&family=Dancing+Script&family=Pacifico&display=swap" rel="stylesheet">

<style>
/* ============================================
   POD DESIGN UPLOAD - MODERN LAYOUT
   ============================================ */

/* ============================================
   PRO EDITOR THEME (IMG.LY INSPIRED)
   ============================================ */

:root {
    /* We-Brand.shop Theme Colors */
    --brand-primary: #6366f1;
    --brand-primary-hover: #4f46e5;
    --brand-primary-light: #eef2ff;
    --brand-success: #10b981;
    --brand-warning: #f59e0b;
    --brand-danger: #ef4444;
    
    /* Neutral Palette */
    --editor-bg: #f8fafc;
    --panel-bg: #ffffff;
    --border-color: #e2e8f0;
    --border-focus: var(--brand-primary);
    
    /* Text Colors */
    --text-main: #0f172a;
    --text-sub: #64748b;
    --text-muted: #94a3b8;
    
    /* Interactive States */
    --surface-hover: #f1f5f9;
    --surface-active: var(--brand-primary-light);
    
    /* Layout */
    --header-height: 60px;
    --spacing-unit: 8px;
    
    /* Shadows */
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    
    /* Transitions */
    --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
}

.pod-app-container {
    display: flex;
    height: 85vh;
    min-height: 650px;
    background: var(--editor-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: var(--text-main);
    box-shadow: var(--shadow-lg);
    transition: box-shadow var(--transition-base);
}

.pod-app-container:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* --- LEFT PANEL: LAYERS --- */
.editor-sidebar-left {
    width: 260px;
    background: var(--panel-bg);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    z-index: 20;
    transition: width var(--transition-base);
}

.sidebar-header {
    height: 56px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    padding: 0 20px;
    font-weight: 700;
    font-size: 14px;
    letter-spacing: 0.3px;
    color: var(--text-main);
    justify-content: space-between;
    background: linear-gradient(to bottom, #ffffff, #fafbfc);
}

.layers-list {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 16px;
}

.layers-list::-webkit-scrollbar {
    width: 6px;
}

.layers-list::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.layers-list::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}

.layer-entry {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    background: #fff;
    border: 1.5px solid var(--border-color);
    border-radius: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all var(--transition-fast);
    font-size: 13px;
    font-weight: 500;
    user-select: none;
}

.layer-entry:hover {
    background: var(--surface-hover);
    border-color: var(--brand-primary);
    transform: translateX(2px);
    box-shadow: var(--shadow-sm);
}

.layer-entry.active {
    background: var(--brand-primary-light);
    border-color: var(--brand-primary);
    color: var(--brand-primary);
    font-weight: 600;
    box-shadow: var(--shadow-md);
}

.layer-icon {
    color: var(--text-sub);
    font-size: 16px;
    transition: color var(--transition-fast);
}

.layer-entry:hover .layer-icon,
.layer-entry.active .layer-icon {
    color: var(--brand-primary);
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

.asset-card:hover { 
    border-color: var(--brand-primary); 
    background: var(--brand-primary-light); 
    color: var(--brand-primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}
.asset-card i { 
    font-size: 24px;
    transition: transform var(--transition-fast);
}
.asset-card:hover i {
    transform: scale(1.1);
}
.asset-card span { 
    font-size: 13px; 
    font-weight: 600;
    letter-spacing: 0.2px;
}

/* Config Inputs */
.config-group { 
    margin-bottom: 18px;
}

.config-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
    letter-spacing: 0.2px;
}

.config-input {
    width: 100%;
     background: #fff;
    border: 1.5px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    color: var(--text-main);
    transition: all var(--transition-fast);
    font-family: inherit;
}

.config-input:hover {
    border-color: var(--text-sub);
}

.config-input:focus { 
    outline: none; 
    border-color: var(--brand-primary);
    background: #fff;
    box-shadow: 0 0 0 3px var(--brand-primary-light);
}

/* Color Circles */
.color-options { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 10px;
    padding: 4px;
}

.color-dot {
    width: 36px; 
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    border: 3px solid transparent;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12), inset 0 0 0 1px rgba(0,0,0,0.1);
    transition: all var(--transition-fast);
    position: relative;
}

.color-dot:hover {
    transform: scale(1.15);
    box-shadow: 0 4px 12px rgba(0,0,0,0.18), inset 0 0 0 1px rgba(0,0,0,0.1);
}

.color-dot.active { 
    border-color: var(--brand-primary); 
    transform: scale(1.2);
    box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4), inset 0 0 0 1px rgba(0,0,0,0.1);
}

.color-dot.active::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 16px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
}

/* --- RESPONSIVE --- */
@media (max-width: 1024px) {
    .pod-app-container { flex-direction: column; height: auto; }
    .editor-sidebar-left, .editor-sidebar-right { width: 100%; height: auto; max-height: 200px; }
    .editor-canvas-stage { height: 500px; }
    .stage-actions { bottom: 20px; }
}

/* Hide legacy */
/* ============================================
   UNIFIED TABS & FORM STYLING
   ============================================ */

.unified-details {
    margin-top: 30px;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    background: #fff;
    box-shadow: var(--shadow-md);
}

.unified-tabs {
    display: flex;
    background: #f8fafc;
    border-bottom: 1px solid var(--border-color);
    padding: 0 10px;
}

.unified-tab {
    padding: 16px 24px;
    font-weight: 600;
    font-size: 14px;
    color: var(--text-sub);
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.2s;
    user-select: none;
}

.unified-tab:hover {
    color: var(--brand-primary);
    background: rgba(99, 102, 241, 0.05);
}

.unified-tab.active {
    color: var(--brand-primary);
    border-bottom-color: var(--brand-primary);
    background: #fff;
}

.unified-tab-content {
    padding: 30px;
}

.unified-panel {
    display: none;
}

.unified-panel.active {
    display: block;
}

/* Grid Layouts for Tabs */
.tab-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
}

.tab-grid.full {
    grid-template-columns: 1fr;
}

/* Custom form elements matching POD theme */
.u-form-group {
    margin-bottom: 20px;
}

.u-form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
}

.u-input {
    width: 100%;
    padding: 10px 16px; /* Reduced vertical padding slightly */
    height: 45px; /* Fixed height to prevent cutoff */
    line-height: 1.5; /* Proper line height */
    border: 1px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s;
    background-color: #fff; /* Ensure white background for selects */
    appearance: none; /* Remove default browser arrow to style consistently if needed, or keep standard */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    background-size: 1em;
}

/* Fix for standard inputs to match */
input.u-input {
    background-image: none;
    padding: 12px 16px;
}

.u-input:focus {
    border-color: var(--brand-primary);
    outline: none;
    box-shadow: 0 0 0 3px var(--brand-primary-light);
}

.u-checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
    margin-bottom: 15px;
}

.u-checkbox-wrapper input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

/* Attributes Section Styling */
#catAttributes, #subcatAttributes, #childcatAttributes {
    background: #fdfdfd;
    padding: 15px;
    border-radius: 8px;
    border: 1px dashed #e2e8f0;
    margin-top: 10px;
}

/* Variation Section */
.variation-row {
    background: #fff;
    padding: 15px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 10px;
    position: relative;
    display: grid;
    grid-template-columns: repeat(4, 1fr) 40px;
    gap: 15px;
    align-items: end;
}

.remove-var {
    background: #fee2e2;
    color: #ef4444;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

/* Stock Manage Toggle */
.stock-toggle-box {
    background: var(--brand-primary-light);
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid rgba(99, 102, 241, 0.2);
}

.pod-hide { display: none !important; }

/* Pricing Section Helper */
.pricing-helper {
    background: #f1f5f9;
    padding: 20px;
    border-radius: 12px;
    margin-top: 20px;
    border: 1px solid #e2e8f0;
}


/* --- COMPACT FORM LAYOUTS --- */
.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 24px;
}

.info-grid .form-group {
    margin-bottom: 0; /* Remove default margin for grid items */
}

.info-grid .full-width {
    grid-column: span 3;
}

.pricing-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    padding: 24px;
}

/* Pricing Summary Bar */
.pricing-summary {
    justify-content: space-around;
    align-items: center;
}

/* Wizard Navigation Buttons */
.unified-footer {
    border-top: 1px solid var(--border-color);
    padding-top: 20px;
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
}

.wizard-btn {
    padding: 10px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.wizard-next {
    background: var(--brand-primary);
    color: #fff;
}
.wizard-next:hover {
    background: var(--brand-secondary);
    transform: translateX(3px);
}

.wizard-prev {
    background: #e2e8f0;
    color: var(--text-main);
}
.wizard-prev:hover {
    background: #cbd5e1;
}

/* Bottom Sticky Submit Bar */
.submit-section {
    position: sticky;
    bottom: 0;
    background: #fff;
    padding: 15px 30px;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.05); /* Softer shadow */
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 99; /* Ensure it stays above other content but below modals */
    border-top: 1px solid var(--border-color);
    margin: 0 -30px -30px -30px; /* Negative margin to span full width of container */
}

.submit-info {
    font-size: 14px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.submit-btn {
    padding: 12px 28px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.submit-btn.primary {
    background: var(--brand-primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.submit-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(99, 102, 241, 0.4);
}

.submit-btn.secondary {
    background: #f1f5f9;
    color: #334155;
    margin-right: 15px;
}

.submit-btn.secondary:hover {
    background: #e2e8f0;
}
}

.pricing-item {
    text-align: center;
}

.pricing-item .label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-sub);
    margin-bottom: 4px;
}

.pricing-item .value {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-main);
}

.pricing-item.highlight .value {
    color: var(--brand-primary);
    font-size: 24px;
}

/* Section Styling Override */
.pod-section {
    background: #fff;
    border: 1px solid var(--border-color);
    border-radius: 12px;
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.pod-section-header {
    background: var(--surface-hover);
    padding: 16px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 12px;
}

.pod-section-header i {
    font-size: 20px;
    color: var(--brand-primary);
}

.pod-section-header h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-main);
}

.pod-section-header span {
    display: block;
    font-size: 12px;
    color: var(--text-sub);
    margin-top: 2px;
}


/* ============================================
   ENHANCED UX COMPONENTS
   ============================================ */

/* Tooltips & Help Text */
.help-tooltip {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--text-muted);
    color: white;
    font-size: 11px;
    font-weight: 600;
    cursor: help;
    margin-left: 6px;
    transition: all var(--transition-fast);
}

.help-tooltip:hover {
    background: var(--brand-primary);
    transform: scale(1.1);
}

.help-tooltip::before {
    content: '?';
}

.help-tooltip::after {
    content: attr(data-tip);
    position: absolute;
    bottom: calc(100% + 8px);
    left: 50%;
    transform: translateX(-50%) scale(0.9);
    padding: 8px 12px;
    background: var(--text-main);
    color: white;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    border-radius: 8px;
    opacity: 0;
    pointer-events: none;
    transition: all var(--transition-fast);
    box-shadow: var(--shadow-lg);
    z-index: 1000;
}

.help-tooltip:hover::after {
    opacity: 1;
    transform: translateX(-50%) scale(1);
}

/* Form Validation States */
.form-group {
    position: relative;
    margin-bottom: 20px;
}

.form-label {
    display: flex;
    align-items: center;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
    letter-spacing: 0.2px;
}

.form-label .required {
    color: var(--brand-danger);
    margin-left: 4px;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
     background: #fff;
    border: 1.5px solid var(--border-color);
    border-radius: 10px;
    font-size: 14px;
    color: var(--text-main);
    transition: all var(--transition-fast);
    font-family: inherit;
}

.form-input:hover,
.form-select:hover,
.form-textarea:hover {
    border-color: var(--text-sub);
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--brand-primary);
    box-shadow: 0 0 0 3px var(--brand-primary-light);
}

.form-input.is-valid,
.form-select.is-valid,
.form-textarea.is-valid {
    border-color: var(--brand-success);
    background: #f0fdf4;
}

.form-input.is-invalid,
.form-select.is-invalid,
.form-textarea.is-invalid {
    border-color: var(--brand-danger);
    background: #fef2f2;
}

.validation-icon {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    opacity: 0;
    transition: opacity var(--transition-fast);
}

.form-input.is-valid ~ .validation-icon.success,
.form-input.is-invalid ~ .validation-icon.error {
    opacity: 1;
}

.validation-icon.success {
    color: var(--brand-success);
}

.validation-icon.error {
    color: var(--brand-danger);
}

.help-text {
    display: block;
    font-size: 12px;
    color: var(--text-sub);
    margin-top: 6px;
    line-height: 1.4;
}

.error-message {
    display: block;
    font-size: 12px;
    color: var(--brand-danger);
    margin-top: 6px;
    font-weight: 500;
    animation: slideDown var(--transition-fast) ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-4px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading States */
.btn-loading {
    position: relative;
    pointer-events: none;
    color: transparent !important;
}

.btn-loading::after {
    content: '';
    position: absolute;
    left: 50%;
    top: 50%;
    width: 16px;
    height: 16px;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid white;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Success/Error Notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 20px;
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 400px;
    z-index: 9999;
    animation: slideInRight var(--transition-base) ease-out;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.notification.success {
    border-left: 4px solid var(--brand-success);
}

.notification.error {
    border-left: 4px solid var(--brand-danger);
}

.notification-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.notification.success .notification-icon {
    background: var(--brand-success);
    color: white;
}

.notification.error .notification-icon {
    background: var(--brand-danger);
    color: white;
}

/* Progress Bar */
.progress-wrapper {
    margin-top: 24px;
    padding: 20px;
    background: var(--surface-hover);
    border-radius: 12px;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
}

.progress-bar {
    height: 8px;
    background: var(--border-color);
    border-radius: 999px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--brand-primary), var(--brand-primary-hover));
    border-radius: 999px;
    transition: width 0.3s ease-out;
    box-shadow: 0 0 8px rgba(99, 102, 241, 0.3);
}

/* Button Enhancements */
.btn-primary {
    background: linear-gradient(135deg, var(--brand-primary), var(--brand-primary-hover));
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all var(--transition-fast);
    box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.3);
    letter-spacing: 0.3px;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 12px -1px rgba(99, 102, 241, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-sub);
}

.empty-state-icon {
    font-size: 48px;
    color: var(--border-color);
    margin-bottom: 16px;
}

.empty-state-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 8px;
}

.empty-state-text {
    font-size: 14px;
    line-height: 1.5;
}

/* Card Hover Effects */
.pod-section {
    transition: all var(--transition-base);
}

.pod-section:hover {
    transform: translateY(-2px);
}

/* Accessibility Improvements */
.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Focus Visible */
*:focus-visible {
    outline: 2px solid var(--brand-primary);
    outline-offset: 2px;
}

button:focus-visible,
a:focus-visible {
    outline: 2px solid var(--brand-primary);
    outline-offset: 2px;
}



/* Fix for Select Dropdowns */
select.config-input,
.form-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 40px !important;
    line-height: normal; /* Ensure text isn't cut off */
    display: block;
}

select.config-input:focus,
.form-select:focus {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236366f1' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
}

/* Revert custom appearance for Font Selector to avoid artifacts with optgroups */
#text-font {
    appearance: auto !important;
    -webkit-appearance: auto !important;
    background-image: none !important;
    padding-right: 14px !important;
}

/* Product Type Card Styles */
.product-type-card {
    position: relative;
    overflow: hidden;
}

.product-type-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.product-type-card.active {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
}

.product-type-card:active {
    transform: translateY(0);
}


/* End of Product Type Card Styles */

/* Progress Bar Overlay */
.progress-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.9);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.progress-card {
    background: white;
    padding: 30px;
    border-radius: 16px;
    width: 90%;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.progress-icon {
    font-size: 40px;
    color: var(--brand-primary);
    margin-bottom: 20px;
    animation: bounce 2s infinite;
}

.progress-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 8px;
}

.progress-desc {
    font-size: 14px;
    color: var(--text-sub);
    margin-bottom: 24px;
}

.progress-track {
    height: 8px;
    background: #e2e8f0;
    border-radius: 99px;
    overflow: hidden;
    margin-bottom: 12px;
}

.progress-fill-anim {
    height: 100%;
    background: linear-gradient(90deg, var(--brand-primary), #818cf8);
    width: 0%;
    transition: width 0.3s ease-out;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}
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
                        <div class="editor-sidebar-right" style="display:flex; flex-direction:column; padding:0; background:#fff; border-left:1px solid #e2e8f0; width:320px;">
                            
                            <!-- TABS HEADER -->
                            <div class="sidebar-tabs" style="display:flex; border-bottom:1px solid #e2e8f0;">
                                <div class="sidebar-tab active" data-target="tab-product" style="flex:1; padding:15px 0; text-align:center; cursor:pointer; font-weight:600; color:var(--text-color); border-bottom:2px solid var(--brand-primary); transition:all 0.2s;">
                                    <i class="fas fa-tshirt"></i> Product
                                </div>
                                <div class="sidebar-tab" data-target="tab-text" style="flex:1; padding:15px 0; text-align:center; cursor:pointer; font-weight:600; color:#94a3b8; border-bottom:2px solid transparent; transition:all 0.2s;">
                                    <i class="fas fa-font"></i> Text
                                </div>
                                <div class="sidebar-tab" data-target="tab-uploads" style="flex:1; padding:15px 0; text-align:center; cursor:pointer; font-weight:600; color:#94a3b8; border-bottom:2px solid transparent; transition:all 0.2s;">
                                    <i class="fas fa-cloud-upload-alt"></i> Uploads
                                </div>
                            </div>
                            
                            <!-- TAB CONTENT CONTAINER -->
                            <div class="sidebar-content" style="flex:1; overflow-y:auto; padding:20px;">
                                
                                <!-- 1. PRODUCT TAB -->
                                <div id="tab-product" class="tab-panel">
                                     <div class="config-group">
                                        <label class="form-label" style="font-size:12px; font-weight:bold; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">Product Type</label>
                                        <div class="product-type-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                                            <button type="button" class="product-type-card active" data-product="tshirt" style="padding:16px; border:2px solid var(--brand-primary); border-radius:12px; background:#fff; cursor:pointer; text-align:center; transition:all 0.2s; display:flex; flex-direction:column; align-items:center; gap:8px;">
                                                <i class="fas fa-tshirt" style="font-size:28px; color:var(--brand-primary);"></i>
                                                <span style="font-weight:600; font-size:13px; color:var(--text-main);">T-Shirt</span>
                                            </button>
                                            <button type="button" class="product-type-card" data-product="hoodie" style="padding:16px; border:2px solid var(--border-color); border-radius:12px; background:#fff; cursor:pointer; text-align:center; transition:all 0.2s; display:flex; flex-direction:column; align-items:center; gap:8px;">
                                                <i class="fas fa-user-ninja" style="font-size:28px; color:var(--text-sub);"></i>
                                                <span style="font-weight:600; font-size:13px; color:var(--text-main);">Hoodie</span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="config-group">
                                        <label class="form-label" style="font-size:12px; font-weight:bold; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">Product Colors</label>
                                        <div class="color-options" style="justify-content:flex-start; gap:10px; flex-wrap:wrap;">
                                            <div class="color-dot active" style="background:#ffffff; border:1px solid #ddd;" data-color="white" data-hex="#ffffff"></div>
                                            <div class="color-dot" style="background:#0f172a" data-color="black" data-hex="#0f172a"></div>
                                            <div class="color-dot" style="background:#ef4444" data-color="red" data-hex="#ef4444"></div>
                                            <div class="color-dot" style="background:#3b82f6" data-color="blue" data-hex="#3b82f6"></div>
                                            <div class="color-dot" style="background:#10b981" data-color="green" data-hex="#10b981"></div>
                                            <div class="color-dot" style="background:#f59e0b" data-color="yellow" data-hex="#f59e0b"></div>
                                            <div class="color-dot" style="background:#8b5cf6" data-color="purple" data-hex="#8b5cf6"></div>
                                            <div class="color-dot" style="background:#ec4899" data-color="pink" data-hex="#ec4899"></div>
                                            <div class="color-dot" style="background:#64748b" data-color="grey" data-hex="#64748b"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 2. TEXT TAB -->
                                <div id="tab-text" class="tab-panel pod-hide">
                                     <div class="config-group">
                                        <label class="form-label" style="font-size:12px; font-weight:bold; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">Text Content</label>
                                        <textarea id="text-input" class="config-input" rows="3" placeholder="Enter text here..."></textarea>
                                    </div>
                                    
                                     <div class="config-group">
                                         <label class="form-label" style="font-size:12px; font-weight:bold; color:#64748b; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:10px;">Typography</label>
                                         <div class="font-picker-wrapper" style="position:relative;">
                                            <div style="display:flex; gap:8px; margin-bottom:8px;">
                                                <input type="text" id="font-search-input" class="config-input" placeholder="Search Font..." autocomplete="off">
                                                <button type="button" id="btn-filter-handwriting" class="config-input" style="width:auto; padding:0 10px; color:#555; background:#f1f5f9; border:none; cursor:pointer;" title="Script Fonts"><i class="fas fa-pen-fancy"></i></button>
                                            </div>
                                            <div id="font-results-dropdown" class="pod-hide" style="position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid var(--border-color); border-radius:8px; max-height:220px; overflow-y:auto; z-index:100; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);"></div>
                                            <input type="hidden" id="text-font" value="Arial">
                                         </div>
                                         <a href="javascript:;" id="btn-toggle-custom-font" style="display:block; text-align:right; font-size:11px; margin-top:5px; color:var(--brand-primary); text-decoration:none;">Use Custom Font</a>
                                         <input type="text" id="custom-font-input" class="config-input pod-hide" placeholder="Enter font name..." style="margin-top:8px;">
                                     </div>
                                     
                                     <div class="config-group">
                                         <div style="display:flex; gap:10px; align-items:center;">
                                             <div style="flex:1;">
                                                 <label style="font-size:11px; color:#64748b;">Size</label>
                                                 <input type="number" id="text-size" class="config-input" value="32" title="Font Size">
                                             </div>
                                             <div style="flex:0 0 50px;">
                                                  <label style="font-size:11px; color:#64748b;">Color</label>
                                                  <input type="color" id="text-color" value="#000000" style="height:42px; width:100%; padding:0; border:none; border-radius:6px; cursor:pointer;">
                                             </div>
                                         </div>
                                     </div>
                                     
                                     <div class="config-group">
                                          <div style="display:flex; gap:8px;">
                                              <button type="button" id="text-bold" class="config-input" style="flex:1; font-weight:bold;">Bold</button>
                                              <button type="button" id="text-italic" class="config-input" style="flex:1; font-style:italic;">Italic</button>
                                          </div>
                                     </div>
                                     
                                     <div class="config-group" style="margin-top:20px; padding-top:20px; border-top:1px solid #f1f5f9;">
                                         <div style="display:flex; gap:10px;">
                                            <button type="button" class="config-input" id="add-text-btn" style="background:#22c55e; color:#fff; border:none; cursor:pointer; font-weight:600; flex:1; padding:12px;"><i class="fas fa-plus"></i> Add New</button>
                                            <button type="button" class="config-input" id="update-text-btn" style="background:var(--brand-primary); color:#fff; border:none; cursor:pointer; font-weight:600; flex:1; padding:12px;"><i class="fas fa-sync-alt"></i> Update</button>
                                         </div>
                                     </div>
                                </div>
                                
                                <!-- 3. UPLOADS TAB -->
                                <div id="tab-uploads" class="tab-panel pod-hide">
                                    <div class="config-group" style="text-align:center; padding:40px 0; border:2px dashed #e2e8f0; border-radius:12px; cursor:pointer; transition:all 0.2s; background:#f8fafc;" onclick="document.getElementById('design-upload-input').click()" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                        <i class="fas fa-cloud-upload-alt" style="font-size:32px; color:#94a3b8; margin-bottom:15px; display:block;"></i>
                                        <p style="margin:0; font-weight:600; color:#475569; margin-bottom:5px;">Click to Upload Image</p>
                                        <span style="font-size:12px; color:#94a3b8;">Supports PNG, JPG</span>
                                    </div>
                                    <input type="file" id="design-upload-input" accept="image/*" style="display:none;">
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================================
                 UNIFIED PRODUCT CONFIGURATION (TABS)
                 ============================================ --}}
            <div class="pod-section unified-details">
                <div class="pod-section-header details">
                    <i class="fas fa-cog"></i>
                    <div>
                        <h3>{{ __('Product Configuration') }}</h3>
                        <span>Define categories, variations, pricing, and SEO settings</span>
                    </div>
                </div>
                <div class="pod-section-body" style="padding:0;">
                    
                    <!-- TAB NAVIGATION -->
                    <div class="unified-tabs">
                        <div class="unified-tab active" data-target="info-general"><i class="fas fa-info-circle"></i> General</div>
                        <div class="unified-tab" data-target="info-attributes"><i class="fas fa-list-ul"></i> Attributes</div>
                        <div class="unified-tab" data-target="info-inventory"><i class="fas fa-boxes"></i> Inventory</div>
                        <div class="unified-tab" data-target="info-pricing"><i class="fas fa-tag"></i> Pricing & POD</div>
                        <div class="unified-tab" data-target="info-advanced"><i class="fas fa-tools"></i> Advanced</div>
                        <div class="unified-tab" data-target="info-seo"><i class="fas fa-search"></i> SEO</div>
                    </div>

                    <div class="unified-tab-content">
                        
                        <!-- 1. GENERAL TAB -->
                        <div id="info-general" class="unified-panel active">
                            <div class="tab-grid">
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Select Language') }}*</label>
                                    <select name="language_id" class="u-input" required>
                                        @foreach(DB::table('languages')->get() as $ldata)
                                            <option value="{{ $ldata->id }}">{{ $ldata->language }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Product Name') }}*</label>
                                    <input type="text" name="name" class="u-input" placeholder="e.g. Summer Vintage Tee" required>
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Product SKU') }}*</label>
                                    <input type="text" name="sku" class="u-input" value="{{ Str::random(3).substr(time(), 6,8).Str::random(3) }}" required>
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Category') }}*</label>
                                    <select id="cat" name="category_id" class="u-input" required>
                                        <option value="">{{ __('Select Category') }}</option>
                                        @foreach($cats as $cat)
                                            <option data-href="{{ route('vendor-subcat-load',$cat->id) }}" value="{{ $cat->id }}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="u-form-group" id="subcat-section" style="display:none;">
                                    <label class="u-form-label">{{ __('Sub Category') }}</label>
                                    <select id="subcat" name="subcategory_id" class="u-input" disabled>
                                        <option value="">{{ __('Select Sub Category') }}</option>
                                    </select>
                                </div>
                                <div class="u-form-group" id="childcat-section" style="display:none;">
                                    <label class="u-form-label">{{ __('Child Category') }}</label>
                                    <select id="childcat" name="childcategory_id" class="u-input" disabled>
                                        <option value="">{{ __('Select Child Category') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="tab-grid" style="margin-top:20px;">
                                <div>
                                    <label class="u-form-label">{{ __('Tags') }}*</label>
                                    <ul id="tags" class="myTags"></ul>
                                </div>
                                <div>
                                    <label class="u-form-label">{{ __('Condition & Status') }}</label>
                                    <div class="u-checkbox-wrapper">
                                        <input type="checkbox" name="product_condition_check" id="cond_check" value="1">
                                        <label for="cond_check">{{ __('Allow Product Condition') }}</label>
                                    </div>
                                    <div id="cond_box" class="pod-hide" style="margin-bottom:15px;">
                                        <select name="product_condition" class="u-input">
                                            <option value="2">{{ __('New') }}</option>
                                            <option value="1">{{ __('Used') }}</option>
                                        </select>
                                    </div>
                                    <div class="u-checkbox-wrapper">
                                        <input type="checkbox" name="preordered_check" id="pre_check" value="1">
                                        <label for="pre_check">{{ __('Allow Product Preorder') }}</label>
                                    </div>
                                    <div class="u-checkbox-wrapper">
                                        <input type="checkbox" name="minimum_qty_check" id="minqty_check" value="1">
                                        <label for="minqty_check">{{ __('Allow Minimum Order Qty') }}</label>
                                    </div>
                                    <div class="u-checkbox-wrapper">
                                        <input type="checkbox" name="shipping_time_check" id="shiptime_check" value="1">
                                        <label for="shiptime_check">{{ __('Allow Estimated Shipping Time') }}</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Gallery Uploader -->
                            <div class="u-form-group" style="margin-top:20px;">
                                <label class="u-form-label">{{ __('Additional Gallery Images') }} <small>(Optional)</small></label>
                                <div id="gallery-dropzone" style="border:2px dashed var(--border-color); border-radius:10px; padding:20px; text-align:center; cursor:pointer; background:#f8fafc; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--brand-primary)';this.style.background='#f1f5f9'" onmouseout="this.style.borderColor='var(--border-color)';this.style.background='#f8fafc'">
                                    <i class="fas fa-images" style="font-size:32px; color:#cbd5e1; margin-bottom:10px;"></i>
                                    <p style="margin:0; font-weight:600; color:#64748b;">Click to Add Images</p>
                                    <span style="font-size:12px; color:#94a3b8;">or drag and drop here</span>
                                </div>
                                <div id="gallery-preview" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;"></div>
                            </div>
                            
                            <div class="unified-footer">
                                <div></div> <!-- Spacer -->
                                <button type="button" class="wizard-btn wizard-next" data-next="info-attributes">Next: Attributes <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- 2. ATTRIBUTES TAB -->
                        <div id="info-attributes" class="unified-panel">
                            <div class="alert alert-info" style="border-radius:10px;">
                                <i class="fas fa-info-circle"></i> {{ __('Attributes will be loaded automatically based on your selected categories.') }}
                            </div>
                            <div id="catAttributes"></div>
                            <div id="subcatAttributes"></div>
                            <div id="childcatAttributes"></div>
                            
                            <div class="unified-footer">
                                <button type="button" class="wizard-btn wizard-prev" data-prev="info-general"><i class="fas fa-arrow-left"></i> Back</button>
                                <button type="button" class="wizard-btn wizard-next" data-next="info-inventory">Next: Inventory <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- 3. INVENTORY TAB -->
                        <div id="info-inventory" class="unified-panel">
                            <div class="stock-toggle-box">
                                <div class="u-checkbox-wrapper" style="margin-bottom:0;">
                                    <input type="checkbox" name="stock_check" id="manage_stock_check" value="1">
                                    <label for="manage_stock_check" style="font-weight:700; color:var(--brand-primary);">{{ __('Manage Stock & Variations') }}</label>
                                </div>
                                <p style="font-size:12px; margin: 5px 0 0 28px; color:var(--text-sub);">Enable to define specific sizes, colors, and stock levels for each variation.</p>
                            </div>

                            <div id="default_stock_section">
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Global Stock Quantity') }}</label>
                                    <input type="number" name="stock" class="u-input" placeholder="e.g 999" value="999" min="0">
                                    <small class="text-muted">{{ __('Leave empty for unlimited availability') }}</small>
                                </div>
                                <div class="tab-grid" style="margin-top:20px;">
                                    <div class="u-form-group">
                                        <label class="u-form-label">{{ __('Supported Colors') }} (Simple Selection)</label>
                                        <div class="u-checkbox-wrapper">
                                            <input type="checkbox" name="color_check" id="color_all_check" value="1">
                                            <label for="color_all_check">{{ __('Enable Colors') }}</label>
                                        </div>
                                        <div id="color_all_box" class="pod-hide">
                                            <div id="color-section-standard">
                                                <div class="input-group colorpicker-component cp" style="margin-bottom:10px;">
                                                    <input type="text" name="color_all[]" class="u-input cp tcolor" value="#ffffff"/>
                                                    <span class="input-group-addon"><i></i></span>
                                                </div>
                                            </div>
                                            <a href="javascript:;" id="color-btn-standard" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Add More</a>
                                        </div>
                                    </div>
                                    <div class="u-form-group">
                                        <label class="u-form-label">{{ __('Supported Sizes') }} (Simple Selection)</label>
                                        <div class="u-checkbox-wrapper">
                                            <input type="checkbox" name="size_check" id="size_all_check" value="1">
                                            <label for="size_all_check">{{ __('Enable Sizes') }}</label>
                                        </div>
                                        <div id="size_all_box" class="pod-hide">
                                            <div id="size-section-standard">
                                                <input type="text" name="size_all[]" class="u-input" placeholder="e.g. XL" style="margin-bottom:10px;">
                                            </div>
                                            <a href="javascript:;" id="size-btn-standard" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Add More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="variation_section" class="pod-hide">
                                <label class="u-form-label">{{ __('Product Variations') }}</label>
                                <div id="variation-container">
                                    <!-- Variation rows injected here -->
                                </div>
                                <a href="javascript:;" id="add-variation-btn" class="btn btn-primary mt-3"><i class="fas fa-plus"></i> {{ __('Add Variation Record') }}</a>
                            </div>

                            <div class="unified-footer">
                                <button type="button" class="wizard-btn wizard-prev" data-prev="info-attributes"><i class="fas fa-arrow-left"></i> Back</button>
                                <button type="button" class="wizard-btn wizard-next" data-next="info-pricing">Next: Pricing & POD <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- 4. PRICING & POD TAB -->
                        <div id="info-pricing" class="unified-panel">
                            <div class="tab-grid">
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Retail Price') }} ({{ $sign->name }})*</label>
                                    <input type="number" name="price" id="final-price-input" class="u-input" placeholder="0.00" step="0.01" required>
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Previous Price') }} (Optional)</label>
                                    <input type="number" name="previous_price" class="u-input" placeholder="0.00" step="0.01">
                                </div>
                            </div>

                            <div class="pricing-helper">
                                <h5 style="font-size:14px; margin-bottom:15px; font-weight:700;"><i class="fas fa-calculator"></i> {{ __('POD Profit Calculator') }}</h5>
                                <div class="tab-grid" style="gap:15px;">
                                    <div class="u-form-group">
                                        <label class="u-form-label">Base Garment</label>
                                        <select id="clothing-quality" class="u-input">
                                            <option value="basic" data-price="8">Basic Cotton ($8)</option>
                                            <option value="standard" data-price="12" selected>Standard ($12)</option>
                                            <option value="premium" data-price="18">Premium ($18)</option>
                                        </select>
                                    </div>
                                    <div class="u-form-group">
                                        <label class="u-form-label">Print Quality</label>
                                        <select id="print-quality" name="quality_tier" class="u-input">
                                            <option value="standard" data-price="0">Standard (+$0)</option>
                                            <option value="premium" data-price="5">Premium (+$5)</option>
                                        </select>
                                    </div>
                                    <div class="u-form-group">
                                        <label class="u-form-label">Profit Margin</label>
                                        <div style="display:flex;align-items:center;gap:12px;height:45px;background:#fff;padding:0 12px;border-radius:10px;border:1px solid var(--border-color);">
                                            <input type="range" id="profit-margin" min="10" max="200" value="50" style="flex:1;cursor:pointer;">
                                            <span id="margin-display" style="font-weight:700;color:var(--brand-primary);min-width:45px;text-align:right;">50%</span>
                                        </div>
                                    </div>
                                    <div class="u-form-group" style="display:flex; flex-direction:column; justify-content:center; background:#fff; padding:10px; border-radius:10px; border:1px solid #e2e8f0;">
                                        <label class="u-form-label" style="margin:0; font-size:11px;">Calculated Retail</label>
                                        <div id="final-price-display" style="font-size:20px; font-weight:800; color:var(--brand-primary);">$18.00</div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-grid" style="margin-top:20px;">
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Production Cap') }}</label>
                                    <input type="number" name="production_cap" class="u-input" value="1" min="1">
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Print Time (Mins)') }}</label>
                                    <input type="number" name="print_time_minutes" class="u-input" value="30" min="15">
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Shipping Method') }}</label>
                                    <select name="shipping_id" class="u-input">
                                        <option value="">{{ __('Default Shipping') }}</option>
                                        @foreach($shippings as $s)
                                            <option value="{{ $s->id }}">{{ $s->title }} (+{{ $sign->sign }}{{ $s->price }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Packaging') }}</label>
                                    <select name="package_id" class="u-input">
                                        <option value="">{{ __('Default Packaging') }}</option>
                                        @foreach($packages as $p)
                                            <option value="{{ $p->id }}">{{ $p->title }} (+{{ $sign->sign }}{{ $p->price }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="base_cost" id="base-cost-input" value="12.00">
                            <input type="hidden" name="price" id="real-price-input" value="">
                        
                            <div class="unified-footer">
                                <button type="button" class="wizard-btn wizard-prev" data-prev="info-inventory"><i class="fas fa-arrow-left"></i> Back</button>
                                <button type="button" class="wizard-btn wizard-next" data-next="info-advanced">Next: Advanced <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

<script>
    // Inline script for Profit Calculator (placed here for proximity to elements)
    $(document).ready(function() {
        function updateProfit() {
            const baseCost = parseFloat($('#base-cost-input').val()) || 12.00;
            const margin = parseInt($('#profit-margin').val()) || 50;
            
            // Calculate Price: Cost + (Cost * Margin / 100)
            const price = baseCost + (baseCost * (margin / 100));
            
            $('#margin-display').text(margin + '%');
            $('#final-price-display').text('$' + price.toFixed(2));
            $('#real-price-input').val(price.toFixed(2));
        }
        
        $('#profit-margin').on('input change', updateProfit);
        
        // Initial call
        setTimeout(updateProfit, 500); 
    });
</script>

                        <!-- 5. ADVANCED TAB -->
                        <div id="info-advanced" class="unified-panel">
                            <div class="u-form-group">
                                <label class="u-form-label">{{ __('Product Description') }}*</label>
                                <textarea name="details" class="nic-edit-p" style="width:100%; height:200px;"></textarea>
                            </div>
                            <div class="u-form-group">
                                <label class="u-form-label">{{ __('Return Policy') }}</label>
                                <textarea name="policy" class="nic-edit-p" style="width:100%; height:100px;"></textarea>
                            </div>
                            <div class="tab-grid">
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Youtube Video URL') }}</label>
                                    <input type="text" name="youtube" class="u-input" placeholder="https://youtube.com/watch?v=...">
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Measurement Unit') }}</label>
                                    <select name="product_measure" class="u-input">
                                        <option value="">{{ __('None') }}</option>
                                        <option value="Gram">{{ __('Gram') }}</option>
                                        <option value="Kilogram">{{ __('Kilogram') }}</option>
                                        <option value="Litre">{{ __('Litre') }}</option>
                                        <option value="Pound">{{ __('Pound') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="u-form-group">
                                <div class="u-checkbox-wrapper">
                                    <input type="checkbox" name="whole_check" id="w_check" value="1">
                                    <label for="w_check">{{ __('Allow Whole Sell') }}</label>
                                </div>
                                <div id="whole_box" class="pod-hide">
                                    <div id="whole-section">
                                        <div class="row mb-2">
                                            <div class="col-6"><input type="number" name="whole_sell_qty[]" class="u-input" placeholder="Min Qty"></div>
                                            <div class="col-6"><input type="number" name="whole_sell_discount[]" class="u-input" placeholder="Discount %"></div>
                                        </div>
                                    </div>
                                    <a href="javascript:;" id="whole-btn" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Add Field</a>
                                </div>
                            </div>

                            <div class="unified-footer">
                                <button type="button" class="wizard-btn wizard-prev" data-prev="info-pricing"><i class="fas fa-arrow-left"></i> Back</button>
                                <button type="button" class="wizard-btn wizard-next" data-next="info-seo">Next: SEO <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- 6. SEO TAB -->
                        <div id="info-seo" class="unified-panel">
                            <div class="u-checkbox-wrapper" style="margin-bottom:20px;">
                                <input type="checkbox" name="seo_check" id="s_check" value="1">
                                <label for="s_check" style="font-weight:700;">{{ __('Enable SEO Optimization') }}</label>
                            </div>
                            <div id="seo_box" class="pod-hide">
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Meta Tags') }}</label>
                                    <ul id="metatags" class="myTags"></ul>
                                </div>
                                <div class="u-form-group">
                                    <label class="u-form-label">{{ __('Meta Description') }}</label>
                                    <textarea name="meta_description" class="u-input" rows="4" placeholder="Brief summary for search engines..."></textarea>
                                </div>
                            </div>

                            <div class="unified-footer">
                                <button type="button" class="wizard-btn wizard-prev" data-prev="info-advanced"><i class="fas fa-arrow-left"></i> Back</button>
                                <div><small class="text-muted">Review your details and click Create</small></div>
                            </div>
                        </div>

                    </div> <!-- End Tab Content -->

                    <!-- Hidden Assets for Submission -->
                    <input type="hidden" name="photo" id="mockup-image-hidden">
                    <input type="hidden" name="print_image" id="print-image-hidden">
                    <input type="hidden" name="design_data" id="design-json-hidden">
                    <input type="hidden" name="is_pod" id="is-pod-hidden" value="1">
                    <input type="file" name="gallery[]" id="gallery-hidden-input" multiple style="display:none;">

                </div>
            </div>

            {{-- ============================================
                 SUBMIT BAR
                 ============================================ --}}
            <div class="submit-section">
                <div class="submit-info">
                    <i class="fas fa-check-circle" style="color:var(--brand-success)"></i>
                    <span>All changes are automatically saved to draft</span>
                </div>
                <div class="submit-actions">
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

    <!-- Progress Overlay -->
    <div id="publish-progress" class="progress-overlay">
        <div class="progress-card">
            <div class="progress-icon"><i class="fas fa-rocket"></i></div>
            <div class="progress-title">Publishing Design</div>
            <div class="progress-desc" id="progress-text">Initializing...</div>
            <div class="progress-track">
                <div class="progress-fill-anim" id="progress-bar-fill"></div>
            </div>
            <div style="font-size:12px; color:#94a3b8;">Please do not close this window</div>
        </div>
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
                    printArea: { x: 185, y: 130, width: 230, height: 350 }
                },
                'tshirt_black': {
                    name: 'Black T-Shirt',
                    frontUrl: "{{ asset('assets/images/mockups/tshirt/front.svg') }}",
                    backUrl: "{{ asset('assets/images/mockups/tshirt/back.svg') }}",
                    leftUrl: "{{ asset('assets/images/mockups/tshirt/left.svg') }}",
                    rightUrl: "{{ asset('assets/images/mockups/tshirt/right.svg') }}",
                    productType: 'tshirt',
                    color: 'black',
                    printArea: { x: 185, y: 130, width: 230, height: 350 }
                },
                'hoodie_white': {
                    name: 'White Hoodie',
                    frontUrl: "{{ asset('assets/images/mockups/hoodie/front.png') }}",
                    backUrl: "{{ asset('assets/images/mockups/hoodie/back.png') }}",
                    leftUrl: "{{ asset('assets/images/mockups/hoodie/front.png') }}",
                    rightUrl: "{{ asset('assets/images/mockups/hoodie/back.png') }}",
                    productType: 'hoodie',
                    color: 'white',
                    printArea: { x: 380, y: 350, width: 600, height: 600 }
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
            
            window.isSwitchingTemplate = false;
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
        
        /* --- FONT SEARCH LOGIC --- */
        const googleFonts = [
            "Roboto", "Open Sans", "Lato", "Montserrat", "Oswald", "Source Sans Pro", "Slabo 27px", "Raleway", "PT Sans", 
            "Merriweather", "Nunito", "Prompt", "Work Sans", "Bebas Neue", "Anton", "Dancing Script", "Pacifico", "Poppins", 
            "Inter", "Noto Sans", "Playfair Display", "Ubuntu", "Arimo", "Lora", "Rubik", "Mukta", "Kanit", "Barlow", 
            "Quicksand", "Inconsolata", "Titillium Web", "Josefin Sans", "Lobster", "Abril Fatface", 
            "Comfortaa", "Exo 2", "Fjalla One", "Crimson Text", "Hind", "Bitter", "Cabin", "Oxygen", "Dosis", "Righteous", 
            "Fira Sans", "Varela Round", "Bree Serif", "Shadows Into Light", "Indie Flower", "Amatic SC", 
            "Courgette", "Satisfy", "Great Vibes", "Sacramento", "Permanent Marker", "Creepster", "Bangers", "Orbitron",
            "Gloria Hallelujah", "Kaushan Script", "Cookie", "Parisienne", "Yellowtail", "Caveat", "Covered By Your Grace"
        ];
        
        const handwritingFonts = [
             "Dancing Script", "Pacifico", "Shadows Into Light", "Indie Flower", "Amatic SC", 
             "Courgette", "Satisfy", "Great Vibes", "Sacramento", "Permanent Marker", "Creepster", 
             "Lobster", "Abril Fatface", "Gloria Hallelujah", "Kaushan Script", "Cookie", "Parisienne", "Yellowtail",
             "Caveat", "Covered By Your Grace"
        ];
        
        let fontObserver;

        function renderFontDropdown(list) {
            const $dropdown = $('#font-results-dropdown');
            $dropdown.removeClass('pod-hide').html('');
            
            if(list.length === 0) {
                $dropdown.append('<div style="padding:10px;text-align:center;color:#999;">No fonts found</div>');
                return; 
            }
            
            // Disconnect previous observer
            if(fontObserver) fontObserver.disconnect();
            
            fontObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                   if(entry.isIntersecting) {
                       const el = entry.target;
                       const fontName = el.getAttribute('data-font');
                       // Load Font optimized for preview (text=FontName is efficient but complicates URL)
                       // We will load the full font for simplicity as the user likely picks one
                       const linkId = 'font-preview-' + fontName.replace(/\s+/g, '-').toLowerCase();
                       if(!document.getElementById(linkId)) {
                            const link = document.createElement('link');
                            link.id = linkId;
                            link.rel = 'stylesheet';
                            // We can use &text=... to optimize but some fonts have ligatures.
                            // Let's load full font.
                            link.href = 'https://fonts.googleapis.com/css2?family=' + fontName.replace(/\s+/g, '+') + '&display=swap';
                            document.head.appendChild(link);
                       }
                       el.style.fontFamily = `"${fontName}", sans-serif`;
                       observer.unobserve(el);
                   } 
                });
            }, { root: document.querySelector('#font-results-dropdown'), rootMargin: '50px' });
            
            list.forEach(font => {
                const $item = $('<div class="font-result-item" data-font="' + font + '" style="padding:8px 12px;cursor:pointer;border-bottom:1px solid #f1f5f9; font-size:18px;">' + font + '</div>');
                $item.hover(function(){ $(this).css('background','#f8fafc') }, function(){ $(this).css('background','#fff') });
                
                $item.on('click', function() {
                    selectGoogleFont(font);
                });
                $dropdown.append($item);
                fontObserver.observe($item[0]);
            });
        }

        // Search Handler
        $('#font-search-input').on('keyup focus', function() {
            const query = $(this).val().toLowerCase();
            const filtered = googleFonts.filter(f => f.toLowerCase().includes(query));
            renderFontDropdown(filtered);
        });
        
        // Handwriting Filter Handler
        $('#btn-filter-handwriting').on('click', function() {
             renderFontDropdown(handwritingFonts);
             $('#font-search-input').focus(); // Focus input to keep dropdown logic active if needed
        });

        function selectGoogleFont(fontName) {
            $('#font-search-input').val(fontName);
            $('#text-font').val(fontName);
            $('#font-results-dropdown').addClass('pod-hide');
            
            // Load Font Dynamically
            const linkId = 'font-link-' + fontName.replace(/\s+/g, '-').toLowerCase();
            if(!document.getElementById(linkId)) {
                const link = document.createElement('link');
                link.id = linkId;
                link.rel = 'stylesheet';
                link.href = 'https://fonts.googleapis.com/css2?family=' + fontName.replace(/\s+/g, '+') + '&display=swap';
                document.head.appendChild(link);
            }
        }

        // Hide dropdown on click outside
        $(document).on('click', function(e) {
            if(!$(e.target).closest('.font-picker-wrapper').length) {
                $('#font-results-dropdown').addClass('pod-hide');
            }
        });

        // Toggle Custom Font Input (Updated)
        $('#btn-toggle-custom-font').on('click', function(e) {
            e.preventDefault();
            const $customInput = $('#custom-font-input');
            const $pickerWrapper = $('.font-picker-wrapper');
            const $btn = $(this);
            
            if ($customInput.hasClass('pod-hide')) {
                // Show Custom Input, Hide Picker
                $customInput.removeClass('pod-hide').focus();
                $pickerWrapper.addClass('pod-hide');
                $btn.text('Use Google Fonts');
            } else {
                // Show Picker, Hide Custom Input
                $customInput.addClass('pod-hide');
                $pickerWrapper.removeClass('pod-hide');
                $btn.text('Use Custom Font');
            }
        });

        function getTextParams() {
              const text = $('#text-input').val() || 'New Text';
              let font = $('#text-font').val(); 
              if (!$('#custom-font-input').hasClass('pod-hide') && $('#custom-font-input').val().trim() !== '') {
                  font = $('#custom-font-input').val().trim();
              } else {
                   if($('#font-search-input').val()) {
                       font = $('#font-search-input').val();
                       selectGoogleFont(font); 
                   }
              }
              const size = parseInt($('#text-size').val());
              const color = $('#text-color').val();
              const isBold = $('#text-bold').hasClass('active');
              const isItalic = $('#text-italic').hasClass('active');
              
              return { text, font, size, color, isBold, isItalic };
        }

        // Add Text Button (ALWAYS NEW)
        $('#add-text-btn').on('click', function() {
              const p = getTextParams();
              if(mockupPreview) {
                  mockupPreview.addTextLayer({
                      text: p.text,
                      fontFamily: p.font,
                      fontSize: p.size,
                      color: p.color,
                      bold: p.isBold,
                      italic: p.isItalic
                  });
              }
        });
        
        // Update Button (ALWAYS UPDATE)
        $('#update-text-btn').on('click', function() {
              const p = getTextParams();
              if(mockupPreview) {
                   const layers = mockupPreview.getCurrentLayers();
                   const idx = mockupPreview.selectedLayerIndex;
                   if (idx >= 0 && idx < layers.length && layers[idx].type === 'text') {
                       mockupPreview.updateSelectedLayer({
                          text: p.text,
                          fontFamily: p.font,
                          fontSize: p.size,
                          color: p.color,
                          bold: p.isBold,
                          italic: p.isItalic
                       });
                   }
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
        
        /* --- TAB SWITCHING --- */
        $('.sidebar-tab').on('click', function() {
            $('.sidebar-tab').removeClass('active').css({'color':'#94a3b8', 'border-bottom-color':'transparent'});
            $('.tab-panel').addClass('pod-hide');
            
            $(this).addClass('active').css({'color':'var(--text-color)', 'border-bottom-color':'var(--brand-primary)'});
            const target = $(this).data('target');
            $('#' + target).removeClass('pod-hide');
        });

        // Hook into MockupPreview to auto-switch to Text Tab
        setTimeout(() => {
            if(typeof MockupPreview !== 'undefined') {
                 MockupPreview.prototype.updateTextPanel = function() {
                     if(this.selectedLayerIndex >= 0) {
                         const layer = this.getCurrentLayers()[this.selectedLayerIndex];
                         if(layer && layer.type === 'text') {
                             $('[data-target="tab-text"]').click();
                             
                             $('#text-input').val(layer.text);
                             $('#text-size').val(layer.fontSize);
                             $('#text-color').val(layer.color);
                             $('#text-font').val(layer.fontFamily);
                             $('#font-search-input').val(layer.fontFamily);
                             
                             if(layer.bold) $('#text-bold').addClass('active').css('background','#e2e8f0'); 
                             else $('#text-bold').removeClass('active').css('background','white');
                             
                             if(layer.italic) $('#text-italic').addClass('active').css('background','#e2e8f0'); 
                             else $('#text-italic').removeClass('active').css('background','white');
                         }
                     }
                 };
            }
        }, 500);

        // --- CONFIGURATION TOOLS ---

        // Product Type Card Switcher
        $('.product-type-card').on('click', function() {
            if (window.isSwitchingTemplate) return;
            
            const productType = $(this).data('product');
            
            // Update active state
            $('.product-type-card').removeClass('active').css({
                'border-color': 'var(--border-color)'
            }).find('i').css('color', 'var(--text-sub)');
            
            $(this).addClass('active').css({
                'border-color': 'var(--brand-primary)'
            }).find('i').css('color', 'var(--brand-primary)');
            
            // Load appropriate template
            if(mockupPreview) {
                window.isSwitchingTemplate = true;
                
                // Get old template info
                const oldTemplate = mockupPreview.getCurrentTemplate();
                const oldPrintArea = oldTemplate && oldTemplate.printArea ? oldTemplate.printArea : null;
                const fallbackOldArea = oldTemplate ? { x:0, y:0, width: oldTemplate.width, height: oldTemplate.height } : null;

                const templateId = productType + '_white';
                if(mockupPreview.templates[templateId]) {
                    // Show wait cursor
                    $('body').css('cursor', 'wait');
                    console.log('Loading template:', templateId);
                    
                    mockupPreview.loadTemplate(templateId).then(() => {
                        console.log('Template loaded successfully');
                        const newTemplate = mockupPreview.getCurrentTemplate();
                        
                        // Force canvas resize
                        if (newTemplate && mockupPreview.canvas) {
                            mockupPreview.canvas.width = newTemplate.width;
                            mockupPreview.canvas.height = newTemplate.height;
                            
                            // Remap Layers
                            if (newTemplate.printArea) {
                                if (oldPrintArea) {
                                    console.log('Remapping layers from old area...');
                                    mockupPreview.remapLayers(oldPrintArea, newTemplate.printArea);
                                } else if (fallbackOldArea) {
                                    mockupPreview.remapLayers(fallbackOldArea, newTemplate.printArea);
                                }
                            } else {
                                // Fallback scaling
                                if (fallbackOldArea && newTemplate.width !== fallbackOldArea.width) {
                                     const scale = newTemplate.width / fallbackOldArea.width;
                                     mockupPreview.scaleLayers(scale);
                                }
                            }
                            mockupPreview.render();
                        }
                        console.log('Switched to:', templateId);
                    }).catch(err => {
                        console.error('Failed to load template:', err);
                        alert('Failed to switch. Check console.');
                    }).finally(() => {
                        window.isSwitchingTemplate = false;
                        $('body').css('cursor', 'default');
                        console.log('Switch complete, unlock UI');
                    });
                } else {
                    console.warn('Template not found:', templateId);
                    window.isSwitchingTemplate = false;
                }
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

    // --- UNIFIED TABS LOGIC ---
    $(document).on('click', '.unified-tab', function() {
        // 1. Toggle Tabs
        $('.unified-tab').removeClass('active');
        $(this).addClass('active');
        
        // 2. Toggle Content Panels
        $('.unified-panel').removeClass('active');
        const target = $(this).data('target');
        $('#' + target).addClass('active');
        
        // 3. Optional: Reset Scroll or Notify other components
        console.log('Switched to tab:', target);
    });

    // Wizard Navigation
    $(document).on('click', '.wizard-next', function() {
        const nextTab = $(this).data('next');
        $('.unified-tab[data-target="' + nextTab + '"]').click();
    });

    $(document).on('click', '.wizard-prev', function() {
        const prevTab = $(this).data('prev');
        $('.unified-tab[data-target="' + prevTab + '"]').click();
    });

    // Gallery Uploader Logic
    $('#gallery-dropzone').on('click', function() {
        $('#gallery-hidden-input').click();
    });

    $('#gallery-hidden-input').on('change', function() {
        $('#gallery-preview').html('');
        const files = this.files;
        if(files) {
            Array.from(files).forEach(file => {
                 const reader = new FileReader();
                 reader.onload = function(e) {
                     const img = $('<img>').attr('src', e.target.result).css({
                         'width': '60px', 'height': '60px', 'object-fit': 'cover', 'border-radius': '8px', 'border':'1px solid #e2e8f0'
                     });
                     $('#gallery-preview').append(img);
                 }
                 reader.readAsDataURL(file);
            });
        }
    });

    // --- SIMPLE ATTRIBUTE ADDERS ---
    
    // Add More Colors (Standard)
    $('#color-btn-standard').on('click', function() {
        $('#color-section-standard').append(`
            <div class="input-group colorpicker-component cp" style="margin-bottom:10px; display:flex;">
                <input type="text" name="color_all[]" class="u-input cp tcolor" value="#ffffff" style="flex:1;"/>
                <span class="input-group-addon" style="padding:10px; background:#f1f5f9; border:1px solid #e2e8f0; border-left:none;"><i></i></span>
                <span class="remove-color" style="cursor:pointer; margin-left:10px; padding:10px; color:red;"><i class="fas fa-times"></i></span>
            </div>
        `);
        // Re-initialize colorpicker for new elements if plugin exists
        if($.fn.colorpicker) {
            $('.cp').colorpicker();
        }
    });

    // Remove Color
    $(document).on('click', '.remove-color', function() {
        $(this).parent().remove();
    });

    // Add More Sizes (Standard)
    $('#size-btn-standard').on('click', function() {
        $('#size-section-standard').append(`
            <div style="display:flex; margin-bottom:10px;">
                <input type="text" name="size_all[]" class="u-input" placeholder="e.g. XL" style="flex:1;">
                <span class="remove-size" style="cursor:pointer; margin-left:10px; padding:10px; color:red;"><i class="fas fa-times"></i></span>
            </div>
        `);
    });

    // Remove Size
    $(document).on('click', '.remove-size', function() {
        $(this).parent().remove();
    });

    // --- DYNAMIC CATEGORY & ATTRIBUTE LOADING ---
    
    // 1. Category Change
    // 1. Category Change
    // 1. Category Change
    $('#cat').on('change', function() {
        var link = $(this).find(':selected').data('href');
        var catId = $(this).val();
        
        // Reset and hide Child Category when main Category changes
        $('#childcat').html('<option value="">{{ __("Select Child Category") }}</option>').prop('disabled', true);
        $('#childcat-section').hide();
        $('#childcatAttributes').html('');
        $('#subcatAttributes').html('');

        // Load Subcategories
        if (link != "") {
            $('#subcat').load(link, function() {
                // Check if we have options other than the placeholder
                if ($('#subcat option').length > 1) {
                    $('#subcat-section').show();
                    $('#subcat').prop('disabled', false);
                } else {
                    $('#subcat-section').hide();
                    $('#subcat').prop('disabled', true);
                }
            });
            
        } else {
             $('#subcat').html('<option value="">{{ __("Select Sub Category") }}</option>').prop('disabled', true);
             $('#subcat-section').hide();
        }

        // Load Attributes for Category
        if(catId) {
             $('#catAttributes').load('{{ route('vendor-prod-getattributes') }}?cat_id='+catId, function() {
                 if($('#catAttributes').children().length > 0) {
                     $('#catAttributes').show();
                 } else {
                     $('#catAttributes').hide();
                 }
             });
        }
    });

    // 2. SubCategory Change
    $('#subcat').on('change', function() {
        var link = $(this).find(':selected').data('href');
        var subId = $(this).val();
        
        // Load Child Categories
        if (link != "") {
            $('#childcat').load(link, function() {
                 if ($('#childcat option').length > 1) {
                    $('#childcat-section').show();
                    $('#childcat').prop('disabled', false);
                } else {
                    $('#childcat-section').hide();
                    $('#childcat').prop('disabled', true);
                }
            });
        } else {
             $('#childcat').html('<option value="">{{ __("Select Child Category") }}</option>').prop('disabled', true);
             $('#childcat-section').hide();
        }

        // Load Attributes for SubCategory
        if(subId) {
             $('#subcatAttributes').load('{{ route('vendor-prod-getattributes') }}?subcat_id='+subId, function() {
                 if($('#subcatAttributes').children().length > 0) {
                     $('#subcatAttributes').show();
                 } else {
                     $('#subcatAttributes').hide();
                 }
             });
        }
    });

    // 3. ChildCategory Change
    $('#childcat').on('change', function() {
        var childId = $(this).val();
        
        // Load Attributes for ChildCategory
        if(childId) {
             $('#childcatAttributes').load('{{ route('vendor-prod-getattributes') }}?childcat_id='+childId, function() {
                 if($('#childcatAttributes').children().length > 0) {
                     $('#childcatAttributes').show();
                 } else {
                     $('#childcatAttributes').hide();
                 }
             });
        }
    });
    
    // Form submission handler - Export canvas before submit
    $('#geniusform').on('submit', async function(e) {
        e.preventDefault(); 
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const $overlay = $('#publish-progress');
        const $bar = $('#progress-bar-fill');
        const $text = $('#progress-text');
        
        // Show Overlay
        $overlay.css('display', 'flex');
        
        try {
            if (mockupPreview) {
                // Export with Progress Callback
                console.log('Starting Design Export (Scale 3x)...');
                
                const views = ['front', 'back'];
                const galleryInputs = [];
                let mainPhoto = null;
                let printImage = null;
                let designJson = null;

                // Save current state
                const originalView = mockupPreview.currentView;

                for (let i = 0; i < views.length; i++) {
                    const view = views[i];
                    console.log(`Processing ${view} view...`);
                    
                    // Check if we should export this view
                    // Always export front. Export back if it has layers.
                    const hasLayers = mockupPreview.views[view].layers.length > 0;
                    
                    if (view === 'front' || hasLayers) {
                        $text.text(`Generating ${view.charAt(0).toUpperCase() + view.slice(1)} View...`);
                        
                        // Switch view logic
                        if (!mockupPreview.views[view].templateImage && mockupPreview.currentTemplateId) {
                             $text.text(`Loading ${view} template...`);
                             try {
                                await mockupPreview.loadTemplate(mockupPreview.currentTemplateId, view);
                             } catch(e) { 
                                 console.warn(`Could not load ${view} template`, e); 
                                 continue;
                             }
                        }
                        mockupPreview.switchView(view); 
                        
                        // Wait for canvas render
                        await new Promise(r => setTimeout(r, 200));

                        // Export (Scale 3) -> 1200-1500px width
                        const exported = await mockupPreview.exportDesign(3, (msg, pct) => {
                             // Update progress bar
                             const range = 100 / (views.length || 1); 
                             const basePct = (i * range); 
                             const currentPct = basePct + (pct * (range/100)); 
                             $bar.css('width', currentPct + '%');
                        });

                        if (view === 'front') {
                            mainPhoto = exported.mockup;
                            printImage = exported.print; 
                            designJson = exported.json; 
                        } else {
                            // Add to gallery
                            galleryInputs.push(exported.mockup);
                        }
                    }
                }
                
                // Restore logic
                mockupPreview.switchView(originalView);
                
                if (mainPhoto && mainPhoto.length > 100) {
                     $('#mockup-image-hidden').val(mainPhoto);
                     $('#print-image-hidden').val(printImage);
                     $('#design-json-hidden').val(designJson); // Full JSON is returned from any export
                } else {
                     throw new Error('Main image generation failed');
                }
                
                // Set Gallery Inputs
                $('#geniusform').find('input[name="gallery[]"].pod-generated').remove();
                galleryInputs.forEach(base64 => {
                     $('<input>').attr({
                        type: 'hidden',
                        name: 'gallery[]',
                        class: 'pod-generated',
                        value: base64
                    }).appendTo('#geniusform');
                });
                $('#is-pod-hidden').val('1');
                
                // DEBUG: Verify values were set
                console.log('Values Set:');
                console.log('  mockup-image length:', $('#mockup-image-hidden').val().length);
                console.log('  print-image length:', $('#print-image-hidden').val().length);
                console.log('  design-json length:', $('#design-json-hidden').val().length);
                console.log('  First 50 chars of mockup:', $('#mockup-image-hidden').val().substring(0, 50));
                
                // Submit via AJAX with FormData
                const formData = new FormData($form[0]);
                console.log('Submitting via AJAX...');
                
                $.ajax({
                    method: "POST",
                    url: $form.prop('action'),
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        console.log('Server Response:', data);
                        $overlay.hide();
                        
                        if (data.errors) {
                            $('.alert-success').hide();
                            $('.alert-danger').show();
                            $('.alert-danger ul').html('');
                            for (var error in data.errors) {
                                $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                            }
                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                        } else {
                            $('.alert-danger').hide();
                            $('.alert-success').show();
                            $('.alert-success p').html(data);
                            $('html, body').animate({ scrollTop: 0 }, 'fast');
                            
                            // Redirect after 2 seconds
                            setTimeout(function() {
                                window.location.href = '{{ route("vendor-prod-index") }}';
                            }, 2000);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        console.error('Response:', xhr.responseText);
                        $overlay.hide();
                        alert('Upload failed: ' + error + '\n\nCheck console for details.');
                    }
                });
            } else {
                alert('Design canvas not initialized.');
                $overlay.hide();
            }
        } catch (error) {
            console.error('Export failed:', error);
            alert('Failed to export design. Please try again.');
             $overlay.hide();
        }
        
        return false;
    });
});
</script>

@endsection
