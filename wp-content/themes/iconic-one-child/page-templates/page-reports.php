<?php
/**
* Template Name: Admin Reports Page Template
*
* Description: Use this page template for admin reports.
 *
 * @package WordPress - Themonic Framework
 * @subpackage Iconic_One
 * @since Iconic One 1.0
 */

get_header(); ?>
<style>
.td-number {
  padding-left: 5px;
  text-align: right;
}
</style>
<div id="primary" class="site-content">
<div id="content" role="main">

<?php

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

if(in_array($current_user->data->ID, $whitelist))
{
$querystr = "
    SELECT
    wp_ihc_user_levels.level_id AS level_id,
    COUNT(DISTINCT wp_ihc_user_levels.user_id) AS count
FROM batc_wordpress.wp_ihc_user_levels
WHERE
    wp_ihc_user_levels.expire_time > curdate()
GROUP BY level_id
ORDER BY level_id 
";

$user_level_map = array();
$user_level_map[1] = "1y Cyber";
$user_level_map[5] = "2y Cyber";
$user_level_map[2] = "1y Full UK";
$user_level_map[6] = "2y Full UK";
$user_level_map[3] = "1y Full EU";
$user_level_map[7] = "2y Full EU";
$user_level_map[4] = "1y Full RoW";
$user_level_map[8] = "2y Full RoW";
$user_level_map[10] = "Repeater";
$user_level_map[11] = "Honorary";
$user_level_map[12] = "Special Event";
$user_level_map[13] = "Complementary";
$user_level_map[14] = "[Expired]";

$user_levels = $wpdb->get_results($querystr, OBJECT);

?>
<h1>Adminstrator Reports</h1>
<br>
<table>
<?php
$user_count = 0;
foreach($user_levels as $level)
{
  echo "<tr><td>{$user_level_map[$level->level_id]}</td><td class=\"td-number\">{$level->count}</td></tr>";
  if(in_array($level->level_id, [1,5,2,6,3,7,4,8]))
  {
    $user_count += $level->count;
  }
}
?>
<tr><td><b>Active Paid Subscriptions:</td><td class="td-number"><?php echo $user_count; ?></b></td></tr>
</table>

<br><br>
<a href="/reports/nomail-expiring.php">No-email members expiring in the next month</a> (csv)

<br><br>
<a href="/reports/new-3month.php">New members in the last 3 months</a> (csv)
<br><br>

<h2>CQTV</h2>
<br>
<a href="/reports/cqtv-eu.php">CQTV EU Posting List</a> (csv)
<br><br>
<a href="/reports/cqtv-row.php">CQTV RoW Posting List</a> (csv)
<br><br>
<a href="/reports/cqtv-uk.php">CQTV UK Posting List</a> (csv)
<br><br>

<?php
}
else if(!is_user_logged_in())
{
  wp_redirect( '/members/login' );
}
else
{
  echo "Access Denied.";
}
?>

</div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>
