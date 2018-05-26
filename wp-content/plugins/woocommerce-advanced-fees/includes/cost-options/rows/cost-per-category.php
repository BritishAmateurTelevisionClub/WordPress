<div class='cost-per-category-option repeater-row row columns-4 span-6'>

	<div class='col'>
		<select class='cost-per-category-category' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][category]'><?php
			?><option value=''><?php _e( 'Select a category', 'woocommerce-advanced-fees' ); ?></option><?php
			foreach ( get_terms( 'product_cat', array( 'hide_empty' => false ) ) as $cat ) :
				?><option value='<?php echo esc_attr( $cat->term_id ); ?>' <?php selected( $category, $cat->term_id ); ?>><?php
					echo esc_html( $cat->name );
				?></option><?php
			endforeach;
		?></select>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-category-min' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][min]' value='<?php echo $min; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-category-max' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][condition][max]' value='<?php echo $max; ?>'>
	</div>

	<div class='col'>
		<input type='text' class='cost-per-category-cost waf_input_price' name='advanced_costs_<?php echo esc_attr( $this->id ); ?>[<?php echo absint( $i ); ?>][action][cost]' value='<?php echo esc_attr( wc_format_localized_price( $cost ) ); ?>' placeholder='<?php _e( 'Fixed amount or percentage', 'woocommerce-advanced-fees' ); ?>'>
	</div>

	<div class='col-inline'>
		<span class='dashicons dashicons-no-alt repeater-remove-row' style='line-height: 29px;'></span>
	</div>

</div>