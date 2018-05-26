<?php 
include $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

include get_stylesheet_directory() .'/dompdf/autoload.inc.php';

$usermeta = get_user_meta( $_GET['user_id'] );

$pdf_template = get_option('reminder_email_template');

$styles = '<style>img{margin:20px;}</style>';

$pdf_template = $styles . $pdf_template;



use Dompdf\Dompdf;

$dompdf = new Dompdf();

if (strpos( $_GET['user_id'] , ',' ) > 0 ) {

    $users = explode(',' ,$_GET['user_id']);
    
    $template = '';

    foreach($users as $user) {
        $template .= generateTemplate($pdf_template , get_user_meta( $user ),$user);
        $template .= '<div style="page-break-before: always;"></div>';
    }

    $dompdf->loadHtml($template);

$dompdf->setPaper('A4', 'portrait');

$dompdf->render();

$pdf_title = 'bulk_reminder_letters.pdf';

$dompdf->stream($pdf_title);

} else {

    $usermeta = get_user_meta($_GET['user_id']);
    
    $template = generateTemplate( $pdf_template,$usermeta,$_GET['user_id']);

    $dompdf->loadHtml($template);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();
    $pdf_title = ($_GET['type'] == 'reminder') ? $usermeta['first_name'][0].'_'.$usermeta['last_name'][0].'_reminder_'.date('Y').'.pdf' : $usermeta['first_name'][0].'_'.$usermeta['last_name'][0].'_renewal_'.date('Y').'.pdf';
    $dompdf->stream($pdf_title);

}

function generateTemplate($template, $usermeta, $userid) {

$regex = '/{{\K[^}]*(?=})/m';
global $wpdb;

    preg_match_all($regex, $template, $matches);

    $return = $template;

    foreach($matches[0] as $match) {
        if($match == 'expire_date') {
           $result =  $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $userid . '"');
          $return = str_replace('{{'.$match.'}}' , $result->expire_time , $return);  
        } else {
           $return = str_replace('{{'.$match.'}}' , $usermeta[$match][0] , $return);
        }
       
    }

    return stripslashes($return);

}

