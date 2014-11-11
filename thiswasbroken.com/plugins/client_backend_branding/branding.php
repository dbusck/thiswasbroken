<?php
/*
Plugin Name: Client backend branding
Description: Customizing admin and login to suit client.
Version: 1.1
Author: Douglas Busck http://douglasb.se
*/



// Client accessable site description setting
function site_description_callback() {
    $settings = (array) get_option( 'site-settings' );
    $value = $settings['site-description']; ?>
    <input type="text" name="site-settings[site-description]" value="<?php echo esc_attr( get_option('blogdescription') ); ?>" />
<?php }

// Output settings form
function render_site_custom_settings_page() {
    ?>
    <div class="wrap">
    	<?php screen_icon(); ?>
        <h2>Inställningar</h2>
        
        <form action="options.php" method="POST">
            <?php settings_fields( 'site-custom-settings-group' ); ?>
            <?php do_settings_sections( 'site-custom-options' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


// Sanitize
function my_settings_sanitize( $input ) {
    $input['quantity'] = absint( $input['quantity'] );
    return $input;
}


// Fix capability issue
add_filter( 'option_page_capability_site-custom-settings-group', 'site_option_page_capability' );
function site_option_page_capability( $capability ) {
	return 'edit_theme_options';
}

 

// Remove menu items from admin sidebar
add_action( 'admin_menu', 'remove_unused_menu_pages' );
function remove_unused_menu_pages() {
	        
	// Keep settings and SEO if admin (checks if user can delete plugins, last cap I would add to a client)
	if (!current_user_can('delete_plugins')) {
		remove_menu_page( 'wpseo_dashboard' );
	}
	
    //top level menus
    remove_menu_page('edit-comments.php');
    remove_menu_page('link-manager.php');
    remove_menu_page('edit.php');
    	        
    //submenus
    remove_submenu_page( 'themes.php', 'themes.php' );
    remove_submenu_page( 'tools.php', 'tools.php' );
}

// Remove menu items in admin bar
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_links' );
function remove_admin_bar_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('new-link');
	$wp_admin_bar->remove_menu('new-media');
	//$wp_admin_bar->remove_menu('themes');
	//$wp_admin_bar->remove_menu('customize');
	$wp_admin_bar->remove_menu('menus');
	//$wp_admin_bar->remove_menu('updates');
    $wp_admin_bar->remove_node( 'new-post' );
}


// Remove admin metaboxes
add_action( 'admin_menu', 'remove_unused_dashboard_widgets' );
function remove_unused_dashboard_widgets() {
	// Remove each dashboard widget metabox for Incoming Links, Plugins, the WordPress Blog and Other WordPress News
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	remove_meta_box('dashboard_primary', 'dashboard', 'normal');
	remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
	remove_meta_box('postcustom', 'page', 'normal');
	remove_meta_box('postcustom', 'post', 'normal');
	remove_meta_box('wpseo_meta', 'post', 'normal');
}

// unregister WP Widgets
add_action('widgets_init', 'unregister_default_wp_widgets', 1);
function unregister_default_wp_widgets() {
	// Commented out to keep
	//unregister_widget('WP_Widget_Archives');
    //unregister_widget('WP_Widget_Search');
	//unregister_widget('WP_Widget_Text');
	//unregister_widget('WP_Widget_Recent_Posts');
	//unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Nav_Menu_Widget');
}



// Add customised welcome-box in dashboard
// Remove default wp panel
remove_action( 'welcome_panel', 'wp_welcome_panel' );


// Disable the web file editor
define('DISALLOW_FILE_EDIT', true);


// Add custom caps
// Only needs to be run once, comment out after
add_action( 'admin_init', 'add_client_user_caps');
function add_client_user_caps() {
    // gets the author role
    $role = get_role( 'editor' );

    // This only works, because it accesses the class instance.
    // would allow the author to edit others' posts for current theme only
    $role->add_cap( 'update_core' );
    $role->add_cap( 'import' ); 
    $role->add_cap( 'export' ); 
    $role->add_cap( 'edit_theme_options' ); 
    $role->add_cap( 'update_plugins' );
    $role->remove_cap( 'manage_options' );
}


// remove junk from head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
    

// Change dashboard footer text
add_filter('admin_footer_text', 'change_footer_admin');
function change_footer_admin() {
	echo '/ This Was Broken @ Hyper Island 2014';
}


//Login page styling
add_action( 'login_enqueue_scripts', 'admin_login_styling' );
function admin_login_styling() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_bloginfo( 'template_directory' ) ?>/images/logo.svg);
            padding-bottom: 20px;
            height:105px;
            width:200px;
            margin:0 auto;
            background-size:auto;
        }
        body.login {
        	background: rgb(48, 47, 47);
        }
        body.login #nav a, body.login #back.current_menu_itemoblog a {
        	color:white !important;
        	text-shadow:none; 
        }
        body.login #nav a:hover, body.login #backtoblog a:hover { 
        	color:black; 
        }
        body.login form input.input {
        	color:white;
        	background:black;
        	border-radius:0;
        	border:0;
        }
    </style>
<?php }

// Login logo url
add_filter( 'login_headerurl', 'admin_login_logo_url' );
function admin_login_logo_url() {
    return get_bloginfo( 'url' );
}

// Login logo url title
add_filter( 'login_headertitle', 'admin_login_logo_url_title' );
function admin_login_logo_url_title() {
    return 'Logotype';
}


?>