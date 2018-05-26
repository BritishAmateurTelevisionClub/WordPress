<?php 

add_action('wppdfs_daily_cron_event', 'wppdfs_daily_cron_event_handler');

function wppdfs_daily_cron_event_handler()
{
	pdf_stamper_debug("PDF Stamper cronjob handler got called. Checking if automatic bulk delete settings...", true);
	$wp_ps_config = PDF_Stamper_Config::getInstance();
	if($wp_ps_config->getValue('auto_bulk_delete_enabled')=='1')
	{
		pdf_stamper_debug("Automatic bulk delete feature is enabled. Preparing to bulk delete.", true);
		$interval_val = $wp_ps_config->getValue('auto_bulk_delete_after_days');
		if(empty($interval_val)){$interval_val = "1";}//set it to 1 day by default
		$interval_unit = 'DAY';//MINUTE
		$cur_time = current_time('mysql');

		$cond = " DATE_SUB('$cur_time',INTERVAL '$interval_val' $interval_unit) > creation_time";
		$resultset = WpPdfStamperDbAccess::findAll(WP_PDF_STAMPED_FILES_TABLE_NAME, $cond);	
		if($resultset)	
		{
			foreach ($resultset as $result)
			{
				//var_dump($result);
				$retVal = pdf_stamp_delete_stamped_record($result);				
			}			
			pdf_stamper_debug("Files older than ".$interval_unit." Days have been deleted", true);
		}
	}
	pdf_stamper_debug("PDF Stamper cronjob end.", true);
}
