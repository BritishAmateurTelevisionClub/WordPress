<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Avery Labels</title>
    <link href="labels.css" rel="stylesheet" type="text/css" >
    <style>
    body {
        width: 8.5in;
        margin: 0in .1875in;
    }
    .label{
      margin-bottom:20px;

        text-align: center;
        overflow: hidden;
height: 1.41732in;
width	:250px;
    padding: 0;
    margin-right: .125in;
    margin-bottom: 20px;
    text-align: center;
    overflow: hidden;
    outline: 1px dotted;
    display: flex;
    justify-content: center;
    align-items: center;
        outline: 1px dotted; /* outline doesn't occupy space like border does */
        }
    .page-break  {
        clear: left;
        display:block;
        page-break-after:always;
        }
        p { 
            padding:0;
            margin:0;
            padding-bottom: 1px;
        }
        p b {
                margin-bottom: 1px;
    display: block;
        }
    </style>

</head>
<body>
<?php 



include $_SERVER['DOCUMENT_ROOT'].'/wp-load.php';

if (strpos( $_GET['user_id'] , ',' ) > 0 ) {

    $users = explode(',' ,$_GET['user_id']);

    foreach($users  as $user ) {
	   
	
        $usermeta = get_user_meta( $user);
	  if(empty($usermeta['billing_first_name'][0])) continue;
        ?>
        <div class="label">
<div>
<p><b><?= $usermeta['billing_first_name'][0]. ' ' . $usermeta['billing_last_name'][0]; ?></b></p>
<p><?= $usermeta['billing_address_1'][0] ?></p>
<p><?= $usermeta['billing_city'][0] ?></p>
<p><?= $usermeta['billing_postcode'][0] ?></p>
</div>
</div>
        <?php
    }

} else {
  
 
$usermeta = get_user_meta( $_GET['user_id'] );



if(empty($usermeta['billing_first_name'][0])){
echo '<h1>Error: No billing details for User</h1>';

} else {

?>
<div class="label">
<div>
<p><b><?= $usermeta['billing_first_name'][0]. ' ' . $usermeta['billing_last_name'][0]; ?></b></p>
<p><?= $usermeta['billing_address_1'][0] ?></p>
<p><?= $usermeta['billing_city'][0] ?></p>
<p><?= $usermeta['billing_postcode'][0] ?></p>
</div>
</div>

<?php 

}
}
?>

<div class="page-break"></div>

</body>
</html>