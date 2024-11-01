<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Gutenberg block assets for both frontend + backend.
 */
function ultimate_blog_layouts_backend_enqueue() { 

	wp_enqueue_script(
		'ultimate-blog-layouts-block-js',
		plugins_url( '/dist/blocks.build.js', dirname( __FILE__ ) ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-core-data'),
		null,
		true
	);

	wp_localize_script('ultimate-blog-layouts-block-js', 'ultimate_blog_layouts_size',array(
		'imagesizes' => ultimate_blog_layouts_get_image_sizes_options())
);

	wp_enqueue_style(
		'ultimate-blog-layouts-block-editor-css',
		plugins_url( '/dist/blocks.editor.build.css', dirname( __FILE__ ) ), 
		array( 'wp-edit-blocks' ),
		null
	);

}
add_action( 'enqueue_block_editor_assets', 'ultimate_blog_layouts_backend_enqueue' );


function ultimate_blog_layouts_get_image_sizes_options( $show_dimension = true ) {

	global $_wp_additional_image_sizes;

	$choices = array();

	$choices['thumbnail'] 		= esc_html__( 'Thumbnail', 'ultimate-blog-layouts' );
	$choices['medium']    		= esc_html__( 'Medium', 'ultimate-blog-layouts' );
	$choices['large']      		= esc_html__( 'Large', 'ultimate-blog-layouts' );

	if ( true === $show_dimension ) {
		foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
			$choices[ $_size ] = $choices[ $_size ] . ' (' . get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
		}
	}

	if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {
		foreach ( $_wp_additional_image_sizes as $key => $size ) {
			$choices[ $key ] = $key;
			if ( true === $show_dimension ) {
				$choices[ $key ] .= ' (' . $size['width'] . 'x' . $size['height'] . ')';
			}
		}
	}

	return $choices;

}

function ultimate_blog_layouts_enqueue(){

	wp_enqueue_style(
		'ultimate-blog-layouts-style-css', 
		plugins_url( '/dist/blocks.style.build.css', dirname( __FILE__ ) ), 
		array( 'wp-editor' ),
		null
	);
}
add_action( 'init', 'ultimate_blog_layouts_enqueue' );

//Create Category
add_filter( 'block_categories', function( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'bloglayouts',
				'title' => __( 'Ultimate Blog Layouts', 'ultimate-blog-layouts' ),
			),
		)
	);
}, 10, 2 );

//Register Blog Grid
register_block_type(
	'blg/blog-grid', array(
		'render_callback' => 'ultimate_blog_layouts_render_blog_grid',
		'attributes'	=> array(
			'excerptLength'=> array(
				'type'=> 'number',
				'default'=> 25
			),
			'showExcerpt'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'category'=> array(
				'type'=> 'string',
			),
			'order'=> array(
				'type'=> 'string',
				'default'=> 'desc'
			),
			'orderBy'=> array(
				'type'=> 'string',
				'default'=> 'date'
			),
			'perPage'=> array(
				'type'=> 'number',
				'default'=> 3
			),
			'column'=> array(
				'type'=> 'number',
				'default'=> 3
			),
			'showText'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'text'=> array(
				'type'=> 'string',
				'default'=> 'Read More'
			),
			'showAuth'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'showDate'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'showTerms' => array(
				'type'=> 'boolean',
				'default' => true
			),
			'layouts' => array(
				'type' => 'string',
				'default' => 'layout-1'
			),
			'imageSize' => array(
				'type' => 'string',
				'default' => 'large'
			),
			'showImage' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
	)
);

//Register Blog List
register_block_type(
	'blg/blog-list', array(
		'render_callback' => 'ultimate_blog_layouts_render_blog_list',
		'attributes'	=> array(
			'excerptLength'=> array(
				'type'=> 'number',
				'default'=> 25
			),
			'showExcerpt'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'category'=> array(
				'type'=> 'string',
			),
			'order'=> array(
				'type'=> 'string',
				'default'=> 'desc'
			),
			'orderBy'=> array(
				'type'=> 'string',
				'default'=> 'date'
			),
			'perPage'=> array(
				'type'=> 'number',
				'default'=> 3
			),
			'showText'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'text'=> array(
				'type'=> 'string',
				'default'=> 'Read More'
			),
			'showAuth'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'showDate'=> array(
				'type'=> 'boolean',
				'default'=> true
			),
			'showTerms' => array(
				'type'=> 'boolean',
				'default' => true
			),
			'showTags' => array(
				'type'=> 'boolean',
				'default' => true
			),
			'layouts' => array(
				'type' => 'string',
				'default' => 'layout-1'
			),
			'imageSize' => array(
				'type' => 'string',
				'default' => 'large'
			),
			'showImage' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
	)
);

// Create Api for feature image 
add_action( 'rest_api_init', 'ultimate_blog_layouts_create_api' );

function ultimate_blog_layouts_create_api() {

	// Feature image
	register_rest_field( 
		array( 'post' ),
		'featured_image_urls', 
		array(
			'get_callback'    => 'ultimate_blog_layouts_featured_callback',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Category
	register_rest_field( 
		'post',
		'category_list', 
		array(
			'get_callback'    => 'ultimate_blog_layouts_category_meta_callback',
			'update_callback' => null,
			'schema'          => null,
		)
	);

	//Author
	register_rest_field( 
		'post',
		'author_info', 
		array(
			'get_callback'    => 'ultimate_blog_layouts_author_meta_callback',
			'update_callback' => null,
			'schema'          => null,
		)
	);

}

function ultimate_blog_layouts_featured_callback( $object) {
	return ultimate_blog_layouts_featured_image_urls( $object['featured_media'] );
}

function ultimate_blog_layouts_featured_image_urls( $attachment_id ) {
	$image = wp_get_attachment_image_src( $attachment_id, 'thumbnail', false );
	$sizes = get_intermediate_image_sizes();

	$imageSizes = array(
		'thumbnail' => is_array( $image ) ? $image : '',
	);

	foreach ( $sizes as $size ) {
		$imageSizes[ $size ] = is_array( $image ) ? wp_get_attachment_image_src( $attachment_id, $size, false ) : '';
	}

	return $imageSizes;
}

//Category meta callback
function ultimate_blog_layouts_category_meta_callback( $object) {

	$formatted_categories = array();

	$categories = get_the_category( $object['id'] );

	foreach ($categories as $category) {

		$formatted_categories[] = sprintf('<a href="%1$s" target="_blank">%2$s</a>',
			get_category_link($category->term_id),
			esc_html($category->name)
		);
	}

	return $formatted_categories;
}

//Author meta callback
function ultimate_blog_layouts_author_meta_callback($object){

	$author_data['display_name'] = get_the_author_meta( 'display_name', $object['author'] );

	$author_data['author_link']  = get_author_posts_url( $object['author'] );

	return $author_data;
}

function ultimate_blog_layouts_the_excerpt( $length, $post_obj = null ) {

  global $post;

  if ( is_null( $post_obj ) ) {
    $post_obj = $post;
  }

  $length = absint( $length );

  if ( 0 === $length ) {
    return;
  }

  $source_content = $post_obj->post_content;

  if ( ! empty( $post_obj->post_excerpt ) ) {
    $source_content = $post_obj->post_excerpt;
  }

  $source_content = strip_shortcodes( $source_content );
  $trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );

  return $trimmed_content;
}
