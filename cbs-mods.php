<?php
/**
 * Plugin Name: CBS Mods
 * Description: 
 * Version: 1.7
 * Author: Randal Pope
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


function cbs_mods_register() {
    // Automatically register blocks by scanning the build directory
    $blocks_dir = plugin_dir_path(__FILE__) . 'src';
    
    if (file_exists($blocks_dir)) {
        $block_folders = array_filter(glob($blocks_dir . '/*'), 'is_dir');
        
        foreach ($block_folders as $block_folder) {
            $block_json = $block_folder . '/block.json';
            
            if (file_exists($block_json)) {
                register_block_type($block_folder);
                error_log('Registered block: ' . basename($block_folder));
            } else {
                error_log('Missing block.json in: ' . basename($block_folder));
            }
        }
    } else {
        error_log('Build directory not found');
    }
}
add_action('init', 'cbs_mods_register');

// Add debugging notice
function cbs_mods_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->base === 'post') {
        echo '<div class="notice notice-info is-dismissible"><p>VM Bubble Blocks plugin is active. Search for "VM Bubble" in the block inserter.</p></div>';
    }
}
add_action('admin_notices', 'cbs_mods_admin_notice');




function change_kadence_blocks_variable_font_sizes() {
  $arr = array(
    'sm'   => 'clamp(0.8rem, 0.73rem + 0.217vw, 0.9rem)',
    'md'   => 'clamp(1.125rem, 2vw, 1.313rem); letter-spacing: -0.21px;', // regular 0.995rem + 0.326vw
    'lg'   => 'clamp(1.313rem, 2vw, 1.75rem); letter-spacing: -0.42px !important;', // large text 1.576rem + 0.543vw
    'xl'   => 'clamp(1.375rem, 3vw, 2rem)', // x large text
    'xxl'  => 'clamp(2.5rem, 1.456rem + 3.26vw, 3.25rem)', // heading 2
    'xxxl' => 'clamp(2.75rem, 0.489rem + 7.065vw, 4rem)', // heading 1
  );
  return $arr;
}
add_filter('kadence_blocks_variable_font_sizes','change_kadence_blocks_variable_font_sizes', 25 );


function enqueue_cbs_mods_custom_css() {
    // Enqueue the custom CSS file
    wp_enqueue_style(
        'cbs-mods-custom-css',
        plugin_dir_url(__FILE__) . 'build/main.css',
        array(),
        time()
    );
	
}
add_action( 'wp_enqueue_scripts', 'enqueue_cbs_mods_custom_css' , 100 );

// Enqueue custom admin CSS
function enqueue_cbs_mods_admin_css() {
    // Enqueue the custom CSS file for admin
    wp_enqueue_style(
        'cbs-mods-admin-css',
        plugin_dir_url(__FILE__) . 'build/admin.css',
        array(),
        time()
    );
}
add_action( 'admin_enqueue_scripts', 'enqueue_cbs_mods_admin_css' );


// Enqueue custom admin CSS
function enqueue_cbs_mods_slider_js() {
    // Enqueue the custom CSS file for admin
    wp_enqueue_script(
        'cbs-mods-slider-js',
        plugin_dir_url(__FILE__) . 'build/tabbed-content-slider.js',
        array(),
        time(),
        array(
            'in_footer' => true, // Load in footer  
        ),

    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_cbs_mods_slider_js' );


// GravityForms_ContactUs_DomainBlocker
function custom_email_domain_block($result, $value, $form, $field) {
    $blocked_domains = array('baddomain.com', 'ptengert.net', 'iubridge.com');
    $email_domain = substr(strrchr($value, "@"), 1);

    if (in_array(strtolower($email_domain), $blocked_domains)) {
        $result['is_valid'] = false;
        $result['message'] = 'Email addresses from this domain are not allowed.';
    }

    return $result;
}
add_filter('gform_field_validation_2_3', 'custom_email_domain_block', 10, 4);


// custom queries
add_filter( 'kadence_blocks_pro_query_loop_query_vars', function( $query, $ql_query_meta, $ql_id ) {

    if ( $ql_id == 9858 ) {
        $current_team_member_id = get_the_ID();
        $linked_author = get_users(array(
            'meta_query' => array(
                array(
                    'meta_key' => 'link_to_team_member', // ACF field name
                    'value'   => $current_team_member_id,
                    'compare' => 'LIKE'
                )
            ),
            'number' => 1,
            'fields' => 'ID'
        ));
        
        if ( !empty($linked_author) ) {
            $author_id = $linked_author[0];
            
            // Check if this author actually has posts
            $author_posts = get_posts(array(
                'author' => $author_id,
                'post_type' => 'post',
                'post_status' => 'publish',
                'numberposts' => 1,
                'fields' => 'ids'
            ));
            
            if ( !empty($author_posts) ) {
                // Author has posts, filter by author
                $query['author'] = $author_id;
                $query['post_type'] = 'post';
            } else {
                // Author has no posts, show all posts
                $query['post_type'] = 'post';
                // Explicitly remove any author filtering
                unset($query['author']);
            }
        } else {
            // No linked author found, show all posts
            $query['post_type'] = 'post';
            // Explicitly remove any author filtering
            unset($query['author']);
        }
    }
    
    return $query;
}, 10, 3 );


add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
	'key' => 'group_690a7990a6248',
	'title' => 'Global Scripts',
	'fields' => array(
		array(
			'key' => 'field_690a7a0ecc59d',
			'label' => 'Head Scripts',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'selected' => 0,
		),
		array(
			'key' => 'field_690a7991e19f8',
			'label' => 'Head Scripts',
			'name' => 'head_scripts',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => 36,
			'placeholder' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_690a7a2dcc59e',
			'label' => 'Body Scripts',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'selected' => 0,
		),
		array(
			'key' => 'field_690a79ebe19fb',
			'label' => 'Body Scripts',
			'name' => 'body_scripts',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => 36,
			'placeholder' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_690a7a3ecc59f',
			'label' => 'Footer Scripts',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'selected' => 0,
		),
		array(
			'key' => 'field_690a79dce19f9',
			'label' => 'Footer Scripts',
			'name' => 'footer_scripts',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => 36,
			'placeholder' => '',
			'new_lines' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'header-footer-scripts',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
	'display_title' => '',
) );

	acf_add_local_field_group( array(
	'key' => 'group_690a86c65e680',
	'title' => 'Page Scripts',
	'fields' => array(
		array(
			'key' => 'field_690a86c665bd6',
			'label' => 'Head Scripts',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'selected' => 0,
		),
		array(
			'key' => 'field_690a86c665c48',
			'label' => 'Head Scripts',
			'name' => 'page_head_scripts',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => '',
			'placeholder' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_690a86c665c98',
			'label' => 'Body Scripts',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'selected' => 0,
		),
		array(
			'key' => 'field_690a86c665d26',
			'label' => 'Body Scripts',
			'name' => 'page_body_scripts',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => '',
			'placeholder' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_690a86c665d7b',
			'label' => 'Footer Scripts',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
			'selected' => 0,
		),
		array(
			'key' => 'field_690a86c665db9',
			'label' => 'Footer Scripts',
			'name' => 'page_footer_scripts',
			'aria-label' => '',
			'type' => 'textarea',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'allow_in_bindings' => 0,
			'rows' => '',
			'placeholder' => '',
			'new_lines' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
	'display_title' => '',
) );
} );

add_action( 'acf/init', function() {

    if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page( array(
	'page_title' => 'Scripts',
	'menu_slug' => 'header-footer-scripts',
	'position' => '',
	'redirect' => false,
	'menu_icon' => array(
		'type' => 'dashicons',
		'value' => 'dashicons-editor-code',
	),
	'icon_url' => 'dashicons-editor-code',
) );
} );




/**
 * Insert custom text/code into the WordPress head section
 */
