jQuery(function(){

  jQuery( ".call_sign" ).insertBefore( ".form-table:nth-of-type(2)" );
  jQuery( ".membership_number" ).insertBefore( ".form-table:nth-of-type(2)" );
  jQuery( ".user-role-wrap" ).insertBefore( ".user-pass1-wrap" );
  jQuery( ".chat_name" ).insertBefore( ".form-table:nth-of-type(4)" );
/*
  jQuery('.woocommerce').each(function() {
      var text = jQuery(this).text();
      jQuery(this).text(text.replace('Check payments', 'doll'));
  });
*/

jQuery(".woocommerce li a:contains('Check payments')").html("Cheque Payment - Memberships");
jQuery(".woocommerce h2:contains('Check payments')").html("Cheque Payment - Memberships");



jQuery("#stream_type_member, #stream_type_repeater, #stream_type_event").on('change', function () {
  jQuery("#stream_type_member, #stream_type_repeater, #stream_type_event").prop( "checked", false );
  jQuery(this).prop( "checked", true );
});

jQuery("#streaming_type_flash, #streaming_type_html5").on('change', function () {
  jQuery("#streaming_type_flash, #streaming_type_html5").prop( "checked", false );
  jQuery(this).prop( "checked", true );
});

});
