<?php 
include $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

include get_stylesheet_directory() .'/dompdf/autoload.inc.php';

$usermeta = get_user_meta( $_GET['user_id'] );

$pdf_template = get_option($_GET['type'].'_template');