<?php
	/*

	.o8888 .o8888 .o8888
	88     88     88    
	88     'Y88o. 'Y88o.
	88         88     88
	'Y8888 8888Y' 8888Y'

	*/

// Front-end styles (Gutenberg styling is set in /lib/gutenberg/editor-styles.css)
add_action( 'wp_enqueue_scripts', function() {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	// Dequeue styles
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	
	// Enqueue styles
	$styles = [ [
		'name' => 'gutenbergblocks-css-bootstrap',
		'file' => '/assets/css/third-party/bootstrap-grid.min.css'
	],[
		'name' => 'gutenbergblocks-css-keen-slider',
		'file' => '/assets/css/third-party/keen-slider.min.css'
	],[
		'name' => 'gutenbergblocks-css-magnific-popup',
		'file' => '/assets/css/third-party/magnific-popup.css'
	],[
		'name' => 'gutenbergblocks-css-variables',
		'file' => '/assets/css/variables.css'
	],[
		'name' => 'gutenbergblocks-css-googlefonts',
		'file' => '//fonts.googleapis.com/css2?family=Rubik:wght@400;700&display=swap',
		'external' => true
	],[
		'name' => 'gutenbergblocks-theme-style',
		'file' => '/style.css'
	],[
		'name' => 'gutenbergblocks-css-custom-style',
		'file' => '/assets/css/style.css'
	] ];

	foreach( $styles as $style ) {
		if (!empty($style['external']) && $style['external']) {
			$file_uri = $style['file'];
			$file_dir = false;
		} else {
			$file_uri = $uri . $style['file'];
			$file_dir = $dir . $style['file'];
		}

		wp_register_style( $style['name'], $file_uri, [], ($file_dir ? filemtime( $file_dir ) : null) );
		wp_enqueue_style( $style['name'] );
	}
	
	// Async load CSS files, including non-JS fallback
	function prefix_defer_css_rel_preload( $html, $handle, $href, $media ) {
		if ( ! is_admin() ) {
			$html = '<link rel="stylesheet" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '">';
		}
		return $html;
	}
	add_filter( 'style_loader_tag', 'prefix_defer_css_rel_preload', 10, 4 );
});
