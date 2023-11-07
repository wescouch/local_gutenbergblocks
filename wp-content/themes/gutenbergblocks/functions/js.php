<?php
	/*

	    88 .o8888
	    88 88
	    88 'Y88o.
	88  88     88
	'Y88Y' 8888Y'

	*/

// Front-end scripts (Gutenberg styling is set in /lib/gutenberg/editor-scripts.js)
add_action( 'wp_enqueue_scripts', function() {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	// Deregister scripts
	wp_deregister_script( 'jquery' ); //We will re-enqueue in footer down below

	// Enqueue scripts
	$scripts = [ [
		'name'   	=> 'jquery',
		'file'   	=> includes_url() . 'js/jquery/jquery.min.js',
		'external'	=> true,
		'version'	=> '3.6.0'
	],[
		'name'   	=> 'gutenbergblocks-js-barba',
		'file'   	=> '//cdn.jsdelivr.net/npm/@barba/core',
		'external'	=> true,
		'version'	=> false
	],[
		'name'   	=> 'gutenbergblocks-js-keen-slider',
		'file'   	=> '/assets/js/third-party/keen-slider.js',
		'version'	=> false
	],[
		'name'   	=> 'gutenbergblocks-js-gsap',
		'file'   	=> '/assets/js/third-party/gsap.min.js',
		'version'	=> false
	],[
		'name'   	=> 'gutenbergblocks-js-debounce_throttle',
		'file'   	=> '/assets/js/third-party/debounce_throttle.js',
		'version'	=> false
	],[
		'name'   	=> 'gutenbergblocks-js-scripts',
		'file'   	=> '/assets/js/scripts.js',
		'version'	=> true
	]];

	foreach( $scripts as $script ) {
		if (!empty($script['external']) && $script['external']) {
			$file_uri = $script['file'];
			$file_version = ($script['version'] ? $script['version'] : null);
		} else {
			$file_uri = $uri.$script['file'];
			$file_version = ($script['version'] ? filemtime( $dir.$script[ 'file' ] ) : null );
		}

		wp_register_script( $script['name'], $file_uri, [], $file_version, true );
		wp_enqueue_script( $script['name'] );
	}
});
