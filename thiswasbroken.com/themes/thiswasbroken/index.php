<?php get_header(); ?>

		<div class="container">
		  	<h1><?php the_title(); ?></h1>
	  	</div>
	  	
	  	<section class="container">
    		<div class="row">
				<?php the_content(); ?>
		    </div>
		</section>

<?php get_footer(); ?>