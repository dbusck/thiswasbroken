<?php get_header(); ?>

		<div class="cover-image front-cover">
			<div class="cover-logo">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.svg" alt="Logotype">
			</div>
		</div>

	  	<?php while (have_posts()) : the_post(); ?>
			<section id="about" class="description page-section">
				<div class="intro-text row">
					<?php the_content(); ?>
				</div>	
			</section>
		<?php endwhile; ?>

		<section id="cases" class="page-section odd">
			<div class="row">
				<h2 class="alpha">Showcase</h2>
				<div class="row">			
					<?php $args = array( 'post_type' => 'projects', 'orderby' => 'title', 'order' => 'ASC' );
					$loop = new WP_Query( $args ); 
					while ( $loop->have_posts() ) : $loop->the_post();?>
						<a href="<?php the_permalink(); ?>" class="showcase-link column medium-4">
							<?php $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'homepage-thumb' );
							$url = $thumb['0']; ?>
							<img src="<? echo $url ?>" alt="Project-image" width="600" height="400">
							<div class="blur" style="background-image:url(<? echo $url ?>)"></div>
							<div class="overlay">
								<div class="caption-wrapper">
									<hgroup class="caption">
										<h2 class="caption-title"><?php the_title(); ?></h2>
									</hgroup>
								</div>
							</div>
						</a><?php
					endwhile;
					wp_reset_postdata(); ?>
				</div>
			</div>
		</section>

		<section id="people" class="page-section">
			<div class="row">
				<h2 class="alpha">People</h2>
				<div class="row">
					<?php $args = array( 'post_type' => 'people', 'orderby' => 'title', 'order' => 'ASC' );
					$loop = new WP_Query( $args ); 
					while ( $loop->have_posts() ) : $loop->the_post();
						?><div class="person column small-6 medium-3 large-2">
							<a href="#" class="image-holder <?php echo strtolower(str_replace(' ', '', get_the_title())); ?>">
								<img class="head-image up" src="<?php the_field( "image_up" ); ?>" alt="Portrait"/>
								<img class="head-image up-left" src="<?php the_field( "image_upleft" ); ?>" alt="Portrait"/>
								<img class="head-image left" src="<?php the_field( "image_left" ); ?>" alt="Portrait"/>
								<img class="head-image down-left" src="<?php the_field( "image_downleft" ); ?>" alt="Portrait"/>
								<img class="head-image down" src="<?php the_field( "image_down" ); ?>" alt="Portrait"/>
								<img class="head-image down-right" src="<?php the_field( "image_downright" ); ?>" alt="Portrait"/>
								<img class="head-image right" src="<?php the_field( "image_right" ); ?>" alt="Portrait"/>
								<img class="head-image up-right" src="<?php the_field( "image_upright" ); ?>" alt="Portrait"/>
								<img class="head-image front" src="<?php the_field( "image_front" ); ?>" alt="Portrait"/>
								<img class="dummy" src="<?php echo get_stylesheet_directory_uri(); ?>/images/profile-pic/dummy.gif"/>
							</a>
							<div class="popup-bubble">
								<h4 class="name"><?php the_title(); ?></h4>
								<?php if (  get_field("portfolio_link") ) : ?>
									<a href="<?php the_field("portfolio_link"); ?>" title="Portfolio">Portfolio</a>
								<?php endif;
								if ( get_field("linkedin_link", $p->ID) ) : ?>
									<a href="<?php the_field("linkedin_link"); ?>" title="LinkedIn">LinkedIn</a>
								<?php endif;
								if ( get_field("twitter_link", $p->ID) ) : ?>
									<a href="<?php the_field("twitter_link"); ?>" title="Twitter">Twitter</a>
								<?php endif;
								if ( get_field("facebook_link", $p->ID) ) : ?>
									<a href="<?php the_field("facebook_link"); ?>" title="Facebook">Facebook</a>
								<?php endif; ?>
							</div>
						</div><?php
					endwhile;
					wp_reset_postdata(); ?>
				</div>
			</div>
		</section>

<?php get_footer(); ?>