<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WAF_Cost_Option_Cost_Per_Category.
 *
 * Cost per category cost option.
 *
 * @class       WAF_Cost_Option_Cost_Per_Category
 * @author     	Jeroen Sormani
 * @package		WooCommerce Advanced Fees
 * @version		1.1.0
 */
class WAF_Cost_Option_Cost_Per_Category extends WAF_Cost_Option_Abstract {


	/**
	 * Constructor.
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		$this->id = 'cost_per_category';
		$this->name = __( 'Cost per category', 'woocommerce-advanced-fees' );

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

		?><div class='cost-per-category-wrap'>

			<div class='repeater-header row columns-4 span-6'>
				<div class='col'>
					<span class='label-text'><?php _e( 'Category', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'Select a category to apply the fee amount to when the min/max quantity match.', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>
				<div class='col'>
					<span class='label-text'><?php _e( 'Min quantity', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'You can set a minimum product quantity per row before the fee amount is applied.<br/>Leave empty to not set a minimum.', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>
				<div class='col'>
					<span class='label-text'><?php _e( 'Max quantity', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'You can set a maximum product quantity per row before the fee amount is applied.<br/>Leave empty to not set a maximum.', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>
				<div class='col'>
					<span class='label-text'><?php _e( 'Fee amount', 'woocommerce-advanced-fees' ); ?></span>
					<img class="help_tip" data-tip="<?php _e( 'A fixed amount (e.g. 5 / -5), percentage (e.g. 5% / -5%) to add as a fee. <br/>Add a asterisk (*) to multiply the fee with the quantity of items with the category.', 'woocommerce-advanced-fees' ); ?>" src="<?php echo  WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16" />
				</div>

				<div class='col-inline'></div>
			</div>

			<div class="repeater-wrap">
				<div class='repeater-rows'><?php

					$i = 0;
					if ( is_array( $cost_options ) ) :
						foreach ( $cost_options as $values ) :

							$i++;
							$category = isset( $values['condition']['category'] ) ? esc_attr( $values['condition']['category'] ) : '';
							$min      = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : '';
							$max      = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : '';
							$cost     = isset( $values['action']['cost'] ) ? esc_attr( $values['action']['cost'] ) : '';

							require 'rows/cost-per-category.php';

						endforeach;
					else :

						$category = $min = $max = $cost = '';
						require 'rows/cost-per-category.php';

					endif;

				?></div>

				<div class="repeater-template hidden" style="display: none;"><?php
					$i = 9999;
					$category = $min = $max = $cost = '';
					require 'rows/cost-per-category.php';
				?></div>
				<a href='javascript:void(0);' class='button secondary-button add repeater-add-row'><?php _e( 'Add new', 'woocommerce-advanced-fees' ); ?></a>

			</div>
		</div><?php

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

		if ( ! WC()->cart->get_cart() ) :
			return $fee_amount;
		endif;

		$cost_options = $this->get_cost_options( $fee_id );

		// Loop through fee cost options
		if ( is_array( $cost_options ) ) :
			foreach ( $cost_options as $values ) :

				$category             = isset( $values['condition']['category'] ) ? esc_attr( $values['condition']['category'] ) : null;
				$min                  = isset( $values['condition']['min'] ) ? esc_attr( $values['condition']['min'] ) : null;
				$max                  = isset( $values['condition']['max'] ) ? esc_attr( $values['condition']['max'] ) : null;
				$cost                 = isset( $values['action']['cost'] ) ? esc_attr( $values['action']['cost'] ) : null;

				$category_quantity    = $this->get_quantity( $category );
				$category_subtotal    = $this->get_quantity( $category, '$' );
				$min_compare_quantity = $this->get_quantity( $category, $min );
				$max_compare_quantity = $this->get_quantity( $category, $max );

				$min = str_replace( array( 'w', '$' ), '', $min );
				$max = str_replace( array( 'w', '$' ), '', $max );

				// Bail if cost is not set
				if ( is_null( $cost ) ) :
					continue;
				endif;

				// Bail if none of this category is in the cart.
				if ( 0 == $category_quantity ) :
					continue;
				endif;

				// Bail if minimum is not set, or item qty is not met
				if ( is_null( $min ) || ( ! empty( $min ) && $min_compare_quantity < $min ) ) :
					continue;
				endif;

				// Bail if maximum is not set, or item qty is not met
				if ( is_null( $max ) || ( ! empty( $max ) && $max_compare_quantity > $max ) ) :
					continue;
				endif;

				$fee_amount += waf_apply_fee_amount( $cost, $category_quantity, $category_subtotal );

			endforeach;
		endif;

		return $fee_amount;

	}


	/**
	 * Get related products.
	 *
	 * Get the related products from the cart where the cost should be applied to.
	 *
	 * @since 1.1.8
	 *
	 * @param string $value Set value for the advanced option.
	 * @return array List of related products
	 */
	public function get_related_products( $value = null ) {

		$related_products = array();

		$cart_items = WC()->cart->get_cart();
		foreach ( $cart_items as $cart_key => $item )  :

			$product_id = ! empty( $item['variation_id'] ) ? $item['variation_id'] : $item['product_id'];
			$product    = wc_get_product( $product_id );

			if ( has_term( $value, 'product_cat', $product->get_id() ) ) :
				$related_products[ $cart_key ] = $item;
			endif;

		endforeach;

		return $related_products;

	}


}
