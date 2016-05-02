<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

?>

		</section>
		<div id="footer-container">
			<footer id="footer">
				<?php do_action( 'foundationpress_before_footer' ); ?>
				<?php dynamic_sidebar( 'footer-widgets' ); ?>
				<?php do_action( 'foundationpress_after_footer' ); ?>
			</footer>
			<div id="copiright-wrapper" class="large-12" >
				<div id="copiright-content">
					<div class="row">
					  <div class="small-6 large-6 columns text-center copiright-text">What ever Copiright I am in footer.php</div>
					  <div class="small-6 large-6 columns text-center copiright-oe">
							<a target="_blank" href="http://www.open-ecommerce.org">
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logos/powered-by-open-ecommerce-org.png" alt="Open-ecommerce.org" class="oe">
						</a>
						</div>
					</div>
			</div>
			</div>
		</div>
		<?php do_action( 'foundationpress_layout_end' ); ?>

<?php if ( get_theme_mod( 'wpt_mobile_menu_layout' ) == 'offcanvas' ) : ?>
		</div><!-- Close off-canvas wrapper inner -->
	</div><!-- Close off-canvas wrapper -->
</div><!-- Close off-canvas content wrapper -->
<?php endif; ?>


<?php wp_footer(); ?>
<?php do_action( 'foundationpress_before_closing_body' ); ?>



<script id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.2.12.3.js'><\/script>".replace("HOST", location.hostname));
//]]></script>

</body>
</html>
