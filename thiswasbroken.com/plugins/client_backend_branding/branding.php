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
// Add new
add_action( 'welcome_panel', 'add_client_welcome_panel' );
function add_client_welcome_panel() {
	echo
	'<div class="welcome-panel-content">
		<h3>Välkommen till admin-sidan för Joo Bygg.</h3>
		<p class="about-description">Här har jag samlat lite länkar som gör det lite lättare att hitta.</p>
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column">
				<a class="button button-primary button-hero load-customize hide-if-no-customize" href="'.site_url().'/wp-admin/post.php?post=2&action=edit">Redigera startsidan</a>
			</div>
			<div class="welcome-panel-column">
				<h4>Skapa nytt</h4>
				<ul>	
					<li><a href="'.site_url().'/wp-admin/post.php?post=7&action=edit" class="welcome-icon welcome-edit-page">Ändra tjänster</a></li>
					
				</ul>
			</div>
			<div class="welcome-panel-column welcome-panel-last">
				<h4>Se ändringar</h4>
				<ul>
					<li><a href="'.site_url().'/" class="welcome-icon welcome-view-site">Visa hemsidan</a></li>
				</ul>
			</div>
		</div>
	</div>';
}


// Add custom dashboard widgets
add_action('wp_dashboard_setup', 'client_custom_dashboard_widgets'); 
function client_custom_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget(
'custom_help_widget',
'Snabbhjälp',
'custom_dashboard_help');
}
function custom_dashboard_help() {
echo '<p>
<strong>Behöver du hjälp med något?</strong>
<p>Sidan gjord av Douglas Busck, http://douglasb.se<br>
Kontakta mig på tel 0762-14 03 98, eller maila mig <a href="mailto:hej@douglasb.se">här</a>.</p>';
}


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


//change the menu items label
! defined( 'ABSPATH' ) and exit; // Not a WordPress context? Stop.
add_action( 'init', array ( 'chg_post_menu_labels', 'init' ) );
add_action( 'admin_menu', array ( 'chg_post_menu_labels', 'admin_menu' ) );
class chg_post_menu_labels
{
    public static function init()
    {
        global $wp_post_types;
        $labels = &$wp_post_types['post']->labels;
        $labels->name = __('Nyheter');
        $labels->singular_name = __('Nyhet');
        $labels->add_new = __('Lägg till nyhet');
        $labels->add_new_item = __('Lägg till nyhet');
        $labels->edit_item = __('Redigera nyhet');
        $labels->new_item = __('Lägg till nyhet');
        $labels->view_item = __('Visa nyheter');
        $labels->search_items = __('Sök nyheter');
        $labels->not_found = __('Kunde inte hitta några nyheter');
        $labels->not_found_in_trash = __('Hittade inga nyheter i papperskorgen');
        $labels->name_admin_bar = __('Nyhet');
    }

    public static function admin_menu()
    {
        global $menu;
        global $submenu;
        $menu[5][0] = __('Nyheter');
        $submenu['edit.php'][5][0] = __('Nyheter');
        $submenu['edit.php'][10][0] = __('Lägg till nyhet');
    }
}
    

// Change dashboard footer text
add_filter('admin_footer_text', 'change_footer_admin');
function change_footer_admin() {
	echo '/ Douglas Busck. Kontakta mig gärna på <a href="mailto:douglas.busck@gmail.com">douglas.busck@gmail.com</a>';
}


//Login page styling
add_action( 'login_enqueue_scripts', 'time_login_styling' );
function time_login_styling() { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_bloginfo( 'template_directory' ) ?>/images/logga.svg);
            padding-bottom: 20px;
            height:105px;
            width:150px;
            margin:0 auto;
            background-size:auto;
        }
        body.login {
        	background: #111;
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
add_filter( 'login_headerurl', 'joo_login_logo_url' );
function joo_login_logo_url() {
    return get_bloginfo( 'url' );
}

// Login logo url title
add_filter( 'login_headertitle', 'joo_login_logo_url_title' );
function joo_login_logo_url_title() {
    return 'Joo Bygg AB';
}


?>