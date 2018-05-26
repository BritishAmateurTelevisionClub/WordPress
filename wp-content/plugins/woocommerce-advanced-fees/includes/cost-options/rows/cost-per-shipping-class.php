<div class='cost-per-shipping-class-option repeater-row row columns-4 span-6'>

	<div class='col'>
		<select class='cost-per-shipping-class-shipping-class' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][shipping_class]'><?php
			?><option value='-1'><?php _e( 'No shipping class', 'woocommerce-advanced-fees' ); ?></option><?php

			foreach ( get_terms( 'product_shipping_class', array( 'hide_empty' => false ) ) as $ship_class ) :
				?><option value='<?php echo esc_attr( $ship_class->slug ); ?>' <?php selected( $shipping_class, $ship_class->slug ); ?>><?php
					echo esc_attr( $ship_class->name );
				?></option><?php
			endforeach;
		?></select>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-shipping-class-min' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][min]' value='<?php echo $min; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-shipping-class-max' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][max]' value='<?php echo $max; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-shipping-class-cost waf_input_price' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][cost]' value='<?php echo esc_attr( wc_format_localized_price( $cost ) ); ?>' placeholder='<?php _e( 'Fixed amount or percentage', 'woocommerce-advanced-fees' ); ?>'>
	</div>

	<div class='col-inline'>
		<span class='dashicons dashicons-no-alt repeater-remove-row' style='line-height: 29px;'></span>
	</div>

</div>