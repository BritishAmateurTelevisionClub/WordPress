<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WAF_Cost_Option_Cost_Per_Weight.
 *
 * Cost per weight cost option.
 *
 * @class       WAF_Cost_Option_Cost_Per_Weight
 * @author     	Jeroen Sormani
 * @package		WooCommerce Advanced Fees
 * @version		1.1.0
 */
class WAF_Cost_Option_Cost_Per_Weight extends WAF_Cost_Option_Abstract {


	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		$this->id = 'cost_per_weight';
		$this->name = __( 'Cost per weight', 'woocommerce-advanced-fees' );

		parent::__construct();
	}


	/**
	 * Output settings.
	 *
	 * Output the settings related to this cost option.
	 *
	 * @since 1.1.0
	 */
	public function output() {

		$cost_options = $this->get_cost_options();

		?><div class='cost-per-weight-wrap'>

			<div class='repeater-header row columns-4 span-6'>
				<div class='col'>
					<span class='label-text'><?php _e( 'Min weight', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'You can set a minimum weight per row before the fee amount is applied.<br/>Leave empty to not set a minimum.', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>
				<div class='col'>
					<span class='label-text'><?php _e( 'Max weight', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'You can set a maximum weight per row before the fee amount is applied.<br/>Leave empty to not set a maximum.', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>
				<div class='col'>
					<span class='label-text'><?php _e( 'Fee amount', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. <br/>Add a asterisk (*) to multiply the fee by weight (accurate to the decimals).', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>
				<div class='col-inline'></div>
			</div>

			<div class="repeater-wrap">
				<div class='repeater-rows'><?php

					$i = 0;
					if ( is_array( $cost_options ) ) :
						foreach ( $cost_options as $values ) :

							$i++;
							$min  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : '';
							$max  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : '';
							$cost = isset( $values['action']['cost'] ) ? esc_attr( $values['action']['cost'] ) : '';

							require 'rows/cost-per-weight.php';

						endforeach;
					else :

						$min = $max = $cost = '';
						require 'rows/cost-per-weight.php';

					endif;

				?></div>

				<div class="repeater-template hidden" style="display: none;"><?php
					$i = 9999;
					$category = $min = $max = $cost = '';
					require 'rows/cost-per-weight.php';
				?></div>
				<a href='javascript:void(0);' class='button secondary-button add repeater-add-row'><?php _e( 'Add new', 'woocommerce-advanced-fees' ); ?></a>

			</div>
		</div><?php


		// Add new repeater row
		wc_enqueue_js( "
			jQuery( '.waf-tabbed-settings #cost_per_weight ' ).on( 'click', '.add-repeat-row', function() {

				var repeater_wrap = $( this ).prev( '.repeater-wrap' );
				var clone = repeater_wrap.find( '.repeater-row' ).first().clone();
				var repeater_index = repeater_wrap.find( '.repeater-row' ).length;
				repeater_index++;
				clone.find( '[name*=\"[condition][min]\"]' ).attr( 'name', 'advanced_costs_cost_per_weight[' + repeater_index + '][condition][min]' ).val( '' );
				clone.find( '[name*=\"[condition][max]\"]' ).attr( 'name', 'advanced_costs_cost_per_weight[' + repeater_index + '][condition][max]' ).val( '' );
				clone.find( '[name*=\"[action][cost]\"]' ).attr( 'name', 'advanced_costs_cost_per_weight[' + repeater_index + '][action][cost]' ).val( '' );
				repeater_wrap.append( clone ).find( '.repeater-row' ).last().hide().slideDown( 'fast' );

			} );
		" );

	}


	/**
	 * Apply cost.
	 *
	 * Apply the advanced pricing cost to the fee.
	 *
	 * @since 1.1.0
	 *
	 * @param  float $fee_amount Current fee amount.
	 * @param  int   $fee_id     Post ID of the fee being processed.
	 * @return float             Modified fee amount.
	 */
	public function apply_cost_option_cost( $fee_amount, $fee_id ) {

		$cost_options = $this->get_cost_options( $fee_id );
		$weight       = WC()->cart->get_cart_contents_weight();

		if ( is_array( $cost_options ) ) :
			foreach ( $cost_options as $values ) :

				$min  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : null;
				$max  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : null;
				$cost = isset( $values['action']['cost'] ) ? esc_attr( $values['action']['cost'] ) : null;

				// Bail if cost is not set
				if ( is_null( $cost ) ) :
					continue;
				endif;

				// Bail if minimum is not set, or item qty is not met
				if ( is_null( $min ) || ( ! empty( $min ) && $weight < $min ) ) :
					continue;
				endif;

				// Bail if maximum is not set, or item qty is not met
				if ( is_null( $max ) || ( ! empty( $max ) && $weight > $max ) ) :
					continue;
				endif;

				$fee_amount += waf_apply_fee_amount( $cost, $weight );

			endforeach;
		endif;

		return $fee_amount;

	}


}
