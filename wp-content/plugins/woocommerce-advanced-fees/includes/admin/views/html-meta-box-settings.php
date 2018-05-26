<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_nonce_field( 'waf_settings_meta_box', 'waf_settings_meta_box_nonce' );

global $post;
$fee_title     = get_post_meta( $post->ID, 'fee_title', true );
$fee_amount    = get_post_meta( $post->ID, 'fee_amount', true );
$fee_tax_class = get_post_meta( $post->ID, 'fee_tax_class', true );

?><div class='waf-meta-box waf-meta-box-settings'>

	<p class='waf-option'>

		<label for='fee_title'><?php _e( 'Fee title', 'woocommerce-advanced-fees' ); ?></label>
		<input type='text' id="fee_title" name='fee_title' class='medium' value='<?php echo esc_attr( $fee_title ); ?>' placeholder='<?php _e( 'e.g. Handling fee', 'woocommerce-advanced-fees' ); ?>'>

	</p>


	<p class='waf-option'>

		<label for='fee_amount'><?php _e( 'Fee amount', 'woocommerce-advanced-fees' ); ?></label>
		<span class='wpc-currency'><?php echo esc_html( get_woocommerce_currency_symbol() ); ?></span>
		<input type='text' class='waf_input_price' id='fee_amount' name='fee_amount'
			value='<?php echo esc_attr( wc_format_localized_price( $fee_amount ) ); ?>' placeholder='<?php _e( 'Fixed amount or percentage', 'woocommerce-advanced-fees' ); ?>'>
		<img class='help_tip' src='<?php echo WC()->plugin_url(); ?>/assets/images/help.png' height='16' width='16' data-tip="<?php _e( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. Add a asterisk (*) to apply the price per item.', 'woocommerce-advanced-fees' ); ?>" />

	</p>


	<p class='waf-option'>

		<label for='fee_tax_class'><?php _e( 'Tax class', 'woocommerce-advanced-fees' ); ?></label>
		<select name='fee_tax_class' id="fee_tax_class" style='width: 189px;'>
			<option value='not_taxable'><?php _e( 'Not taxable', 'woocommerce-advanced-fees' ); ?></option>
			<option value='' <?php selected( $fee_tax_class, '' ); ?>><?php _e( 'Standard', 'woocommerce-advanced-fees' ); ?></option><?php

			foreach ( WC_Tax::get_tax_classes() as $tax_class ) :
				?><option value='<?php echo sanitize_title( $tax_class ); ?>' <?php selected( sanitize_title( $tax_class ), $fee_tax_class ); ?>><?php echo esc_html( $tax_class ); ?></option><?php
			endforeach;

		?></select>

	</p><?php

	do_action( 'woocommerce_advanced_fees_after_meta_box_settings', $post->ID );

	?><h3 style='padding-left: 0;'><?php _e( 'Extra cost options', 'woocommerce-advanced-fees' ); ?></h3><?php

	$cost_options = waf_get_cost_options();
	$first_option = reset( $cost_options );
	?><div class='waf-tabbed-settings'>

		<div class='inside'>

			<div class='tabs-panels-wrap'>
				<div class='tabs'>
					<ul><?php
						foreach ( $cost_options as $key => $option ) :
							?><li class='<?php echo $option->id == $first_option->id ? 'active' : ''; ?>'><a href='javascript:void(0);' data-target='<?php echo esc_attr( $key ); ?>'><?php echo esc_html( $option->name ); ?></a></li><?php
						endforeach;
					?></ul>
				</div>

				<div class='panels'><?php

					foreach ( $cost_options as $key => $option ) :

						?><div id='<?php echo esc_attr( $option->id ); ?>' class='panel' style='<?php echo $option->id != $first_option->id ? 'display: none;' : ''; ?>'><?php
							$option->output();
						?></div><?php

					endforeach;

				?></div>

				<div class='clear'></div>
			</div>

		</div>

	</div>

</div>
