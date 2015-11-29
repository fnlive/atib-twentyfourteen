<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * Add following below get_sidebar() if we need copyright notice:
			<aside class="footer-sidebar widget-area masonry" style="padding: 2em; ">
			Copyright © 2014, Släktföreningen Annika och Torkel i Berg. All rights reserved.
			</aside>
 *
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

		</div ><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo" >

			<?php get_sidebar( 'footer' ); ?>

		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
