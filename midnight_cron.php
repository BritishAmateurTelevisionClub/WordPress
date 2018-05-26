<?php

require "wp-blog-header.php";

function reminder_email_plus_1month() {
  global $wpdb;
// Your code here
  $users = $wpdb->get_results('select * from wp_users');
  foreach($users as $user) {
     $result = $wpdb->get_row('select * from wp_ihc_user_levels where user_id = '. $user->ID);
     $user_level = Ihc_Db::get_user_levels($user->ID);
     foreach ($user_level as $level) {
        if(is_object($level) && !$level->is_expired) {
           $level_name = $level['label'];
        } else {
          $level_name = 'n/a';
        }
      }

  if($result) {
          update_usermeta( $user->ID , 'ihc_user_levels_level_id',$result->level_id  );
          update_usermeta( $user->ID , 'ihc_user_levels_start_time',$result->start_time  );
          update_usermeta( $user->ID , 'ihc_user_levels_update_time',$result->update_time  );
          update_usermeta( $user->ID , 'ihc_user_levels_expire_time',$result->expire_time  );
          update_usermeta( $user->ID , 'ihc_user_levels_notification',$result->notification  );
          update_usermeta( $user->ID , 'ihc_user_levels_status',$result->notification  );
          update_usermeta( $user->ID ,'ihc_user_levels_name' , $level_name);
      }
      
      $reminder_sent = get_user_meta( $user->ID, 'reminder_sent' );
      $dateplusmonth = date('dmY',strtotime('+1 month'));
      $expiredate = is_object($result) ? date('dmY',strtotime($result->expire_time)) : 'n/a';
      if(($dateplusmonth == $expiredate) && ($reminder_sent != $dateplusmonth)) {
         $email_template = generateTemplate(get_option('reminder_email_template'), get_user_meta($user->ID), $user->ID);
         $to = $userdata->user_email;
          $subject = get_option('email_subject');
          $headers = "From: noreply@batc.org.uk" . "\r\n";
          $headers .= "Reply-To: noreply@batc.org.uk" . "\r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
          $message = '<html><body>';
          $message .= $email_template;
          $message = '</html></body>';
          mail($to, $subject, $message, $headers);
          update_user_meta( $user->ID, 'reminder_sent', strtotime('+1 month'));
      } else if ($reminder_sent < time()) {
          update_user_meta( $user->ID, 'reminder_sent', false);
      }
  }

}

add_action( 'init', 'reminder_email_plus_1month');
