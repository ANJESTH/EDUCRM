<!DOCTYPE html>
<html lang="en" class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div id="home">

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
						<div class="navmenu"style="text-align: center;">
							<ul id="menu" class="notloggedIn">
								<li><a href="<?php echo home_url( '#login' ); ?>">Login</a>
								<li class="last"><a href="<?php echo home_url( '/#registration' ); ?>">Register</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- SLIDER START -->
		<div class="banner">
			<div class="overlay">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class=""></li>
						<li data-target="#myCarousel" data-slide-to="1" class=""></li>
						<li data-target="#myCarousel" data-slide-to="2" class="active"></li>
					</ol>

					<!-- Wrapper for Slides -->
					<div class="carousel-inner" role="listbox">
						<div class="item">
							<div class="fill">
								<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide.jpg'; ?>" class="img-responsive" width="100%" alt="...">
							</div>

							<div class="carousel-caption">
								<div class="carousel-text">
									<h1>Welcome To EDUCRM</h1>
									<div class="lead"><p><span class="blueTxt">EDU</span><span class="greenTxt">CRM</span> is all about managing your bussiness.</p>
									</div>
								</div>
							</div>
						</div>
						<div class="item">
							<div class="fill">
								<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide1.jpg'; ?>" class="img-responsive" width="100%" alt="...">
							</div>

							<div class="carousel-caption">
								<div class="carousel-text">
									<h1>Our Difference</h1>
									<div class="lead"><p><span class="blueTxt">EDU</span><span class="greenTxt">CRM</span> is consultancy management system. Our goal is to provide high quality service to achieve high level of satisfaction from our clients.</p>
									</div>
								</div>
							</div>
						</div>
						<div class="item active">
							<div class="fill">
								<img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide2.jpg'; ?>" class="img-responsive" width="100%" alt="...">
							</div>

							<div class="carousel-caption">
								<div class="carousel-text">
									<h1>Why Choose Us?</h1>
									<div class="lead"><p><span class="blueTxt">EDU</span><span class="greenTxt">CRM</span> is developedto provide ease to your bussiness, management and record of application and management of agent details.</p>
									</div>
								</div>
							</div>
						</div>

					</div>

					<!-- Controls -->
					<a class="left carousel-control" href="#myCarousel" data-slide="prev">
						<span class="icon-prev"></span>
					</a>
					<a class="right carousel-control" href="#myCarousel" data-slide="next">
						<span class="icon-next"></span>
					</a>

				</div>
			</div>
		</div>
		<!-- SLIDER END -->
