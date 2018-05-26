<?php
/**
 * Footer section template.
 * @package WordPress
 * @subpackage Iconic_One
 * @since Iconic One 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
		<div class="footercopy">
      <?php echo get_theme_mod( 'textarea_copy', 'custom footer text left' ); ?><br>
      <?php global $current_user; get_currentuserinfo(); ?>
      <?php if ( is_user_logged_in() ) { ?>
       Logged in as:
			 <strong>
				 <?php echo $current_user->user_login; ?>
			 </strong>
			 <a href="/members/logout/?ihcdologout=true" class="logout">Logout</a>
		 <?php } else { ?>
			 <a href="/members/login/">Login</a>
			 <?php } ?>
    </div>
		<div class="footercredit"><?php echo get_theme_mod( 'custom_text_right', 'custom footer text right' ); ?></div>
		<div class="clear"></div>
		</div><!-- .site-info -->
		</footer><!-- #colophon -->
		<div class="site-wordpress">
				<a href="http://themonic.com/iconic-one/">Iconic One</a> Theme | Powered by <a href="http://wordpress.org">Wordpress</a>
				</div><!-- .site-info -->
				<div class="clear"></div>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
