<?php
educrm_authenticate_app_pages( 'agent' );
acf_form_head();
get_header( 'educrm' );
?>
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
					<h1 class="page-title text-bold color-white fade-scroll" >
						<?php echo educrm_get_the_consultancy_title(); ?> | Edit application
					</h1>
				</div>
				<div class="clear"></div>
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
						'post_id'		=> get_the_id(),
						// 'post_title' 	=> true,
						'fields' => array(
							'field_58a1af7f70e07', // applicant_name
							'field_58a1af8a70e08', // applicant_email
							'field_58a1af9670e09', // applicant_phone
							'field_58a1af9c70e0a', // applicant_academic_qualificatin
							'field_58a1afa770e0b', // applicant_academic_score
							'field_58a1b09170e0c', // applicant_english_languate
							'field_58a1b0d470e0d', // applicant_english_test_score,
							'field_58a1b0f970e0e', // applied_to_institute
							'field_58a1b11070e0f', // choosen_course
							'field_58a1b39266378', // application_status

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
