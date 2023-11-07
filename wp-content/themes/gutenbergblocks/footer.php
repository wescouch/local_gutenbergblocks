<?php
$theme_logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
?>

		</main>

		<footer id="footer" role="contentinfo">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-4">
						<div class="footer-general-info">
							<a href="<?php echo bloginfo('url'); ?>" data-title="<?php echo bloginfo('name'); ?> Logo - footer">
								<?php
								/* check to see if the logo exists and add it to the page */
								if (!empty($theme_logo)) : ?>
									<img class="footer-logo" src="<?php echo $theme_logo[0]; ?>" alt="<?php bloginfo('name'); ?>" width="120" height="44"/>
								<?php else : /* add a fallback if the logo doesn't exist */ ?>
									<span class="site-title"><?php bloginfo('name'); ?></span>
								<?php endif; ?> 
							</a>
						</div>
					</div>
					<div class="col-md-12 col-lg-8">
						<div class="row">
							<?php
								$first_footer_menu = true;
								$footer_menus = ['footer_1', 'footer_2', 'footer_3', 'footer_4'];
								foreach($footer_menus as $footer_menu):
							?>
								<div class="<?php echo ($first_footer_menu ? 'col-sm-6 col-md-3 col-lg-auto col-xl-4' : 'col-sm-6 col-md-3 col-lg'); ?>">
									<div class="widget">
										<?php
											$location = $footer_menu;
											if (has_nav_menu($location)) :
												$locations = get_nav_menu_locations();
												$menu_id = $locations[ $location ] ;
												$menu_obj = wp_get_nav_menu_object($menu_id);
												$menu_title = esc_html($menu_obj->name);
												echo '<h4 class="menu-title">'.$menu_title.'</h4>';
												wp_nav_menu( array(
													'theme_location' => $footer_menu,
													'container' => 'nav'
												) );
											endif;
										?>
									</div>
								</div>
							<?php
							$first_footer_menu = false;
							endforeach;
							?>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<?php wp_footer(); ?>
</body>
</html>