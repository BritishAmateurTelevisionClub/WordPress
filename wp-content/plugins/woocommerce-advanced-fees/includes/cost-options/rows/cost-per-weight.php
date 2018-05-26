<div class='cost-per-weight-option repeater-row row columns-4 span-6'>

	<div class='col'>
		<input type='text' class='cost-per-weight-min' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][min]' value='<?php echo $min; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-weight-max' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][max]' value='<?php echo $max; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-weight-cost waf_input_price' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][cost]' value='<?php echo esc_attr( wc_format_localized_price( $cost ) ); ?>' placeholder='<?php _e( 'Fixed amount or percentage', 'woocommerce-advanced-fees' ); ?>'>
	</div>

	<div class='col-inline'>
		<span class='dashicons dashicons-no-alt repeater-remove-row' style='line-height: 29px;'></span>
	</div>

</div>