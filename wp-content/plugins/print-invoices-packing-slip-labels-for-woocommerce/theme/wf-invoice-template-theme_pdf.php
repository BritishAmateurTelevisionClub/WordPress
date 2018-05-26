<?php 
ob_start();
$developer_value = 'wf_invoice_dev_com_4';
$check_data = get_option('wf_developer_tool_for_change_data_in_pdf') ===false ? update_option('wf_developer_tool_for_change_data_in_pdf','print_invoice') : 'yes'; 
if(get_option('wf_developer_tool_for_change_data_in_pdf') && get_option('wf_developer_tool_for_change_data_in_pdf') != $developer_value )
{
  update_option('wf_invoice_template_1pdf', "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />

<style type='text/css'>
#wpfooter
{
  position:unset !important;
}
.qty {
   border: 1px solid lightgrey !important; padding:5px;  width: auto;
 }
 .desc {
   border: 1px solid lightgrey !important; padding:10px;  width: auto;
 }
 .unit {
   border: 1px solid lightgrey !important; padding:5px; min-width: 70px; max-width: 90px; width: auto;
 }
 .total{
   border: 1px solid lightgrey !important; padding:5px; width: auto; 
 }
 .new_table_value
 {
  background: [table background color];
  color: #FFFFFF;
}
.new_table_border
{
 border: 1px solid [table background color] !important;
}
* {transition: none !important;
-webkit-print-color-adjust:exact;}
</style>
</head>
<body style='width:85%;border: 3px solid lightgrey;padding:10px;line-height:26px;'>
<div style='[invoice main height and width]'>
  <header>
    <div style='float:left; width:auto; text-align:left;'>
    <h1 style='[extra field1 size]'>
    <label id='company_text_image' style='[company text show hide]'> [company name] </label>
    <img style='[company logo visible]' class='logo' src='[image url for company logo]' alt='' id='company_logo_image' height='[logo height]' width='[logo width]' />

    </h1><br/>
      <div id='wf_extra_data_below_logo' style='[wf extra filed show hide][invoice extra field font size]'> [Extra data below logo] </div>
      <strong>
        <div style='[invoice number switch][invoice number prob][invoice_number_color]' id='wf_invoice_name'>
          <label id='wf_invoice_label' style='[invoice font weight]'>[invoice name]</label> [invoice number]
        </div>
      </strong>
      <div style='[invoice date font size][invoice date color code][invoice date show hide]' id='wf_invoice_date'> <label id='wf_invoice_date_label' style='[invoice date label font weight]' > [invoice Date label text] </label> <label style='font-weight:normal' id='wf_invoice_main_date' >[invoice created date]</label> 
      </div>
      <div style='[order date font size][order date color code][order date show hide]' id='wf_order_date'> <label id='wf_order_date_label' style='[order date label font weight]' > [order date label]</label> <label style='font-weight:normal' id='wf_order_main_date' >[order date]</label> 
      </div>
    </div>
    <div style='float:right; width:25%;[from address show hide][from address left right][from address text color]' id ='wf_from_address_filed' >
      <p style='font-size:[from address font size]px;margin-top:0px;'> [from address]
      </p>
    </div>

    <div style='clear:both;'></div>
  </header>
  <div>
    <div class='article' >
      <header style='height: 200px;'>
        <div style='float:left; width: 49%;font-size:[billing address title size]px;'>
        <div style='font-size:[billing address title size]px; margin-top: 2px;[billing address show hide][billing address left right][billing address text color]' id ='wf_billing_address_filed'>
          <p style='margin-bottom: 0px;'  id='wf_billing_address_title_main'><strong>[billing address label] </strong></p>
          <p style='font-size:[billing address font size]px;  margin-top: 2px;'>
            [billing address data]
          </p>
        </div>

        <p style='padding:unset;margin:unset;line-height:unset;[wf email show hide][wf email font size][wf email position set][wf_email color code default]' id='wf_font_size_for_email'><font id='wf_email_text_main'><strong>[email label]</strong></font> [email address]</p>
        
        <p style='padding:unset;margin:unset;line-height:unset;[wf tel show hide][wf tel font size][wf tel position set][wf_tel color code default]' id='wf_font_size_for_tel'><font id='wf_tel_text_main'><strong>[mobile label]</strong></font>[mobile number]</p>
        
        <p style='padding:unset;margin:unset;line-height:unset;[wf vat show hide][wf vat font size][wf vat position set][wf_vat color code default]' id='wf_font_size_for_vat'><font id='wf_vat_text_main'><strong>[VAT label]</strong></font> [VAT data]</p>
        
         [invoice extra firlds import old one]
      
      </div>

       <div style='float:right; width: 49%;font-size:[shipping address title size]px;'>
        <div style='font-size:[shipping address title size]px; margin-top: 2px;[shipping address show hide][shipping address left right][shipping address text color]' id ='wf_shipping_address_filed'>
         <p  id='wf_shipping_address_title_main' style='margin-bottom: 0px'><strong>[shipping address title]</strong></p>
          <p style='margin-top: 2px;font-size:[shipping address content size]px;' >
            [shipping address data]
          </p>
        </div>
        <p style='padding:unset;margin:unset;line-height:unset;[wf tp show hide][wf tp font size][wf tp position set][wf_tp color code default]' id='wf_font_size_for_tp'><font id='wf_tp_text_main'><strong>[tracking label]</strong></font> [tracking data]</p>
        
        <p style='padding:unset;margin:unset;line-height:unset;[wf tn show hide][wf tn font size][wf tn position set][wf_tn color code default]' id='wf_font_size_for_tn'><font id='wf_tn_text_main'><strong>[tracking number label]</strong></font>[tracking number data]</p>
        
        <p style='padding:unset;margin:unset;line-height:unset;[wf ssn show hide][wf ssn font size][wf ssn position set][wf_ssn color code default]' id='wf_font_size_for_ssn'><font id='wf_ssn_text_main'><strong>[SSN label]</strong></font>[SSN data]</p>
        
        [invoice extra firlds import]
      
      </div>
      <div style='clear:both;'></div>
    </header>
    <br>
    <div class='datagrid' id='wf_product_table_main_tag' style='[wf product table show hide]'>
      <table class='tableclass' style='width: 100%;[border-base-theme-color] border-collapse: collapse;border-bottom-width: thin; border-top-width: thin;' cellspacing='0' cellpadding='0' class='new_table_border' colspan='[table colum span]'>
        <thead style='display:table-header-group;[wf product table head color][wf product table head text color]' class='new_table_value' id='wf_product_head'>
          <tr style='width:100%;background:unset;color:unset; font-size:[table header font size]px;page-break-inside:avoid; page-break-after:auto;[wf product table text align]' id='wf_head_tr_align_purpose'>

           <th scope='col' id='th_sku' class='new_table_border' style='[table colum span hide]background:unset;color:unset;text-align:unset; border-top-width: thin; padding: 5px;'>
            [sku label text]
          </th>

          <th scope='col' class='new_table_border' colspan='[table colum span]' id='th_product' style='background:unset;color:unset;text-align:unset; border-top-width: thin; padding: 5px;'>[product label text]
          </th>
          <th scope='col' class='new_table_border' id='th_qty' style='background:unset;color:unset;text-align:unset;  border-top-width: thin; padding: 5px;'>[table quantity text]
          </th>
          <th scope='col' class='new_table_border' id='th_price' style='background:unset;color:unset;text-align:unset; border-top-width: thin; padding: 5px;'>[table price text]
          </th>
          <th scope='col' class='new_table_border' id='th_total' style='background:unset;color:unset;text-align:unset; border-top-width: thin; padding: 5px;'>[table toatl price text]
          </th>
        </tr>
      </thead>
      <tfoot style='font-size:[table tfoot content size]px; border:1px solid lightgrey !important;'>
        <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;'>
          <th colspan='2'  style='color:black;text-align:center; border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_st' style='color:black;text-align:center; border-bottom: 1px solid lightgrey; padding: 5px;'>
            [table subtotal label]
          </th>
          <td style='color:black;text-align:center; padding: 5px;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table subtotal value]
          </td>
        </tr>
        <tr style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;[wf shipping show hide]' >
          <th colspan='2' style='color:black;text-align:center;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_shipping' style='padding:3px;color:black;text-align:center;border-bottom: 1px solid lightgrey;'>
            [table shipping select text]
          </th>
          <td style='color:black;text-align:center; padding: 5px;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table shipping select value]
          </td>
        </tr>
        <tr style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;[wf cd show hide]'>
          <th colspan='2' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_cd' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
            [table cart discount text]
          </th>
          <td style='color:black;text-align:center; padding: 5px;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table cart discount value]
          </td>
        </tr>
        <tr style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;[wf od show hide]'>
          <th colspan='2' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_od' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
            [table Order discount text]
          </th>
          <td style='color:black;text-align:center;border-right: 1px solid lightgrey; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table Order discount value]
          </td>
        </tr>
        [table tax items]
        <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[wf tt show hide]'>
          <th colspan='2' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_tt' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
            [table Total Tax text]
          </th>
          <td style='color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table tax item value]
          </td>
        </tr>
        <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[wf total show hide]'>
          <th colspan='2' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_total' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
            [table invoice total label]
          </th>
          <td style='color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table invoice total value]
          </td>
        </tr>
        <tr style='color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;[payment method show hide]'>
          <th colspan='2' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' id='wf_id_for_paym' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
            [table payment info label]
          </th>
          <td style='color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table payment info value]
          </td>
        </tr>
        <tr style='[table coupon show hide]color:black;text-align:center;border-bottom: 1px solid lightgrey;width:100%;'>
          <th colspan='2' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>&nbsp;</th>
          <th scope='row' style='color:black;text-align:center; padding: 3px;border-bottom: 1px solid lightgrey;'>
            [table coupon info label]
          </th>
          <td style='color:black;text-align:center;border-right: 1px solid lightgrey; padding: 5px; border-bottom: 1px solid lightgrey;border-top: 1px solid lightgrey;border-left: 1px solid lightgrey;'>
            [table coupon info value]
          </td>
        </tr>
      </tfoot>

      <tbody style='font-size:[table tbody content label]px;[wf product table text color main][wf product table body text align]' id='wf_product_body'>
      
      </tbody>
    </table>
    [invoice barcode data]
    <div style='clear:both;'></div>
  </div>
  <div style='clear:both;'></div>
</div>
</div>
<div style='[wffootor style]'>
  <div class='article' style='border-bottom: solid 1px lightgrey;font-size:[return policy content size]px;'>
    [invoice return policy data]
  </div>
  <div class='footer' style='font-size:[footor content size]px;bottom:10px;' >[invoice footor data]</div>
</div>      
<div style='clear:both;'></div>
</div>
</body>
</html>
"); 
}
if(get_option('wf_developer_tool_for_change_data_in_pdf') && get_option('wf_developer_tool_for_change_data_in_pdf') != $developer_value )
{
update_option('wf_invoice_template_2pdf',"
   <html>      <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>Print Shipment Label</title>
    
  <style type='text/css'>
html, body, div, span, applet, object, iframe,
h1, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
  margin: 0;
  padding: 0;
  border: 0;
  font: inherit;
  font-size: 100%;
  vertical-align: baseline;
}

#wpfooter
{
   position:unset !important;
}

ol, ul {
  list-style: none;
}

table {
  border-collapse: collapse;
  border-spacing: 0;
}

caption, th, td {
  text-align: left;
  font-weight: normal;
  vertical-align: middle;
}

q, blockquote {
  quotes: none;
}
q:before, q:after, blockquote:before, blockquote:after {
  content: '';
  content: none;
}

a img {
  border: none;
}

article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
  display: block;
}

body {
  font-family: 'Source Sans Pro', sans-serif;
  font-weight: 300;
  font-size: 12px;
  margin: 0;
  padding: 0;
  color: #555555;
} 
body a {
  text-decoration: none;
  color: inherit;
}
body a:hover {
  color: inherit;
  opacity: 0.7;
}
body .container {
  min-width: 460px;
  margin: 0 auto;
  padding: 0 10px;
}
body .clearfix:after {
  content: '';
  display: table;
  clear: both;
}
body .left {
  float: left;
}
body .right {
  float: right;
}
body .helper {
  display: inline-block;
  height: 100%;
  vertical-align: middle;
}
body .no-break {
  page-break-inside: avoid;
}
section .details .right{
  float: right;
}
header {
  margin-top: 15px;
  margin-bottom: 25px;
}
header figure {
  float: left;
  margin-right: 10px;
  text-align: center;
}
header figure img {
  margin-top: 10px;
}
header .company-info {
  float: right;
  color: #66BDA9;
  line-height: 14px;
}
header .company-info .address, header .company-info .phone, header .company-info .email {
  position: relative;
}
header .company-info .address img, header .company-info .phone img {
  margin-top: 2px;
}
header .company-info .email img {
  margin-top: 3px;
}
header .company-info .title {
  color: #66BDA9;
  font-weight: 400;
  font-size: 1.33333333333333em;
}
header .company-info .icon {
  position: absolute;
  left: -15px;
  top: 1px;
  width: 10px;
  height: 10px;
  background-color: #66BDA9;
  text-align: center;
  line-height: 0;
}

section .details {
  min-width: 440px;
  margin-bottom: 25px;
  padding: 5px 10px;
  background-color: #CC5A6A;
  color: #ffffff;
  line-height: 20px;
}
section .details .client {
  width: 50%;
}
section .details .client .name {
  font-size: 1.16666666666667em;
  font-weight: 600;
}
section .details .data {
  width: 50%;
  text-align: right;
}
section .details .data .name {
  font-size: 1.16666666666667em;
  font-weight: 600;
}
section .details .title {
  margin-bottom: 5px;
  font-size: 1.33333333333333em;
  text-transform: uppercase;
}
section table {
  width: 100%;
  margin-bottom: 20px;
  table-layout: fixed;
  border-collapse: collapse;
  border-spacing: 0;
}
section table .qty, section table .unit, section table .total {
  width: 15%;
}
section table .desc {
  width: 55%;
}
section table thead {
  display: table-header-group;
  vertical-align: middle;
  border-color: inherit;
}
section table thead th {
  padding: 7px 10px;
  background: #66BDA9;
  border-right: 5px solid #FFFFFF;
  color: white;
  text-align: center;
  font-weight: 400;
  text-transform: uppercase;
}
section table thead th:last-child {
  border-right: none;
}
section table tbody tr:first-child td {
  border-top: 10px solid #ffffff;
}
section table tbody td {
  padding: 10px 10px;
  text-align: center;
  border-right: 3px solid #66BDA9;
}
section table tbody td:last-child {
  border-right: none;
}
section table tbody td.desc {
  text-align: left;
}
section table tbody td.qty {
  text-align: left;
}
section table tbody td.total {
  color: #66BDA9;
  font-weight: 600;
  text-align: right;
}
section table tbody font {
  margin-bottom: 5px;
  color: #66BDA9;
  font-weight: 600;
}
section table.grand-total {
  margin-bottom: 50px;
}
section table.grand-total tbody tr td {
  padding: 0px 10px 12px;
  border: none;
  background-color: #B2DDD4;
  color: #555555;
  font-weight: 300;
  text-align: right;
}
section table.grand-total tbody tr:first-child td {
  padding-top: 12px;
}
section table.grand-total tbody tr:last-child td {
  background-color: transparent;
}
section table.grand-total tbody .grand-total {
  padding: 0;
}
section table.grand-total tbody .grand-total div {
  float: right;
  padding: 11px 10px;
  background-color: #66BDA9;
  color: #ffffff;
  font-weight: 600;
}
section table.grand-total tbody .grand-total div span {
  display: inline-block;
  margin-right: 20px;
  width: auto;
}

footer {
  margin-bottom: 15px;
  padding: 0 5px;
}
footer .thanks {
  margin-bottom: 40px;
  color: #66BDA9;
  font-size: 1.16666666666667em;
  font-weight: 600;
}
footer .notice {
  margin-bottom: 15px;
}
footer .end {
  padding-top: 5px;
  border-top: 2px solid #66BDA9;
  text-align: center;
}
.default-table-data{

  background: #66BDA9;
  color:white;
}
section .details .left{
  float: left;

}

  </style>
  </head>
  <body style='width:85%;border: 3px solid lightgrey;padding:10px;' >
  
<div>
  <header class='clearfix' style='margin-bottom: 8px;'>
    <div class='container' style='display: table;clear: both;width:100%;'>
      <figure>
      <h1 style='font-size:50px;'>
      <label id='company_text_image' style='[company text show hide]'> [company name] </label>
    <img style='[company logo visible]' class='logo' src='[image url for company logo]' alt='' id='company_logo_image' height='[logo height]' width='[logo width]' />

      </h1>
      </figure>
     
      <div class='company-info' >
         <div class='address' id ='wf_from_address_filed' style='[from address show hide][from address left right][from address text color]'> 
          <p>
            [from address]
          </p>
        </div>
        
      </div>

    </div>
    
  </header>
  <div id='wf_extra_data_below_logo' style='margin-bottom:5px;padding-left: 10px;[wf extra filed show hide][invoice extra field font size]'> [Extra data below logo] </div>
  <section> 
     <div class='container' style='margin-bottom: 25px;
padding: 5px;
background-color: #cc5a6a;
color: #ffffff;'>
      <div class='details clearfix'>
        <div class='client left'>
          <p class='name' id='wf_invoice_name' style='[invoice number switch][invoice number prob][invoice_number_color]'><label id='wf_invoice_label' style='[invoice font weight]'>[invoice name]</label> [invoice number]</p>
        </div>
        <div class='data right'>
          <p class='name' id='wf_invoice_date' style='[invoice date font size][invoice date color code][invoice date show hide]'><label id='wf_invoice_date_label' style='[invoice date label font weight]' > [invoice Date label text] </label> <label style='font-weight:normal' id='wf_invoice_main_date' >[invoice created date]</label></p>
        </div>
        <div class='data right'>
          <p class='name' id='wf_order_date' style='[order date font size][order date color code][order date show hide]'><label id='wf_order_date_label' style='[order date label font weight]' > [order date label]</label> <label style='font-weight:normal' id='wf_order_main_date' >[order date]</label></p>
        </div>
      </div>
    </div>
</section>
  <section>
    <div class='container'>
      <div class='details clearfix' style='min-height: 170px;'>
        <div class='client left'  >
        <div id ='wf_shipping_address_filed' style='[shipping address show hide][shipping address left right][shipping address text color]'>
          <p class='name' id='wf_shipping_address_title_main'>[shipping address title]</p>
          <p> [shipping address data] </p>

        </div >
        
          <p style='padding:unset;margin:unset;line-height:unset;[wf email show hide][wf email font size][wf email position set][wf_email color code default]' id='wf_font_size_for_email'><font id='wf_email_text_main'>[email label]</font> [email address]</p>
      <p style='padding:unset;margin:unset;line-height:unset;[wf tel show hide][wf tel font size][wf tel position set][wf_tel color code default]' id='wf_font_size_for_tel'><font id='wf_tel_text_main'>[mobile label]</font>[mobile number]</p>
            <p style='padding:unset;margin:unset;line-height:unset;[wf vat show hide][wf vat font size][wf vat position set][wf_vat color code default]' id='wf_font_size_for_vat'><font id='wf_vat_text_main'>[VAT label]</font> [VAT data]</p>
      [invoice extra firlds import]
          
        </div>
        <div class='data right' id ='wf_billing_address_filed' style='[billing address show hide][billing address left right][billing address text color]'>
          <p class='name' id='wf_billing_address_title_main'>[billing address label]</p>
          <p> [billing address data] </p>
        </div>

         <p style='padding:unset;margin:unset;line-height:unset;[wf tp show hide][wf tp font size][wf tp position set][wf_tp color code default]' id='wf_font_size_for_tp'><font id='wf_tp_text_main'>[tracking label]</font> [tracking data]</p>
      <p style='padding:unset;margin:unset;line-height:unset;[wf tn show hide][wf tn font size][wf tn position set][wf_tn color code default]' id='wf_font_size_for_tn'><font id='wf_tn_text_main'>[tracking number label]</font>[tracking number data]</p>
     
             <p style='padding:unset;margin:unset;line-height:unset;[wf ssn show hide][wf ssn font size][wf ssn position set][wf_ssn color code default]' id='wf_font_size_for_ssn'><font id='wf_ssn_text_main'>[SSN label]</font>[SSN data]</p>
             [invoice extra firlds import old one]
      </div>
      <div id='wf_product_table_main_tag' style='[wf product table show hide]'>
      <table border='0' cellspacing='0' cellpadding='0' style='page-break-inside:auto' colspan='[table colum span]'>
        <thead style='display:table-header-group;[wf product table head color][wf product table head text color]' id='wf_product_head' class='default-table-data'>
          <tr style='page-break-inside:avoid; page-break-after:auto;[wf product table text align]' id='wf_head_tr_align_purpose'>
            <th class='qty' id='th_sku' style='[table colum span hide]text-align:unset;background:unset;color:unset;'>[sku label text]</th>
            <th class='desc' colspan='[table colum span]' style='width: 59%;text-align:unset;background:unset;color:unset;' id='th_product'>[product label text]</th>
            <th class='unit' id='th_qty' style='text-align:unset;background:unset;color:unset;'>[table quantity text]</th>
            <th class='total' id='th_total' style='text-align:unset;background:unset;color:unset;'>[table toatl price text]</th>
          </tr>
        </thead>
        <tbody style='[wf product table text color main][wf product table body text align]' id='wf_product_body'>
         [table tbody content value]
        </tbody>
      </table>
      </div>
      <div class='no-break'>
        <table class='grand-total'>
          <tbody>
            <tr class='total'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit' id='wf_id_for_st' >[table subtotal label]</td>
              <td class='total'>[table subtotal value]</td>
            </tr>
            <tr class='total' style='[wf shipping show hide]'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit' id='wf_id_for_shipping'>[table shipping select text]</td>
              <td class='total'>[table shipping select value]</td>
            </tr>
            <tr class='total' style='[wf cd show hide]'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit' id='wf_id_for_cd'>[table cart discount text] </td>
              <td class='total'>[table cart discount value]</td>
            </tr>
            <tr class='total' style='[wf od show hide]'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit' id='wf_id_for_od'>[table Order discount text]</td>
              <td class='total'>[table Order discount value]</td>
            </tr>
            [table tax items]
            <tr class='total' style='[wf tt show hide]'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit' id='wf_id_for_tt'>[table Total Tax text]</td>
              <td class='total'>[table tax item value]</td>
            </tr>
             <tr class='total' style='[payment method show hide]'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit' id='wf_id_for_paym'>[table payment info label]</td>
              <td class='total'>[table payment info value]</td>
            </tr>
             <tr class='total' style ='[table coupon show hide]'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='unit'>[table coupon info label]</td>
              <td class='total'>[table coupon info value]</td>
            </tr>
            <tr class='total'>
              <td class='qty'></td>
              <td class='desc'></td>
              <td class='grant-total' colspan='2' style='font-weight: bold;font-size: 15px;background: #CC5A6A;padding: 10px;color: white;' id='wf_id_for_total'> [table invoice total label] : <span style='padding-left:2px;'> [table invoice total value]</span> </td>
              
            </tr>

            
          </tbody>
        </table>
      </div>
    </div>
  </section>
  <footer style='[wffootor style]'>
    <div class='container'>
      <div class='thanks'>[invoice barcode data]</div>
      <div class='notice'>
        <div>[invoice return policy data]</div>
      </div>
      <div class='end'>[invoice footor data]</div>
    </div>

  </footer>

</div>
  </body>
  </html>
");
}
if(get_option('wf_developer_tool_for_change_data_in_pdf') && get_option('wf_developer_tool_for_change_data_in_pdf') != $developer_value )
{
	update_option('wf_invoice_template_3pdf'," 

<html>
  <head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
    <title>Print Shipment Label</title>
    
  <style type='text/css'>
article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
    display: block;
}
.h1, .h2, .h3, h1, h2, h3 {
    margin-top: 10px;
    margin-bottom: 10px;
}
footer {
    margin-left: 230px;
}
footer {
    background: #fff;
    padding: 15px 20px;
    display: block;
}
h1, .h1 {
    font-size: 36px;
}
.table-responsive {
    min-height: .01%;
    overflow-x: auto;
}
.well-sm {
    padding: 9px;
    border-radius: 3px;
}
.well {
    min-height: 20px;
    padding: 19px;
    margin-bottom: 20px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
}
.text-muted {
    color: #777;
}
p {
    margin: 0 0 10px;
}
.table .table {
    background-color: #fff;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
}
table {
    background-color: transparent;
}
table {
    border-spacing: 0;
    border-collapse: collapse;
}
*, *::after, *::before {
    box-sizing: border-box;
}
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
h1, .h1, h2, .h2, h3, .h3 {
    margin-top: 20px;
    margin-bottom: 10px;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    font-family: inherit;
    font-weight: 500;
    line-height: 1.1;
    color: inherit;
}
img {
    vertical-align: middle;
}
img {
    border: 0;
}
.pull-right {
    float: right !important;
}
h1 small, .h1 small, h2 small, .h2 small, h3 small, .h3 small, h1 .small, .h1 .small, h2 .small, .h2 .small, h3 .small, .h3 .small {
    font-size: 65%;
}
h1 small, h2 small, h3 small, h4 small, h5 small, h6 small, .h1 small, .h2 small, .h3 small, .h4 small, .h5 small, .h6 small, h1 .small, h2 .small, h3 .small, h4 .small, h5 .small, h6 .small, .h1 .small, .h2 .small, .h3 .small, .h4 .small, .h5 .small, .h6 .small {
    font-weight: normal;
    line-height: 1;
    color: #777;
}
h1 {
    margin: .67em 0;
    font-size: 2em;
}

*, *::after, *::before {
    box-sizing: border-box;
}
.row {
    margin-right: -10px;
    margin-left: -10px;
}
col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-55, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    position: relative;
    min-height: 1px;
    float: left;
    padding-right: 10px;
    padding-left: 10px;
}
.col-xs-12 {
    width: 100%;
}
.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
    float: left;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
    position: relative;
    min-height: 1px;
    padding-right: 15px;
    padding-left: 15px;
}
*, *::after, *::before {
    box-sizing: border-box;
}
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
body {
    font-family: 'Helvetica Neue',Roboto,Arial,'Droid Sans',sans-serif;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.471;
}
#wpfooter
{
   position:unset !important;
}
  </style>
