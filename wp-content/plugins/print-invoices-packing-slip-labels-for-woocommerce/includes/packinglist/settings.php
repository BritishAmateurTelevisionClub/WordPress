<?php
$woocommerce_wf_enable_packing_slip = get_option('woocommerce_wf_enable_packing_slip') != '' ? get_option('woocommerce_wf_enable_packing_slip') : 'Yes';

$woocommerce_wf_packinglist_disable_total_weight = get_option('woocommerce_wf_packinglist_disable_total_weight') != '' ? get_option('woocommerce_wf_packinglist_disable_total_weight') : 'no';

if(isset($_POST['new_custom_click']))
{
	if(get_option('wf_packing_list_own_meta_field_import'))
	{
		if(isset($_POST['wf_old_custom_filed_pk']) && isset($_POST['wf_old_custom_filed_pk_meta']))
		{
			$data_array =array();
			$data_array = get_option('wf_packing_list_own_meta_field_import');
			$data_array[str_replace( ' ' , '_' ,$_POST['wf_old_custom_filed_pk_meta'])] = $_POST['wf_old_custom_filed_pk'];
			update_option('wf_packing_list_own_meta_field_import',$data_array);

			$data_slected_array = get_option('wf_packinglist_contactno_email') !='' ? get_option('wf_packinglist_contactno_email') : array();
			
			if(!in_array(str_replace( ' ' , '_' ,$_POST['wf_old_custom_filed_pk_meta']),$data_slected_array))
			{
				$data_slected_array[] =str_replace( ' ' , '_' ,$_POST['wf_old_custom_filed_pk_meta']);
				update_option('wf_packinglist_contactno_email',$data_slected_array);
				
			}


		}
	}
	else
	{
		if(isset($_POST['wf_old_custom_filed_pk']) && isset($_POST['wf_old_custom_filed_pk_meta']))
		{
			$data_array =array();
			$data_array[str_replace( ' ' , '_' ,$_POST['wf_old_custom_filed_pk_meta'])] = $_POST['wf_old_custom_filed_pk'];
			update_option('wf_packing_list_own_meta_field_import',$data_array);

			$data_slected_array = get_option('wf_packinglist_contactno_email') !='' ? get_option('wf_packinglist_contactno_email') : array();
			
			if(!in_array(str_replace( ' ' , '_' ,$_POST['wf_old_custom_filed_pk_meta']),$data_slected_array))
			{
				$data_slected_array[] =str_replace( ' ' , '_' ,$_POST['wf_old_custom_filed_pk_meta']);
				update_option('wf_packinglist_contactno_email',$data_slected_array);
				
			}
		}
	}

	if(get_option('wf_packing_list_own_product_meta_field_import'))
	{
		if(isset($_POST['wf_old_product_custom_filed_pk']) && isset($_POST['wf_old_product_custom_filed_pk_meta']))
		{
			$data_array =array();
			$data_array = get_option('wf_packing_list_own_product_meta_field_import');
			$data_array[$_POST['wf_old_product_custom_filed_pk_meta']] = $_POST['wf_old_product_custom_filed_pk'];
			update_option('wf_packing_list_own_product_meta_field_import',$data_array);
			
			$data_slected_array = get_option('wf_packing_list_product_meta_fields') !='' ? get_option('wf_packing_list_product_meta_fields') : array();
			
			if(!in_array($_POST['wf_old_product_custom_filed_pk_meta'],$data_slected_array))
			{
				$data_slected_array[] =$_POST['wf_old_product_custom_filed_pk_meta'];
				update_option('wf_packing_list_product_meta_fields',$data_slected_array);
			}
			

		}
	}
	else
	{
		if(isset($_POST['wf_old_product_custom_filed_pk']) && isset($_POST['wf_old_product_custom_filed_pk_meta']))
		{
			$data_array =array();
			$data_array[str_replace( ' ' , '_' ,$_POST['wf_old_product_custom_filed_pk_meta'])] = $_POST['wf_old_product_custom_filed_pk'];
			update_option('wf_packing_list_own_product_meta_field_import',$data_array);

			$data_slected_array = get_option('wf_packing_list_product_meta_fields') !='' ? get_option('wf_packing_list_product_meta_fields') : array();
			
			if(!in_array(str_replace( ' ' , '_' ,$_POST['wf_old_product_custom_filed_pk_meta']),$data_slected_array))
			{
				$data_slected_array[] =str_replace( ' ' , '_' ,$_POST['wf_old_product_custom_filed_pk_meta']);
				update_option('wf_packing_list_product_meta_fields',$data_slected_array);
				
			}
		}
	}
	
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$('[data-toggle="popover"]').popover({
			html: true,
			template: '<div class="popover"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div><div class="popover-footer"><button name="new_custom_click" id="new_custom_click" class="btn btn-info btn-sm">ADD</a></div></div>'
		});
		
    // Custom jQuery to hide popover on click of the close button
    $(document).on("click", ".popover-footer .btn" , function(){
    	$(this).parents(".popover").popover('hide');
    });
});
</script>
<style type="text/css">
	.bs-example{
		margin: 150px 50px;
	}
	/* Styles for custom popover template */
	.popover-footer{
		padding: 6px 14px;
		background-color: #f7f7f7;
		border-top: 1px solid #ebebeb;
		text-align: right;
	}
	.toggle-box {
		display: none;
		visibility: hidden;
	}

	.toggle-box + label {
		cursor: pointer;
		display: block;
		font-weight: bold;
		line-height: 21px;
		margin-bottom: 5px;
	}

	.toggle-box + label + div {
		display: none;
		margin-bottom: 10px;
	}

	.toggle-box:checked + label + div {
		display: block;
	}

	.toggle-box + label:before {
		background-color: #4F5150;
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		border-radius: 10px;
		color: #FFFFFF;
		content: "+";
		display: block;
		float: left;
		font-weight: bold;
		height: 25px;
		line-height: 25px;
		margin-right: 5px;
		text-align: center;
		width: 25px;
	}

	.toggle-box:checked + label:before {
		content: "\2212";
	}

