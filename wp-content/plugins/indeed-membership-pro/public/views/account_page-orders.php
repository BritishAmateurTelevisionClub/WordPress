<div class="ihc-ap-wrap">
	<?php if (!empty($data['title'])):?>
		<h3><?php echo do_shortcode($data['title']);?></h3>
	<?php endif;?>
	<?php if (!empty($data['content'])):?>
		<p><?php echo do_shortcode($data['content']);?></p>
	<?php endif;?>

<?php
	if (!empty($data['orders'])){
		?>
				<table class="wp-list-table ihc-account-tranz-list">
						<thead>
							<tr>	
								<th style="text-align:left;">
									<span>
										<?php _e('Code', 'ihc');?>
									</span>									  
								</th>																		  
								<th style="text-align:left;">
									<span>
										<?php _e('Level', 'ihc');?>
									</span>									  
								</th>
								<th>
									<span>
										<?php _e('Amount', 'ihc');?>
									</span>									  
								</th>
								<th>
									<span>
										<?php _e('Payment Type', 'ihc');?>
									</span>
								</th>	
								<?php if (!empty($data['show_invoices'])):?>
									<th>
										<span>
											<?php _e('Invoice', 'ihc');?>
										</span>
									</th>										
								<?php endif;?>									
								<th>
									<span>
										<?php _e('Status', 'ihc');?>
									</span>
								</th>												  								  
								<th class="manage-column" style="text-align:right;">
									<span>
										<?php _e('Date', 'ihc');?>
									</span>
								</th>											  										  								  								  
							</tr>
						</thead>
				<?php
				foreach ($data['orders'] as $k=>$array){
					?>
					<tr>
						<td data-title="<?php _e('Code', 'ihc');?>"><?php  
							if (!empty($array['metas']['code'])){
								echo $array['metas']['code'];
							} else {
								echo '-';
							}
						?></td>				
						<td class="manage-column"  style="text-align:left;" data-title="<?php _e('Level', 'ihc');?>"><?php echo $array['level'];?></td>
						<td class="manage-column" data-title="<?php _e('Amount', 'ihc');?>">
							<span class="level-payment-list"><?php echo ihc_format_price_and_currency($array['amount_type'], $array['amount_value']);?></span>
						</td>
						<td style="text-transform:capitalize;" data-title="<?php _e('Payment Type', 'ihc');?>"><?php 
							if (empty($array['metas']['ihc_payment_type'])):
								echo '-';
							else:
								if (!empty($array['metas']['ihc_payment_type'])){
									$gateway_key = $array['metas']['ihc_payment_type'];
									if (isset($payment_gateways[$gateway_key])){
	                                    echo $payment_gateways[$gateway_key];
	                                } else {
	                                    echo $gateway_key;
	                                }
								}					
							endif;	
						?></td>
						<?php if (!empty($data['show_invoices'])):?>
							<?php if (!empty($data['show_only_completed_invoices']) && strcmp($array['status'], 'Completed')!==0):?>
							<td data-title="<?php _e('Level', 'ihc');?>">-</td>
							<?php else:?>
							<td data-title="<?php _e('Invoice', 'ihc');?>">
								<i class="fa-ihc fa-invoice-preview-ihc iump-pointer" onClick="iump_generate_invoice(<?php echo $array['id'];?>);"></i>
							</td>	
							<?php endif;?>									
						<?php endif;?>							
						<td class="manage-column" style="font-family: Oswald, arial, sans-serif !important;" data-title="<?php _e('Status', 'ihc');?>">
						 	<?php 
						 		//echo $array['status'];
								switch ($array['status']){
									case 'Completed':
										_e('Completed', 'ihc');
										break;
									case 'pending':
										_e('Pending', 'ihc');
										break;
									case 'fail':
									case 'failed':
										_e('Failed', 'ihc');
										break;
									case 'error':
										_e('Error', 'ihc');
										break;
								}							 	
						 	?>
						</td>
						<td class="manage-column" style="text-align:right;" data-title="<?php _e('Date', 'ihc');?>">
							<span>
								<?php echo ihc_convert_date_to_us_format($array['create_date']);//date("F j, Y, g:i a", strtotime($array['create_date']));?>
							</span>
						</td>		
					</tr>	
				<?php
				}///end of foreach
				?>
						<tfoot>
							<tr>	
								<th style="text-align:left;">
									<span>
										<?php _e('Code', 'ihc');?>
									</span>									  
								</th>																			  
								<th style="text-align:left;">
									<span><?php echo __('Level', 'ihc');?></span>									  
								</th>
								<th>
									<span><?php echo __('Amount', 'ihc');?></span>									  
								</th>	
								<th>
									<span><?php echo __('Payment Type', 'ihc');?></span>
								</th>		
								<?php if (!empty($data['show_invoices'])):?>
									<th>
										<span>
											<?php _e('Invoice', 'ihc');?>
										</span>
									</th>										
								<?php endif;?>																			
								<th>
									<span><?php echo __('Status', 'ihc');?></span>
								</th>									  								  
								<th class="manage-column" style="text-align:right;">
									<span><?php echo __('Date', 'ihc');?></span>
								</th>											  										  								  								  
							</tr>
						</tfoot>
			</table>

			<?php if (!empty($data['pagination'])):?>
				<?php echo $data['pagination'];?>
			<?php endif;?>

	<?php			
	} else {
		_e("No transactions available yet!", 'ihc');
	}
	?>

</div>
