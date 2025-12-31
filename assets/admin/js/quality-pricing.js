/**
 * Quality-Based Pricing Calculator
 * Loads options from admin-configurable database
 */

$(document).ready(function() {
    
    // Pricing calculator state
    const pricing = {
        print_quality: 0,
        clothing_quality: 12,
        production_speed: 0,
        tag_option: 0,
        packaging: 0,
        profitMargin: 50
    };
    
    let optionsLoaded = false;
    
    /**
     * Load pricing options from API
     */
    function loadPricingOptions() {
        const apiUrl = (typeof mainurl !== 'undefined' ? mainurl : '') + '/admin/api/pod-pricing';
        
        $.get(apiUrl)
            .done(function(data) {
                optionsLoaded = true;
                populateDropdowns(data);
                calculatePricing();
            })
            .fail(function() {
                console.log('Using fallback pricing options');
            });
    }
    
    /**
     * Populate dropdowns from API data
     */
    function populateDropdowns(data) {
        // Print Quality
        if (data.print_quality) {
            const select = $('#print-quality');
            select.empty();
            data.print_quality.forEach(function(opt) {
                select.append(`<option value="${opt.value}" data-price="${opt.price}">${opt.name} (+$${opt.price})</option>`);
            });
        }
        
        // Clothing Quality
        if (data.clothing_quality) {
            const select = $('#clothing-quality');
            select.empty();
            data.clothing_quality.forEach(function(opt) {
                select.append(`<option value="${opt.value}" data-price="${opt.price}">${opt.name} (+$${opt.price})</option>`);
            });
            // Set default to first option
            pricing.clothing_quality = parseFloat(data.clothing_quality[0].price) || 12;
        }
        
        // Production Speed
        if (data.production_speed) {
            const select = $('#production-speed');
            select.empty();
            data.production_speed.forEach(function(opt) {
                select.append(`<option value="${opt.value}" data-price="${opt.price}">${opt.name} (+$${opt.price})</option>`);
            });
        }
        
        // Tag Options
        if (data.tag_option) {
            const select = $('#tag-option');
            select.empty();
            data.tag_option.forEach(function(opt) {
                select.append(`<option value="${opt.value}" data-price="${opt.price}">${opt.name} (+$${opt.price})</option>`);
            });
        }
        
        // Packaging
        if (data.packaging) {
            const select = $('#packaging');
            select.empty();
            data.packaging.forEach(function(opt) {
                select.append(`<option value="${opt.value}" data-price="${opt.price}">${opt.name} (+$${opt.price})</option>`);
            });
        }
    }
    
    /**
     * Calculate total pricing
     */
    function calculatePricing() {
        // Calculate base cost (sum of all options)
        const baseCost = 
            pricing.print_quality + 
            pricing.clothing_quality + 
            pricing.production_speed + 
            pricing.tag_option + 
            pricing.packaging;
        
        // Calculate margin amount
        const marginAmount = (baseCost * pricing.profitMargin) / 100;
        
        // Calculate final retail price
        const finalPrice = baseCost + marginAmount;
        
        // Features cost (everything except base clothing)
        const featuresCost = 
            pricing.print_quality + 
            pricing.production_speed + 
            pricing.tag_option + 
            pricing.packaging;
        
        // Update displays
        $('#base-cost-display').text('$' + pricing.clothing_quality.toFixed(2));
        $('#features-cost-display').text('$' + featuresCost.toFixed(2));
        $('#margin-amount-display').text('$' + marginAmount.toFixed(2));
        $('#final-price-display').text('$' + finalPrice.toFixed(2));
        
        // Update hidden inputs
        $('#final-price-input').val(finalPrice.toFixed(2));
        $('#base-cost-input').val(baseCost.toFixed(2));
        
        // Update cost breakdown text
        const printText = $('#print-quality option:selected').text().split('(')[0].trim();
        const clothingText = $('#clothing-quality option:selected').text().split('(')[0].trim();
        const speedText = $('#production-speed option:selected').text().split('(')[0].trim();
        
        $('#cost-breakdown').text(`${printText}, ${clothingText}, ${speedText}`);
        
        // Calculate monthly profit estimate (50 sales)
        const monthlyProfit = marginAmount * 50;
        $('#monthly-profit').text('$' + monthlyProfit.toFixed(0));
        
        // Also update original price field if exists
        if ($('input[name="price"]').not('#final-price-input').length) {
            $('input[name="price"]').not('#final-price-input').val(finalPrice.toFixed(2));
        }
    }
    
    // Event listeners for all pricing options
    $(document).on('change', '#print-quality', function() {
        pricing.print_quality = parseFloat($(this).find(':selected').data('price')) || 0;
        calculatePricing();
    });
    
    $(document).on('change', '#clothing-quality', function() {
        pricing.clothing_quality = parseFloat($(this).find(':selected').data('price')) || 12;
        calculatePricing();
    });
    
    $(document).on('change', '#production-speed', function() {
        pricing.production_speed = parseFloat($(this).find(':selected').data('price')) || 0;
        calculatePricing();
    });
    
    $(document).on('change', '#tag-option', function() {
        pricing.tag_option = parseFloat($(this).find(':selected').data('price')) || 0;
        calculatePricing();
    });
    
    $(document).on('change', '#packaging', function() {
        pricing.packaging = parseFloat($(this).find(':selected').data('price')) || 0;
        calculatePricing();
    });
    
    $(document).on('input', '#profit-margin', function() {
        pricing.profitMargin = parseInt($(this).val());
        $('#margin-display').text(pricing.profitMargin);
        calculatePricing();
    });
    
    // Load options on page load
    setTimeout(function() {
        loadPricingOptions();
    }, 500);
});
