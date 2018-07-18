<?php

/*Enqueue parent theme styles*/
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_uri(), array('parent-style')  );
}

/*Enqueue custom BATC scripts*/
function batc_scripts() {
    wp_enqueue_script( 'batc', get_stylesheet_directory_uri() . '/js/batc.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'batc_scripts' );

/*If is category page Memberships*/
add_filter( 'template_include', 'so_25789472_template_include' );
function so_25789472_template_include( $template ) {
 if ( is_product_category('memberships')) {
  $template = get_stylesheet_directory() . '/woocommerce/archive-countries.php';
  }
  return $template;
}

/*If is category page Country*/
add_filter( 'template_include', 'so_25789473_template_include' );
function so_25789473_template_include( $template ) {
 if ( is_product_category('country')) {
  $template = get_stylesheet_directory() . '/woocommerce/archive-countries.php';
  }
  return $template;
}

/*Define sortable columns in user table*/
function user_sortable_columns( $columns ) {
    $columns['call_sign'] = 'call_sign';
    $columns['id'] = 'id';
    $columns['name'] - 'name';
    $columns['membership_level'] = 'membership_level';
    $columns['subscription_orders'] = 'subscription_orders';
    $columns['chat_name'] = 'chat_name';
    $columns['renewal_date'] = 'renewal_date';
    $columns['first_name'] = 'first_name';
    $columns['last_name'] = 'last_name';
    $columns['postcode'] = 'postcode';
    return $columns;
}
add_filter( 'manage_users_sortable_columns', 'user_sortable_columns' );

/*Define shop single template based on category*/
add_filter( 'woocommerce_locate_template', 'so_25789472_locate_template', 10, 3 );
function so_25789472_locate_template( $template, $template_name, $template_path ){
    if( is_product() && has_term( 'country', 'product_cat' ) && strpos( $template_name, 'single-product/') !== false ){
        $mock_template_name = str_replace("single-product/", "single-product-membership/", $template_name );
        $mock_template = locate_template(
            array(
                trailingslashit( $template_path ) . $mock_template_name,
                $mock_template_name
            )
        );
        if ( $mock_template ) {
            $template = $mock_template;
        }
    }
    return $template;
}
if ( has_term( 'country', 'product_cat' ) ) {
    wc_get_template( 'archive-product.php' );
}
else { wc_get_template( 'archive-product-list.php' );
}

/*Country Select shortcode*/
function country_select ($atts) {
  echo '<div class="country_select">';
  echo '<p class="description">There are several tiers of membership available – all members receive an email with a download link when a new edition of the CQ-TV magazine is made available.</p><p class="description">Full membership includes a printed copy on high quality gloss sent direct to you by post.</p><p class="description">Cyber members receive an email with a download link to the PDF version.</p><p class="description">Due to postage rates there are different types of membership depending on where you live:</p>';
  $args = array(
      'order'      => 'ASC',
      'hide_empty' => $hide_empty,
      'include'    => $ids,
      'posts_per_page' =>'-1',
      'child_of' => '17'
  );
  $product_categories = get_terms( 'product_cat', $args );
  echo "<select id='country_select'>";
  foreach( $product_categories as $category ){
      echo "<option value = '/category/country/" . esc_attr( $category->slug ) . "'>" . esc_html( $category->name ) . "</option>";
  }
  echo "</select>";
  echo "</div>";
}
add_shortcode('country_select','country_select');

/*Remove add to cart button from loop from category page*/
function remove_loop_button(){
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
}
add_action('init','remove_loop_button');


/*Insert See Details button in loop on category page*/
add_action('woocommerce_after_shop_loop_item','replace_add_to_cart');
function replace_add_to_cart() {
global $product;
$link = $product->get_permalink();
echo do_shortcode('<br><a rel="nofollow" href="' . esc_attr($link) . '" class="button product_type_simple add_to_cart_button ajax_add_to_cart">See Details</a>');
}

/*Remove reviews tab*/
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
    function wcs_woo_remove_reviews_tab($tabs) {
    unset($tabs['reviews']);
    return $tabs;
}

/*Remove My Dashboard link from Woocommerce dashbaord*/
remove_action(
	'woocommerce_account_navigation',
	'woocommerce_account_navigation'
);

/*Remove memberships from shop homepage*/
add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
function custom_pre_get_posts_query( $q ) {
	if ( ! $q->is_main_query() ) return;
	if ( ! $q->is_post_type_archive() ) return;
	if ( ! is_admin() && is_shop() ) {
		$q->set( 'tax_query', array(array(
			'taxonomy' => 'product_cat',
			'field' => 'slug',
			'terms' => array( 'country' ),
			'operator' => 'NOT IN'
		)));
	}
	remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );
}

/*Register custom menu - Accounts Menu*/
function wpb_custom_new_menu() {
  register_nav_menus(
    array(
      'accounts-menu' => __( 'Accounts Menu' ),
    )
  );
}
add_action( 'init', 'wpb_custom_new_menu' );

/*Woocommerce billing phone & company not required*/
add_filter( 'woocommerce_billing_fields', 'wc_npr_filter_phone', 10, 1 );
function wc_npr_filter_phone( $address_fields ) {
$address_fields['billing_phone']['required'] = false;
return $address_fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
 unset($fields['billing']['billing_company']);
 unset($fields['billing']['billing_phone']);
    return $fields;
}


/*Allow only one item from membership category in cart*/
add_filter('woocommerce_add_to_cart', 'my_woocommerce_add_to_cart', 8, 6);
function my_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){
global $woocommerce;
$_category_id = 6;
$_categorie = get_the_terms($product_id, 'product_cat');
if ($_categorie) {
    $product_is_licence = false;
    foreach ($_categorie as $_cat) {
        $_lacat = $_cat->term_id;
        if ($_lacat === $_category_id) {
            $product_is_licence = true;
        }
    }
}
if($product_is_licence){
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $value) {
        $_product = $value['data'];
        $_thisID    = $_product->id;
        $terms    = get_the_terms($_product->id, 'product_cat');
        if ($terms) {
            foreach ($terms as $term) {
                $_categoryid = $term->term_id;
                if (($_categoryid === $_category_id)&&($product_id !== $_thisID)) {
                    $woocommerce->cart->remove_cart_item($cart_item_key);
                    $message = sprintf( '%s has been removed from your cart.',$_product->get_title());
                    wc_add_notice($message, 'success');
                }
            }
        }
    }
  }
}

/*Removes admin colour scheme options*/
remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

/*Adds admin.css & admin.js to header*/
add_action('admin_head', 'admin_styles_scripts');
function admin_styles_scripts() {
  echo '<link rel="stylesheet" type="text/css" href="/wp-content/themes/iconic-one-child/admin.css">';
  echo '<script src="/wp-content/themes/iconic-one-child/admin.js"></script>';
}

/*Admin text changes*/
function gb_change_batc_keywords_admin($translated_text, $text, $domain) {
  $translated_text = str_replace("Customer shipping address", "Customer delivery address", $translated_text);
  $translated_text = str_replace("The password should be at least twelve characters long", " The password should be at least eight characters long", $translated_text);
  return $translated_text;
}
add_filter('gettext', 'gb_change_batc_keywords_admin', 100, 3);

/*Identify users by username, not first + last names*/
function wpse_20160110_user_register ( $user_id ) {
    $user_info = get_userdata( $user_id );
    $display_publicly_as = $user_info->user_login;
    wp_update_user( array ('ID' => $user_id, 'display_name' =>  $display_publicly_as));
}
add_action( 'user_register', 'wpse_20160110_user_register', 10, 1 );