function insert_custom_head_content() {

    $head_scripts = get_field('head_scripts', 'option');
    $page_head_scripts = get_field('page_head_scripts', get_the_ID() );


    if ($head_scripts) {
        echo '<!-- Custom Head Scripts -->' . "\n";
        echo $head_scripts;
        echo "\n";
        echo $page_head_scripts;
        echo '<!-- End Custom Head Scripts -->' . "\n";
    }
}



/**
 * Insert custom text/code after the opening body tag
 */
function insert_custom_body_content() {

    $body_scripts = get_field('body_scripts', 'option');
    $page_body_scripts = get_field('page_body_scripts', get_the_ID() );
    if ($body_scripts) {
        echo $body_scripts;
        echo "\n";
        echo $page_body_scripts;
    }
}



/**
 * Insert custom text/code into the WordPress footer
 */
function insert_custom_footer_content() {

    $footer_scripts = get_field('footer_scripts', 'option');
    $page_footer_scripts = get_field('page_body_scripts', get_the_ID() );
    if ($footer_scripts) {
        echo $footer_scripts;
        echo "\n";
        echo $page_footer_scripts;
    }
}



if( is_plugin_active('advanced-custom-fields-pro/acf.php') ){
    // ACF Pro is active
    add_action('wp_head', 'insert_custom_head_content');
    add_action('wp_body_open', 'insert_custom_body_content');
    add_action('wp_footer', 'insert_custom_footer_content');
}





?>
