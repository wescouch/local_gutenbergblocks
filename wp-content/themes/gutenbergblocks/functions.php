<?php
/**
 * GutenbergBlocks's functions and definitions
 *
 * @package GutenbergBlocks
 * @since GutenbergBlocks 1.0
 */

if ( ! function_exists( 'gutenbergblocks_setup' ) ) :
function gutenbergblocks_setup() {
	// Add default posts and comments RSS feed links to <head>
	add_theme_support( 'automatic-feed-links' );

	// Register Nav Menus
	register_nav_menus( array(
		'primary'   => __( 'Primary Menu', 'gutenbergblocks' ),
		'secondary' => __( 'Secondary Menu', 'gutenbergblocks' )
	) );

	// Enable support for post thumbnails and featured images
	add_theme_support( 'post-thumbnails' );

	// Add support for block sizes
	add_theme_support( 'align-wide' );

	/**
	 * Add Custom Theme Block Category
	 *
	 * @param array $categories Array of block categories.
	 * @return array
	 */
	function theme_block_category( $categories ) {
		$theme_slug = get_stylesheet();

		// Check to see if we already have a category with the same name as the theme
		$include = true;
		foreach( $categories as $category ) {
			if( $theme_slug === $category['slug'] ) {
				$include = false;
			}
		}

		if( $include ) {
			$categories = array_merge(
				array(
					array(
						'slug' => $theme_slug,
						'title' => __( 'Theme Blocks', 'custom-blocks'),
					)
				),
				$categories
			);
		}
		return $categories;
	}
	add_filter( 'block_categories_all', 'theme_block_category', 10, 2);

	// Disable the WP admin toolbar completely for all users on front-end
	add_filter('show_admin_bar', '__return_false');

	// Add body classes to help with block styling
	add_filter( 'body_class', 'gutenbergblocks_blocks_body_classes' );
	function gutenbergblocks_blocks_body_classes( $classes ) {
		global $post;

		// Adds page slug to body class
		if ( isset( $post ) ) {
			$classes[] = $post->post_type . '-' . $post->post_name;
		}

		// Add category to blog post body class
		if ( is_singular() ) {
			$cats = get_the_category($post->ID);

			if (!empty($cats) && isset($cats)):
				foreach ($cats as $cat):
					$classes[] = $cat->slug;
				endforeach;
			endif;
		}
		
		return $classes;
	}

	// CSS & JS
	include get_stylesheet_directory().'/functions/css.php';
	include get_stylesheet_directory().'/functions/js.php';
}

endif;
add_action( 'after_setup_theme', 'gutenbergblocks_setup' );