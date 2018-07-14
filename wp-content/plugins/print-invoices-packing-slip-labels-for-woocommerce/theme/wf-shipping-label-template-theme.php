<?php
ob_start();
$developer_data='wf_shipping_label_dev_key_70';
$check_data=get_option('wf_developer_tool_for_change_data_shipping_label') === false ? update_option('wf_developer_tool_for_change_data_shipping_label','shipping_label') : 'yes';
if(get_option('wf_developer_tool_for_change_data_shipping_label') && get_option('wf_developer_tool_for_change_data_shipping_label') != $developer_data){

	update_option('wf_shipping_label_template_1',"
		<style>
			.order_details
			{
				float:right;
				line-height:15px;
				margin-right:5%;
			}
			.from_address_details
			{
				float:left; 
				width:49%; 
				margin-top:10px; 
				margin-left:5px; 
				font-size:10px;
			}
			.to_address_details
			{
				width: 100%;
				margin-left: 20%;
			}
		</style>
		<style media='print'>
  @page {
  size: auto;
  margin: 0;
      }
</style>
<div class='RTL_wrapper'>
	<div id='main-div' style='border: 1px solid black; width: [main width];height:[main height];'>
	<div id='logo_qrcode1' style='[hide both]'>
		<div id='sl_logo_div' style='width:100%; [margin top] [margin bottom] [margin bottom] [margin left]'>
			<img id='sllogoimage' src='[image source]' style='padding: 2px;[logo switch]height:[logo image height]; width:[logo image width]' ><br/>
		</div>
		<div id='sl_company_name' style='width:49%; text-align:left; margin: 10px 20px 0 0;[company name font size][text switch]'><strong>[company Name]</strong><br/>
		</div></div>
		<div style='clear:both;'></div>
		</header>
			<div >
				<div class='article' >
					<header>
						<div class='order_details'>
							<div>
								<table id='slorderDetailsFont' style='[order details font]'>
									<tr id='ordertitle'>
										<td >[Order Number Id]</td>
										<td> : </td>
										<td  id='ordertitlevalue'><strong >[Order Number Value]</strong></td>
									</tr>
									<tr id='weighttitle'>
										<td >[Weight]</td>
										<td> : </td>
										<td><strong>[weight value]</strong></td>
									</tr>
									<tr ><div>
										<td id='shiptitle'>[Ship Date]</td></div>
										<td id='formatid'><strong>[ship date value]</strong></td>
									</tr>
									<tr id='boxlabeltitle'>
										
										
										<td>[box name]</td>
									</tr>
								</table>
							</div>
						</div>

						<div class='from_address_details'>
							<div id='slfromid' style='[from font size]padding-bottom:4px;padding-left:5px '><strong>[FROM]</strong></div>
							<div id='fromAddress' style='[from address font size] [from address display] line-height:15px;padding-left:5px;'>
								[Address]
							</div>

							
			</div>


						</div>
						<div style='clear:both;'></div>
		</header>
		<div class='to_address_details'>
			<div id='sltoid' style='padding-left:5px;'><strong style='[to font size]'>[TO]</strong></div>
				<div id='toAddress' style='[to address font size] ;line-height:15px;padding-left:5px;'>
					[To Address]
				</div>
			</div>
		<div class='datagrid'>
			<div style='clear:both;'></div>
			</div>
		<div style='float:right [qr code display]' >[QR Code]</div>
		<div style='clear:both;'></div>
			</div>
		<div style='[hide barcode]'>
		[barcode adjust]
		<div style='text-align:center;[barcode font size]' ><strong>[tracking Number]</strong> </div><center>
		[barcode image source]</center>
		</div>
		</div>
		<div style='clear:both;'></div></div>
		<div style='width: [main width];height:[main height];'>
		<div class='article' style='border-bottom: solid 1px;[return font size][return policy hide]'>[Return Policy]</div>
		<div class='footer' style='[footer font size]'>[Footer]</div></div>
</div>
		");
}


if(get_option('wf_developer_tool_for_change_data_shipping_label') && get_option('wf_developer_tool_for_change_data_shipping_label') != $developer_data ){

	update_option('wf_shipping_label_template_2',"

		<div id='main' style='border: 2px solid black; width: [main width];height:[main height]'>

			<div id='logo_qrcode1' style='border-bottom:1px solid #c5c5c5;[hide both]'>
				<table  style='width: 100%;'>
					<tr><div id='sl_logo_div'>
						<img id='sllogoimage' src='[image source]' style='padding: 2px;[logo switch]height:[logo image height]; width:[logo image width][margin top] [margin bottom] [margin bottom] [margin left]' ></div>
						<div id='sl_company_name' style='width:49%; text-align:left; margin: 10px 20px 0 0;[company name font size][text switch]'><strong>[company Name]</strong><br/>
						</div>
						<th id= 'qrcode1' style='width: 30%; [qr code display]'>[QR Code]</th>
					</tr></table>
			</div>

			<table style='width: 100%;' >
				<tr>
					<td align='center' style='border-right: 1px solid #c5c5c5;line-height: initial;'>
					<br>
							<h5 id='slfromid' style='padding: 0.05;padding-left:5px;margin: 0;[from font size]'>[FROM] :<br></h5>
							<p id='fromAddress' style='[from address display] padding: 0.05;padding-left:5px;margin: 0;[from address font size] '>[Address]</p>
					<br>
					</td>
						
					<td align='center'>
						<div>
						<br>
							<h5 id='sltoid' style='padding: 0.05;padding-left:5px;margin: 0;[to font size] '>[TO] :</h5>
							<div id='toAddress' style='padding: 0.05;padding-left:5px;margin: 0;[to address font size]'>[To Address]</div>
						<br>
						</div>
					</td>
				</tr>
			</table>

			
			<table style='border-top: 1px solid #c5c5c5;width:100%;'>
									<td align='center' style='padding:1%;''>
										<div id='slorderDetailsFont' class='extra' style='line-height: initial;'><p>[box name]</p></div></td>
										
										
				<tr>
					<td align='center' style='padding:1%;''>
						<div id='slorderDetailsFont' class='extra' style='line-height: initial;[order details font]'><p><div id='ordertitle'>[Order Number Id] :[Order Number Value]</div><br><div id='weighttitle'> [Weight] : [weight value]</div><br>
						<div id='shiptitle'> [Ship Date] <label id='formatid'>[ship date value]</label></p></div>
					</td>
				</tr>
			</table>
			<div style='[hide barcode]'>
			<table style='width:100%;'>
				<div style='clear:both;text-align:center;border-bottom: 1px solid #c5c5c5;'>
					<tr >[barcode adjust]
						<div style='padding-top:1%;padding-bottom:0.5%;[barcode font size]' align='center'><strong>[tracking Number]</strong> </div>
						<center>[barcode image source]</center>
						</div>
					</tr>
				</table>
			</div>	
			</div>
			<div style='clear:both;'></div>
			<div style='width: [main width];height:[main height];'>
				<div class='article' style='border-bottom: solid 1px;[return font size][return policy hide]'>[Return Policy]</div>
				<div class='footer' style='[footer font size]'>[Footer]</div>
			</div>	
		");
}


if(get_option('wf_developer_tool_for_change_data_shipping_label') && get_option('wf_developer_tool_for_change_data_shipping_label') != $developer_data ){

	update_option('wf_shipping_label_template_3',"
				<div id='main02' style='border:2px solid black;width: [main width];height:[main height]'>
				<table>
				<tr style='width:100%;'><td style='line-height: initial;width: 60%;border-right:1px solid #c5c5c5;text-align:left;padding-left:10%;'> 
				<div id='fromaddress01' >
						<h5 id='slfromid' style='[from font size] padding: 0;margin: 0;'>[FROM] :</h5>
						<p id='fromAddress' style='font-size:[from address font size];padding: 0;margin: 0;[from address display]'>[Address]</p>
				</div></td>
				<td>
				<div id='extra02' style='float: right;line-height: initial;margin-left: 0;padding-left: 0;'>
				<p id='boxlabeltitle'>
										<p >[box name]</p>
										
										
									
					<p id='slorderDetailsFont' style='[order details font]'> <div id='ordertitle'>[Order Number Id] : [Order Number Value] </div><br><div id='weighttitle'> [Weight] : [weight value]</div><br> <div id='shiptitle'>[Ship Date] <div id='formatid'> [ship date value]</div></p>
					<div id='logo_qrcode1' style='[hide both]'>
					<h3 style='padding: 0;margin: 0;'><div id='sl_logo_div'><img id='sllogoimage' src='[image source]' style='padding: 2px; [logo switch]height:[logo image height]; width:[logo image width][margin top] [margin bottom] [margin bottom] [margin left]' ></div>
						<div id='sl_company_name' style='text-align:right; margin: 10px 20px 0 0;[company name font size][text switch]'><strong>[company Name]</strong><br/>
						</div></h3></div>
				</div></td></tr></table>
				<div id='dummy' style='clear: both;border-bottom: solid #c5c5c5;border-width:1px;'></div>
					<h2 style='float:right;padding: 0;margin: 0;[qr code display]'>[QR Code]</h2> 
				<div id='toaddress02' style='padding-left:5%;padding-top:2%;width: 65%;max-height: 100%;line-height: initial;border-right: solid #c5c5c5;border-width:1px;'>
					<h3 id='sltoid' style='margin: 0;[to font size]'>[TO] :</h3>
					<p id='toAddress' style='padding: 0;margin: 0;[to address font size] '>[To Address]</p>
					<br><br>
				</div>
				<div style='border-top: solid #c5c5c5;border-width:1px; text-align:center;[hide barcode]'>
				[barcode adjust]
		<div style='padding-top:0.7%;[barcode font size]'><strong>[tracking Number]</strong> </div>
		<center>[barcode image source]</center>
		</div></div>
		<div style='width: [main width];height:[main height];'>
		<div class='article' style='border-bottom: solid 1px;[return font size][return policy hide]'>[Return Policy]</div>
		<div class='footer' style='[footer font size]'>[Footer]</div></div>
		");
}

if(get_option('wf_shipping_label_active_key') === false){
	update_option('wf_shipping_label_active_key','wf_shipping_label_template_1');
}
if( get_option('wf_developer_tool_for_change_data_shipping_label') && get_option('wf_developer_tool_for_change_data_shipping_label') != $developer_data){

	update_option('wf_shipping_label_active_value'," dummy|50|50|14|10|10|Order Number|Weight|Ship Date : |14|12|16|14|dummy|10|10|From|To|logo|no|d-m-y|10|0|0|15|10|10");
}

if(get_option('wf_developer_tool_for_change_data_shipping_label') && get_option('wf_developer_tool_for_change_data_shipping_label') != $developer_data)
{
  for ($i=1; get_option('wf_shipping_label_template_'.$i) !='' ;$i++)
  {
    if(get_option('wf_shipping_label_template_'.$i.'from'))
    {
      $data_change = get_option('wf_shipping_label_template_'.$i.'from');
      update_option('wf_shipping_label_template_'.$i,get_option($data_change));
    }
  }

  update_option('wf_developer_tool_for_change_data_shipping_label', $developer_data );
  
}
