<?php
/**
 * Plugin Name: CBS Mods
 * Description: 
 * Version: 1.9
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
    $page_head_scripts = get_field('page_head_scripts', get_the_ID());

    if ($head_scripts || $page_head_scripts) {
        echo '<!-- Custom Head Scripts -->' . "\n";
        
        if ($head_scripts) {
            echo $head_scripts . "\n";
        }
        
        if ($page_head_scripts) {
            echo $page_head_scripts . "\n";
        }
        
        echo '<!-- End Custom Head Scripts -->' . "\n";
    }
}

/**
 * Insert custom text/code after the opening body tag
 */
function insert_custom_body_content() {
    $body_scripts = get_field('body_scripts', 'option');
    $page_body_scripts = get_field('page_body_scripts', get_the_ID());
    
    if ($body_scripts) {
        echo $body_scripts . "\n";
    }
    
    if ($page_body_scripts) {
        echo $page_body_scripts . "\n";
    }
}

/**
 * Insert custom text/code into the WordPress footer
 */
function insert_custom_footer_content() {
    $footer_scripts = get_field('footer_scripts', 'option');
    $page_footer_scripts = get_field('page_footer_scripts', get_the_ID()); // Fixed: was 'page_body_scripts'
    
    if ($footer_scripts) {
        echo $footer_scripts . "\n";
    }
    
    if ($page_footer_scripts) {
        echo $page_footer_scripts . "\n";
    }
}

if (is_plugin_active('advanced-custom-fields-pro/acf.php')) {
    add_action('wp_head', 'insert_custom_head_content');
    add_action('wp_body_open', 'insert_custom_body_content');
    add_action('wp_footer', 'insert_custom_footer_content');
}


// Ensure that Blog Posts Load in Advanced Query Lop
add_filter( 'rest_authentication_errors', function( $result ) {
    $route = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
    if ( strpos( $route, '/wp-json/wp/v2/kadence_query/query' ) !== false ) {
        return null;
    }
    return $result;
} );


/* -------------------------------------------------------------------------
 * 0. CONFIG  -- set your site base here (no trailing slash)
 * ---------------------------------------------------------------------- */
if ( ! defined( 'SITE_SCHEMA_BASE' ) ) {
	// Base URL is pulled from WordPress (Settings > General). No hardcoding needed.
	define( 'SITE_SCHEMA_BASE', untrailingslashit( home_url() ) );
}
 
/* Adjust these to match your registered post type + ACF field names */
if ( ! defined( 'TEAM_MEMBER_POST_TYPE' ) ) {
	define( 'TEAM_MEMBER_POST_TYPE', 'team_member' );
}
if ( ! defined( 'BLOG_AUTHOR_ACF_FIELD' ) ) {
	define( 'BLOG_AUTHOR_ACF_FIELD', 'link_to_team_member' );
}
 
/* -------------------------------------------------------------------------
 * 1. HELPERS
 * ---------------------------------------------------------------------- */
 
/**
 * Convert an ACF WYSIWYG / <ul> blob into a clean array of strings.
 * The export stores Expertise, Education, etc. as <ul><li>..</li></ul>.
 */
function sjld_html_list_to_array( $html ) {
	if ( empty( $html ) ) {
		return array();
	}
	$items = array();
	if ( preg_match_all( '/<li\b[^>]*>(.*?)<\/li>/is', $html, $m ) ) {
		foreach ( $m[1] as $li ) {
			$txt = trim( wp_strip_all_tags( html_entity_decode( $li, ENT_QUOTES, 'UTF-8' ) ) );
			$txt = preg_replace( '/\s+/u', ' ', $txt );
			if ( $txt !== '' ) {
				$items[] = $txt;
			}
		}
	}
	return $items;
}
 
/** Strip an ACF text field down to a clean scalar string. */
function sjld_clean_text( $val ) {
	if ( is_array( $val ) ) {
		$val = reset( $val );
	}
	$val = wp_strip_all_tags( (string) $val );
	$val = html_entity_decode( $val, ENT_QUOTES, 'UTF-8' );
	// Normalise smart quotes the export uses around the Quote field.
	$val = trim( $val, " \t\n\r\0\x0B\"'“”‘’" );
	return preg_replace( '/\s+/u', ' ', trim( $val ) );
}
 
/** Resolve the canonical Person @id for a given team member post ID. */
function sjld_person_id( $team_member_id ) {
	return SITE_SCHEMA_BASE . '/#person-' . (int) $team_member_id;
}
 
/**
 * Best-effort first/last name split from a display title.
 * Keeps any trailing credential suffix (e.g. "CPA, CGMA") out of the family name.
 */
