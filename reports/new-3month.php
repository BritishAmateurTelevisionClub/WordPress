<?php

require_once('header.php');

$querystr = "
SELECT
    wp_usermeta.user_id,
    wp_usermeta_id.start_time,
    MAX(CASE WHEN wp_usermeta.meta_key = 'call_sign' THEN wp_usermeta.meta_value ELSE NULL END) AS Callsign,
    MAX(CASE WHEN wp_usermeta.meta_key = 'first_name' THEN wp_usermeta.meta_value ELSE NULL END) AS First_Name,
    MAX(CASE WHEN wp_usermeta.meta_key = 'last_name' THEN wp_usermeta.meta_value ELSE NULL END) AS Last_Name,
    MAX(CASE WHEN wp_usermeta.meta_key = 'shipping_city' THEN wp_usermeta.meta_value ELSE NULL END) AS Shipping_City
FROM batc_wordpress.wp_usermeta
INNER JOIN
(SELECT
    wp_ihc_user_levels.user_id,
    MIN(wp_ihc_user_levels.start_time) AS start_time
    FROM batc_wordpress.wp_ihc_user_levels
    GROUP BY wp_ihc_user_levels.user_id
) AS wp_usermeta_id
ON wp_usermeta.user_id = wp_usermeta_id.user_id
WHERE wp_usermeta_id.start_time BETWEEN DATE_SUB(curdate(), INTERVAL 3 MONTH) AND curdate()
GROUP BY wp_usermeta.user_id
";

$new_3month = $wpdb->get_results($querystr, OBJECT);

cqtvCSV($new_3month, 'newmembers_last3months.csv');

?>