</head>
<body style='width:85%;height:100%;border: 3px solid lightgrey;padding:10px;>


  <section class='content invoice bodyclass'  style='[invoice main height and width]'>

	<!-- title row -->
	<div class='row'>
		<div class='col-xs-12 invoice-header'>
			<h1>      
				
        <label id='company_text_image' style='[company text show hide]'> [company name] </label>
    <img style='[company logo visible]' class='logo' src='[image url for company logo]' alt='' id='company_logo_image' height='[logo height]' width='[logo width]' />

				<small class='pull-right' style='text-align:right;' ><font id='wf_invoice_name' style='[invoice number switch][invoice number prob][invoice_number_color]'><label style='[invoice font weight]' id='wf_invoice_label'>[invoice name]</label> [invoice number] <br> </font><p style='text-align:right;font-size: 20px;'> <font id='wf_invoice_date' style='[invoice date font size][invoice date color code][invoice date show hide]'> <label id='wf_invoice_date_label' style='[invoice date label font weight]' > [invoice Date label text] </label> <label style='font-weight:normal' id='wf_invoice_main_date' >[invoice created date]</label> <br></font><font id='wf_order_date' style='[order date font size][order date color code][order date show hide]'> <label id='wf_order_date_label' style='[order date label font weight]' > [order date label]</label> <label style='font-weight:normal' id='wf_order_main_date' >[order date]</label></font></p></small>
			</h1>
      <div id='wf_extra_data_below_logo' style='[wf extra filed show hide][invoice extra field font size]'> [Extra data below logo] </div>
		</div>
    
		<!-- /.col -->
	</div>
	<!-- info row -->
	<div class='row invoice-info'>
		<div class='col-sm-4 invoice-col'>
		
			<address id ='wf_from_address_filed' style='[from address show hide][from address left right][from address text color]'>
				<strong id='wf_from_address_title_main'> [from address label] </strong>
				<br>
				[from address] 
			</address>
      <p style='padding:unset;margin:unset;line-height:unset;[wf vat show hide][wf vat font size][wf vat position set][wf_vat color code default]' id='wf_font_size_for_vat'><font id='wf_vat_text_main'>[VAT label]</font> [VAT data]</p>
      <p style='padding:unset;margin:unset;line-height:unset;[wf ssn show hide][wf ssn font size][wf ssn position set][wf_ssn color code default]' id='wf_font_size_for_ssn'><font id='wf_ssn_text_main'>[SSN label]</font>[SSN data]</p>
      [invoice extra firlds import old one]
		</div>
		<!-- /.col -->
		<div class='col-sm-4 invoice-col'>
			
			<address id ='wf_billing_address_filed' style='[billing address show hide][billing address left right][billing address text color]'>
				<strong id='wf_billing_address_title_main'> [billing address label]</strong>
				<br>
				[billing address data]
			</address>	
      <p style='padding:unset;margin:unset;line-height:unset;[wf email show hide][wf email font size][wf email position set][wf_email color code default]' id='wf_font_size_for_email'><font id='wf_email_text_main'>[email label]</font> [email address]</p>
      <p style='padding:unset;margin:unset;line-height:unset;[wf tel show hide][wf tel font size][wf tel position set][wf_tel color code default]' id='wf_font_size_for_tel'><font id='wf_tel_text_main'>[mobile label]</font>[mobile number]</p>

			</div>
		<!-- /.col -->
		<div class='col-sm-4 invoice-col'>
			<address id ='wf_shipping_address_filed' style='[shipping address show hide][shipping address left right][shipping address text color]'>
				<strong id='wf_shipping_address_title_main'> [shipping address title]</strong>
				<br>
				[shipping address data]
			</address>
      <p style='padding:unset;margin:unset;line-height:unset;[wf tp show hide][wf tp font size][wf tp position set][wf_tp color code default]' id='wf_font_size_for_tp'><font id='wf_tp_text_main'>[tracking label]</font> [tracking data]</p>
      <p style='padding:unset;margin:unset;line-height:unset;[wf tn show hide][wf tn font size][wf tn position set][wf_tn color code default]' id='wf_font_size_for_tn'><font id='wf_tn_text_main'>[tracking number label]</font>[tracking number data]</p>
      [invoice extra firlds import]
		</div>

		<!-- /.col -->
	</div>
	<!-- /.row -->


	<!-- Table row -->
	<div class='row' id='wf_product_table_main_tag' style='[wf product table show hide]'>
		<div class='col-xs-12 table' >
			<table class='table table-striped' style='page-break-inside:auto' colspan='[table colum span]'>
				<thead style='display:table-header-group;[wf product table head color][wf product table head text color]' id='wf_product_head'>
					<tr style='page-break-inside:avoid; page-break-after:auto;[wf product table text align]' id='wf_head_tr_align_purpose'>
						<th id='th_sku' style='[table colum span hide]text-align:unset;'>[sku label text]</th>
						<th style='width: 59%;text-align:unset;' id='th_product' colspan='[table colum span]'>[product label text]</th>
						<th id='th_qty' style='text-align:unset;'>[table quantity text]</th>
						<th id='th_total' style='text-align:unset;'>[table toatl price text]</th>
					</tr>
				</thead>
				<tbody style='[wf product table text color main][wf product table body text align]' id='wf_product_body' >
					[table tbody content value]	
				</tbody>
			</table>
		</div>
		<!-- /.col -->
	</div>
	<!-- /.row -->

	<div class='row' style='page-break-inside: auto;'>
		<!-- accepted payments column -->
		<div class='col-xs-6'  >
			
			<p class='text-muted well well-sm no-shadow' style='margin-top: 10px;[invoice return policy hide]'>
				[invoice return policy data]
			</p>
			[invoice barcode data]
		</div>
		<!-- /.col -->
		<div class='col-xs-6'>
			<div class='table-responsive'>
				<table class='table'>
					<tbody>
						<tr>
							<th style='width:50%' id='wf_id_for_st' >[table subtotal label]</th>
							<td>[table subtotal value]</td>
						</tr>
						<tr style='[wf shipping show hide]'>
							<th id='wf_id_for_shipping'>[table shipping select text]</th>
							<td>[table shipping select value]</td>
						</tr>

						<tr style='[wf cd show hide]'>
							<th id='wf_id_for_cd'>[table cart discount text]</th>
							<td>[table cart discount value]</td>
						</tr>
						<tr style='[wf od show hide]' >
							<th id='wf_id_for_od'>[table Order discount text]</th>
							<td>[table Order discount value]</td>
						</tr>
						[table tax items]
						<tr style='[wf tt show hide]'>
							<th id='wf_id_for_tt'>[table Total Tax text]</th>
							<td>[table tax item value]</td>
						</tr>
						<tr>
							<th id='wf_id_for_total' >[table invoice total label]</th>
							<td>[table invoice total value]</td>
						</tr>
						<tr style='[payment method show hide]'>
							<th id='wf_id_for_paym'>[table payment info label]</th>
							<td>[table payment info value]</td>
						</tr>
            <tr style ='[table coupon show hide]'>
              <th >[table coupon info label]</th>
              <td>[table coupon info value]</td>
            </tr>

					</tbody>
				</table>
			</div>
		</div>
		<!-- /.col -->
	</div>
<footer class='footor' style=[wffootor style]'>[invoice footor data]</footer>
	<!-- /.row -->
</section>  
  </body>
  </html>");

}