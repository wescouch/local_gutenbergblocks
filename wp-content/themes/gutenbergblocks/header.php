<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width" />
	<?php wp_head(); ?>
</head>
<body data-barba="wrapper" <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div data-barba="container" data-barba-namespace="<?php echo get_post_field( 'post_name'); ?>" class="site-container">
		<header id="header" role="banner">
			<div id="skip-links">
				<a href="#content">Skip to main content</a>
			</div>
			<?php
			// Show message to enable JS if disabled
			?>
			<noscript>
				<section id="no-js-message">
					Please enable JavaScript in your browser to utilize this website
				</section>
			</noscript>
			<?php

			// Get header logo from Customizer -> Site Identity
			$theme_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

			// Check page, set CTA button theme depending
			$button_theme = 'btn-trans-white';
			if (is_front_page()) {
				$button_theme = 'btn-orange-white';
			}
			?>

			<div class="top-header">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-12">
							<div class="top-header-inner">
								<div class="top-header-left">
									<!-- Header Logo -->
									<div class="logo">
										<a href="<?php echo bloginfo('url'); ?>" data-title="<?php echo bloginfo('name'); ?> Logo - header">
											<img width="120" height="44" src="<?php echo $theme_logo[0]; ?>" alt="<?php bloginfo('name'); ?>" />
										</a>
									</div>
									<div class="nav-wrap">
										<div class="col-12">
											<!-- Header Navigation -->
											<nav class="nav-primary" aria-label="Main navigation">
												<?php
													wp_nav_menu( array(
														'theme_location' => 'primary',
														'container' => '',
														'menu_class' => 'menu'
													) );
												?>
											</nav>
										</div>
									</div>
								</div>

								<div class="top-header-right">
									<a href="/contact" class="theme-btn <?php echo $button_theme; ?> d-none d-lg-inline-flex" data-title="Global CTA Button (header)">Contact</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

		<main id="content" class="container" tabindex="-1">