<!--FOOTER-->
<footer>
	<h3 class="cta alpha">Want to get in touch? <a href="mailto:info@thiswasbroken.com" title="Mail us">Contact us!</a></h3>
	<span class="watermark">This Was Broken, 2014</span>
</footer>

<?php wp_footer(); ?>

<script type="text/javascript">

    var mouseX;
    var mouseY;

    /* Calling the initialization function */
    $(init);

    /* The images need to re-initialize on load and on resize, or else the areas
     * where each image is displayed will be wrong. */
    $(window).load(init);
    $(window).resize(init);

    /* Setting the mousemove event caller */
    $(window).mousemove(getMousePosition);

    function init() {	        
        /* Instanciate the mouse position variables */
        mouseX = 0;
        mouseY = 0;
        
        <?php if ( !is_single() ) :
			$loop = new WP_Query( array( 'post_type' => 'people' ) ); 
			while ( $loop->have_posts() ) : $loop->the_post();
				$imageName = strtolower(str_replace(' ', '', get_the_title())); ?>
	        		<?php echo $imageName ?> = new HeadImage("<?php echo $imageName ?>");
	        <?php endwhile;
	        wp_reset_postdata();
	    else :
	    	foreach( get_field('people_involved') as $p ) :
	    		$imageName = strtolower(str_replace(' ', '', get_the_title($p->ID))); ?>
	    		<?php echo $imageName ?> = new HeadImage("<?php echo $imageName ?>");
	    	<?php endforeach;
	    endif ?>
    }

    /* This function is called on mouse move and gets the mouse position. 
     * It also calls the HeadImage function to display the correct image*/
    function getMousePosition(event){
        
        /* Setting the mouse position variables */
        mouseX = event.pageX;
        mouseY = event.pageY;
        
        /*Calling the setImageDirection function of the HeadImage class
         * to display the correct image*/

        <?php if ( !is_single() ) :
			$loop = new WP_Query( array( 'post_type' => 'people' ) ); 
			while ( $loop->have_posts() ) : $loop->the_post();
				$imageName = strtolower(str_replace(' ', '', get_the_title())); ?>
	        	<?php echo $imageName ?>.setImageDirection();
	        <?php endwhile;
	        wp_reset_postdata();
	    else :
	    	foreach( get_field('people_involved') as $p ) :
	    		$imageName = strtolower(str_replace(' ', '', get_the_title($p->ID))); ?>
	    		/* Instanciate a HeadImage class for every image */
	    		<?php echo $imageName ?>.setImageDirection();
	    	<?php endforeach;
	    endif ?>
    }
</script>
</body>
</html>