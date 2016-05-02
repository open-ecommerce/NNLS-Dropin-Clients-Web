<?php
	// If a feature image is set, get the id, so it can be injected as a css background property
//if (!is_front_page() ) {
	if ( has_post_thumbnail( $post->ID ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		$image = $image[0];
	} else {
			$image = get_template_directory_uri() . '/assets/images/defaults/dropin-pages-headers-1900x300.jpg';
	}

	echo '<header id="featured-hero" class="site-header" role="banner" style="background-image: url('.$image.')">';
//}
?>
