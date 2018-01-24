<?php
/**
 * Only Consultancy user can view this page.
 */
educrm_authenticate_app_pages();
educrm_maybe_add_course();
educrm_maybe_delete_course();
$courses = educrm_get_courses( educrm_get_the_consultancy_id() );
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
						<?php echo educrm_get_the_consultancy_title(); ?>  |  Courses
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
				<a  class="btn btn-primary" href="?action=new">Add New course</a>
					<?php if ( ! empty( $courses ) ) { ?>
					  <table class="table table-striped table-bordered table-responsive">
						  <thead>
							  <tr>
								  <th>Course title</th>
								  <th>Course code</th>
								  <th>Faculty</th>
								  <th>Fee</th>
								  <th>Level</th>
								  <th>Duration</th>
								  <th>Institution</th>
								  <th>Actions</th>
							  </tr>
						  </thead>

						  <tbody>
							<?php foreach ( $courses as $course ) {
								$course_meta = get_post_custom();

								?>
							  <tr>
								  <td><?php echo $course->post_title; ?></td>
								  <td><?php echo $course_meta['course_code'][0]; ?></td>
								  <td><?php echo $course_meta['faculty'][0]; ?></td>
								  <td><?php echo $course_meta['fee'][0]; ?></td>
								  <td><?php echo $course_meta['level'][0]; ?></td>
								  <td><?php echo $course_meta['duration'][0]; ?></td>
								  <td>
									  <a href="<?php echo get_permalink( $course_meta['institution'][0] ); ?>">
										<?php echo get_post( $course_meta['institution'][0] )->post_title; ?>
									  </a>
								  </td>
								  <td>
									  <a href="<?php echo get_the_permalink( $course->ID ); ?>"> EDIT </a>
									  <a onClick="return confirmDeleteCourse();" href="?action=delete&course_id=<?php echo $course->ID ?>"> DELETE </a>
								  </td>
							  </tr>
								<?php } ?>
						  </tbody>
					  </table>
						<?php } else { ?>
					 <h2>No courses found.</h2>
						<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
