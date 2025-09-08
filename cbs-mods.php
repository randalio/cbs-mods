<?php
/**
 * Plugin Name: CBS Mods
 * Description: 
 * Version: 1.5
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
            $query['author'] = $linked_author[0];
            $query['post_type'] = 'post';
        } else {
            $query['post__in'] = array(0); // No results
        }

    }
    
    return $query;
 }, 10, 3 );

?>