function sjld_split_name( $full ) {
	$full  = trim( $full );
	$parts = preg_split( '/\s+/', $full );
	$given = array_shift( $parts );
	$family = $parts ? implode( ' ', $parts ) : '';
	return array( $given, $family );
}
 
/* -------------------------------------------------------------------------
 * 2. BUILD PERSON SCHEMA (array) FROM A TEAM MEMBER POST
 * ---------------------------------------------------------------------- */
 
/**
 * @param int  $team_member_id  Team Member post ID.
 * @param bool $reference        If true, return only a lightweight node reference
 *                               ({@id, name}) for embedding inside BlogPosting.
 * @return array|null
 */
function sjld_build_person_schema( $team_member_id, $reference = false ) {
 
	$team_member_id = (int) $team_member_id;
	if ( ! $team_member_id || get_post_status( $team_member_id ) !== 'publish' ) {
		// Still allow non-publish in admin/preview, but bail if no post at all.
		if ( ! get_post( $team_member_id ) ) {
			return null;
		}
	}
 
	$person_id = sjld_person_id( $team_member_id );
	$name      = sjld_clean_text( get_the_title( $team_member_id ) );
 
	if ( $reference ) {
		return array(
			'@type' => 'Person',
			'@id'   => $person_id,
			'name'  => $name,
			'url'   => get_permalink( $team_member_id ),
		);
	}
 
	list( $given, $family ) = sjld_split_name( $name );
 
	// ACF fields (use get_field if available, fall back to post meta).
	$get = function ( $key ) use ( $team_member_id ) {
		if ( function_exists( 'get_field' ) ) {
			return get_field( $key, $team_member_id );
		}
		return get_post_meta( $team_member_id, $key, true );
	};
 
	$job_title = sjld_clean_text( $get( 'position_title' ) ); // "Position/Title"
	$linkedin  = esc_url_raw( trim( (string) $get( 'linkedin_url' ) ) );
	$quote     = sjld_clean_text( $get( 'quote' ) );
 
	$expertise   = sjld_html_list_to_array( $get( 'expertise' ) );
	$education    = sjld_html_list_to_array( $get( 'education' ) );
	$industry     = sjld_html_list_to_array( $get( 'industry_experience' ) );
	$professional = sjld_html_list_to_array( $get( 'professional_experience' ) );
	$associations = sjld_html_list_to_array( $get( 'associations' ) );
	$affiliations = sjld_html_list_to_array( $get( 'affiliations' ) );
 
	$schema = array(
		'@type' => 'Person',
		'@id'   => $person_id,
		'name'  => $name,
		'url'   => get_permalink( $team_member_id ),
	);
 
	if ( $given ) {
		$schema['givenName'] = $given;
	}
	if ( $family ) {
		$schema['familyName'] = $family;
	}
	if ( $job_title ) {
		$schema['jobTitle'] = $job_title;
	}
 
	// Headshot -> Person.image
	$thumb = get_the_post_thumbnail_url( $team_member_id, 'full' );
	if ( $thumb ) {
		$schema['image'] = $thumb;
	}
 
	// LinkedIn -> sameAs
	if ( $linkedin ) {
		$schema['sameAs'] = array( $linkedin );
	}
 
	// Quote -> description
	if ( $quote ) {
		$schema['description'] = $quote;
	}
 
	// knowsAbout = expertise + industry experience (Schema.org supported property)
	$knows_about = array_values( array_unique( array_merge( $expertise, $industry ) ) );
	if ( $knows_about ) {
		$schema['knowsAbout'] = $knows_about;
	}
 
	// Education -> alumniOf (EducationalOrganization nodes)
	if ( $education ) {
		$schema['alumniOf'] = array_map(
			function ( $e ) {
				return array(
					'@type' => 'EducationalOrganization',
					'name'  => $e,
				);
			},
			$education
		);
	}
 
	// Associations / Affiliations -> memberOf (Organization nodes)
	$member_of = array_values( array_unique( array_merge( $associations, $affiliations ) ) );
	if ( $member_of ) {
		$schema['memberOf'] = array_map(
			function ( $o ) {
				return array(
					'@type' => 'Organization',
					'name'  => $o,
				);
			},
			$member_of
		);
	}
 
	// Professional experience -> hasOccupation
	if ( $professional ) {
		$schema['hasOccupation'] = array_map(
			function ( $p ) {
				return array(
					'@type' => 'Occupation',
					'name'  => $p,
				);
			},
			$professional
		);
	}
 
	// worksFor -> your organisation (publisher)
	$schema['worksFor'] = array(
		'@type' => 'Organization',
		'@id'   => SITE_SCHEMA_BASE . '/#organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => SITE_SCHEMA_BASE,
	);
 
	return $schema;
}
 
/* -------------------------------------------------------------------------
 * 3. BUILD BLOGPOSTING SCHEMA (array) FROM A POST
 * ---------------------------------------------------------------------- */
 
