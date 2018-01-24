<?php
/**
 * Only Consultancy user can view this page.
 */
educrm_authenticate_app_pages();
educrm_maybe_add_institution();
educrm_maybe_delete_institution();
$institutions = educrm_get_institutions( educrm_get_the_consultancy_id() );
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
						<?php echo educrm_get_the_consultancy_title(); ?> | Institutions
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
				<a class="btn btn-primary" href="?action=new">Add New institution</a>
					<?php if ( ! empty( $institutions ) ) { ?>
					  <table class="table table-striped table-bordered table-responsive">
						  <thead>

							  <tr>
								  <th>Institution title</th>
								  <th>Address</th>
								  <th>Email</th>
								  <th>Website</th>
								  <th>Phone</th>
								  <th>Action</th>
							  </tr>
						  </thead>

						  <tbody>
							<?php foreach ( $institutions as $institution ) { ?>
							  <tr>
								  <td><?php echo $institution->post_title; ?></td>
								  <td><?php echo get_post_meta( $institution->ID, 'address', true ); ?></td>
								  <td><?php echo get_post_meta( $institution->ID, 'email', true ); ?></td>
								  <td><?php echo get_post_meta( $institution->ID, 'website', true ); ?></td>
								  <td><?php echo get_post_meta( $institution->ID, 'phone', true ); ?></td>
								  <td>
									  <a href="<?php echo get_the_permalink( $institution->ID ); ?>"> EDIT </a>
									  <a onClick="return confirmDeleteInstitute();" href="?action=delete&institution_id=<?php echo $institution->ID ?>"> DELETE </a>
								  </td>
							  </tr>
								<?php } ?>
						  </tbody>
					  </table>
						<?php } else { ?>
					 <h2>No institutions found.</h2>
						<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
