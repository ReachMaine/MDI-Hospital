<?php 
/*
 Modifications:
 	-4Sept15 zig - add search form to bottom of 404 page.
 */
if (isset($_GET['asearch'])) {
		get_template_part('search-staff');
		return;
	}
?>
<?php get_header(); ?>
	<div class="page-content">
		<div class="container">
			<main>
				<div class='grid-row' id="header-404">
					<div id='title-404'>
						<?php _e("oh no! it&#8217;s a", THEME_SLUG); ?>
					</div>
				</div>
				<div class='grid-row' id="block-404">
					<div id="block-404-substrate">
					</div>
					<div id="block-404-icon">
					</div>
				</div>
				<div class='grid-row' id="text-404">
					<div class='sel'>
					<?php _e("Looks like the page you are looking for does not exists", THEME_SLUG); ?>
					</div>
					<?php _e("If you come here from a bookmark, please remember to update your bookmark", THEME_SLUG); ?>
				</div>
				<div class='grid-row' id="button-404">
					<a class="cws_button arrow" href="<?php echo home_url(); ?>">
					<?php _e("go back to home page", THEME_SLUG); ?>
					</a>
				</div>
				<div class='grid-row'>
					<?php  /*zig  get_template part not working.... */ ?> 
					<div class='sel'>
							<?php _e("or try a search...", THEME_SLUG); ?>
					</div>
					<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<label>
							<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ); ?></span>
							<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php echo get_search_query(); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ); ?>" required />
						</label>
						<input type="submit" class="search-submit" value="'. esc_attr_x( 'Search', 'submit button' ) .'" />
					</form>
				</div>
			</main>
		</div>
	</div>
<?php get_footer(); ?>