//function for setting the last login
function set_last_login($login) {
    $user = get_userdatabylogin($login);
    $curent_login_time = get_user_meta( $user->ID , 'current_login', true);
    //add or update the last login value for logged in user
    if(!empty($curent_login_time)){
        update_usermeta( $user->ID, 'last_login', $curent_login_time );
        update_usermeta( $user->ID, 'current_login', current_time('mysql') );
    }else {
        update_usermeta( $user->ID, 'current_login', current_time('mysql') );
        update_usermeta( $user->ID, 'last_login', current_time('mysql') );
    }
}
add_action('wp_login', 'set_last_login');


/*Embed admin User Fields*/
function batc_user_profile_fields( $user ) {
if ( defined('IS_PROFILE_PAGE') && IS_PROFILE_PAGE ) {
    $user_id = get_current_user_id();
} elseif (! empty($_GET['user_id']) && is_numeric($_GET['user_id']) ) {
    $user_id = $_GET['user_id'];
} else {
    die( 'No user id defined.' );
}
?>
<table class="form-table call_sign">
    <tr>
      <th><label for="call_sign"><?php _e("Call Sign"); ?></label></th>
      <td>
        <input type="text" name="call_sign" id="call_sign" class="regular-text"
            value="<?php echo esc_attr( get_user_meta( $user->ID, 'call_sign', true ) ); ?>" />
    </td>
    </tr>
  </table>
  <table class="form-table membership_number">
      <tr>
        <th><label for="membership_number"><?php _e("Membership Number"); ?></label></th>
        <td>
          <input type="text" name="membership_number" id="membership_number" class="regular-text"
              value="<?php echo $user->ID; ?>" disabled="disabled" /><span class="description"><?php _e('Membership numbers cannot be changed.'); ?></span>
      </td>
      </tr>
    </table>
    <table class="form-table chat_name">
        <tr>
          <th><label for="chat_name"><?php _e("Chat Nick Name"); ?></label></th>
          <td>
            <input type="text" name="chat_name" id="chat_name" class="regular-text"
                value="<?php echo esc_attr( get_user_meta( $user->ID, 'chat_name', true ) ); ?>">
        </td>
        </tr>
      </table>
      <h2>Registration Details</h2>
      <table class="form-table expire_date">
        <tr>
          <th>Joined date</th>
            <td>
              <p>
                <?php
                global $wpdb;
                get_currentuserinfo();
                  $data = $wpdb->get_row('SELECT expire_time, start_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user->ID . '";');
                  $joined_date = $data->start_time;
                  $joined_date_format = date("d-m-Y", strtotime($joined_date));
                  echo $joined_date_format
                ?>
              </p>
            </td>
          </tr>
      </table>
      <table class="form-table expire_date">
        <tr>
          <th>Renewal date</th>
            <td>
              <p>
                <?php
                global $wpdb;
                get_currentuserinfo();
                  $data = $wpdb->get_row('SELECT expire_time, start_time FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user->ID . '";');
                  $renewal_date = $data->expire_time;
                  $renewal_date_format = date("Y-m-d", strtotime($renewal_date));
                  echo $renewal_date_format
                ?>
              </p>
            </td>
          </tr>
      </table>
      <table class="form-table expire_date">
        <tr>
          <th>User Level</th>
            <td>
              <p>
                <?php
                global $wpdb;
                get_currentuserinfo();
                  $level = $wpdb->get_row('SELECT level_id FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user->ID . '";');
                  $level_id = $level->level_id;
                  $level_array = $wpdb->get_row('SELECT option_value FROM ' . $wpdb->prefix . 'options WHERE option_id="583";');
                  $level_array = unserialize($level_array->option_value);
                  echo $level_array[$level_id]['label'];
                ?>
              </p>
            </td>
          </tr>
      </table>
    <table class="form-table last_login">
  		<tr>
  			<th>Last login date</th>
    			<td>
    				<p>
              <?php
                 $last_login = get_user_meta($user->ID, 'last_login', true);
                 $date_format = get_option('date_format') . ' ' . get_option('time_format');
              		$the_last_login = mysql2date($date_format, $last_login, false);
                 echo $the_last_login;
              ?>
            </p>
    			</td>
    		</tr>
  	</table>
    <table class="form-table modified_date">
        <tr>
          <th><label for="modified_date"><?php _e("Record updated on"); ?></label></th>
          <td>
            <?php $update_date = esc_attr( get_user_meta( $user->ID, 'wpse216609_profile_updated', true ) );
            $update_date_format = date("d-m-Y h:m:s", strtotime($update_date));
            echo $update_date_format;
            ?>
        </td>
        </tr>
      </table>
      <table class="form-table note_field">
          <tr>
            <th><label for="note_field"><?php _e("Note Field"); ?></label></th>
            <td>
              <textarea name="note_field" id="note_field" rows="5" cols="30"><?php echo esc_attr( get_user_meta( $user->ID, 'note_field', true ) ); ?></textarea>
          </td>
          </tr>
        </table>
        <table class="form-table notice_sent">
            <tr>
              <th><label for="notice_sent"><?php _e("Notice Sent (Date)"); ?></label></th>
              <td>
                <input type="date" name="notice_sent" id="notice_sent" class="regular-text"
                    value="<?php echo esc_attr( get_user_meta( $user->ID, 'notice_sent', true ) ); ?>">
            </td>
            </tr>
          </table>
          <h2>Streaming Details</h2>
          <table class="form-table stream_title">
              <tr>
                <th><label for="stream_title"><?php _e("Stream Title"); ?></label></th>
                <td>
                  <input type="text" name="stream_title" id="stream_title" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'stream_title', true ) ); ?>" />
              </td>
              </tr>
              <tr>
				<th><label for="stream_output_url"><?php _e("Stream Output URL<br> https://batc.org.uk/live/<br>  This is the streamname"); ?></label></th>
                <td>
                  <input type="text" name="stream_output_url" id="stream_output_url" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'stream_output_url', true ) ); ?>" />
              </td>
              </tr>
              <tr>
				<th><label for="stream_rtmp_input_url"><?php _e("RTMP Input URL<br>  rtmp://rtmp.batc.org.uk/live/streamname-<br>  This is the key<br>  Use streamname from field above"); ?></label></th>
                <td>
                  <input type="text" name="stream_rtmp_input_url" id="stream_rtmp_input_url" class="regular-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'stream_rtmp_input_url', true ) ); ?>" />
              </td>
              </tr>
              <table class="form-table stream_description">
                  <tr>
                    <th><label for="note_field"><?php _e( 'Stream Description', 'woocommerce' ); ?></label></th>
                    <td>
                      <textarea name="stream_description" id="stream_description" rows="5" cols="30"><?php echo esc_attr( get_user_meta( $user->ID, 'stream_description', true ) ); ?></textarea>
                  </td>
                  </tr>
                </table>
            </table>
          <table class="form-table stream_title">
              <p class="checkbox_title">Stream Type</p>
            <p class="form-row form-row-thirds">
              <label for="stream_type_member"><?php _e( 'Member', 'woocommerce' ); ?></label>
              <input type="checkbox" name="stream_type_member" id="stream_type_member" value="1" <?php if (esc_attr( get_the_author_meta( "stream_type_member", $user->ID )) == "1") echo "checked"; ?>/>
            </p>
            <p class="form-row form-row-thirds">
              <label for="stream_type_repeater"><?php _e( 'Repeater', 'woocommerce' ); ?></label>
              <input type="checkbox" name="stream_type_repeater" id="stream_type_repeater" value="1"  <?php if (esc_attr( get_the_author_meta( "stream_type_repeater", $user->ID )) == "1") echo "checked"; ?>/>
            </p>
            <p class="form-row form-row-thirds">
              <label for="stream_type_event"><?php _e( 'Event', 'woocommerce' ); ?></label>
              <input type="checkbox" name="stream_type_event" id="stream_type_event" value="1"  <?php if (esc_attr( get_the_author_meta( "stream_type_event", $user->ID )) == "1") echo "checked"; ?>/>
            </p>
            </table>
          <table class="form-table stream_options">
              <p class="checkbox_title">Stream Options</p>
            <p class="form-row form-row-thirds">
              <label for="stream_listed"><?php _e( 'Stream Listed', 'woocommerce' ); ?></label>
              <input type="checkbox" name="stream_listed" id=" stream_listed " value="1" <?php if (esc_attr( get_the_author_meta( "stream_listed", $user->ID )) == "1") echo "checked"; ?> />
            </p>
            <p class="form-row form-row-thirds">
              <label for="chat_on"><?php _e( 'Chat On', 'woocommerce' ); ?></label>
              <input type="checkbox" name="chat_on" id=" chat_on " value="1" <?php if (esc_attr( get_the_author_meta( "chat_on", $user->ID )) == "1") echo "checked"; ?> />
            </p>
            <p class="form-row form-row-thirds">
              <label for="guest_chat_login"><?php _e( 'Guest Chat Log In', 'woocommerce' ); ?></label>
              <input type="checkbox" name="guest_chat_login" id=" guest_chat_login " value="1" <?php if (esc_attr( get_the_author_meta( "guest_chat_login", $user->ID )) == "1") echo "checked"; ?> />
            </p>
            </table>
          <table class="form-table streaming_tye">
            <p class="checkbox_title">Streaming Type</p>
          <p class="form-row form-row-thirds">
            <label for="streaming_type_flash"><?php _e( 'Flash', 'woocommerce' ); ?></label>
            <input type="checkbox" name="streaming_type_flash" id="streaming_type_flash" value="1" <?php if (esc_attr( get_the_author_meta( "streaming_type_flash", $user->ID )) == "1") echo "checked"; ?> />
          </p>
          <p class="form-row form-row-thirds">
            <label for="streaming_type_html5"><?php _e( 'HTML 5', 'woocommerce' ); ?></label>
            <input type="checkbox" name="streaming_type_html5" id="streaming_type_html5" value="1" <?php if (esc_attr( get_the_author_meta( "streaming_type_html5", $user->ID )) == "1") echo "checked"; ?> />
          </p>
            </table>
