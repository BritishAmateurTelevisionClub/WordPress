jQuery(function(){
  // bind change event to select
  jQuery('#country_select').on('change', function () {
      var url = jQuery(this).val(); // get selected value
      if (url) { // require a URL
          window.location = url; // redirect
      }
      return false;
  });

  /*jQuery('#country_select option[value="/category/country/united-kingdom"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');*/

  jQuery(function() {
    var loc = window.location.href; // returns the full URL
    if(/country/.test(loc)) {
      jQuery('body').addClass('membership_archive');
    }
  });

  jQuery('#country_select option[value="/category/country/united-kingdom"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/united-states"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/australia"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/austria"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/belgium"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/croatia"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/cyprus"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/czech-republic"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/denmark"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/estonia"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/finland"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/france"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/germany"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/greece"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/hungary"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/ireland"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/italy"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/latvia"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/lithuania"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/luxembourge"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/malta"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/netherlands"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/poland"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/portugal"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/romania"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/slovakia"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/spain"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="/category/country/sweden"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select').append('<option value="#"></option>');
  jQuery('#country_select').append('<option value="##">Please Select</option>');
  jQuery('#country_select option[value="#"]').insertBefore('#country_select option[value="/category/country/afghanistan"]');
  jQuery('#country_select option[value="##"]').insertBefore('#country_select option[value="/category/country/united-kingdom"]')
  jQuery('#country_select option[value="##"]').attr('selected','selected');


jQuery( "#call_sign_field" ).append( jQuery( ".create-account" ) );


jQuery("#call_sign").keyup(function() {
  var $account_username = jQuery("#account_username");
  var $stream_title = jQuery("#stream_title");
  var $stream_output_url = jQuery("#stream_output_url");
  var $stream_output_url_lower = jQuery("#call_sign").val().toLowerCase();

    $account_username.val( this.value );
    $stream_title.val( this.value );
    $stream_output_url.val( $stream_output_url_lower );

});


jQuery("#call_sign").keyup(function() {
    var first_name = jQuery("#billing_first_name").val();
    var call_sign = jQuery("#call_sign").val();
    jQuery("#chat_name").val( first_name + "_" + call_sign );
});

jQuery('.checked_default input').prop('checked', true);


function makeid() {
  var text = "";
  var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

  for (var i = 0; i < 6; i++)
    text += possible.charAt(Math.floor(Math.random() * possible.length));

  return text;
}
jQuery('#stream_rtmp_input_url').val(makeid);


jQuery("#streaming_type_flash, #streaming_type_html5").on('change', function () {
  jQuery("#streaming_type_flash, #streaming_type_html5").prop( "checked", false );
  jQuery(this).prop( "checked", true );
});

jQuery(".save_changes").click(function(){
  jQuery("#save_changes").click()
})


var minLength = 3;
var maxLength = 10;
jQuery("#account_username").on("keydown keyup change", function(){
    var value = jQuery(this).val();
    if (value.length < minLength)
        jQuery(".username_valid").text("Username is too short");
    else if (value.length > maxLength)
        jQuery(".username_valid").text("Username is too long");
    else
        jQuery(".username_valid").text("");
});
jQuery("#call_sign").on("keydown keyup change", function(){
    var value = jQuery(this).val();
    if (value.length < minLength)
        jQuery(".callsign_valid").text("Callsign is too short");
    else if (value.length > maxLength)
        jQuery(".callsign_valid").text("Callsign is too long");
    else
        jQuery(".callsign_valid").text("");
});

var minLength_cnn = 3;
var maxLength_cnn = 20;
jQuery(".chat_nick_name input").on("keydown keyup change", function(){
    var value = jQuery(this).val();
    if (value.length < minLength_cnn)
        jQuery(".chat_nick_name_valid").text("Chat Nick Name is too short");
    else if (value.length > maxLength_cnn)
        jQuery(".chat_nick_name_valid").text("Chat Nick Name is too long");
    else
        jQuery(".chat_nick_name_valid").text("");
});

jQuery(".username_valid").appendTo("#account_username_field");
jQuery(".callsign_valid").prependTo(".create-account");
jQuery(".chat_nick_name_valid").appendTo(".streaming_wrapper .woocommerce-form-row.woocommerce-form-row--first.form-row.form-row-first:first-of-type");

jQuery('.woocommerce-orders-table__cell .woocommerce-button.button.pay').remove();


});
