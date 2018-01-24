<?php
/**
 * Only Consultancy user can view this page.
 */
educrm_authenticate_app_pages();
educrm_maybe_add_agent();
educrm_maybe_delete_agent();
$agents = educrm_get_agents( educrm_get_the_consultancy_id() );
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
						<?php echo educrm_get_the_consultancy_title(); ?> | Agents
					</h1>
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
				<a class="btn btn-primary" href="?action=new">Add New Agent</a>
				<?php if ( ! empty( $agents ) ) { ?>
					<table class="table table-striped table-bordered table-responsive">
						<thead>
							<tr>
								<th>Agent Name</th>
								<th>Address</th>
								<th>Email</th>
								<th>Phone</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							<?php foreach ( $agents as $agent ) { ?>
								<tr>
									<td><?php echo $agent->post_title; ?></td>
									<td><?php echo get_post_meta( $agent->ID, 'address', true ); ?></td>
									<td><?php echo get_post_meta( $agent->ID, 'email', true ); ?></td>
									<td><?php echo get_post_meta( $agent->ID, 'phone', true ); ?></td>
									<td>
										<a href="<?php echo get_the_permalink( $agent->ID ); ?>"> EDIT </a>
										<a href="?action=delete&agent_id=<?php echo $agent->ID ?>"> DELETE </a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>

				<?php } else { ?>
					<h2>No agents found.</h2>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php get_footer();
