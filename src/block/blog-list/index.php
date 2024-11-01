<?php
function ultimate_blog_layouts_render_blog_list($attributes){

	$category = isset($attributes['category']) ? $attributes['category'] : '';
	$post_output = '';

	$args = array(
		'post_status' 		=> 'publish',
		'post_type' 		=> 'post',
		'orderby'			=> $attributes['orderBy'],
		'order'				=> $attributes['order'],
		'posts_per_page' 	=> $attributes['perPage'],
		'cat'				=> $category,

	);

	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {

		$post_output.= '<section class="ultimate-blog-layouts blog-list">';
		$post_output.= '<div class="blog-list-wrapper">';

		while ( $query->have_posts() ) {
			$query->the_post();
			
			$post_id = get_the_ID();
			$category = get_the_terms($post_id, 'category');

			if ( $attributes['excerptLength'] ) {
				$excerpt = wpautop(ultimate_blog_layouts_the_excerpt($attributes['excerptLength']));
			}
			
			$post_title = esc_html(get_the_title());
			if (!$post_title) {
				$post_title = esc_html__('Untitled','ultimate-blog-layouts');
			}  

			$title = sprintf('<h3><a href="%1$s">%2$s</a></h3>',
				esc_url(get_permalink()), 
				$post_title );
			
			$content = null;
			if ($attributes['showAuth'] || $attributes['showDate'] || $attributes['showTerms']){

				$content.= '<div class="blg-meta-box">';
				
				if ($attributes['showAuth']) {
					$content.= sprintf('<span class="blg-author"><a href="%1$s">%2$s</a></span>',
						esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) )),
						esc_html(get_the_author())
					);
				}
				if ($attributes['showDate']) {
					$content.= sprintf('<span class="blg-date">%1$s</span>', 
						esc_html(get_the_date('F j, Y')));
				}

				if (is_array($category) && $attributes['showTerms']) {
					$content.='<span class="blg-category">';
					foreach ($category as $cat) {	
						$content.= sprintf('<a href="%1$s">%2$s</a>',
							esc_url(get_term_link($cat->term_id)), 
							esc_html($cat->name));
					}
					$content.= '</span>';
				}

				$content.= '</div>';

			}

			$post_output.= '<article class="blog-item">';

			if ($attributes['showImage'] && get_the_post_thumbnail() && $attributes['layouts'] !== 'layout-3') {
				$post_output.= sprintf('<div class="blg-image-wrapper '. $attributes['layouts'].'"><a href="%1$s">%2$s</a></div>',get_permalink(), get_the_post_thumbnail($post_id, $attributes['imageSize']));
			}

			$post_output.= '<div class="content-box">';

			$post_output.= '<div class="blg-inner-box">';

			if ($attributes['layouts'] === 'layout-1' || $attributes['layouts'] === 'layout-3') {
				$post_output.= $title;
			}
			$post_output.= $content;

			if ($attributes['layouts'] === 'layout-2') {
				$post_output.= $title;
			}

			$post_output.= '</div>';

			if ($attributes['showExcerpt']) {
				$post_output.= sprintf('<div class="blg-content">%1$s</div>', 
					$excerpt
				); 
			}

			if ($attributes['showText']) {
				$post_output.= sprintf('<div class="blg-link"><a href="%1$s">%2$s</a></div>',
					esc_url(get_permalink()),
					esc_html( $attributes['text']));
			}

			$post_output.= '</div>';

			if ($attributes['showImage'] && get_the_post_thumbnail() && $attributes['layouts'] === 'layout-3') {
				$post_output.= sprintf('<div class="blg-image-wrapper '. $attributes['layouts'].'"><a href="%1$s">%2$s</a></div>',
					esc_url(get_permalink()), 
					get_the_post_thumbnail($post_id, $attributes['imageSize']));
			}

			$post_output.= '</article>';
		}

		$post_output .= '</div>';
		$post_output .= '</section>';
	}
	else{
		$post_output.= sprintf('<p>%1$s</p>', esc_html__('No Posts','ultimate-blog-layouts'));
	}

	return $post_output;
}
