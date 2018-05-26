<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item  <?php echo ($_REQUEST['ihc-new-user'] =='true') ? 'ihc-subtab-selected' : '';?>" href="<?php echo $url.'&tab='.$tab.'&ihc-new-user=true';?>"><?php _e('Add New User', 'ihc');?></a>
	<a class="ihc-subtab-menu-item  <?php echo (!isset($_REQUEST['ihc-new-user'])) ? 'ihc-subtab-selected' : '';?>" href="<?php echo $url.'&tab='.$tab;?>"><?php _e('Manage Users', 'ihc');?></a>
	<div class="ihc-clear"></div>
</div>
<?php
echo ihc_inside_dashboard_error_license();
$is_uap_active = ihc_is_uap_active();

//delete
if (isset($_POST['delete_users'])){
	ihc_delete_users(0, $_POST['delete_users']);
}

$form = '';
include_once IHC_PATH . 'classes/UserAddEdit.class.php';
$obj = new UserAddEdit();
if (isset($_REQUEST['Update'])){
	//update
	$args = array(
			'type' => 'edit',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
			'user_id' => $_REQUEST['user_id'],
	);
	$obj->setVariable($args);//setting the object variables
	$obj->save_update_user();

} else if (isset($_REQUEST['Submit'])){
	//create
	$args = array(
			'user_id' => false,
			'type' => 'create',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
	);
	$obj->setVariable($args);//setting the object variables
	$obj->save_update_user();
}

$obj_form = new UserAddEdit;
if (isset($_REQUEST['ihc-edit-user'])){
	///EDIT USER FORM
	$args = array(
			'user_id' => $_REQUEST['ihc-edit-user'],
			'type' => 'edit',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
	);
	$obj_form->setVariable($args);//setting the object variables
	$form = $obj_form->form();
} else {
	/// CREATE USER FORM
	$args = array(
			'user_id' => false,
			'type' => 'create',
			'tos' => false,
			'captcha' => false,
			'action' => $url . '&tab=users',
			'is_public' => false,
	);
	$obj_form->setVariable($args);//setting the object variables
	$form = $obj_form->form();
}

global $ihc_error_register;
if (!empty($ihc_error_register) && count($ihc_error_register)>0){
	echo '<div class="ihc-wrapp-the-errors">';
	foreach ($ihc_error_register as $key=>$err){
		echo __('Field ', 'ihc') . $key . ': ' . $err;
	}
	echo '</div>';
}


