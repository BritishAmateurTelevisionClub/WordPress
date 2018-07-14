<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo 'Invoice-'.$invoice_number;?></title>

		<?php if ( 'Yes' ==  get_option('woocommerce_wf_packinglist_rtl_settings_enable') ): ?>
		    <link href="<?php $plugin_url = untrailingslashit(plugins_url('/', __FILE__)); echo $plugin_url . '//css/style-rtl.css' ?>" rel="stylesheet" type="text/css" media="screen,print" />
		<?php endif; ?>
		<link href="<?php echo $this->wf_packinglist_template('uri','wf-4-6-template-header.php');?>css/wf-packinglist.css" rel="stylesheet" type="text/css" media="scrren,print" />
		<link href="<?php echo $this->wf_packinglist_template('uri','wf-4-6-template-header.php');?>css/wf-packinglist-print.css" rel="stylesheet" type="text/css" media="print" />
	</head>
	<body style="height:100%;"><?php
		$heading_size;
		$title_size;
		$content_size;
		switch($this->wf_pklist_font_size) {
			case 'small':
				$heading_size = 23;
				$title_size = 16;
				$content_size = 14;
				break;
			case 'large':
				$heading_size = 27;
				$title_size = 20;
				$content_size = 18;
				break;
			default:
				$heading_size = 25;
				$title_size = 18;
				$content_size = 16;
				break;
		}
	?>