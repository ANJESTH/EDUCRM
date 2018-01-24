<?php
educrm_authenticate_app_pages();
acf_form_head();
get_header( 'educrm' );
?>
<!-- content -->
<div id="banner" class="section-banner" >
	<div class="section parallax-section" data-parallax="scroll" data-image-src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide.jpg'; ?>" data-bleed="0" data-position="center">
		<div class="parallax-mirror">
			<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide1.jpg'; ?>" class="img-responsive" width="100%" alt="...">
		</div>
	</div>
	<div class="container banner-container">
		<div class="col-xs-12">
			<div class="banner-block">
				<div class="col-xs-12 text-center">
					<h1 class="page-title text-bold color-white fade-scroll" >
						<?php echo educrm_get_the_consultancy_title();  ?> | Edit Agent
				</div>
				<div class="clear">

				</div>
			</div>
		</div>
	</div>
</div>
<div class="section the_body">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 form-div">
				<?php
				// Displaying add new agent form.
				acf_form(
					array(
						'post_id'	=> get_the_id(),

						'fields' => array(
							'field_589fa0a8aa821', // firstname
							'field_589fbcffaa822', // lastname
							'field_58a1ace142bc7', // address
							'field_589fbd08aa823', // email
							'field_58a4256b41d6e', // password
							'field_589fbd14aa824', // phone
						),

						'form_attributes' => array( 'action' => get_the_permalink( get_the_id() ) ),
						'honeypot' 		=> true,
						'return'		=> get_the_permalink( get_the_id() ),
						'submit_value'	=> 'Update',
					)
				); ?>

			</div>
		</div>
	</div>
</div>
<?php get_footer();
