<div class='cost-per-product-option repeater-row row columns-4 span-6'>

	<div class='col'>
		<select
			class='cost-per-product wc-product-search'
			name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][product]'
			data-placeholder="<?php _e( 'Select a product', 'woocommerce-advanced-shipping-advanced-pricing' ); ?>&hellip;"
			style="width: 100%;"
		><?php
			if ( $selected_product_name ) :
				?><option value="<?php echo absint( $product ); ?>"><?php echo $selected_product_name; ?></option><?php
			endif;
		?></select>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-product-min' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][min]' value='<?php echo $min; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-product-max' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][max]' value='<?php echo $max; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-product-cost waf_input_price' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][cost]' value='<?php echo esc_attr( wc_format_localized_price( $cost ) ); ?>' placeholder='<?php _e( 'Fixed amount or percentage', 'woocommerce-advanced-fees' ); ?>'>
	</div>

	<div class='col-inline'>
		<span class='dashicons dashicons-no-alt repeater-remove-row' style='line-height: 29px;'></span>
	</div>

</div>