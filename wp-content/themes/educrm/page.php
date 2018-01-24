<?php
get_header(); ?>

<div id="banner" class="section-banner" >
	<div class="section parallax-section" data-parallax="scroll" data-image-src="./images/slide.jpg" data-bleed="0" data-position="center">
	   <div class="parallax-mirror">
		   <img src="<?php echo get_stylesheet_directory_uri() . '/assets/images/slide.jpg'; ?>">
	   </div>
	</div>
	<div class="container banner-container">
	   <div class="col-xs-12">
		   <div class="banner-block">
			   <div class="col-xs-12 text-center">
				   <h1 class="page-title text-bold color-white fade-scroll" > <?php the_title(); ?></h1>
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
		   <div class="col-sm-6 form-div">
				<?php while ( have_posts() ) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>

<?php
get_footer();
