<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$posts = waf_get_fee_posts( array( 'post_status' => array( 'draft', 'publish' ) ) );

?><tr valign="top">
	<th scope="row" class="titledesc"><?php
		_e( 'Advanced Fees', 'woocommerce-advanced-fees' ); ?><br />
	</th>
	<td class="forminp" id="advanced-fees-table">

		<table class='wp-list-table wpc-conditions-post-table wpc-sortable-post-table widefat'>
			<thead>
				<tr>
					<th style='width: 17px;'></th>
					<th style='padding-left: 10px;'><?php _e( 'Title', 'woocommerce-advanced-fees' ); ?></th>
					<th style='padding-left: 10px;'><?php _e( 'Fee title', 'woocommerce-advanced-fees' ); ?></th>
					<th style='padding-left: 10px; width: 100px;'><?php _e( 'Fee amount', 'woocommerce-advanced-fees' ); ?></th>
					<th style='width: 70px;'><?php _e( '# Groups', 'woocommerce-advanced-fees' ); ?></th>
				</tr>
			</thead>
			<tbody><?php

				$i = 0;
				foreach ( $posts as $post ) :

					$fee_title  = get_post_meta( $post->ID, 'fee_title', true );
					$fee_amount = get_post_meta( $post->ID, 'fee_amount', true );
					$conditions = get_post_meta( $post->ID, 'conditions', true );

					$alt = ( $i++ ) % 2 == 0 ? 'alternate' : '';
					?><tr class='<?php echo $alt; ?>'>

						<td class='sort'>
							<input type='hidden' name='sort[]' value='<?php echo absint( $post->ID ); ?>' />
						</td>
						<td>
							<strong>
								<a href='<?php echo get_edit_post_link( $post->ID ); ?>' class='row-title' title='<?php _e( 'Edit Method', 'woocommerce-advanced-fees' ); ?>'><?php
									echo _draft_or_post_title( $post->ID );
								?></a><?php
								_post_states( $post );
							?></strong>
							<div class='row-actions'>
								<span class='edit'>
									<a href='<?php echo get_edit_post_link( $post->ID ); ?>' title='<?php _e( 'Edit Method', 'woocommerce-advanced-fees' ); ?>'>
										<?php _e( 'Edit', 'woocommerce-advanced-fees' ); ?>
									</a>
									|
								</span>
								<span class='trash'>
									<a href='<?php echo get_delete_post_link( $post->ID ); ?>' title='<?php _e( 'Delete Method', 'woocommerce-advanced-fees' ); ?>'>
										<?php _e( 'Delete', 'woocommerce-advanced-fees' ); ?>
									</a>
								</span>
							</div>
						</td>
						<td><?php echo esc_html( $fee_title ); ?></td>
						<td><?php echo esc_html( $fee_amount ); ?></td>
						<td><?php echo absint( count( $conditions ) ); ?></td>
					</tr><?php

				endforeach;

				if ( empty( $post ) ) :
					?><tr>
						<td colspan='2'><?php _e( 'There are no Advanced Fees. Yet...', 'woocommerce-advanced-fees' ); ?></td>
					</tr><?php
				endif;

			?></tbody>
			<tfoot>
				<tr>
					<th colspan='5' style='padding-left: 10px;'>
						<a href='<?php echo admin_url( 'post-new.php?post_type=advanced_fee' ); ?>' class='add button'><?php _e( 'Add Advanced Fee', 'woocommerce-advanced-fees' ); ?></a>
					</th>
				</tr>
			</tfoot>
		</table>
	</td>
</tr>
