<?php
/*
Plugin Name: Card Block
Plugin URI: https://github.com/prajapatisagar/card-block
Description: Card block for WordPress Gutenberg.
Author: Sagar Prajapati
Author URI: https://www.sagarprajapati.com/
Text Domain: card-block
Version: 1.0
*/

function gutenberg_card_block_register() {

	// Register our block script with WordPress
	wp_register_script(
		'gutenberg-card-block',
		plugins_url('js/block.build.js', __FILE__),
		array('wp-blocks', 'wp-element', 'wp-editor')
	);

	// Register our block's base CSS
	wp_register_style(
		'gutenberg-card-block-style',
		plugins_url( 'css/blocks.style.build.css', __FILE__ )
	);

	// Register our block's editor-specific CSS
	wp_register_style(
		'gutenberg-card-block-edit-style',
		plugins_url('css/blocks.editor.build.css', __FILE__),
		array( 'wp-edit-blocks' )
	);

	// Enqueue the script in the editor
	register_block_type('card-block/main', array(
		'editor_script' => 'gutenberg-card-block',
		'editor_style' => 'gutenberg-card-block-edit-style',
		'style' => 'gutenberg-card-block-style'
	));
}

add_action('init', 'gutenberg_card_block_register');