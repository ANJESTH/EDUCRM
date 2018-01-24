<!DOCTYPE html>
<html lang="en" class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<?php global $current_user; ?>
	<?php $user_name = ( $current_user->first_name )? $current_user->first_name : $current_user->display_name; ?>
</head>
<body>

	<div class="inner-content">
		<div class="header-wrap">
			<div id="menuF" class="default">
				<div class="container">
					<div class="row">
						<div class="logo col-md-4">
							<div>
								<a href="<?php echo home_url(); ?>">
									<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/logo.png'; ?>">
								</a>
							</div>
						</div>
						<div class="col-md-8">
							<?php if ( ! empty( educrm_get_current_user_type() ) ) { ?>
								<div  id="loggedin" > <label>Consultancy</label><span> <?php echo educrm_get_the_consultancy_title(); ?></span></div>
								<?php } ?>
								<div class="navmenu">
									<ul id="menu">
										<li>
											<span>Welcome, <?php echo $user_name; ?> !</span>
										</li>
										<li class="last">
											<a href="<?php echo wp_logout_url(); ?>">Logout</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- sub menu section -->
					<div class="sub-menuw-wrap no-padding">
						<div class="col-xs-12 no-padding">
							<div class="sub-menu-block">
								<ul>
									<li class="no-padding">
										<div class="sub-menu-item bg-red">
											<a href="<?php echo home_url( '/account/' ); ?>">
												Account
											</a>
										</div>
									</li>
									<?php if ( current_user_can( 'consultancy' ) ) { ?>
										<li class="no-padding">
											<div class="sub-menu-item bg-purple">
												<a href="<?php echo home_url( '/agents/' ); ?>">
													Agent
												</a>
											</div>
										</li>
										<li class=" no-padding">
											<div class="sub-menu-item bg-yellow">
												<a href="<?php echo home_url( '/institutions/' ); ?>">
													Institution
												</a>
											</div>
										</li>
										<li class=" no-padding">
											<div class="sub-menu-item bg-orange">
												<a href="<?php echo home_url( '/courses/' ); ?>">
													Course
												</a>
											</div>
										</li>
										<?php } ?>
										<li class=" no-padding">
											<div class="sub-menu-item bg-green">
												<a href="<?php echo home_url( '/applications/' ); ?>">
													Application
												</a>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
