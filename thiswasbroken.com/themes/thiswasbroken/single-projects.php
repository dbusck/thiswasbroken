<?php get_header(); ?>

	<?php while (have_posts()) : the_post(); ?>
		<div class="cover-image">
			<?php the_post_thumbnail(); ?>
		</div>

		<?php if ( get_field("project_background") || get_field("project_color") ) : ?>
			<style type="text/css" media="screen">
				.description {
					background: <?php the_field("project_background"); ?>;
					color: <?php the_field("project_textcolor"); ?>;
				}
				<?php if ( get_field("project_headercolor") ) : ?>
					.description h1, .description h2 {
						color: <?php the_field("project_textcolor"); ?>;
					}
				<?php endif; ?>
			</style>
		<?php endif; ?>

		<section class="description page-section">
			<div class="project-text row">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>

			<div class="project-media row">
				<?php if ( get_field("video_embed") && get_field("video_ontop") ) :
				 	the_field("video_embed");
				endif; ?>
				<?php while(the_repeater_field('project_images')) :
					$image = get_sub_field('project_image'); ?>
					<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>">
					<?php if( $image['caption'] ): ?>
						<p class="wp-caption-text"><?php echo $image['caption']; ?></p>
					<?php endif; ?>
				<?php endwhile;
				if ( get_field("video_embed") && !get_field("video_ontop") ) : 
					the_field("video_embed"); 
				endif; ?>
			</div>
		</section>
	<?php endwhile; ?>

	<?php // Query Arguments 
    $ids = get_field('people_involved', false, false);      
    $loop = new WP_Query(array('post_type' => 'people',	'post__in' => $ids, 'orderby' => 'title', 'order' => 'ASC' )); ?>
    
		<section class="people-involved page-section text-center <?php if ( !get_field("project_background") ): ?>odd<?php endif; ?>">
			<div class="row">
				<h2>Who's involved?</h2>
				<div class="row">
					<?php while ( $loop->have_posts() ) : $loop->the_post();
					    ?><div class="person column column small-6 medium-3 large-2">
					    	<a href="#" class="image-holder <?php echo strtolower( str_replace(' ', '', get_the_title() )); ?>">
						    	<img class="head-image up" src="<?php $image = get_field('image_up'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image up-left" src="<?php $image = get_field('image_upleft'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image left" src="<?php $image = get_field('image_left'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image down-left" src="<?php $image = get_field('image_downleft'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image down" src="<?php $image = get_field('image_down'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image down-right" src="<?php $image = get_field('image_downright'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image right" src="<?php $image = get_field('image_right'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image up-right" src="<?php $image = get_field('image_upright'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
								<img class="head-image front" src="<?php $image = get_field('image_front'); echo $image['sizes'][ 'thumbnail' ]; ?>" alt="Portrait"/>
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
								<?php endif;
								if ( get_field("email_link") ) : ?>
									<a href="mailto:<?php the_field("email_link"); ?>" title="Contact">Contact</a>
								<?php endif; ?>
							</div>
					    </div><?php 
					endwhile; ?>
				</div>
			</div>
		</section>
	<?php wp_reset_postdata(); ?>

	<section class="more-cases page-section <?php if ( get_field("project_background") ): ?>odd<?php endif; ?>">
		<div class="row">
			<h2>See more awesome</h2>
			<div class="row">			
				<?php $args = array( 'post_type' => 'projects', 'orderby' => 'title', 'order' => 'ASC' );
				$loop = new WP_Query( $args ); 
				while ( $loop->have_posts() ) : $loop->the_post();?>
					<a href="<?php the_permalink(); ?>" class="showcase-link column medium-3">
						<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'homepage-thumb' );
						$url = $thumb['0']; ?>
						<img src="<? echo $url ?>" alt="Project-image" width="600" height="400">
						<div class="overlay">
							<div class="caption-wrapper">
								<hgroup class="caption">
									<h3 class="caption-title"><?php the_title(); ?></h3>
								</hgroup>
							</div>
						</div>
					</a><?php
				endwhile;
				wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

<?php get_footer(); ?>