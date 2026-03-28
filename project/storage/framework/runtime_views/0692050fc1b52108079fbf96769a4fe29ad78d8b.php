
<div class="row" style="margin-top: 30px;">
	<div class="col-lg-12">
		<div class="product-description">
			<div class="body-area" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; padding: 25px; border-radius: 12px;">
				<h4 class="heading" style="color: #fff; border-bottom: 2px solid rgba(255,255,255,0.3); padding-bottom: 15px; margin-bottom: 20px;">
					<i class="fas fa-calculator"></i> <?php echo e(__('Quality-Based Pricing')); ?>

				</h4>
				
				
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-lg-4 col-md-6">
						<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; backdrop-filter: blur(10px);">
							<label style="display: block; margin-bottom: 10px; font-weight: 600;">
								<i class="fas fa-print"></i> <?php echo e(__('Print Quality')); ?>

							</label>
							<select id="print-quality" class="form-control pricing-option" style="background: rgba(255,255,255,0.9); border: none; padding: 10px; border-radius: 6px;">
								<option value="standard" data-price="0">Standard Print (+$0)</option>
								<option value="hd" data-price="3">HD Print (+$3)</option>
								<option value="premium" data-price="5">Premium Print (+$5)</option>
								<option value="dtg" data-price="8">Direct-to-Garment (+$8)</option>
							</select>
							<small style="display: block; margin-top: 8px; opacity: 0.9;">Quality affects final product appearance</small>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; backdrop-filter: blur(10px);">
							<label style="display: block; margin-bottom: 10px; font-weight: 600;">
								<i class="fas fa-tshirt"></i> <?php echo e(__('Clothing Quality')); ?>

							</label>
							<select id="clothing-quality" class="form-control pricing-option" style="background: rgba(255,255,255,0.9); border: none; padding: 10px; border-radius: 6px;">
								<option value="basic" data-price="8">Basic Cotton (+$8)</option>
								<option value="standard" data-price="12">Standard Cotton (+$12)</option>
								<option value="premium" data-price="18">Premium Cotton (+$18)</option>
								<option value="organic" data-price="25">Organic Premium (+$25)</option>
							</select>
							<small style="display: block; margin-top: 8px; opacity: 0.9;">Material and fabric quality</small>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; backdrop-filter: blur(10px);">
							<label style="display: block; margin-bottom: 10px; font-weight: 600;">
								<i class="fas fa-boxes"></i> <?php echo e(__('Production Speed')); ?>

							</label>
							<select id="production-speed" class="form-control pricing-option" style="background: rgba(255,255,255,0.9); border: none; padding: 10px; border-radius: 6px;">
								<option value="standard" data-price="0">Standard (5-7 days) (+$0)</option>
								<option value="fast" data-price="5">Fast (2-3 days) (+$5)</option>
								<option value="express" data-price="10">Express (1 day) (+$10)</option>
							</select>
							<small style="display: block; margin-top: 8px; opacity: 0.9;">Production turnaround time</small>
						</div>
					</div>
				</div>
				
				
				<div class="row" style="margin-bottom: 20px;">
					<div class="col-lg-4 col-md-6">
						<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; backdrop-filter: blur(10px);">
							<label style="display: block; margin-bottom: 10px; font-weight: 600;">
								<i class="fas fa-tag"></i> <?php echo e(__('Tag Options')); ?>

							</label>
							<select id="tag-option" class="form-control pricing-option" style="background: rgba(255,255,255,0.9); border: none; padding: 10px; border-radius: 6px;">
								<option value="standard" data-price="0">Standard Tag (+$0)</option>
								<option value="custom" data-price="2">Custom Brand Tag (+$2)</option>
								<option value="tagless" data-price="1">Tagless Print (+$1)</option>
							</select>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; backdrop-filter: blur(10px);">
							<label style="display: block; margin-bottom: 10px; font-weight: 600;">
								<i class="fas fa-shield-alt"></i> <?php echo e(__('Packaging')); ?>

							</label>
							<select id="packaging" class="form-control pricing-option" style="background: rgba(255,255,255,0.9); border: none; padding: 10px; border-radius: 6px;">
								<option value="standard" data-price="0">Standard Bag (+$0)</option>
								<option value="premium" data-price="2">Premium Box (+$2)</option>
								<option value="custom" data-price="5">Custom Branded (+$5)</option>
							</select>
						</div>
					</div>
					
					<div class="col-lg-4 col-md-6">
						<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 8px; backdrop-filter: blur(10px);">
							<label style="display: block; margin-bottom: 10px; font-weight: 600;">
								<i class="fas fa-percent"></i> <?php echo e(__('Your Profit Margin')); ?> %
							</label>
							<input type="range" id="profit-margin" min="10" max="200" value="50" step="5" style="width: 100%; margin-bottom: 5px;">
							<div style="text-align: center; font-size: 18px; font-weight: bold; background: rgba(255,255,255,0.2); padding: 8px; border-radius: 6px;">
								<span id="margin-display">50</span>%
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="row">
					<div class="col-lg-12">
						<div style="background: rgba(255,255,255,0.95); color: #333; padding: 20px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
							<div class="row">
								<div class="col-md-3">
									<div style="text-align: center; padding: 15px;">
										<div style="font-size: 12px; color: #666; margin-bottom: 5px;">Base Cost</div>
										<div id="base-cost-display" style="font-size: 24px; font-weight: bold; color: #6c757d;">$0.00</div>
									</div>
								</div>
								<div class="col-md-3">
									<div style="text-align: center; padding: 15px;">
										<div style="font-size: 12px; color: #666; margin-bottom: 5px;">Added Features</div>
										<div id="features-cost-display" style="font-size: 24px; font-weight: bold; color: #17a2b8;">$0.00</div>
									</div>
								</div>
								<div class="col-md-3">
									<div style="text-align: center; padding: 15px;">
										<div style="font-size: 12px; color: #666; margin-bottom: 5px;">Your Margin</div>
										<div id="margin-amount-display" style="font-size: 24px; font-weight: bold; color: #28a745;">$0.00</div>
									</div>
								</div>
								<div class="col-md-3">
									<div style="text-align: center; padding: 15px; border-left: 2px solid #e9ecef;">
										<div style="font-size: 12px; color: #666; margin-bottom: 5px;">Final Retail Price</div>
										<div id="final-price-display" style="font-size: 32px; font-weight: bold; color: #667eea;">$0.00</div>
									</div>
								</div>
							</div>
							
							<div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #e9ecef; font-size: 12px; color: #666;">
								<div class="row">
									<div class="col-md-6">
										<strong>Cost Breakdown:</strong> <span id="cost-breakdown">Standard Print, Basic Cotton, Standard Production</span>
									</div>
									<div class="col-md-6 text-right">
										<strong>Est. Monthly Profit (50 sales):</strong> <span id="monthly-profit" style="color: #28a745; font-weight: bold;">$0</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<input type="hidden" name="price" id="final-price-input">
				<input type="hidden" name="base_cost" id="base-cost-input">
			</div>
		</div>
	</div>
</div>
<?php /**PATH C:\laragon\www\xmerch\project\resources\views\partials\quality-pricing.blade.php ENDPATH**/ ?>