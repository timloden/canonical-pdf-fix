<?php
/**
 * Plugin Name:     Canonical PDF Fix
 * Plugin URI:      https://caweb.cdt.ca.gov
 * Description:     CAWeb fix for canonical pdf issue on large sites
 * Author:          Tim Loden
 * Author URI:      https://caweb.cdt.ca.gov
 * Text Domain:     canonical-pdf
 * Version:         1.0.0
 *
 * @package         Canonical_PDF_Fix
 */

// add custom page template

add_filter( 'theme_page_templates', 'pdf_canonical_template', 10, 4 );

function pdf_canonical_template( $post_templates, $wp_theme, $post, $post_type ) {

    $post_templates['page-pdf-canonical.php'] = __('pdf-canonical');

    return $post_templates;
}

// load the page template

add_filter( 'template_include', 'pdf_canonical_template_load' );

function pdf_canonical_template_load( $template ) {

    if(  get_page_template_slug() === 'page-pdf-canonical.php' ) {

        if ( $theme_file = locate_template( array( 'page-pdf-canonical.php' ) ) ) {
            $template = $theme_file;
        } else {
            $template = plugin_dir_path( __FILE__ ) . 'page-pdf-canonical.php';
        }
    }

    if($template == '') {
        throw new \Exception('No template found');
    }

    return $template;
}


// add query vars 

add_filter( 'query_vars', 'pdf_canonical_query_vars' );

function pdf_canonical_query_vars( $query_vars ) {
	$query_vars[] = 'file';
	return $query_vars;
}


// on plugin activation add page

register_activation_hook( __FILE__, 'pdf_canonical_page_install' );

function pdf_canonical_page_install() {
        $new_page_title = 'pdf-canonical';
        $new_page_content = '';
        $new_page_template = 'page-pdf-canonical.php';

        $page_check = get_page_by_title($new_page_title);
        $new_page = array(
                'post_type' => 'page',
                'post_title' => $new_page_title,
                'post_content' => $new_page_content,
                'post_status' => 'publish',
                'post_author' => 1,
        );
        
        if(!isset($page_check->ID)) {
               
                $new_page_id = wp_insert_post($new_page);
               
                if(!empty($new_page_template)){
                        update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
                }
        }
}


// on deactivation do all these things

register_deactivation_hook( __FILE__, 'pdf_canonical_deactivation' );

function pdf_canonical_deactivation() {

	$page = get_page_by_path( 'pdf-canonical' );
    wp_delete_post($page->ID, true);

}