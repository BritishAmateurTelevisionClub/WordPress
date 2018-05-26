<?php
/**
* Template Name: Page Template with Woocommerce Sidebar
*
* Description: Use this page template for right sidebar.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress - Themonic Framework
 * @subpackage Iconic_One
 * @since Iconic One 1.0
 */

get_header(); ?>



	<div id="primary" class="site-content account">

		<div id="content" role="main">

			<nav class="woocommerce-MyAccount-navigation">
				<?php
				wp_nav_menu( array(
				    'theme_location' => 'accounts-menu', 
				    'container_class' => 'custom-menu-class' ) );
				?>

			</nav>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>