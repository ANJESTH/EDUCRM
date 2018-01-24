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
						<?php echo educrm_get_the_consultancy_title(); ?> | New institution</h1>
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
							WHERE option_name LIKE %s", '%new_institution%'
					)
				);

				// Displaying add new agent form.
				acf_form(
					array(
						'post_id'			=> 'new_institution',

						'new_institution'	=> array(
							'post_type'		=> 'institution',
							'post_status' 	=> 'publish',
						),

						'post_title' 		=> true,

						'fields' => array(
							'field_589fc0244efcb', // address
							'field_589fc02e4efcc', // website
							'field_589fc0414efcd', // email
							'field_589fc0504efce', // phone

						),

						'form_attributes'	=> array( 'action' => home_url( '/institutions/?action=new' ) ),
						'honeypot' 			=> true,
						'return'			=> home_url( 'institutions' ),
						'submit_value'		=> 'Create',
					)
				);
				?>
				</div>
		</div>
	</div>
</div>

<?php
get_footer();
