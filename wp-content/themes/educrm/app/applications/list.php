<?php
/**
 * Only Consultancy user can view this page.
 */
educrm_authenticate_app_pages( 'agent' );
educrm_maybe_add_application();
educrm_maybe_delete_application();
$applications = educrm_get_applications( educrm_get_the_consultancy_id() );
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
						<?php echo educrm_get_the_consultancy_title(); ?>  | Applications
					</h1>
				</div>
				<div class="clear">

				</div>
			</div>
		</div>
	</div>
</div>
<!-- form content -->
<div class="section the_body">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 form-div">
				<a  class="btn btn-primary" href="?action=new">Add New application</a>
				<?php if ( ! empty( $applications ) ) { ?>
					<table class="table table-striped table-bordered table-responsive">
						<thead>
							<!-- 'field_58a1af7f70e07', //applicant_name
							'field_58a1af8a70e08', //applicant_email
							'field_58a1af9670e09', //applicant_phone
							'field_58a1af9c70e0a', //applicant_academic_qualificatin
							'field_58a1afa770e0b', //applicant_academic_score
							'field_58a1b09170e0c', //applicant_english_languate
							'field_58a1b0d470e0d', //applicant_english_test_score,
							'field_58a1b0f970e0e', // applied_to_institute
							'field_58a1b11070e0f', // choosen_course
							'field_58a1b39266378', // application_status  -->
							<tr>
								<th>Applicant Name</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Academic qualification</th>
								<th>Academic score</th>
								<th>English test</th>
								<th>English test score</th>
								<th>Institution</th>
								<th>Course</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ( $applications as $application ) {
								$application_meta = get_post_custom();  var_dump( $application_meta); die;?>
								<tr>
									<td><?php echo $application_meta['applicant_name'][0]; ?></td>
									<td><?php echo $application_meta['applicant_email'][0]; ?></td>
									<td><?php echo $application_meta['applicant_phone'][0]; ?></td>
									<td><?php echo $application_meta['application_academic_qualification'][0]; ?></td>
									<td><?php echo $application_meta['applicant_academic_score'][0]; ?></td>
									<td><?php echo $application_meta['english_test'][0]; ?></td>
									<td><?php echo $application_meta['english_test_score'][0]; ?></td>
									<td>
										<a href="<?php echo get_permalink( $application_meta['applied_institution'][0] ); ?>">
											<?php echo get_post( $application_meta['applied_institution'][0] )->post_title; ?>
										</a>
									</td>
									<td>
										<a href="<?php echo get_permalink( $application_meta['choosen_course'][0] ); ?>">
											<?php echo get_post( $application_meta['choosen_course'][0] )->post_title; ?>
										</a>
									</td>
									<td><?php echo ! empty($application_meta['application_status'][0]) ? $application_meta['application_status'][0] : 'draft'; ?></td>
									<td>
										<a href="<?php echo get_the_permalink( $application->ID ); ?>"> EDIT </a>
										<a href="?action=delete&application_id=<?php echo $application->ID ?>"> DELETE </a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				<?php } else { ?>
					<h2>No applications found.</h2>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