function sjld_build_blogposting_schema( $post_id ) {
 
	$post_id = (int) $post_id;
	$post    = get_post( $post_id );
	if ( ! $post ) {
		return null;
	}
 
	$permalink = get_permalink( $post_id );
 
	// --- Resolve author: ACF link_to_team_member, "both linked together" ---
	$author_node = null;
	$tm = function_exists( 'get_field' )
		? get_field( BLOG_AUTHOR_ACF_FIELD, $post_id )
		: get_post_meta( $post_id, BLOG_AUTHOR_ACF_FIELD, true );
 
	// ACF may return a WP_Post, an ID, or an array of either.
	if ( is_array( $tm ) ) {
		$tm = reset( $tm );
	}
	if ( $tm instanceof WP_Post ) {
		$tm = $tm->ID;
	}
	$tm = (int) $tm;
 
	if ( $tm ) {
		// Embed a reference; the full Person node lives in the @graph too (see output fn).
		$author_node = sjld_build_person_schema( $tm, true );
	} else {
		// Fallback to native WP author.
		$author_node = array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', $post->post_author ),
			'url'   => get_author_posts_url( $post->post_author ),
		);
	}
 
	$schema = array(
		'@type'            => 'BlogPosting',
		'@id'              => $permalink . '#blogposting',
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => $permalink,
		),
		'headline'         => wp_strip_all_tags( get_the_title( $post_id ) ),
		'url'              => $permalink,
		'datePublished'    => get_the_date( 'c', $post_id ),
		'dateModified'     => get_the_modified_date( 'c', $post_id ),
		'author'           => $author_node,
		'publisher'        => array(
			'@type' => 'Organization',
			'@id'   => SITE_SCHEMA_BASE . '/#organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => SITE_SCHEMA_BASE,
			'logo'  => array(
				'@type' => 'ImageObject',
				// TODO: point at your real logo if not using a custom logo.
				'url'   => ( function_exists( 'get_custom_logo' ) && get_theme_mod( 'custom_logo' ) )
					? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' )
					: SITE_SCHEMA_BASE . '/logo.png',
			),
		),
	);
 
	// Featured image -> image
	$img = get_the_post_thumbnail_url( $post_id, 'full' );
	if ( $img ) {
		$schema['image'] = array( $img );
	}
 
	// Excerpt -> description
	$excerpt = has_excerpt( $post_id )
		? get_the_excerpt( $post_id )
		: wp_trim_words( wp_strip_all_tags( $post->post_content ), 40 );
	if ( $excerpt ) {
		$schema['description'] = trim( $excerpt );
	}
 
	// Categories -> articleSection
	$cats = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'names' ) );
	if ( ! is_wp_error( $cats ) && $cats ) {
		$schema['articleSection'] = $cats;
	}
 
	// Keywords from tags
	$tags = wp_get_post_terms( $post_id, 'post_tag', array( 'fields' => 'names' ) );
	if ( ! is_wp_error( $tags ) && $tags ) {
		$schema['keywords'] = implode( ', ', $tags );
	}
 
	$schema['wordCount'] = str_word_count( wp_strip_all_tags( $post->post_content ) );
 
	return array( $schema, $tm ); // return tm id so caller can add full Person node
}
 
/* -------------------------------------------------------------------------
 * 4. OUTPUT TO <head> VIA wp_head
 * ---------------------------------------------------------------------- */
 
function sjld_print_jsonld( $data ) {
	if ( empty( $data ) ) {
		return;
	}
	$graph = array(
		'@context' => 'https://schema.org',
		'@graph'   => $data,
	);
	echo "\n<script type=\"application/ld+json\">\n";
	echo wp_json_encode( $graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
	echo "\n</script>\n";
}
 
add_action( 'wp_head', 'sjld_output_schema', 20 );
function sjld_output_schema() {
 
	// --- Single blog post ---
	if ( is_singular( 'post' ) ) {
		$post_id = get_queried_object_id();
		list( $blogposting, $tm_id ) = sjld_build_blogposting_schema( $post_id );
 
		$graph = array();
		if ( $blogposting ) {
			$graph[] = $blogposting;
		}
		// Include the FULL Person node so the author reference resolves in-page.
		if ( $tm_id ) {
			$person = sjld_build_person_schema( $tm_id, false );
			if ( $person ) {
				$graph[] = $person;
			}
		}
		sjld_print_jsonld( $graph );
		return;
	}
 
	// --- Single team member page ---
	if ( is_singular( TEAM_MEMBER_POST_TYPE ) ) {
		$person = sjld_build_person_schema( get_queried_object_id(), false );
		sjld_print_jsonld( $person ? array( $person ) : array() );
		return;
	}
}




?>