<?php
}
add_action( 'show_user_profile', 'batc_user_profile_fields' );
add_action( 'edit_user_profile', 'batc_user_profile_fields' );

/*Save admin User fields*/
function batc_save_user_profile_fields_call_sign( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'call_sign', wp_kses_post( $_POST['call_sign'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_call_sign' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_call_sign' );

function batc_save_user_profile_fields_chat_name( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'chat_name', wp_kses_post( $_POST['chat_name'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_chat_name' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_chat_name' );

function batc_save_user_profile_fields_note_field( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'note_field', wp_kses_post( $_POST['note_field'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_note_field' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_note_field' );

function batc_save_user_profile_fields_notice_sent( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'notice_sent', wp_kses_post( $_POST['notice_sent'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_notice_sent' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_notice_sent' );

function batc_save_user_profile_fields_stream_title( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'stream_title', wp_kses_post( $_POST['stream_title'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_stream_title' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_stream_title' );

function batc_save_user_profile_fields_stream_output_url( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'stream_output_url', wp_kses_post( $_POST['stream_output_url'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_stream_output_url' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_stream_output_url' );

function batc_save_user_profile_fields_stream_rtmp_input_url( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'stream_rtmp_input_url', wp_kses_post( $_POST['stream_rtmp_input_url'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_stream_rtmp_input_url' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_stream_rtmp_input_url' );

function batc_save_user_profile_fields_stream_type( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'stream_type_member', wp_kses_post( $_POST['stream_type_member'] ) );
    update_usermeta( absint( $user_id ), 'stream_type_repeater', wp_kses_post( $_POST['stream_type_repeater'] ) );
    update_usermeta( absint( $user_id ), 'stream_type_event', wp_kses_post( $_POST['stream_type_event'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_stream_type' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_stream_type' );

function batc_save_user_profile_fields_stream_options( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_usermeta( absint( $user_id ), 'stream_listed', wp_kses_post( $_POST['stream_listed'] ) );
    update_usermeta( absint( $user_id ), 'chat_on', wp_kses_post( $_POST['chat_on'] ) );
    update_usermeta( absint( $user_id ), 'guest_chat_login', wp_kses_post( $_POST['guest_chat_login'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_stream_options' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_stream_options' );

function batc_save_user_profile_fields_streaming_type( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_user_meta( absint( $user_id ), 'streaming_type_flash', wp_kses_post( $_POST['streaming_type_flash'] ) );
    update_user_meta( absint( $user_id ), 'streaming_type_html5', wp_kses_post( $_POST['streaming_type_html5'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_streaming_type' );
add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_streaming_type' );

function sp_update_streaming_ajax(){
  $user_id = get_current_user_id();
  update_user_meta( absint( $user_id ), 'streaming_type_html5', wp_kses_post($_POST['streaming_type_html5']) );
  update_user_meta( absint( $user_id ), 'streaming_type_flash', wp_kses_post($_POST['streaming_type_flash']) );
  wp_die();
}
add_action('wp_ajax_sp_update_streaming','sp_update_streaming_ajax');

function sp_update_streaming_js(){
?>
<script type="text/javascript">
jQuery("#streaming_type_html5").on('change',function(){
    var streaming_type_html5=0;
    var streaming_type_flash=0;
    var admin_url='<?php echo admin_url(); ?>admin-ajax.php';
         if(this.checked) {
            streaming_type_html5=1;
            streaming_type_flash=0;
          }else{
            streaming_type_html5=0;
            streaming_type_flash=1;
          }
        jQuery.ajax({
            url:admin_url,
            data:{streaming_type_html5:streaming_type_html5,streaming_type_flash:streaming_type_flash,action:'sp_update_streaming'},
            type:'post',
            dataType:'html',
            success:function(result){

            }
        });

    });
jQuery("#streaming_type_flash").on('change',function(){
    var streaming_type_html5=0;
    var streaming_type_flash=0;
    var admin_url='<?php echo admin_url(); ?>admin-ajax.php';
         if(this.checked) {
            streaming_type_html5=0;
            streaming_type_flash=1;
          }else{
            streaming_type_html5=1;
            streaming_type_flash=0;
          }
        jQuery.ajax({
            url:admin_url,
            data:{streaming_type_html5:streaming_type_html5,streaming_type_flash:streaming_type_flash,action:'sp_update_streaming'},
            type:'post',
            dataType:'html',
            success:function(result){

            }
        });

    });
</script>
<?php
}
add_action('wp_footer','sp_update_streaming_js');
function batc_save_user_profile_fields_stream_description( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;
    update_user_meta( absint( $user_id ), 'stream_description', wp_kses_post( $_POST['stream_description'] ) );
}
add_action( 'personal_options_update', 'batc_save_user_profile_fields_stream_description' );

add_action( 'edit_user_profile_update', 'batc_save_user_profile_fields_stream_description' );

/*Embed Woocommerce checkout fields*/
function reigel_woocommerce_checkout_fields_rtmp( $checkout_fields = array() ) {
    $checkout_fields['order']['stream_rtmp_input_url'] = array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide rtmp'),
        'label'         => __('RTMP Input URL<br>  rtmp://rtmp.batc.org.uk/live/streamname-'),
        'placeholder'   => __('RTMP Input URL<br>  rtmp://rtmp.batc.org.uk/live/streamname-'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_rtmp' );

function reigel_woocommerce_checkout_fields_call_sign( $checkout_fields = array() ) {
    $checkout_fields['order']['call_sign'] = array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide call_sign'),
        'label'         => __('Call Sign'),
        'placeholder'   => __('Call Sign'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_call_sign' );

function reigel_woocommerce_checkout_fields_chat_name( $checkout_fields = array() ) {
    $checkout_fields['order']['chat_name'] = array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide chat_name'),
        'label'         => __('Chat Name'),
        'placeholder'   => __('Chat Name'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_chat_name' );

function reigel_woocommerce_checkout_fields_stream_output_url( $checkout_fields = array() ) {
    $checkout_fields['order']['stream_output_url'] = array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide stream_output_url'),
        'label'         => __('Stream Output URL'),
        'placeholder'   => __('Stream Output URL'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_stream_output_url' );

function reigel_woocommerce_checkout_fields_stream_title( $checkout_fields = array() ) {
    $checkout_fields['order']['stream_title'] = array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide stream_title'),
        'label'         => __('Stream Title'),
        'placeholder'   => __('Stream Title'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_stream_title' );

function reigel_woocommerce_checkout_fields_stream_type_member( $checkout_fields = array() ) {
    $checkout_fields['order']['stream_type_member'] = array(
        'type'          => 'checkbox',
        'class'         => array('my-field-class form-row-wide stream_type_member checked_default woocommerce-validated'),
        'label'         => __('Stream Type Member'),
        'placeholder'   => __('Stream Type Member'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_stream_type_member' );

function reigel_woocommerce_checkout_fields_chat_on( $checkout_fields = array() ) {
    $checkout_fields['order']['chat_on'] = array(
        'type'          => 'checkbox',
        'class'         => array('my-field-class form-row-wide chat_on checked_default woocommerce-validated'),
        'label'         => __('Chat On'),
        'placeholder'   => __('Chat On'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_chat_on' );

function reigel_woocommerce_checkout_fields_streaming_type_flash( $checkout_fields = array() ) {
    $checkout_fields['order']['streaming_type_flash'] = array(
        'type'          => 'checkbox',
        'class'         => array('my-field-class form-row-wide chat_on checked_default woocommerce-validated'),
        'label'         => __('Streaming Type Flash'),
        'placeholder'   => __('Streaming Type Flash'),
        'required'      => false,
        );
    return $checkout_fields;
}
add_filter( 'woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields_streaming_type_flash' );

function reigel_woocommerce_checkout_update_user_meta_call_sign( $customer_id, $posted ) {
    if (isset($posted['call_sign'])) {
        $somefieldname = sanitize_text_field( $posted['call_sign'] );
        update_user_meta( $customer_id, 'call_sign', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_call_sign', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_rtmp( $customer_id, $posted ) {
    if (isset($posted['stream_rtmp_input_url'])) {
        $somefieldname = sanitize_text_field( $posted['stream_rtmp_input_url'] );
        update_user_meta( $customer_id, 'stream_rtmp_input_url', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_rtmp', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_chat_name( $customer_id, $posted ) {
    if (isset($posted['chat_name'])) {
        $somefieldname = sanitize_text_field( $posted['chat_name'] );
        update_user_meta( $customer_id, 'chat_name', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_chat_name', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_stream_output_url( $customer_id, $posted ) {
    if (isset($posted['stream_output_url'])) {
        $somefieldname = sanitize_text_field( $posted['stream_output_url'] );
        update_user_meta( $customer_id, 'stream_output_url', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_stream_output_url', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_stream_title( $customer_id, $posted ) {
    if (isset($posted['stream_title'])) {
        $somefieldname = sanitize_text_field( $posted['stream_title'] );
        update_user_meta( $customer_id, 'stream_title', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_stream_title', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_stream_type_member( $customer_id, $posted ) {
    if (isset($posted['stream_type_member'])) {
        $somefieldname = sanitize_text_field( $posted['stream_type_member'] );
        update_user_meta( $customer_id, 'stream_type_member', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_stream_type_member', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_stream_listed( $customer_id, $posted ) {
    if (isset($posted['stream_listed'])) {
        $somefieldname = sanitize_text_field( $posted['stream_listed'] );
        update_user_meta( $customer_id, 'stream_listed', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_stream_listed', 10, 2 );

function reigel_woocommerce_checkout_update_user_meta_chat_on( $customer_id, $posted ) {
    if (isset($posted['chat_on'])) {
        $somefieldname = sanitize_text_field( $posted['chat_on'] );
        update_user_meta( $customer_id, 'chat_on', $somefieldname);
    }
}

add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_chat_on', 10, 2 );
function reigel_woocommerce_checkout_update_user_meta_streaming_type_flash( $customer_id, $posted ) {
    if (isset($posted['streaming_type_flash'])) {
        $somefieldname = sanitize_text_field( $posted['streaming_type_flash'] );
        update_user_meta( $customer_id, 'streaming_type_flash', $somefieldname);
    }
}
add_action( 'woocommerce_checkout_update_user_meta', 'reigel_woocommerce_checkout_update_user_meta_streaming_type_flash', 10, 2 );

/*Embed Woocommerce account fields*/
add_action( 'woocommerce_edit_account_form', 'my_woocommerce_edit_account_form' );
add_action( 'woocommerce_save_account_details', 'my_woocommerce_save_account_details' );
function my_woocommerce_edit_account_form() {
  $user_id = get_current_user_id();
  $user = get_userdata( $user_id );
  if ( !$user )
    return;
  $chat_name = get_user_meta( $user_id, 'chat_name', true );
  $url = $user->user_url;
  ?>
  <form>
    <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first chat_nick_name">
      <label for="chat_name">Chat Nick Name</label>
      <input type="text" name="chat_name" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr( $chat_name ); ?>" class="input-text" />
    </p>
  <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
    <label for="stream_title"><?php _e( 'Stream Title', 'woocommerce' ); ?></label>
    <input type="text" name="stream_title" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'stream_title', true ) ); ?>"/>
  </p>
  <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
	<label for="stream_output_url"><?php _e( 'Stream Output URL<br> https://batc.org.uk/live/', 'woocommerce' ); ?></label>
    <input type="text" name="stream_output_url" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'stream_output_url', true ) ); ?>" disabled="disabled"/>
  </p>
  <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-last">
	<label for="stream_rtmp_input_url"><?php _e( 'Stream RTMP Input URL<br>  rtmp://rtmp.batc.org.uk/live/streamname-', 'woocommerce' ); ?></label>
    <input type="text" name="stream_rtmp_input_url" class="woocommerce-Input woocommerce-Input--text input-text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'stream_rtmp_input_url', true ) ); ?>" disabled="disabled"/>
  </p>
<fieldset class="stream_type">
  <p class="checkbox_title">Stream Type</p>
<p class="form-row form-row-thirds">
  <label for="stream_type_member"><?php _e( 'Member', 'woocommerce' ); ?></label>
  <input type="checkbox" name="stream_type_member" id="stream_type_member" value="1" <?php if (esc_attr( get_the_author_meta( "stream_type_member", $user->ID )) == "1") echo "checked"; ?> disabled="disabled" />
</p>
<p class="form-row form-row-thirds">
  <label for="stream_type_repeater"><?php _e( 'Repeater', 'woocommerce' ); ?></label>
  <input type="checkbox" name="stream_type_repeater" id="stream_type_repeater" value="1" <?php if (esc_attr( get_the_author_meta( "stream_type_repeater", $user->ID )) == "1") echo "checked"; ?> disabled="disabled" />
</p>
<p class="form-row form-row-thirds">
  <label for="stream_type_event"><?php _e( 'Event', 'woocommerce' ); ?></label>
  <input type="checkbox" name="stream_type_event" id="stream_type_event" value="1" <?php if (esc_attr( get_the_author_meta( "stream_type_event", $user->ID )) == "1") echo "checked"; ?> disabled="disabled" />
</p>
</fieldset>
<fieldset class="stream_options">
  <p class="checkbox_title">Stream Options</p>
<p class="form-row form-row-thirds">
  <label for="stream_listed"><?php _e( 'Stream Listed', 'woocommerce' ); ?></label>
  <input type="checkbox" name="stream_listed" id=" stream_listed " value="1" <?php if (esc_attr( get_the_author_meta( "stream_listed", $user->ID )) == "1") echo "checked"; ?> />
</p>
<p class="form-row form-row-thirds">
  <label for="chat_on"><?php _e( 'Chat On', 'woocommerce' ); ?></label>
  <input type="checkbox" name="chat_on" id=" chat_on " value="1" <?php if (esc_attr( get_the_author_meta( "chat_on", $user->ID )) == "1") echo "checked"; ?> />
</p>
<p class="form-row form-row-thirds">
  <label for="guest_chat_login"><?php _e( 'Guest Chat Log In', 'woocommerce' ); ?></label>
  <input type="checkbox" name="guest_chat_login" id=" guest_chat_login " value="1" <?php if (esc_attr( get_the_author_meta( "guest_chat_login", $user->ID )) == "1") echo "checked"; ?> />
</p>
</fieldset>
<fieldset class="streaming_type">
  <p class="checkbox_title">Streaming Type</p>
<p class="form-row form-row-thirds">
  <label for="streaming_type_flash"><?php _e( 'Flash', 'woocommerce' ); ?></label>
  <input type="checkbox" name="streaming_type_flash" id="streaming_type_flash" value="1" <?php if (esc_attr( get_the_author_meta( "streaming_type_flash", $user->ID )) == "1") echo "checked"; ?> />
</p>
<p class="form-row form-row-thirds">
  <label for="streaming_type_html5"><?php _e( 'HTML 5', 'woocommerce' ); ?></label>
  <input type="checkbox" name="streaming_type_html5" id="streaming_type_html5" value="1" <?php if (esc_attr( get_the_author_meta( "streaming_type_html5", $user->ID )) == "1") echo "checked"; ?> />
</p>
</fieldset>
<fieldset class="stream_description">
<p class="form-row">
  <label for="stream_description" class="stream_description_title"><?php _e( 'Stream Description', 'woocommerce' ); ?></label>
  <textarea name="stream_description" id="stream_description" rows="5" cols="60"><?php echo esc_attr( get_user_meta( $user->ID, 'stream_description', true ) ); ?></textarea>
</p>
</fieldset>
</form>
  <?php
}


/*Save Woocommerce account fields*/
function my_woocommerce_save_account_details( $user_id ) {
  update_user_meta( $user_id, 'chat_name', htmlentities( $_POST[ 'chat_name' ] ) );
  update_user_meta( $user_id, 'call_sign', htmlentities( $_POST[ 'call_sign' ] ) );
  update_user_meta( $user_id, 'stream_title', htmlentities( $_POST[ 'stream_title' ] ) );
  update_user_meta( $user_id, 'stream_listed', $_POST['stream_listed'] );
  update_user_meta( $user_id, 'chat_on', $_POST['chat_on'] );
  update_user_meta( $user_id, 'guest_chat_login', $_POST['guest_chat_login'] );
  update_user_meta( $user_id, 'stream_description', $_POST['stream_description'] );
  $user = wp_update_user( array( 'ID' => $user_id, 'user_url' => esc_url( $_POST[ 'url' ] ) ) );
}

/*Set last login date*/
add_action('wp_login','wpsnipp_set_last_login', 0, 2);
function wpsnipp_set_last_login($login, $user) {
    $user = get_user_by('login',$login);
    $time = current_time( 'timestamp' );
    $last_login = get_user_meta( $user->ID, '_last_login', 'true' );
    if(!$last_login){
    update_usermeta( $user->ID, '_last_login', $time );
    }else{
    update_usermeta( $user->ID, '_last_login_prev', $last_login );
    update_usermeta( $user->ID, '_last_login', $time );
    }
}

/*Get last login date*/
function wpsnipp_get_last_login($user_id,$prev=null){
  $last_login = get_user_meta($user_id);
  $time = current_time( 'timestamp' );
  if(isset($last_login['_last_login_prev'][0]) && $prev){
          $last_login = get_user_meta($user_id, '_last_login_prev', 'true' );
  }else if(isset($last_login['_last_login'][0])){
          $last_login = get_user_meta($user_id, '_last_login', 'true' );
  }else{
    update_usermeta( $user_id, '_last_login', $time );
    $last_login = $last_login['_last_login'][0];
  }
  return $last_login;
}

/*Set modified date*/
function wpse216609_update_profile_modified( $user_id ) {
  update_user_meta( $user_id, 'wpse216609_profile_updated', current_time( 'mysql' ) );
}
add_action( 'profile_update', 'wpse216609_update_profile_modified' );

/*Define User table columns*/
function new_modify_user_table( $column ) {
  $column['first_name'] = 'First Name';
  $column['last_name'] = 'Last Name';
  $column['postcode'] = 'Postcode';
  $column['call_sign'] = 'Call Sign';
  $column['id'] = 'Membership Number';
  $column['membership_level'] = 'Membership Level';
  $column['subscription_orders'] = 'User Report';
  $column['chat_name'] = 'Chat Nick Name';
  $column['avery_labels'] = 'Avery Labels';
  $column['renewal_letter'] = 'Renewal Letter';
  $column['renewal_date'] = 'Renewal Date';
  $column['view_transactions'] = 'View Transactions';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

/*Define User table row*/
function new_modify_user_table_row( $val, $column_name, $user_id ) {
  global $wpdb;
  get_currentuserinfo();
    $level = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'ihc_user_levels WHERE user_id="' . $user_id . '";');
    $level_id = '';
    if($level) {
      $level_id = $level->level_id;
    $level_array = $wpdb->get_row('SELECT option_value FROM ' . $wpdb->prefix . 'options WHERE option_id="583";');
    $level_array = unserialize($level_array->option_value);
    $membership_level = $level_array[$level_id]['label'];
    } else {
      $membership_level = 'n/a';
    }
    $expire_date = date('d/m/Y', strtotime($level->expire_time) ) ;
    switch ($column_name) {
        case 'call_sign' :
            return (!empty(get_the_author_meta( 'call_sign', $user_id )) ? get_the_author_meta( 'call_sign', $user_id ): 'n/a');
            break;
            case 'first_name' :
            return (!empty(get_the_author_meta( 'first_name', $user_id )) ? get_the_author_meta( 'first_name', $user_id ): 'n/a');
            break;
            case 'last_name' :
            return (!empty(get_the_author_meta( 'last_name', $user_id )) ? get_the_author_meta( 'last_name', $user_id ): 'n/a');
            break;
            case 'postcode' :
            return (!empty(get_the_author_meta( 'billing_postcode', $user_id )) ? get_the_author_meta( 'billing_postcode', $user_id ): 'n/a');
            break;
        case 'id' :
            return get_the_author_meta( 'id', $user_id );
            break;
        case 'chat_name' :
            return get_the_author_meta( 'chat_name', $user_id );
            break;
        case 'membership_level' :
            return $level_array[$level_id]['label'];
            break;
        case 'subscription_orders' :
            return '<a href="/wp-admin/admin.php?page=ihc_manage&tab=orders&uid=' . $user_id . '">View Orders</a>';
            break;
        case 'avery_labels' :
            return '<a target="_blank" href="'. get_stylesheet_directory_uri() .'/avery_labels.php?user_id='. get_the_author_meta( 'id', $user_id ).'">Print Labels</a>';
            break;
        case 'renewal_letter' :
            return '<a target="_blank" href="'. get_stylesheet_directory_uri() .'/generate_reminder.php?user_id='. get_the_author_meta( 'id', $user_id ).'&type=pdf">Print</a>';
            break;
             case 'renewal_date' :
            return $expire_date;
      break;
        case 'view_transactions' :
            return '<a href="/wp-admin/edit.php?s&post_status=all&post_type=shop_order&action=-1&m=0&_customer_user='.$user_id.'&filter_action=Filter&paged=1&action2=-1">View</a>';
            break;
        default:
          }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

function custom_user_list_queries($query){
    if(!empty($query->query_vars['search'])) {
        $query->query_from .= "  LEFT OUTER JOIN wp_usermeta AS alias ON (wp_users.ID = alias.user_id)";//note use of alias
        $query->query_where .= " OR ".
              "(alias.meta_key = 'billing_postcode' AND alias.meta_value LIKE '%".$query->query_vars['search']."%') ".
              " OR ".
              "(alias.meta_key = 'first_name' AND alias.meta_value LIKE '%".$query->query_vars['search']."%') ".
              " OR ".
              "(alias.meta_key = 'last_name' AND alias.meta_value LIKE '%".$query->query_vars['search']."%') ".
              " OR ".
              "(alias.meta_key = 'chat_name' AND alias.meta_value LIKE '%".$query->query_vars['search']."%') ";
    }
    switch (  $query->get( 'orderby' ) ) {
     case 'call_sign' :
      global $wpdb;
     $query->query_from .= "  LEFT OUTER JOIN wp_usermeta AS alias ON (wp_users.ID = alias.user_id)";//note use of alias
     $query->query_where .= " AND alias.meta_key = 'call_sign' ";//which meta are we sorting with?
     $query->query_orderby = " ORDER BY  alias.meta_value ".($query->query_vars["order"] == "ASC" ? "asc " : "desc ");//set sort order
     break;
     case 'chat_name' :
     $query->query_from .= "  LEFT OUTER JOIN wp_usermeta AS alias ON (wp_users.ID = alias.user_id)";//note use of alias
     $query->query_where .= " AND alias.meta_key = 'chat_name' ";//which meta are we sorting with?
     $query->query_orderby = " ORDER BY  alias.meta_value  ".($query->query_vars["order"] == "ASC" ? "ASC " : "DESC ");//set sort order
    break;
    case 'first_name' :
    $query->query_from .= "  LEFT OUTER JOIN wp_usermeta AS alias ON (wp_users.ID = alias.user_id)";//note use of alias
    $query->query_where .= " AND alias.meta_key = 'first_name' ";//which meta are we sorting with?
    $query->query_orderby = " ORDER BY  alias.meta_value ".($query->query_vars["order"] == "ASC" ? "ASC " : "DESC ");//set sort order
   break;
   case 'last_name' :
   $query->query_from .= "  LEFT OUTER JOIN wp_usermeta AS alias ON (wp_users.ID = alias.user_id)";//note use of alias
   $query->query_where .= " AND alias.meta_key = 'last_name' ";//which meta are we sorting with?
   $query->query_orderby = " ORDER BY  alias.meta_value ".($query->query_vars["order"] == "ASC" ? "ASC " : "DESC ");//set sort order
  break;
  case 'postcode' :
  $query->query_from .= "  LEFT OUTER JOIN wp_usermeta AS alias ON (wp_users.ID = alias.user_id)";//note use of alias
  $query->query_where .= " AND alias.meta_key = 'billing_postcode' ";//which meta are we sorting with?
  $query->query_orderby = " ORDER BY  alias.meta_value  ".($query->query_vars["order"] == "ASC" ? "ASC " : "DESC ");//set sort order
 break;
  }
  return $query;
}

function add_course_section_filter() {
  echo '<select name="course_section" style="float:none;">';
  echo '<option value="">Course Section...</option>';
  for ( $i = 1; $i <= 3; ++$i ) {
      if ( $i == $_GET[ 'call_sign' ] ) {
          echo '<option value="'.$i.'" selected="selected">Section '.$i.'</option>';
      } else {
          echo '<option value="'.$i.'">Section '.$i.'</option>';
      }
  }
  echo '<input id="post-query-submit" type="submit" class="button" value="Filter" name="">';
}
add_action( 'restrict_manage_users', 'add_course_section_filter' );

/*Reposition Call Sign User column*/
add_filter('manage_users_columns', 'column_order_call_sign');
function column_order_call_sign($columns) {
  $n_columns = array();
  $move = 'call_sign'; // what to move
  $before = 'name'; // move before this
  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns[$move] = $move;
    }
      $n_columns[$key] = $value;
  }
  return $n_columns;
}

/*Reposition Chat Name User column*/
add_filter('manage_users_columns', 'column_order_chat_name');
function column_order_chat_name($columns) {
  $n_columns = array();
  $move = 'chat_name'; // what to move
  $before = 'name'; // move before this
  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns[$move] = $move;
    }
      $n_columns[$key] = $value;
  }
  return $n_columns;
}

/*Reposition Membership ID User column*/
add_filter('manage_users_columns', 'column_order_membership_number');
function column_order_membership_number($columns) {
  $n_columns = array();
  $move = 'id'; // what to move
  $before = 'call_sign'; // move before this
  foreach($columns as $key => $value) {
    if ($key==$before){
      $n_columns[$move] = $move;
    }
      $n_columns[$key] = $value;
  }
  return $n_columns;
}

/*Remove User columns based on user role - Treasurer*/
add_filter('manage_users_columns','remove_users_columns_treasurer');
function remove_users_columns_treasurer($column_headers) {
    if (current_user_can('treasurer')) {
      unset($column_headers['call_sign']);
      unset($column_headers['chat_name']);
      unset($column_headers['subscription_orders']);
      unset($column_headers['avery_labels']);
    }
    return $column_headers;
}

/*Remove User columns based on user role - Shop Manager*/
add_filter('manage_users_columns','remove_users_columns_shop_manager');
function remove_users_columns_shop_manager($column_headers) {
    if (current_user_can('shop_manager')) {
      unset($column_headers['call_sign']);
      unset($column_headers['chat_name']);
      unset($column_headers['subscription_orders']);
      unset($column_headers['renewal_letter']);
      unset($column_headers['membership_level']);
    }
    return $column_headers;
}

/*Remove User unused columns*/
add_filter('manage_users_columns','remove_users_columns_all', 11);
function remove_users_columns_all($column_headers) {
      unset($column_headers['posts']);
      unset($column_headers['bbp_user_role']);
      unset($column_headers['role']);
    return $column_headers;
}

/*Update user meta for search*/
add_action('profile_update','yoursite_profile_update');
add_action('user_register','yoursite_profile_update');
function yoursite_profile_update($user_id) {
  $metavalues = get_user_metavalues(array($user_id));
  $skip_keys = array(
    'wp_user-settings-time',
    'nav_menu_recently_edited',
    'wp_dashboard_quick_press_last_post_id',
  );
  foreach($metavalues[$user_id] as $index => $meta) {
    if (preg_match('#^a:[0-9]+:{.*}$#ms',$meta->meta_value))
      unset($metavalues[$index]); // Remove any serialized arrays
    else if (preg_match_all('#[^=]+=[^&]\&#',"{$meta->meta_value}&",$m)>0)
      unset($metavalues[$index]); // Remove any URL encoded arrays
    else if (in_array($meta->meta_key,$skip_keys))
      unset($metavalues[$index]); // Skip and uninteresting keys
    else if (empty($meta->meta_value)) // Allow searching for empty
      $metavalues[$index] = "{$meta->meta_key }:null";
    else if ($meta->meta_key!='_search_cache') // Allow searching for everything else
      $metavalues[$index] = "{$meta->meta_key }:{$meta->meta_value}";
  }
  $search_cache = implode('|',$metavalues);
  update_user_meta($user_id,'_search_cache',$search_cache);
}

/*Overwrite shop title*/
function wc_custom_shop_archive_title( $title ) {
    if ( is_shop() && isset( $title['title'] ) ) {
        $title['title'] = 'Members Shop';
    }
    return $title;
}
add_filter( 'document_title_parts', 'wc_custom_shop_archive_title' );

/*Overwrite php.ini*/
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );

/*Define custom visual composer elements*/
add_action( 'vc_before_init', 'vc_before_init_actions' );
function vc_before_init_actions() {
    require_once( get_stylesheet_directory().'/vc-elements/latest_cq-tv_pdf.php' );
    require_once( get_stylesheet_directory().'/vc-elements/archive_cq-tv_pdf.php' );
}

/*Disable Woocommerce breadcrumbs*/
add_filter( 'woocommerce_get_breadcrumb', '__return_false' );

/*Frontend text changes*/
function gb_change_batc_keywords($translated_text, $text, $domain) {
  $translated_text = str_replace("cart", "basket", $translated_text);
  $translated_text = str_replace("Cart", "Basket", $translated_text);
  $translated_text = str_replace("Account details", "Update Membership", $translated_text);
  return $translated_text;
}
add_filter('gettext', 'gb_change_batc_keywords', 100, 4);

include get_stylesheet_directory() . '/avery_page.php';
include get_stylesheet_directory() . '/reminder_page.php';

/*Set BuddyPress roles*/
function bbPressPosting() {
  global $wpdb;
  $users = $wpdb->get_results('select * from wp_users');
  foreach($users as $user) {
      $result = $wpdb->get_row('select * from wp_indeed_members_payments where u_id = '. $user->ID);
      $userdata = get_userdata($user->ID) ;
      $currentRole = (isset($userdata->roles[1])) ? $userdata->roles[1] : '';
 if(!empty($result->paydate) && $currentRole == 'bbp_spectator') {
        $userdata->remove_role( 'bbp_spectator' );
        $userdata->add_role( 'bbp_participant' );
          wp_update_user($userdata);
      }
  }
}
add_action( 'init', 'bbPressPosting');

/*Country Select cookie session*/
function test22() {
$cate = get_queried_object();
foreach(WC()->countries->countries as $cid => $country) {
  if(strpos($country,$cate->name) > -1)  {
    $_SESSION['WC_country'] = $cid;
  }
}
}
add_action('woocommerce_before_shop_loop', 'test22');
function change_location() {
  ?>
  <script>
  var cid =  '<?= $_SESSION['WC_country'] ?>';
  if(cid == ''){
  } else {
  jQuery('document').ready(function() {
    jQuery('#billing_country').val(cid);
    setTimeout(function(){
    jQuery('#billing_country_field .select2').addClass('disabled');
  }, 100);
  })
}
  </script>
  <?php
}
add_action('woocommerce_before_checkout_form','change_location');



add_action( 'show_user_profile', 'yoursite_extra_user_profile_fields' ,1);
add_action( 'edit_user_profile', 'yoursite_extra_user_profile_fields' ,1);
function yoursite_extra_user_profile_fields( $user ) {
 if(is_Admin()) {
?>
  <h3>Reminder Letter Download</h3>
  <table class="form-table">
    <tr>
      <th>
          <p><i><l>User has one month remaining on contact and has not renewed</l></i></p>
          <a href="<?= get_stylesheet_directory_uri() ?>/generate_reminder.php?user_id=<?= $user->id ?>" class="button">Download Letter As PDF</a>
      </th>
    </tr>
  </table>
<?php
 }
}
if(!function_exists('generateTemplate')) {
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
}

/*Payment method text changes*/
function gb_change_cash_keywords($translated_text, $text, $domain) {
  $translated_text = str_replace("Check payments", "Cheque Payment - Memberships", $translated_text);
  $translated_text = str_replace("Cash on delivery", "Cheque Payment - Shop Products", $translated_text);
  return $translated_text;
}
add_filter('gettext', 'gb_change_cash_keywords', 100, 3);


add_filter( 'posts_join', 'segnalazioni_search_join' );
function segnalazioni_search_join ( $join ) {
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni".
    if ( is_admin() && 'edit.php' === $pagenow && 'segnalazioni' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $join .= 'LEFT JOIN ' . $wpdb->postmeta . ' ON ' . $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

add_filter( 'posts_where', 'segnalazioni_search_where' );
function segnalazioni_search_where( $where ) {
    global $pagenow, $wpdb;
    // I want the filter only when performing a search on edit page of Custom Post Type named "segnalazioni".
    if ( is_admin() && 'edit.php' === $pagenow && 'segnalazioni' === $_GET['post_type'] && ! empty( $_GET['s'] ) ) {
        $where = preg_replace(
            "/\(\s*" . $wpdb->posts . ".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(" . $wpdb->posts . ".post_title LIKE $1) OR (" . $wpdb->postmeta . ".meta_value LIKE $1)", $where );
    }
    return $where;
}

/*Remove columns from Woocommerce orders table*/
function wc_new_order_column( $columns ) {
unset( $columns['pdf_invoice_num'] );
unset( $columns['shipping_address'] );
unset( $columns['customer_message'] );
return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'wc_new_order_column' );

/*Custom Woocommerce admin thank you email*/
add_action( 'woocommerce_thankyou', 'custom_email_notification', 10, 1 );
function custom_email_notification( $order_id ) {
    if ( ! $order_id ) return;
    ## THE ORDER DATA ##
    // Get an instance of the WC_Order object
    $order = wc_get_order( $order_id );
    // Targetting Order status 'pending' or 'on-hold'
    if( $order->has_status( 'pending' ) || $order->has_status( 'on-hold' ) ) {
        // Getting all WC_emails objects
        $wc_email_notifications = WC()->mailer()->get_emails();
        // New Email notification (admin)
        $email_object = $wc_email_notifications['WC_Email_New_Order'];
        // Customizing Heading, subject, recipients, email type …
        $email_object->settings = array(
            'enabled' => 'yes',
            'recipient' => 'admin@batc.org.uk', // Set here the recipients emails (separated by a coma)
            'subject' => '[{site_title}] New pending order ({order_number}) - {order_date}',
            'heading' => 'New pending order',
            'email_type' => 'html'
        );
        // Sending the email
        $email_object->trigger( $order_id );
    }
}

/*Overwrite default Woocommerce payment gateway email notifications*/
remove_filter( 'woocommerce_payment_gateways', 'core_gateways' );
add_filter( 'woocommerce_payment_gateways', 'my_core_gateways_bacs' );
add_filter( 'woocommerce_payment_gateways', 'my_core_gateways_cheque' );
add_filter( 'woocommerce_payment_gateways', 'my_core_gateways_cod' );
add_filter( 'woocommerce_payment_gateways', 'my_core_gateways_paypal' );
/**
 * core_gateways function modified.
 *
 * @access public
 * @param mixed $methods
 * @return void
 */
function my_core_gateways_bacs( $methods ) {
  	$methods[] = 'WC_Gateway_BACS_custom';
	return $methods;
}
class WC_Gateway_BACS_custom extends WC_Gateway_BACS {
    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    function process_payment( $order_id ) {
      	global $woocommerce;
	$order = new WC_Order( $order_id );
	if ( $order->get_total() > 0 )
            $order->update_status( 'pending', __( 'Awaiting BACS payment', 'woocommerce' ) );
        else
            $order->payment_complete();
	if( $order->has_status( 'pending' )) {
		$mailer = $woocommerce->mailer();
		$payment_link = site_url()."/shop/checkout/order-pay/".$order_id."?pay_for_order=true&key=".$order->order_key;
	      	$message_body = __( 'Your order has been received and is pending payment.  As soon as the payment is received we will complete the order.' );
	      	$message = $mailer->wrap_message(
			sprintf(
				__( 'Your order #%s has been received' ),
				$order->get_order_number()
			),
			$message_body
		);
	     	$mailer->send( $order->billing_email, sprintf( __( 'Order #%s received' ), $order->get_order_number() ), $message );
			$woocommerce_new_order_recipient = get_option('woocommerce_new_order_settings');
			$woocommerce_new_order_recipient = explode(',',$woocommerce_new_order_recipient['recipient']);
			foreach($woocommerce_new_order_recipient as $email){
				$mailer = $woocommerce->mailer();
			      	$message_body = __( 'A new order has been received, please action.' );
			      	$message = $mailer->wrap_message(
				sprintf(
						__( 'BATC shop order #%s has been received' ),
						$order->get_order_number()
					),
					$message_body
				);
				$email = trim($email);
				$mailer->send( $email, sprintf( __( 'New Order #%s received' ), $order->get_order_number() ), $message );
			}
	}
      	$order->reduce_order_stock();
  	$woocommerce->cart->empty_cart();
  	return array(
  		'result' 	=> 'success',
  		'redirect'	=> $this->get_return_url( $order )
  	);
    }
}
/**
 * core_gateways function modified.
 *
 * @access public
 * @param mixed $methods
 * @return void
 */
function my_core_gateways_cod( $methods ) {
  	$methods[] = 'WC_Gateway_cod_custom';
	return $methods;
}
class WC_Gateway_cod_custom extends WC_Gateway_COD {
    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    function process_payment( $order_id ) {
      global $woocommerce;
  		$order = new WC_Order( $order_id );
  		if ( $order->get_total() > 0 )
		    	$order->update_status( 'pending', __( 'Awaiting COD payment', 'woocommerce' ) );
		else
		    $order->payment_complete();
		if( $order->has_status( 'pending' )) {
			$mailer = $woocommerce->mailer();
			$payment_link = site_url()."/shop/checkout/order-pay/".$order_id."?pay_for_order=true&key=".$order->order_key;
		      	$message_body = __( 'Your order has been received and is pending payment.  As soon as the payment is received we will complete the order.' );
		      	$message = $mailer->wrap_message(
				sprintf(
					__( 'Your order #%s has been received' ),
					$order->get_order_number()
				),
				$message_body
			);
		     	$mailer->send( $order->billing_email, sprintf( __( 'Order #%s received' ), $order->get_order_number() ), $message );
			$woocommerce_new_order_recipient = get_option('woocommerce_new_order_settings');
			$woocommerce_new_order_recipient = explode(',',$woocommerce_new_order_recipient['recipient']);
			foreach($woocommerce_new_order_recipient as $email){
				$mailer = $woocommerce->mailer();
			      	$message_body = __( 'A new order has been received, please action.' );
			      	$message = $mailer->wrap_message(
				sprintf(
						__( 'BATC shop order #%s has been received' ),
						$order->get_order_number()
					),
					$message_body
				);
				$email = trim($email);
				$mailer->send( $email, sprintf( __( 'New Order #%s received' ), $order->get_order_number() ), $message );
			}
		}
	      	$order->reduce_order_stock();
	  	$woocommerce->cart->empty_cart();
	  	return array(
	  		'result' 	=> 'success',
	  		'redirect'	=> $this->get_return_url( $order )
	  	);
    }
}
/**
 * core_gateways function modified.
 *
 * @access public
 * @param mixed $methods
 * @return void
 */
function my_core_gateways_cheque( $methods ) {
  	$methods[] = 'WC_Gateway_Cheque_custom';
	return $methods;
}
class WC_Gateway_Cheque_custom extends WC_Gateway_Cheque {
    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    function process_payment( $order_id ) {
      global $woocommerce;
  		$order = new WC_Order( $order_id );
  		if ( $order->get_total() > 0 )
		    $order->update_status( 'pending', __( 'Awaiting COD payment', 'woocommerce' ) );
		else
		    $order->payment_complete();
		if( $order->has_status( 'pending' )) {
			$mailer = $woocommerce->mailer();
			$payment_link = site_url()."/shop/checkout/order-pay/".$order_id."?pay_for_order=true&key=".$order->order_key;
		      	$message_body = __( 'Your order has been received and is pending payment.  As soon as the payment is received we will complete the order.' );
		      	$message = $mailer->wrap_message(
				sprintf(
					__( 'Your order #%s has been received' ),
					$order->get_order_number()
				),
				$message_body
			);
		     	$mailer->send( $order->billing_email, sprintf( __( 'Order #%s received' ), $order->get_order_number() ), $message );
			$woocommerce_new_order_recipient = get_option('woocommerce_new_order_settings');
			$woocommerce_new_order_recipient = explode(',',$woocommerce_new_order_recipient['recipient']);
			foreach($woocommerce_new_order_recipient as $email){
				$mailer = $woocommerce->mailer();
			      	$message_body = __( 'A new order has been received, please action.' );
			      	$message = $mailer->wrap_message(
				sprintf(
						__( 'BATC shop order #%s has been received' ),
						$order->get_order_number()
					),
					$message_body
				);
				$email = trim($email);
				$mailer->send( $email, sprintf( __( 'New Order #%s received' ), $order->get_order_number() ), $message );
			}
	     	}
	      	$order->reduce_order_stock();
	  	$woocommerce->cart->empty_cart();
	  	return array(
	  		'result' 	=> 'success',
	  		'redirect'	=> $this->get_return_url( $order )
	  	);
    }
}
/**
 * core_gateways function modified.
 *
 * @access public
 * @param mixed $methods
 * @return void
 */
function my_core_gateways_paypal( $methods ) {
  	$methods[] = 'WC_Gateway_Paypal_custom';
	return $methods;
}
class WC_Gateway_Paypal_custom extends WC_Gateway_Paypal {
    /**
     * Process the payment and return the result
     *
     * @access public
     * @param int $order_id
     * @return array
     */
    	public function process_payment( $order_id ) {
		global $woocommerce;
		$r = parent::process_payment($order_id);
		$order = new WC_Order( $order_id );
		$mailer = $woocommerce->mailer();
		$payment_link = site_url()."/shop/checkout/order-pay/".$order_id."?pay_for_order=true&key=".$order->order_key;
	      	$message_body = __( 'Your order has been received and is pending payment.  As soon as the payment is received we will complete the order.' );
	      	$message = $mailer->wrap_message(
		sprintf(
				__( 'Your order #%s has been received' ),
				$order->get_order_number()
			),
			$message_body
		);
	     	$mailer->send( $order->billing_email, sprintf( __( 'Order #%s received' ), $order->get_order_number() ), $message );
			$woocommerce_new_order_recipient = get_option('woocommerce_new_order_settings');
			$woocommerce_new_order_recipient = explode(',',$woocommerce_new_order_recipient['recipient']);
			foreach($woocommerce_new_order_recipient as $email){
				$mailer = $woocommerce->mailer();
			      	$message_body = __( 'A new order has been received, please action.' );
			      	$message = $mailer->wrap_message(
				sprintf(
						__( 'BATC shop order #%s has been received' ),
						$order->get_order_number()
					),
					$message_body
				);
				$email = trim($email);
				$mailer->send( $email, sprintf( __( 'New Order #%s received' ), $order->get_order_number() ), $message );
			}
		return $r;
	}
}

/*Reminder email +1 month*/
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
add_action( 'midnight_event', 'reminder_email_plus_1month');
function my_activation() {
    if ( !wp_next_scheduled( 'midnight_event' ) ) {
        $next12am = ( date('Hi') >= '0000' ) ? strtotime('+1day 12am') : strtotime('12am');  // you can calculate it in any way you want

        wp_schedule_single_event( $next12am, 'midnight_event');
    }
}
add_action('wp', 'my_activation');