//set default pages message
echo ihc_check_default_pages_set();
echo ihc_check_payment_gateways();
echo ihc_is_curl_enable();

	if (isset($_REQUEST['ihc-edit-user']) || isset($_REQUEST['ihc-new-user'])){
		//add edit user
		if (isset($_REQUEST['ihc-edit-user'])){
			?>
			<script>
				jQuery(document).ready(function() {
				    jQuery('.expire_input_text, .start_input_text').datepicker({
				        dateFormat : 'yy-mm-dd',
				        onSelect: function(datetext){
				            var d = new Date();
				            datetext = datetext+" "+d.getHours()+":"+ihc_add_zero(d.getMinutes())+":"+ihc_add_zero(d.getSeconds());
				            jQuery(this).val(datetext);
				        }
				    });
				});
			</script>
			<?php
		}
		?>
			<div class="ihc-stuffbox" style="margin-top: 20px;">
				<h3><?php _e('Add/Edit User', 'ihc');?></h3>
				<div class="inside">
					<?php echo $form;?>
				</div>
			</div>
		<?php
	} else {

?>
<div class="iump-wrapper">
	<div id="col-right" style="vertical-align:top; width: 100%;">

		<div class="iump-page-title">Ultimate Membership Pro -
			<span class="second-text">
				<?php _e('MemberShip Users', 'ihc');?>
			</span>
		</div>
		<a href="<?php echo $url.'&tab=users&ihc-new-user=true';?>" class="indeed-add-new-like-wp">
			<i class="fa-ihc fa-add-ihc"></i><?php _e('Add New User', 'ihc');?>
		</a>
		<div class="ihc-special-buttons-users">
			<div class="ihc-special-button" onclick="ihc_show_hide('.ihc-filters-wrapper');"><i class="fa-ihc fa-export-csv"></i>Add Filters</div>
			<div class="ihc-special-button" style="background-color:#38cbcb;" onClick="ihc_make_user_csv();"><i class="fa-ihc fa-export-csv"></i>Export CSV</div>
			<div class="ihc-hidden-download-link" style="display: none;float: right; padding: 20px 20px 0px 0px;"><a href="" target="_blank"><?php _e("Click on this if download doesn't start automatically in 20 seconds!");?></a></div>
			<div class="ihc-clear"></div>
		</div>

		<?php
		$hidded = 'style="display:none;"';
		if(isset($_GET['search_user']) || isset($_GET['filter_role']) || isset($_GET['ordertype_level']) || isset($_GET['orderby_user']) || isset($_GET['ordertype_user']) ) $hidded ='';
		?>
		<div class="ihc-filters-wrapper" <?php echo $hidded; ?>>
			<form method="get" action="">
				<input type="hidden" name="page" value="ihc_manage" />
				<input type="hidden" name="tab" value="users" />
				<div class="row-fluid">
					<div class="span4">
						<div class="iump-form-line iump-no-border">
							<input name="search_user" type="text" value="<?php echo (isset($_GET['search_user']) ? $_GET['search_user'] : '') ?>" placeholder="<?php _e('Search by Name or Username', 'ihc');?>..."/>
						</div>
					</div>
				<div class="span2">
					<div class="iump-form-line iump-no-border">
						<select name="filter_role" style="min-width:70%;">
							<option value="">...</option>
							<?php
										$filter_roles = ihc_get_wp_roles_list();
										if ($filter_roles){
											foreach ($filter_roles as $k=>$v){
												$selected = (isset($_GET['filter_role']) && $_GET['filter_role']==$k) ? 'selected' : '';
												?>
													<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
												<?php
											}
										}
									?>
						</select>
					</div>
				</div>
				<div class="span2">
					<div class="iump-form-line iump-no-border">
						<select name="ordertype_level">
							<option value="">...</option>
							<?php
								$levels_arr = get_option('ihc_levels');
								if ($levels_arr!==FALSE){
									foreach ($levels_arr as $k=>$v){
										$selected = (isset($_GET['ordertype_level']) && $_GET['ordertype_level']==$k) ? 'selected' : '';
										?>
										<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v['name'];?></option>
										<?php
									}
								}
							?>
						</select>
					</div>
				</div>
				<div class="span3">
					<div class="iump-form-line iump-no-border">
						<select name="orderby_user">
							<option value="display_name" <?php echo (isset($_GET['orderby_user']) && $_GET['orderby_user']=='display_name') ? 'selected' : ''; ?>><?php _e('Name', 'ihc');?></option>
							<option value="user_login" <?php echo (isset($_GET['orderby_user']) && $_GET['orderby_user']=='user_login') ? 'selected' : ''; ?>><?php _e('Username', 'ihc');?></option>
							<option value="user_email" <?php echo (isset($_GET['orderby_user']) && $_GET['orderby_user']=='user_email') ? 'selected' : ''; ?>><?php _e('Email', 'ihc');?></option>
							<option value="ID" <?php echo (isset($_GET['orderby_user']) && $_GET['orderby_user']=='ID') ? 'selected' : ''; ?>><?php _e('ID', 'ihc');?></option>
							<option value="user_registered" <?php echo (isset($_GET['orderby_user']) && $_GET['orderby_user']=='user_registered') ? 'selected' : ''; ?>><?php _e('Registered Time', 'ihc');?></option>
						</select>
						<select name="ordertype_user">
							<option value="ASC" <?php echo (isset($_GET['ordertype_user']) && $_GET['ordertype_user']=='ASC') ? 'selected' : ''; ?>><?php _e('ASC', 'ihc');?></option>
							<option value="DESC" <?php echo (isset($_GET['ordertype_user']) && $_GET['ordertype_user']=='DESC') ? 'selected' : ''; ?>><?php _e('DESC', 'ihc');?></option>
						</select>
					</div>
				</div>
				<div class="span1" style="padding:30px 10px 0 0;">
					<input type="submit" value="Search" name="search" class="button button-primary button-large">
				</div>
			</div>
			</form>
		</div>
		<form method="post" action="" style="margin-top:20px;">
			<?php
				$limit = (isset($_GET['ihc_limit'])) ? $_GET['ihc_limit'] : 25;
				$start = 0;
				if(isset($_GET['ihcdu_page'])){
					$pg = $_GET['ihcdu_page'] - 1;
					$start = (int)$pg * $limit;
				}
				$search_query = isset($_GET['search_user']) ? $_GET['search_user'] : '';
				$filter_role = isset($_GET['filter_role']) ? $_GET['filter_role'] : '';
				$search_level = isset($_GET['ordertype_level']) ? $_GET['ordertype_level'] : -1;
				$order_by = isset($_GET['orderby_user']) ? $_GET['orderby_user'] : 'user_registered';
				$order = isset($_GET['ordertype_user']) ? $_GET['ordertype_user'] : 'DESC';

				$total_users = Ihc_Db::ihc_admin_get_user_with_search(TRUE, $search_query, $filter_role, $search_level, $order_by, $order, $limit, $start);
				$users = Ihc_Db::ihc_admin_get_user_with_search(FALSE, $search_query, $filter_role, $search_level, $order_by, $order, $limit, $start);

			?>
			<div>
				<?php
					//SEARCH FILTER BY USER LEVELS
					if ($start==0) $current_page = 1;
					else $current_page = $_GET['ihcdu_page'];

					require_once IHC_PATH . 'classes/Ihc_Pagination.class.php';

					$url = IHC_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					$pagination_object = new Ihc_Pagination(array(
																'base_url' => $url,
																'param_name' => 'ihcdu_page',
																'total_items' => $total_users,
																'items_per_page' => $limit,
																'current_page' => $current_page,
					));
					$pagination = $pagination_object->output();


					/// UAP
					if ($is_uap_active){
						global $indeed_db;
						if (empty($indeed_db) && defined('UAP_PATH')){
							include UAP_PATH . 'classes/Uap_Db.class.php';
							$indeed_db = new Uap_Db;
						}
					}
					/// UAP

					$magic_feat_user_sites = ihc_is_magic_feat_active('user_sites');

					if ($users){
						?>
							<div style="margin: 10px 0px;">
								<div style="display: inline-block;float: left;" >
									<input type="submit" value="<?php _e('Delete', 'ihc');?>" name="delete" onClick="ihc_first_confirm('<?php _e('Are You Sure You want to delete selected Users?');?>');" class="button button-primary button-large"/>
								</div>

								<div style="display: inline-block;float: right;margin-right:10px;">
									<strong><?php _e('Number of Users to Display:', 'ihc');?></strong>
									<select name="ihc_limit" onChange="window.location = '<?php echo admin_url('admin.php');?>?page=ihc_manage&tab=users&ihc_limit='+this.value;">
										<?php
											foreach(array(5,25,50,100,200,500) as $v){
												?>
													<option value="<?php echo $v;?>" <?php if($limit==$v) echo 'selected';?> ><?php echo $v;?></option>
												<?php
											}
										?>
									</select>
								</div>
									<?php //////////////////PAGINATION
											echo $pagination;
									?>
								<div class="clear"></div>
							</div>

						   <table class="wp-list-table widefat fixed tags ihc-admin-tables">
							  <thead>
								<tr>
									  <th style="width: 30px;">
									  	<input type="checkbox" onClick="ihc_select_all_checkboxes( this, '.ihc-delete-user' );" />
									  </th>
									  <th class="manage-column">
											<?php _e('Username', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Name', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('E-mail', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Level', 'ihc');?>
									  </th>
									  <?php if (!empty($magic_feat_user_sites)):?>
									  <th class="manage-column">
									  		<?php _e('Sites', 'ihc');?>
									  </th>
									  <?php endif;?>
									  <th class="manage-column">
											<?php _e('WP User Role', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('E-mail Status', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Join Date', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Details', 'ihc');?>
									  </th>
							    </tr>
							  </thead>
							  <tfoot>
								<tr>
									  <th style="width: 30px;">
									  	<input type="checkbox" onClick="ihc_select_all_checkboxes( this, '.ihc-delete-user' );" />
									  </th>
									  <th class="manage-column">
											<?php _e('Username', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Name', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('E-mail', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Level', 'ihc');?>
									  </th>
									  <?php if (!empty($magic_feat_user_sites)):?>
									  <th class="manage-column">
									  		<?php _e('Sites', 'ihc');?>
									  </th>
									  <?php endif;?>
									  <th class="manage-column">
											<?php _e('WP User Role', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('E-mail Status', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Join Date', 'ihc');?>
									  </th>
									  <th class="manage-column">
											<?php _e('Details', 'ihc');?>
									  </th>
							    </tr>
							  </tfoot>
							  <?php
							  		$i = 1;
							  		$available_roles = ihc_get_wp_roles_list();
							  		foreach ($users as $user){
							  			$verified_email =  get_user_meta($user->ID, 'ihc_verification_status', TRUE);
											$roles = array_keys(unserialize($user->roles));
							  			?>
			    						   		<tr id="<?php echo "ihc_user_id_" . $user->ID;?>" class="<?php if($i%2==0) echo 'alternate';?>" onMouseOver="ihc_dh_selector('#user_tr_<?php echo $user->ID;?>', 1);" onMouseOut="ihc_dh_selector('#user_tr_<?php echo $user->ID;?>', 0);">
			    						   			<th>
									  					<input type="checkbox" class="ihc-delete-user" name="delete_users[]" value="<?php echo $user->ID;?>" />
									 				</th>
			    						   			<td>
														<?php echo $user->user_login;?>
														<?php
															if ($is_uap_active && !empty($indeed_db)){
																$is_affiliate = $indeed_db->is_user_affiliate_by_uid($user->ID);
																if ($is_affiliate){
																	?>
																	<span class="ihc-user-is-affiliate"><?php _e('Affiliate', 'ihc');?></span>
																	<?php
																}
															}
														?>
														<div style="visibility: hidden;" id="user_tr_<?php echo $user->ID;?>">
															<a href="<?php echo $url.'&tab=users&ihc-edit-user='.$user->ID;?>"><?php _e('Edit', 'ihc');?></a>
															|
															<a onClick="ihc_delete_user_prompot(<?php echo $user->ID;?>);" href="javascript:return false;" style="color: red;"><?php _e('Delete', 'ihc');?></a>
															<?php
																///get role !!!!
																if (isset($roles) && $roles[0]=='pending_user'){
																	?>
																	<span id="approveUserLNK<?php echo $user->ID;?>" onClick="ihc_approve_user(<?php echo $user->ID;?>);">
																	| <span style="cursor:pointer; color: #0074a2;"><?php _e('Approve', 'ihc');?></span>
																	</span>
																	<?php
																}
																if ($verified_email==-1){
																	?>
																	<span id="approve_email_<?php echo $user->ID;?>" onClick="ihc_approve_email(<?php echo $user->ID;?>, '<?php _e("Verified", "ihc");?>');">
																	| <span style="cursor:pointer; color: #0074a2;"><?php _e('Approve E-mail', 'ihc');?></span>
																	</span>
																	<?php
																}
															?>
														</div>
			    						   			</td>
			    						   			<td style="color: #21759b; font-weight:bold; width:120px;font-family: 'Oswald', arial, sans-serif !important;font-size: 14px;font-weight: 400;">
			    						   				<?php
			    						   					$first_name = get_user_meta($user->ID, 'first_name', true);
			    						   					$last_name = get_user_meta($user->ID, 'last_name', true);
			    						   					if ($first_name || $last_name){
			    						   						echo $first_name .' '.$last_name;
			    						   					} else {
			    						   						echo $user->user_nicename;
			    						   					}
			    						   				?>
			    						   			</td>
			    						   			<td>
			    						   				<a href="<?php echo 'mailto:' . $user->user_email;?>" target="_blank"><?php echo $user->user_email;?></a>
			    						   			</td>
			    						   			<td style="font-weight:bold;">
			    						   				<?php
															$user_levels = Ihc_Db::get_user_levels($user->ID);
															if ($user_levels){
																foreach ($user_levels as $lid=>$level_data){
					    						   					$is_expired_class = '';
					    						   					$level_title = "Active";
																	if ($level_data['is_expired']){
					    						   						$is_expired_class = 'ihc-expired-level';
																		$level_title = "Hold/Expired";
					    						   					}
																	?>
																	<div class="level-type-list <?php echo $is_expired_class;?>" title="<?php echo $level_data['level_slug']?>"><?php echo $level_data['label']?></div>
																	<?php
																}
															}
			    						   				?>
			    						   			</td>
			    						   			<?php if (!empty($magic_feat_user_sites)):?>
			    						   				<?php
															$sites = array();
															$temp = array();
															if (!empty($user_levels)){
																foreach ($user_levels as $lid=>$level_data){
																	$temp['blog_id'] = Ihc_Db::get_user_site_for_uid_lid($user->ID, $lid);
																	if (!empty($temp['blog_id'])){
																		$site_details = get_blog_details( $temp['blog_id'] );
																		$temp['link'] = untrailingslashit($site_details->domain . $site_details->path);
																		$temp['blogname'] = $site_details->blogname;
																		if (strpos($temp['link'], 'http')===FALSE){
																			$temp['link'] = 'http://' . $temp['link'];
																		}
																		$temp['extra_class'] = Ihc_Db::is_blog_available($temp['blog_id']) ? 'fa-sites-is-active' : 'fa-sites-is-not-active';
																		$sites[] = $temp;
																	}
																}
															}
			    						   				?>
												  		<td class="manage-column">
												  			<?php if ($sites):?>
												  				<?php foreach ($sites as $site_data):?>
														  			<a href="<?php echo $temp['link'];?>" target="_blank" title="<?php echo $temp['blogname'];?>">
															  			<i class="fa-ihc fa-user_sites-ihc <?php echo $site_data['extra_class'];?>"></i>
														  			</a>
												  				<?php endforeach;?>
												  			<?php endif;?>
												  		</td>
												  	<?php endif;?>
			    						   			<td>
			    						   				<div id="user-<?php echo $user->ID;?>-status">
				    						   				<?php
				    						   					if (isset($roles) && $roles[0]=='pending_user'){
				    						   						 ?>
				    						   						 	<span class="subcr-type-list iump-pending"><?php _e('Pending', 'ihc');?></span>
				    						   						 <?php
				    						   					} else {
				    						   						 ?>
				    						   						 	<span class="subcr-type-list"><?php
				    						   						 		if (isset($roles) && isset($available_roles[$roles[0]])){
				    						   						 			echo $available_roles[$roles[0]];
				    						   						 		} else {
																				echo '-';
				    						   						 		}
				    						   						 	?></span>
				    						   						 <?php
				    						   					}
																		if (count($roles)>1){
																				for ($i=1;$i<count($roles); $i++){
																						?>
																						<span class="subcr-type-list">
																								<?php if (isset($available_roles[$roles[$i]])) echo $available_roles[$roles[$i]];
																											else echo __('Unknown role', 'ihc');
																								?>
																						</span>
																						<?php
																				}
																		}
				    						   				?>
			    						   				</div>
			    						   			</td>
			    						   			<td><?php
			    						   				$div_id = "user_email_" . $user->ID . "_status";
			    						   				$class = 'subcr-type-list';
			    						   				if ($verified_email==1){
			    						   					$label = __('Verified', 'ihc');
			    						   				} else if ($verified_email==-1){
			    						   					$label = __('Unapproved', 'ihc');
			    						   					$class = 'subcr-type-list iump-pending';
			    						   				} else {
			    						   					$label = __('-', 'ihc');
			    						   				}
			    						   				?>
			    						   					<div id="<?php echo $div_id;?>">
			    						   						<span class="<?php echo $class;?>"><?php echo $label;?></span>
			    						   					</div>
			    						   				<?php
			    						   			?></td>
			    						   			<td style="color: #396;">
			    						   				<?php
			    						   					echo ihc_convert_date_to_us_format($user->user_registered);
			    						   				?>
			    						   			</td>
													<td>
														<?php
														$ord_count = ihc_get_user_orders_count($user->ID);
														if(isset($ord_count) && $ord_count > 0): ?>
														<div class="ihc_frw_button"> <a href="<?php echo admin_url('admin.php?page=ihc_manage&tab=orders&uid=') . $user->ID;?>" target="_blank">Orders</a></div>
														<?php endif;?>
														<?php unset($ord_count);?>
														<?php if (ihc_is_magic_feat_active('user_reports') && Ihc_User_Logs::get_count_logs('user_logs', $user->ID)):?>
															<div class="level-type-list"> <a href="<?php echo admin_url('admin.php?page=ihc_manage&tab=view_user_logs&type=user_logs&uid=') . $user->ID;?>" target="_blank" style="color: #fff;"><?php _e('User Reports', 'ihc');?></a></div>
														<?php endif;?>

													</td>
			    						   		</tr>
							  			<?php
							  			$i++;
							  		}
							  ?>
						   </table>
						   <div style="margin-top: 10px;">
						   		<input type="submit" value="<?php _e('Delete', 'ihc');?>" name="delete" onClick="ihc_first_confirm('<?php _e('Are You Sure You want to delete selected Users?');?>');" class="button button-primary button-large"/>
						   </div>
						<?php
					}else{ ?>
					<div  class="ihc-warning-message"><?php _e('No Users Available.', 'ihc');?></div>
					<?php }
				?>
			</div>
		</form>
	</div>
</div>
<div class="clear"></div>
<?php
}
