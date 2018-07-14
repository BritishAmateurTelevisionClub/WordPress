<?php

header('Pragma: no-cache');
header('Expires: 0');

require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/wp-load.php');

global $current_user;
get_currentuserinfo();

# 5511 - Phil M0DNY
# 6860 - Superadmin1 (Noel)
# 6861 - Superadmin2 (Dave)
# 6862 - Superadmin3 (Phil)
# 6858 - Memsec1 (Rob)
# 6857 - Treasurer (Brian)
# 5590 - Frank
$whitelist = array( 5511, 6860, 6861, 6862, 6858, 6857, 5590 );

if(false == in_array($current_user->data->ID, $whitelist))
{
  header('HTTP/1.0 403 Forbidden');
  echo "Access Denied";
  exit();
}

function cqtvCSV($recipients, $filename)
{
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    
    $file = fopen('php://output', 'w');
    
    fputcsv($file, array('First Name', 'Surname', 'Callsign', 'Address', 'Postcode', 'Country'));
    
    foreach($recipients as $recipient)
    {
       $address_string = "";
    
       if($recipient->Shipping_Company != "")
       {
           $address_string .= "{$recipient->Shipping_Company}, ";
       }
       if($recipient->Shipping_Address1 != "")
       {
           $address_string .= "{$recipient->Shipping_Address1}, ";
       }
       if($recipient->Shipping_Address2 != "")
       {
           $address_string .= "{$recipient->Shipping_Address2}, ";
       }
       if($recipient->Shipping_City != "")
       {
           $address_string .= "{$recipient->Shipping_City}, ";
       }
       if($recipient->Shipping_State != "")
       {
           $address_string .= "{$recipient->Shipping_State}, ";
       }
       # Remove last ", "
       $address_string = substr($address_string, 0, -2);
    
       fputcsv($file, array(
           $recipient->First_Name,
           $recipient->Last_Name,
           $recipient->Callsign,
           $address_string,
           $recipient->Shipping_Postcode,
           $recipient->Shipping_Country
       ));
    }

    fclose($file);
}

?>
