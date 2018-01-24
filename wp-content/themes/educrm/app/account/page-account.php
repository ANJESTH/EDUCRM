<?php
/**
 * Template Name: Account Page
 */

acf_form_head();
get_header( 'educrm' ); ?>

<!-- content -->
<div id="banner" class="section-banner" >
	<div class="section parallax-section" data-parallax="scroll" data-image-src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide.jpg'; ?>" data-bleed="0" data-position="center">
		<div class="parallax-mirror">
			<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide.jpg'; ?>">
		</div>
	</div>
	<div class="container banner-container">
		<div class="col-xs-12">
			<div class="banner-block">
				<div class="col-xs-12 text-center">
					<h1 class="page-title text-bold color-white fade-scroll"> Account </h1>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
</div>

<!-- form content -->
<div class="section the_body">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 form-div">
				<h2 class="form-title text-left">Edit Account Details</h2>
				<?php
				if ( ! empty( educrm_get_current_profile_id() ) ) {
					acf_form(
						array(
						'post_id'		=> educrm_get_current_profile_id(),
						'post_title'	=> true,
						'submit_value'	=> 'Update Info',
						)
					);
				};
				?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
