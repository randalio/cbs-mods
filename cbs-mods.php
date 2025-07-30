<?php
/**
 * Plugin Name:CBS Mods
 * Description: 
 * Version: 1.0.0
 * Author: Your Name
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


function vm_bubble_blocks_register() {
    // Automatically register blocks by scanning the build directory
    $blocks_dir = plugin_dir_path(__FILE__) . 'build';
    
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
add_action('init', 'vm_bubble_blocks_register');

// Add debugging notice
function vm_bubble_blocks_admin_notice() {
    $screen = get_current_screen();
    if ($screen && $screen->base === 'post') {
        echo '<div class="notice notice-info is-dismissible"><p>VM Bubble Blocks plugin is active. Search for "VM Bubble" in the block inserter.</p></div>';
    }
}
add_action('admin_notices', 'vm_bubble_blocks_admin_notice');




function change_kadence_blocks_variable_font_sizes() {
  $arr = array(
    'sm'   => 'clamp(0.8rem, 0.73rem + 0.217vw, 0.9rem)',
    'md'   => 'clamp(1.1rem, 0.995rem + 0.326vw, 1.313rem)',
    'lg'   => 'clamp(1.75rem, 1.576rem + 0.543vw, 1.75rem)',
    'xl'   => 'clamp(2rem, 1.728rem + 1.63vw, 2rem)',
    'xxl'  => 'clamp(2.5rem, 1.456rem + 3.26vw, 4rem)',
    'xxxl' => 'clamp(2.75rem, 0.489rem + 7.065vw, 6rem)',
  );
  return $arr;
}
add_filter('kadence_blocks_variable_font_sizes','change_kadence_blocks_variable_font_sizes', 15 );

