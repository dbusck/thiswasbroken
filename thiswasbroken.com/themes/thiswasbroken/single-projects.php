<?php get_header(); ?>

	<?php while (have_posts()) : the_post(); ?>
		<div class="cover-image">
			<?php the_post_thumbnail(); ?>
		</div>

		<style type="text/css" media="screen">
			<?php the_field("custom_css"); ?>
		</style>

		<section class="description page-section">
			<div class="project-text row">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>

			<div class="project-images row">
				<?php while(the_repeater_field('project_images')) :
					$image = get_sub_field('project_image'); ?>
					<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>"
				<?php endwhile; ?>
			</div>
		</section>
	<?php endwhile; ?>

	<?php // Query Arguments 
    $ids = get_field('people_involved', false, false);      
    $loop = new WP_Query(array('post_type' => 'people',	'post__in' => $ids, 'orderby' => 'ASC' )); ?>
    
		<section class="people-involved page-section text-center odd">
			<div class="row">
				<h2>Who's involved?</h2>
				<div class="row">
					<?php while ( $loop->have_posts() ) : $loop->the_post();
					    ?><div class="person column column small-6 medium-3 large-2">
					    	<a href="#" class="image-holder <?php echo strtolower( str_replace(' ', '', get_the_title() )); ?>">
						    	<img class="head-image up" src="<?php the_field( "image_up"); ?>" alt="Portrait"/>
								<img class="head-image up-left" src="<?php the_field( "image_upleft"); ?>" alt="Portrait"/>
								<img class="head-image left" src="<?php the_field( "image_left"); ?>" alt="Portrait"/>
								<img class="head-image down-left" src="<?php the_field( "image_downleft"); ?>" alt="Portrait"/>
								<img class="head-image down" src="<?php the_field( "image_down"); ?>" alt="Portrait"/>
								<img class="head-image down-right" src="<?php the_field( "image_downright"); ?>" alt="Portrait"/>
								<img class="head-image right" src="<?php the_field( "image_right"); ?>" alt="Portrait"/>
								<img class="head-image up-right" src="<?php the_field( "image_upright"); ?>" alt="Portrait"/>
								<img class="head-image front" src="<?php the_field( "image_front"); ?>" alt="Portrait"/>
								<img class="dummy" src="<?php echo get_stylesheet_directory_uri(); ?>/images/profile-pic/dummy.gif"/>
							</a>
							<div class="popup-bubble">
								<h4 class="name"><?php the_title(); ?></h4>
								<?php if(  get_field("portfolio_link") ) : ?>
									<a href="<?php the_field("portfolio_link"); ?>" title="Portfolio">Portfolio</a>
								<?php endif;
								if ( get_field("linkedin_link") ) : ?>
									<a href="<?php the_field("linkedin_link"); ?>" title="LinkedIn">LinkedIn</a>
								<?php endif;
								if ( get_field("twitter_link") ) : ?>
									<a href="<?php the_field("twitter_link"); ?>" title="Twitter">Twitter</a>
								<?php endif;
								if ( get_field("facebook_link") ) : ?>
									<a href="<?php the_field("facebook_link"); ?>" title="Facebook">Facebook</a>
								<?php endif; ?>
							</div>
					    </div><?php 
					endwhile; ?>
				</div>
			</div>
		</section>
	<?php wp_reset_postdata(); ?>

<?php get_footer(); ?>