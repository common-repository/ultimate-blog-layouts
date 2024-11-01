<?php
/**
 * Plugin Name:       Ultimate Blog Layouts For Gutenberg
 * Description:       WordPress plugin to add blog layouts for Gutenberg editor.
 * Version:           1.0.1
 * Author:            Kopila Shrestha
 * Text Domain:       ultimate-blog-layouts
 * Domain Path:       /languages
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

require_once plugin_dir_path( __FILE__ ) . 'src/block/blog-grid/index.php';
require_once plugin_dir_path( __FILE__ ) . 'src/block/blog-list/index.php';