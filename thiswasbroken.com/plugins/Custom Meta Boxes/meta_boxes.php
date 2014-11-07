<?php
/*
Plugin Name: Custom Meta Boxes
*/

$meta_boxes = array();

// Primary preamble meta box
$meta_boxes[] = array(
    'id' => 'page_preamble',
    'title' => 'Preamble',
    'pages' => array('page'), // Admin page (or post type) (multiple=comma-separated)
    'context' => 'normal',	// normal/side
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Preamble',
            'desc' => 'Preamble under title', // Shown under the input
            'id' => 'page_preamble',
            'type' => 'text',
            'std' => '' // value of checkbox
        )
    )
);

// Primary preamble meta box
$meta_boxes[] = array(
    'id' => 'page_secondeditor',
    'title' => 'Editor',
    'pages' => array('page'), // Admin page (or post type) (multiple=comma-separated)
    'context' => 'normal',	// normal/side
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Editor',
            'desc' => 'Secondary editor', // Shown under the input
            'id' => 'pagesecondeditor',
            'type' => 'editor',
            'std' => '' // value of checkbox
        )
    )
);

foreach ($meta_boxes as $meta_box) {
	$my_box = new db_meta_box($meta_box);
}

class db_meta_box { 
    protected $_meta_box;
     
    // create meta box based on given data
    function __construct($meta_box) {
        $this->_meta_box = $meta_box;
        add_action('admin_menu', array(&$this, 'add')); 
        add_action('save_post', array(&$this, 'save'));
    }
 
    // Add meta box for multiple post types
    function add() {
        foreach ($this->_meta_box['pages'] as $page) {
            add_meta_box($this->_meta_box['id'], $this->_meta_box['title'], array(&$this, 'show'), $page, $this->_meta_box['context'], $this->_meta_box['priority']);
        }
    }
 
    // Callback function to show fields in meta box
    function show() {
        global $post;
 
        // Use nonce for verification
        echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
        
        foreach ($this->_meta_box['fields'] as $field) {
                    
            // Get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);
 
            echo	'<label for="', $field['id'], '">', $field['name'], '</label>';
                switch ($field['type']) {
                // Textfield
                case 'text':
                    echo '<input type="text" class="widefat" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" />',
                        '<p>', $field['desc'], '</p>';
                    break;
                // Textarea
                case 'textarea':
                    echo '<textarea class="widefat" name="', $field['id'], '" id="', $field['id'], '" rows="6">', $meta ? $meta : $field['std'], '</textarea>',
                        '<p>', $field['desc'], '</p>';
                    break;
                // HTML Editor
                case 'editor':
                    //echo '<div class="wp-editor-wrap">';
                    wp_editor( $meta ? $meta : $field['std'], $field['id'], array ( 'tinymce' => false ) );
                    //echo '</div>';
                    echo '<p>', $field['desc'], '</p>';
                    break;
                // WYSIWYG
                case 'wysiwyg':
                    //echo '<div class="wp-editor-wrap">';
                    wp_editor( $meta ? $meta : $field['std'], $field['id'], array ( 'tinymce' => false ) );
                    //echo '</div>';
                    echo '<p>', $field['desc'], '</p>';
                    break;
                // Dropdown select
                case 'select':
                    echo '<select class="widefat" name="', $field['id'], '" id="', $field['id'], '">';
                    foreach ($field['options'] as $option) {
                        echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                    }
                    echo '</select>';
                    break;
                // Radio button
                case 'radio':
                    foreach ($field['options'] as $option) {
                        echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                    }
                    break;
                // Checkbox
                case 'checkbox':
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '" value="', $field['value'], '"', $meta ? ' checked=""' : '', ' />';
                    break;
            }
        }
    }
    
 
    // Save data from meta box
    function save($post_id) {
    
        // Verify the nonce before proceeding
        if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
            return $post_id; }
 
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id; }
 
        // Check if the current user has permission to edit the page/post
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id; }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id; }
 
        foreach ($this->_meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
 
            //If the new meta value does not match the old value, update it
            if ($new && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
						} 
            //If there is no new meta value but an old value exists, delete it
            elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
}

    // important: note the priority of 99, the js needs to be placed after tinymce loads
add_action('admin_print_footer_scripts','my_admin_print_footer_scripts',99);
function my_admin_print_footer_scripts()
{
    ?><script type="text/javascript">/* <![CDATA[ */
       /*jQuery(function($)
        {
            var i=1;
            $('textarea.mceEditor').each(function(e)
            {
                var id = $(this).attr('id'); 
                if (!id)
                {
                    id = 'customEditor-' + i++;
                    $(this).attr('id',id);
                }
 
                tinyMCE.execCommand('mceAddControl', false, id);
                 
            });
        });*/
    /* ]]> */</script><?php
}

?>