</style>
<div id="Invoice" class="tabcontent">

	<h3 class="settings_headings"><?php	_e('Packing Slip Settings : ', 'wf-woocommerce-packing-list'); ?></h3>
	<div class="inside shipment-label-printing-preview">
		<table class="form-table">
		<tr>
					<th><span><?php _e('Enable Packing Slip', 'wf-woocommerce-packing-list'); ?></span><div class="woocommerce-help-tip" style="position:absolute;margin-left:30px;"><span class="tooltiptext"><?php _e('Check to enable packing slip','wf-woocommerce-packing-list')?> </span></div></th>
					<td>
						<input type="checkbox" value="Yes" name="woocommerce_wf_enable_packing_slip" class=""<?php if($woocommerce_wf_enable_packing_slip == 'Yes') 
							echo 'checked';
						?> >
						
					</td>
				</tr>
			<tr>
			
							
										<tr>
											<th>
												<span><?php _e('Include Product Image', 'wf-woocommerce-packing-list'); ?></span>
												<div class="woocommerce-help-tip" style="position:absolute;margin-left:auto;padding-left:30px;"><span class="tooltiptext"><?php _e('Check to include item or product image in the packing list','wf-woocommerce-packing-list')?> </span></div>
											</th>
											<td>
												<input type="checkbox" value="Yes" name="woocommerce_wf_attach_image_packinglist" class=""<?php if(get_option('woocommerce_wf_attach_image_packinglist')== "Yes") 
												echo 'checked';
												?> >
												<br>
												<span class="description"><?php _e('Enable to include Product image in packing list',''); ?>
												</span>
											</td>
										</tr>
										

										

										
										
										
										</table>	

								</div>
							</div>

