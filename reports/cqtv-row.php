<?php

require_once('header.php');

$querystr = "
    SELECT
    wp_usermeta.user_id,
    MAX(CASE WHEN wp_usermeta.meta_key = 'call_sign' THEN wp_usermeta.meta_value ELSE NULL END) AS Callsign,
    MAX(CASE WHEN wp_usermeta.meta_key = 'first_name' THEN wp_usermeta.meta_value ELSE NULL END) AS First_Name,
    MAX(CASE WHEN wp_usermeta.meta_key = 'last_name' THEN wp_usermeta.meta_value ELSE NULL END) AS Last_Name,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_company' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_Company,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_address_1' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_Address1,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_address_2' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_Address2,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_city' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_City,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_state' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_State,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_postcode' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_Postcode,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_country' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_Country

FROM batc_wordpress.wp_usermeta
INNER JOIN
(SELECT
    wp_ihc_user_levels.user_id
    FROM batc_wordpress.wp_ihc_user_levels
    WHERE
        wp_ihc_user_levels.level_id IN (4,8)
        AND wp_ihc_user_levels.expire_time > curdate()
) AS wp_usermeta_id
ON wp_usermeta.user_id=wp_usermeta_id.user_id
GROUP BY wp_usermeta.user_id;
  ";

$row_recipients = $wpdb->get_results($querystr, OBJECT);

cqtvCSV($row_recipients, 'cqtv-row.csv');

?>
