<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress - Themonic Framework
 * @subpackage Publisho_Theme
 * @since Publisho 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content memberships">
		<div id="content" role="main">
      <header class="entry-header">
        <h1 class="entry-title">Join The BATC</h1>
      </header>

        <?php echo do_shortcode( '[country_select]' ); ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>
