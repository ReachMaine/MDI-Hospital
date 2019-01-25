<?php
/*
 *
 * Template Name: 120 Anniversary Page Template
 *
*/
	if (isset($_GET['asearch'])) {
		get_template_part('search-staff');
		return;
	}
	$cws_stored_meta = get_post_meta( $post->ID, 'cws-mb' );
	if (isset( $cws_stored_meta[0]['cws-mb-sb_override'] )) {
		get_template_part('blog');
		return;
	}

	get_header();

	$pid = get_query_var("page_id");
	$pid = !empty($pid) ? $pid : get_queried_object_id();
	$sb = cws_GetSbClasses($pid);
	$sb_block = $sb['sidebar_pos'];
	$class_container = 'page-content page-120th' . (cws_has_sidebar_pos($sb_block) ? ( 'both' == $sb_block ? ' double-sidebar' : ' single-sidebar' ) : '');
	?>
	<?php /* 120th Anniversery header here */ ?>
	<div class="MDIH-120th-header">
		<div class="grid-row clearfix">
			<div class="grid-col grid-col-12">
				<section class="cws-widget">
					<section class="cws_widget_content">
						<div style="background-image: url('//www.mdihospital.org/wp-content/uploads/2017/01/header-background-image-dark.jpg');"><img class="aligncenter wp-image-7250 size-full" style="padding: 10px; margin-bottom: 0px;" src="https://www.mdihospital.org/wp-content/uploads/2017/01/logo-with-year.png" width="935" height="100" srcset="https://www.mdihospital.org/wp-content/uploads/2017/01/logo-with-year.png 935w, https://www.mdihospital.org/wp-content/uploads/2017/01/logo-with-year-100x10.png 100w, https://www.mdihospital.org/wp-content/uploads/2017/01/logo-with-year-230x24.png 230w" sizes="(max-width: 935px) 100vw, 935px"></div>
					</section>
				</section>
			</div>
		</div>
	</div>
	<?php /* end anniversary header */ ?>
	<div class="<?php echo $class_container; ?>">
		<div class="container">
		<?php
			if (cws_has_sidebar_pos($sb_block)) {
				if ('both' == $sb_block) {
					echo '<aside class="sbleft">';
					dynamic_sidebar($sb['sidebar1']);
					echo '</aside>';
					echo '<aside class="sbright">';
					dynamic_sidebar($sb['sidebar2']);
					echo '</aside>';
				} else {
					echo '<aside class="sb'.$sb_block.'">';
					dynamic_sidebar($sb['sidebar1']);
					echo '</aside>';
				}
			}
		?>
		<main id="mdih-main-content" tabindex="-1">
			<?php
				if (have_posts()):
					while ( have_posts() ): the_post();
						the_content();
					endwhile;
				endif;
			?>
			<?php comments_template(); ?>

		</main>
		</div>
	</div>
<?php /* 120th Anniversary footer here */ ?>
<div class="MDIH-120th-footer">
	<div class="grid-row clearfix">
		<div class="grid-col grid-col-12">
			<section class="cws-widget">
				<section class="cws_widget_content">
					<div style="background-image: url('//www.mdihospital.org/wp-content/uploads/2017/01/footer-background.jpg'); height: 50px;">&nbsp;</div>
				</section>
			</section>
		</div>
	</div>
</div>
<?php get_footer(); ?>
