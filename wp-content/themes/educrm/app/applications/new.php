<?php
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
						<?php echo educrm_get_the_consultancy_title(); ?> | New application</h1>
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
				// Deleting cached form values
				global $wpdb;
				$wpdb->query(
					$wpdb->prepare(
						"DELETE FROM $wpdb->options
                        WHERE option_name LIKE %s", '%new_application%'
					)
				);
				// Displaying add new agent form.
				acf_form(
					array(
						'post_id'			=> 'new_application',

						'new_application'	=> array(
							'post_type'		=> 'application',
							'post_status' 	=> 'publish',
						),

						// 'post_title' 		=> true,
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
							// 'field_58a1b39266378', // application_status

						),

						'form_attributes'	=> array( 'action' => home_url( '/applications/?action=new' ) ),
						'honeypot' 			=> true,
						'return'			=> home_url( 'applications' ),
						'submit_value'		=> 'Create',
					)
				); ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
