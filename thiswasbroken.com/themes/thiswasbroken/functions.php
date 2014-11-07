<?php

// Add scripts
if( !is_admin()){
	wp_deregister_script('jquery');
	wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"), false, '2.1.1', true);
	wp_enqueue_script('jquery');
	wp_register_script('modernizr', (get_template_directory_uri() . "/js/modernizr.js"), false, '1', false);
	wp_enqueue_script('modernizr');
	wp_register_script('application', (get_template_directory_uri() . "/js/application.js"), false, '1', true);
	wp_enqueue_script('application');
}

// Google Analytics in footer
/*add_action('wp_head', 'add_google_analytics_tracking');
function add_google_analytics_tracking() { ?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '***** ANALYTICS-ID *******']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php }*/



// Add custom post type for projects
add_action( 'init', 'create_posttypes' );

function create_posttypes() {

	register_post_type( 'projects',
		array(
			'labels' => array(
				'name' => __( 'Projects' ),
				'singular_name' => __( 'Project' )
			),
			'public' => true,
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				'revisions',
			),
			'menu_position' => 5,
			'menu_icon' => 'dashicons-portfolio',
		)
	);

	register_post_type( 'people',
		array(
			'labels' => array(
				'name' => __( 'People' ),
				'singular_name' => __( 'Person' )
			),
			'public' => true,
			'has_archive' => false,
			'supports' => array(
				'title',
				'editor',
			),
			'menu_position' => 5,
			'menu_icon' => '',
			'menu_icon' => 'dashicons-groups',
		)
	);
}



// Remove inline styling for gallery
add_filter( 'use_default_gallery_style', '__return_false' );


//Featured Image Support
add_theme_support('post-thumbnails');
set_post_thumbnail_size( 1600, 700 ); // Unlimited height, soft crop
add_image_size( 'homepage-thumb', 600, 400, true ); // Front page thumbnails

//Menu Support
add_theme_support( 'menus' );


// Register menus
add_action( 'init', 'register_my_menus' );
function register_my_menus() {
  register_nav_menus(
    array( 'header-menu' => __( 'Header Menu' ) )
  );
}


//Add to TinyMCE first row
add_filter( 'mce_buttons', 'custom_mce_buttons' );
function custom_mce_buttons( $buttons ) {
    array_splice( $buttons, 6, 0, 'hr');
    return $buttons;
}

//Add to TinyMCE second row
add_filter( 'mce_buttons_2', 'custom_mce_buttons_2' );
function custom_mce_buttons_2( $buttons ) {     
    
    //Add styleselect dropdown (unshift to add it first in row)
    array_unshift( $buttons, 'styleselect' );            
    return $buttons;
}


//Custom stylesheet for editing (looks in theme dir)
/*add_editor_style('custom-editor-style.css');*/


//HTML compression
class WP_HTML_Compression {
	// Settings
	protected $compress_css = false;
	protected $compress_js = false;
	protected $info_comment = true;
	protected $remove_comments = true;

	// Variables
	protected $html;
	public function __construct($html) {
		if (!empty($html)) {
			$this->parseHTML($html);
		}
	}
	public function __toString() {
		return $this->html;
	}
	protected function bottomComment($raw, $compressed) {
		$raw = strlen($raw);
		$compressed = strlen($compressed);
		
		$savings = ($raw-$compressed) / $raw * 100;
		
		$savings = round($savings, 2);
		
		return '<!--HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes-->';
	}
	protected function minifyHTML($html) {
		$pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
		preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
		$overriding = false;
		$raw_tag = false;
		// Variable reused for output
		$html = '';
		foreach ($matches as $token) {
			$tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;
			
			$content = $token[0];
			
			if (is_null($tag)) {
				if ( !empty($token['script']) ) {
					$strip = $this->compress_js;
				}
				else if ( !empty($token['style']) ) {
					$strip = $this->compress_css;
				}
				else if ($content == '<!--wp-html-compression no compression-->') {
					$overriding = !$overriding;
					
					// Don't print the comment
					continue;
				}
				else if ($this->remove_comments) {
					if (!$overriding && $raw_tag != 'textarea') {
						// Remove any HTML comments, except MSIE conditional comments
						$content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
					}
				}
			}
			else {
				if ($tag == 'pre' || $tag == 'textarea') {
					$raw_tag = $tag;
				}
				else if ($tag == '/pre' || $tag == '/textarea') {
					$raw_tag = false;
				}
				else {
					if ($raw_tag || $overriding) {
						$strip = false;
					}
					else {
						$strip = true;
						
						// Remove any empty attributes, except:
						// action, alt, content, src
						$content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
						
						// Remove any space before the end of self-closing XHTML tags
						// JavaScript excluded
						$content = str_replace(' />', '/>', $content);
					}
				}
			}
			
			if ($strip) {
				$content = $this->removeWhiteSpace($content);
			}
			
			$html .= $content;
		}
		
		return $html;
	}
		
	public function parseHTML($html) {
		$this->html = $this->minifyHTML($html);
		
		if ($this->info_comment) {
			$this->html .= "\n" . $this->bottomComment($html, $this->html);
		}
	}
	
	protected function removeWhiteSpace($str) {
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n",  '', $str);
		$str = str_replace("\r",  '', $str);
		
		while (stristr($str, '  ')) {
			$str = str_replace('  ', ' ', $str);
		}
		
		return $str;
	}
}

function wp_html_compression_finish($html) {
	return new WP_HTML_Compression($html);
}

function wp_html_compression_start() {
	ob_start('wp_html_compression_finish');
}
add_action('get_header', 'wp_html_compression_start');