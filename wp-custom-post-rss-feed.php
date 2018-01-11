<?php
/*
Plugin Name: PG Custom RSS Feed
Plugin URI: https://github.com/pioniergarage/wp-custom-feed
Description: Plugin helps to generate xml feeds of post/page/custom post.  
Author: Dominic Seitz
Version: 1.0.1
Author URI: https://github.com/dome4
*/


define('WPCPRF_PAGE_DIR', plugin_dir_path(__FILE__).'pages/');

// Include plugin settings page in wordpress settings
function wpcprf_plugin_menu() {

    add_options_page( "Custom RSS Feed", "Custom RSS Feed", "administrator", "wpcpf-settings-page", "wpcprf_plugin_pages");
}

add_action("admin_menu", "wpcprf_plugin_menu");

function wpcprf_plugin_pages() {

   $itm = WPCPRF_PAGE_DIR.$_GET["page"].'.php';
   include($itm);
}


//set feed template to page
add_filter( 'page_template', 'feed_page_template' );
function feed_page_template( $page_template )
{  
    if ( is_page( 'pg-rss' ) ) {
        $page_template = plugin_dir_path(__FILE__) . 'pages/custom-feed.php';
    }
    return $page_template;
}



//create feed page start
function check_page_feed_page_exist(){
	if( get_page_by_title( 'pg-rss' ) == NULL ){
	 create_pages_feed( 'pg-rss' );
	}
}

 
add_action('init','check_page_feed_page_exist');
function create_pages_feed($pageName) {
	$createPage = array(
	  'post_title'    => $pageName,
	  'post_content'  => 'PG Custom Feed Page',
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'     => 'page',
	  'post_name'     => $pageName
	);

	// Insert the post into the database
	wp_insert_post( $createPage );
}

//create feed page end

//add admin css
function wpcprf_admin_css() {
  wp_register_style('wpcprf_admin_css', plugins_url('includes/feed-admin-style.css',__FILE__ ));
  wp_enqueue_style('wpcprf_admin_css');
}
add_action( 'admin_init','wpcprf_admin_css' );

?>