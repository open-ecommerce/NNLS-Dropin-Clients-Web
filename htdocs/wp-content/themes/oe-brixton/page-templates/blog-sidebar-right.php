<?php
/*
Template Name: Blog Right Sidebar
*/
get_header(); ?>

<?php do_action( 'foundationpress_before_content' ); ?>
<?php while ( have_posts() ) : the_post(); ?>
<section class="intro" role="main">
	<div class="fp-intro">
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<?php do_action( 'foundationpress_page_before_entry_content' ); ?>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<footer>
				<?php wp_link_pages( array('before' => '<nav id="page-nav"><p>' . __( 'Pages:', 'foundationpress' ), 'after' => '</p></nav>' ) ); ?>
				<p><?php the_tags(); ?></p>
			</footer>
			<?php do_action( 'foundationpress_page_before_comments' ); ?>
			<?php comments_template(); ?>
			<?php do_action( 'foundationpress_page_after_comments' ); ?>
		</div>
	</div>
</section>
<?php endwhile;?>
<?php do_action( 'foundationpress_after_content' ); ?>


<div id="page" role="main">
	<article class="main-content">
		<?php $custom_query = new WP_Query('cat=1'); //change if want more cats
		while($custom_query->have_posts()) : $custom_query->the_post(); ?>
			<?php get_template_part( 'template-parts/content', get_post_format() ); ?>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); // reset the query ?>
	</article>
	<?php get_sidebar(); ?>

</div>


<?php get_footer();
