jQuery( function( $ ) {

	// Advanced Cost
	$( '.waf-tabbed-settings' ).on( 'click', '.tabs a', function() {

		if ( $( this ).data( 'target' ) !== undefined ) {

			// Tabs
			var tabs = $( this ).parents( '.tabs' );
			tabs.find( 'li' ).removeClass( 'active' );
			$( this ).parent( 'li' ).addClass( 'active' );

			// Panel
			var panels = tabs.parent().find( '.panels' );
			panels.find( '.panel' ).removeClass( 'active' ).hide();
			panels.find( '.panel#' + $( this ).data( 'target' ) ).addClass( 'active' ).show();

		}

	});

	// Remove select2 before cloning to prevent issues with dynamically adding.
	$( '.cost-per-product-wrap' ).on( 'click', '.repeater-add-row', function() {
		$( '#cost_per_product .repeater-template .wc-product-search.enhanced' ).select2( 'destroy' ).removeClass( 'enhanced' );
	});
	$( '#cost_per_product .repeater-template .wc-product-search.enhanced' ).select2( 'destroy' ).removeClass( 'enhanced' );

	// 'Cost per ..' repeat
	$( document ).ready( function() {
		$( '.repeater-wrap' ).repeater({
			addTrigger: '.repeater-add-row',
			removeTrigger: '.repeater-remove-row',
			template: '.repeater-template .repeater-row',
			elementWrap: '.repeater-row',
			elementsContainer: '.repeater-rows',
		});
	});


	// Assign new ID to repeater row + open collapsible + re-enable nested repeater
	$( document.body ).on( 'repeater-added-row', function( e, template, container, $self ) {
		$( document.body ).trigger( 'wc-enhanced-select-init' );
	});

	// Price input validation / error handling
	$( document.body ).on( 'blur', '.waf_input_price[type=text]', function() {
		$( '.wc_error_tip' ).fadeOut( '100', function() { $( this ).remove(); } );
	})
	.on( 'keyup change', '.waf_input_price[type=text]', function() {
		var value    = $( this ).val();
		var regex    = new RegExp( '[^0-9\/\\-\%\*\\' + woocommerce_admin.mon_decimal_point + ']+', 'gi' );
		var newvalue = value.replace( regex, '' );

		if ( value !== newvalue ) {
			$( this ).val( newvalue );
			$( document.body ).triggerHandler( 'wc_add_error_tip', [ $( this ), 'i18n_mon_decimal_error' ] );
		} else {
			$( document.body ).triggerHandler( 'wc_remove_error_tip', [ $( this ), 'i18n_mon_decimal_error' ] );
		}
	})

});