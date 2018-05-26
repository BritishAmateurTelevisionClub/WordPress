$(document).ready(function(){
	
	$('input[type="checkbox"]').click(function(){

		var inputValue = $(this).attr("value");

		if($(this).is(":checked")) {
			$('#logo_qrcode1').show();
			if(inputValue == 'wf_company_switch'){
				
				var sl_text_width_value = $( "#sl_company_logo_or_text" ).val();

					if(sl_text_width_value != 'logo'){
					
						$('#sl_company_name').show();
					}else{
						$('#sllogoimage').show();
					}
			}
		}else{
				$('#logo_qrcode1').hide();
				
			}
		});

	//company name logo change
	jQuery("#collapseTwo1").on('change','#sl_company_logo_or_text',function(e){
		var text_width_value = $( "#sl_company_logo_or_text" ).val();
		var font_size_compname = $("#company_size_font").val();
		if( text_width_value != 'logo' )
		{
			$('#sl_logo_div').hide();
			$('#sl_company_name').show();
		}
		else{
			$('#sl_logo_div').show();
			$('#sl_company_name').hide();
			jQuery('#sl_company_name').css("font-size",font_size_compname+'px');
			
		}
	});

	//height change for logo
	jQuery("#collapseTwo1").on('keyup','#logoheight',function(e){
		var text_height_value = $( "#logoheight" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sllogoimage').css("height",text_height_value+'px');
		}
	});

	//width change for logo
	jQuery("#collapseTwo1").on('keyup','#logowidth',function(e){
		var text_height_value = $( "#logowidth" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sllogoimage').css("width",text_height_value+'px');
		}
	});	

	//logo top margin
	jQuery("#collapseTwo1").on('keyup','#logomarginbottom',function(e){
		var text_height_value = $( "#logomarginbottom" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sllogoimage').css('margin-bottom',text_height_value+'px');
		}
	});

	//logo left margin
	jQuery("#collapseTwo1").on('keyup','#logomarginleft',function(e){
		var text_height_value = $( "#logomarginleft" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sllogoimage').css('margin-left',text_height_value+'px');
		}
	});

	//logo right margin
	jQuery("#collapseTwo1").on('keyup','#logomarginright',function(e){
		var text_height_value = $( "#logomarginright" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sllogoimage').css('margin-right',text_height_value+'px');
		}
	});

	//logo top margin
	jQuery("#collapseTwo1").on('keyup','#logomargintop',function(e){
		var text_height_value = $( "#logomargintop" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sllogoimage').css('margin-top',text_height_value+'px');
		}
	});

	

	//company font size
	jQuery("#collapseTwo1").on('keyup','#company_size_font',function(e){
		var text_height_value = $( "#company_size_font" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sl_company_name').css("font-size",text_height_value+'px');
		}
	});

	//order details font size 
	jQuery("#collapseThree1").on('keyup','#wf_shipping_details_font',function(e){
		var text_height_value = $( "#wf_shipping_details_font" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#slorderDetailsFont').css('font-size',text_height_value+'px');
		}
	});


	//order title
	jQuery("#collapseThree1").on('keyup','#wf_order_id',function(e){
		var text_height_value = $( "#wf_order_id" ).val();		
			jQuery('#ordertitle').text(text_height_value);

	});

	//weight title
	jQuery("#collapseThree1").on('keyup','#wf_weight_id',function(e){
		var text_height_value = $( "#wf_weight_id" ).val();
			jQuery('#weighttitle').text(text_height_value);
	});
	//ship title
	jQuery("#collapseThree1").on('keyup','#wf_ship_date_id',function(e){
		var text_height_value = $( "#wf_ship_date_id" ).val();
			jQuery('#shiptitle').text(text_height_value);
			jQuery('#formatid').text(text_height_value);
	});

	//ship date
	jQuery("#collapseThree1").on('keyup','#wf_ship_date_format',function(e){
		var text_width_value = $( "#wf_ship_date_format" ).val();
		jQuery.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'wf_get_date_format_live',
				date_format: text_width_value
			},
			success: function (data) {

				jQuery('#formatid').text(data);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});

	});	

	//ship date format
	jQuery("#collapseThree1").on('change','#wf_ship_date_format_selection',function(e){
		var text_color_value = $( "#wf_ship_date_format_selection" ).val();
		if(text_color_value != '0')
			{
				jQuery('#wf_ship_date_format').val(text_color_value);
				var text_width_value = $( "#wf_ship_date_format" ).val();
				jQuery.ajax({
					type: 'post',
					url: ajaxurl,
					data: {
						action: 'wf_get_date_format_live',
						date_format: text_width_value
					},
				success: function (data) {
					jQuery('#formatid').text(data);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
				}
			});
		}
	});

	//from title
	jQuery("#collapseFive1").on('keyup','#wf_from_address_title',function(e){
		var text_height_value = $( "#wf_from_address_title" ).val();
		jQuery('#slfromid').text(text_height_value);
	});

	//from title font size
	jQuery("#collapseFive1").on('keyup','#wf_from_title_font',function(e){
		var text_height_value = $( "#wf_from_title_font" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#slfromid').css('font-size',text_height_value+'px');
		}
	});

	//from address font size
	jQuery("#collapseFive1").on('keyup','#wf_from_address_font',function(e){
		var text_height_value = $( "#wf_from_address_font" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#fromAddress').css('font-size',text_height_value+'px');
			jQuery('#slfromid').css('font-size',text_height_value+'px');
		}
	});

	//to title
	jQuery("#collapse71").on('keyup','#wf_to_title',function(e){

		var text_height_value = $( "#wf_to_title" ).val();
		jQuery('#sltoid').text(text_height_value);
	});

	//to title font size
	jQuery("#collapse71").on('keyup','#wf_to_title_font',function(e){
		var text_height_value = $( "#wf_to_title_font" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#sltoid').css('font-size',text_height_value+'px');
		}
	});

	//to address font size
	jQuery("#collapse71").on('keyup','#wf_to_address_font',function(e){
		var text_height_value = $( "#wf_to_address_font" ).val();
		if( $.isNumeric(text_height_value) )
		{
			jQuery('#toAddress').css('font-size',text_height_value+'px');
			jQuery('#sltoid').css('font-size',text_height_value+'px');
		}
	});

});

