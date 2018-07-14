<?php 
ob_start();
$developer_value = 'wf_invoice_dev_keys_355'; 
$check_data = get_option('wf_developer_tool_for_change_data_in') ===false ? update_option('wf_developer_tool_for_change_data_in','print_invoice') : 'yes'; 
if(get_option('wf_developer_tool_for_change_data_in') && get_option('wf_developer_tool_for_change_data_in') != $developer_value )
{
 update_option('wf_invoice_template_1', "
<link href='[wf link]assets/new_invoice_css_js/font-awesome/css/font-awesome.css' rel='stylesheet'>
<link href='[wf link]assets/new_invoice_css_js/css/custom.min.css' rel='stylesheet'>
<style>
#wpfooter
{
  position:unset !important;
}
.invoice
{
  
  margin:0px;
}
.header_left
{
  float:left; 
  width:auto; 
  text-align:left;
}
.header_right
{
  float:right; 
  width:25%; 
  text-align:right;
}
.qty {
   border: 1px solid lightgrey !important; padding:5px;  width: auto;line-height:2;
 }
 .desc {
   border: 1px solid lightgrey !important; padding:10px;  width: auto;
 }
 .unit {
   border: 1px solid lightgrey !important;line-height:2; padding:5px; min-width: 70px; max-width: 90px; width: auto;
 }
 .total{
   border: 1px solid lightgrey !important; padding:5px; width: auto; line-height:2;
 }
 .new_table_value
 {
  background: #[table background color];
  color: #FFFFFF;
}
.new_table_border
{

 border: 1px solid #[table background color] !important;
}
.tableclass tbody{
  font-family: DejaVu Sans, Helvetica, sans-serif;;
}
th{
  text-align:unset;
}
.billing_address{
  float:left; 
  width: 49%;
}
.shipping_address{
  float:right; 
  width: 49%;
}
.invoice
{
  margin: auto;
}
.invoice-meta
{
  float: left;
  width: 30%;
  line-height: 1.4;
}
.invoice-meta .invoice-number {
    font-size: 15px;
    vertical-align: top;
}
.invoice-meta .invoice-number p {
  margin-bottom: 0;
  margin-top: 0;
}
.address-fields{
  float: left;
  width: 35%;
  padding-left: 30px;
}
.address-fields .address-field-header {
  font-weight: 600;
  font-size: 15px;
  margin-bottom:0;
  margin-top: 0;
}
* {transition: none !important;
-webkit-print-color-adjust:exact;}
</style>

<style media='print'>
@page {
 size: auto;
 margin: 0;
      }
      .invoice{
        padding-left:30px;
        padding-right:30px;
      }
      .new_table_border{
        border-collapse: collapse !important;
        border: 1px solid #[table background color] !important;
      }
      body
      {
        font-family: Arial, sans-serif;;
      }
</style>
<div class='RTL_wrapper'>
  <div class='invoice' style='[invoice main height and width];padding-top:30px;'>
    <header>
      <div class='header_left'>
        <h1>
        <label id='company_text_image' style='[company text show hide]'> [company name] </label>
            <img style='[company logo visible]' class='logo' src='[image url for company logo]' alt='' id='company_logo_image' height='[logo height]' width='[logo width]'/>
        </h1><br/>
        <div id='wf_extra_data_below_logo' style='[wf extra filed show hide][invoice extra field font size]'> [Extra data below logo] </div> 
      </div>
      <div style='float:right; width:25%;[from address show hide][from address left right][from address text color]' id ='wf_from_address_filed' >
        <strong id='wf_from_address_title_main'> [from address label] </strong>
        <p style='margin-top:0px;'> [from address]
        </p>
      </div>
      <div style='clear:both;'></div>
    </header>
    <div class='article'>
      <header class='clearfix'> 
        <table class='invoice-meta'>
          <tr>
            <td class='invoice-number'>
              <p style='[invoice number switch][invoice number prob][invoice_number_color][invoice font weight]' id='wf_invoice_name'><font id='wf_invoice_label'>[invoice name]</font><font>[invoice number]</font></p> <!--hide invoice no-->

              <p style='[order number switch][order number prob][order_number_color][order font weight]' id='wf_order_number'><font id='wf_order_number_label'>[order number label]</font><font>[order number]</font></p> <!--hide order no-->

              <p style='[invoice date show hide][invoice date font size][invoice date color code][invoice date label font weight]' id='wf_invoice_date'><font id='wf_invoice_date_label'>[invoice Date label text]</font><font>[invoice created date]</font></p><!--hide invoice date-->

              <p style='[order date show hide][order date font size][order date color code][order date label font weight]' id='wf_order_date'><font id='wf_order_date_label'>[order date label]</font><font> [order date]</font></p> <!--hide order date-->

              <p style='[wf email show hide]' id='wf_font_size_for_email'><font id='wf_email_text_main'><font>[email label]</font></font> [email address]</p>

              <p style='[wf tel show hide][wf tel font size][wf tel position set][wf_tel color code default]' id='wf_font_size_for_tel'><font id='wf_tel_text_main'><font>[mobile label]</font></font>[mobile number]</p>

              <p style='[wf vat show hide][wf vat font size][wf vat position set][wf_vat color code default]' id='wf_font_size_for_vat'><font id='wf_vat_text_main'><font>[VAT label]</font></font> [VAT data]</p>

              [invoice extra firlds import old one]

            </td> 
          </tr>
        </table>
        <div style='[billing address show hide][billing address left right][billing address text color]' id ='wf_billing_address_filed' class='address-fields'> <!--hide billing address-->
          <h6 id='wf_billing_address_title_main' class='address-field-header'>
            [billing address label]
          </h6>
            [billing address data]
        </div>  
        <div style='[shipping address show hide][shipping address left right][shipping address text color]' id ='wf_shipping_address_filed' class='address-fields'> <!--hide shipping address-->
          <h6 id='wf_shipping_address_title_main' class='address-field-header'>
            [shipping address title]
              </h6>
            [shipping address data]
        </div>      
      </header>
      <br/>
      <div class='datagrid' id='wf_product_table_main_tag' style='[wf product table show hide];margin-top:10px;'>        
        <table class = 'tableclass' style='width:100%;border:1px solid [wf product table head color];  border-collapse: collapse;border-bottom-width:thin; border-top-width:thin;' cellspacing='0' cellpadding='0' class='new_table_border' colspan='[table colum span]'>

          <thead style='display:table-header-group;[wf product table head color][wf product table head text color]' class='new_table_value' id='wf_product_head'>
              <tr style='width:100%;page-break-inside:avoid; page-break-after:auto;[wf product table text align]' id='wf_head_tr_align_purpose'>

                  <th scope='col' id='th_sku' class='new_table_border' style='[table colum span hide];border-top-width: thin; padding: 10px;'>
                  [sku label text]
                  </th>

                  <th scope='col' class='new_table_border' colspan='[table colum span]' id='th_product' style=' border-top-width: thin; padding: 10px;'>[product label text]
                  </th>
                  <th scope='col' class='new_table_border' id='th_qty' style='border-top-width: thin; padding: 10px;'>[table quantity text]
                  </th>
                  <th scope='col' class='new_table_border' id='th_price' style='border-top-width: thin; padding: 10px;'>[table price text]
                  </th>
                  <th scope='col' class='new_table_border' id='th_total' style='border-top-width: thin; padding: 10px;'>[table toatl price text]
                  </th>
              </tr>
          </thead>
          <tbody style='[wf product table text color main][wf product table body text align]' id='wf_product_body'>
            [table tbody content value]
            <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;'>
                <th colspan='2'  style='border-left: 1px solid lightgrey;color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_st' style='font-size:15px;color:black;text-align:center; border-bottom: 1px solid lightgrey; padding: 5px;'>
                [table subtotal label]
                </th>
                <td style='font-size:15px;color:black;text-align:center; padding: 5px;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                [table subtotal value]
                </td>
               </tr>
                <tr style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;[wf shipping show hide]' >
                  <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_shipping' style='font-size:15px;padding:3px;color:black;text-align:center;border-bottom: 1px solid lightgrey;'>
                [table shipping select text]
                </th>
                <td style='font-size:15px;color:black;text-align:center; padding: 5px;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                [table shipping select value]
                </td>
              </tr>
              <tr style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;[wf cd show hide]'>
                <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_cd' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                  [table cart discount text]
                </th>
                <td style='font-size:15px;color:black;text-align:center; padding: 5px;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                  [table cart discount value]
                </td>
              </tr>
              <tr style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;[wf od show hide]'>
                <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_od' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                  [table Order discount text]
                </th>
                <td style='font-size:15px;color:black;text-align:center;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                  [table Order discount value]
                </td>
              </tr>
              [table tax items]

              <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[wf tt show hide]'>
                <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_tt' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                  [table Total Tax text]
                </th>
                <td style='font-size:15px;color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                  [table tax item value]
                </td>
              </tr>

              <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[wf fee show hide]'>
              <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
              <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
              <th scope='row' id='wf_id_for_fee' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                [fee text]
              </th>
              <td style='font-size:15px;color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                [fee total value]
              </td>
              </tr>

              <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[wf total show hide]'>
                <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_total' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                  [table invoice total label]
                </th>
                <td style='font-size:15px;color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                  [table invoice total value]
                </td>
              </tr>
              <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[payment method show hide]'>
                <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' id='wf_id_for_paym' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                  [table payment info label]
                </th>
                <td style='font-size:15px;color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                  [table payment info value]
                </td>
              </tr>
              <tr style='[table coupon show hide]color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;'>
                <th colspan='2' style='border-left: 1px solid lightgrey;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th colspan='1'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
                <th scope='row' style='font-size:15px;color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
                  [table coupon info label]
                </th>
                <td style='font-size:15px;color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
                  [table coupon info value]
                </td>
              </tr>
          </tbody>
          
        </table>
          <div style='[sign align];signature_img_option_style_data'>
            <p id='wf_font_size_for_signature'><font id='wf_signature_text_main' style='signature_text_option_style_data'>[Signature label]</font>
            </p>
            <img style='' class='logo' src='[image url for signature]' alt='' id='company_sign_image' height='[image height]' width='[image width]'/>
          </div>
          <div style='clear:both;'></div>
      </div>
    </div>
    <div style='clear:both;'></div>
    <div style='[wffootor style] clear:both'>
      <div class='article' style='[invoice return policy hide] lightgrey;font-size:[return policy content size]px;'>
        [invoice return policy data]
      </div>
    </div>      
    <div style='clear:both;'></div>
  </div>
</div>
"); 
}

if(get_option('wf_invoice_active_key') === false )
{
  update_option('wf_invoice_active_key','wf_invoice_template_4');
  update_option('wf_invoice_template_4', get_option('wf_invoice_template_1'));
  update_option('wf_invoice_template_4custom', true);
}
$default_language = get_option('WPLANG');
$default_language = empty($default_language) ? 'eng' : $default_language;
$installed_language = get_option('WF_INVOICE_LANG');

if( (get_option('wf_developer_tool_for_change_data_in') && get_option('wf_developer_tool_for_change_data_in') != $developer_value) || (empty($installed_language) || $default_language != $installed_language) )
{
  update_option('wf_invoice_active_value',"auto|60|yes|logo|yes|16|bold|".$this->invoice_labels['document_name'].":|default|no|d-m-Y|16|".$this->invoice_labels['order_date'].":|normal|default|yes|d-m-Y|16|".$this->invoice_labels['date_txt'].":|normal|default|yes|".$this->invoice_labels['from_addr']." :|left|default|yes|".$this->invoice_labels['billing_address'].":|left|default|yes|".$this->invoice_labels['shipping_address'].":|left|default|yes|14|Email:|left|default|yes|14|Tel:|left|default|yes|14|VAT:|left|default|yes|14|SSN:|left|default|yes|14|".$this->invoice_labels['tracking_provider'].":|left|default|yes|14|".$this->invoice_labels['tracking_number'].":|left|default|yes|default|default|center|default|center|".$this->invoice_labels['sku']."|".$this->invoice_labels['product_name']."|".$this->invoice_labels['quantity']."|".$this->invoice_labels['total_price']."|".$this->invoice_labels['sub_total']."|".$this->invoice_labels['shipping']."|".$this->invoice_labels['cart_discount']."|". $this->invoice_labels['order_discount'] ."|".$this->invoice_labels['total_tax']."|".$this->invoice_labels['total']."|".$this->invoice_labels['payment_method']."|none|18|yes|14|".$this->invoice_labels['signature']."|left|auto|60"."|".$this->invoice_labels['price']."|16|Order No:|normal|default"."|".$this->invoice_labels['fee_txt']."|yes");

    update_option('WF_INVOICE_LANG',$default_language);
}

if(get_option('wf_developer_tool_for_change_data_in') && get_option('wf_developer_tool_for_change_data_in') != $developer_value )
{
  for ($i=1; get_option('wf_invoice_template_'.$i) !='' ;$i++)
  {
    if(get_option('wf_invoice_template_'.$i.'from'))
    {
      $data_chenge = get_option('wf_invoice_template_'.$i.'from');
      update_option('wf_invoice_template_'.$i,get_option($data_chenge));
    }
  }

  update_option('wf_developer_tool_for_change_data_in', $developer_value );
  
}