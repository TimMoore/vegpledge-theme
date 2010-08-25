<?php

//
//  Custom Child Theme Functions
//

// I've included a "commented out" sample function below that'll add a home link to your menu
// More ideas can be found on "A Guide To Customizing The Thematic Theme Framework" 
// http://themeshaper.com/thematic-for-wordpress/guide-customizing-thematic-theme-framework/

// Adds a home link to your menu
// http://codex.wordpress.org/Template_Tags/wp_page_menu
//function childtheme_menu_args($args) {
//    $args = array(
//        'show_home' => 'Home',
//        'sort_column' => 'menu_order',
//        'menu_class' => 'menu',
//        'echo' => true
//    );
//	return $args;
//}
//add_filter('wp_page_menu_args','childtheme_menu_args');

function init_vegpledge() {
  remove_action('thematic_header', 'thematic_blogtitle', 3);
  if (!is_admin()) {
    $script_dir = get_bloginfo('stylesheet_directory');
    wp_register_script('jquery-scroll-to', $script_dir . '/jquery.scrollTo-1.4.2-min.js', array('jquery'), '1.4.2');
    wp_register_script('jquery-local-scroll', $script_dir . '/jquery.localscroll-1.2.7-min.js', array('jquery', 'jquery-scroll-to'), '1.2.7');
    wp_register_script('vegpledge', $script_dir . '/script.js', array('jquery', 'jquery-local-scroll'), '1.0');
    wp_enqueue_script('vegpledge');
  }
}
add_action('init', 'init_vegpledge');

function vegpledge_gallery() {
?>
<div id="vegpledge-gallery"></div>
<?php
}
add_action('thematic_aboveheader', 'vegpledge_gallery');

function vegpledge_blogtitle() {
?>
<div class="blog-title"><span><a href="<?php bloginfo('url') ?>/#vegpledge-gallery" title="<?php bloginfo('name') ?>" rel="home"><span class="veg">Veg</span><span class="pledge">Pledge</span>/<span class="veg">Veg</span><span class="out">Out</span></a></span></div>
<?php
}
add_action('thematic_header', 'vegpledge_blogtitle', 3);

function vegpledge_menu() {
?>
<div class="menu">
<ul class="sf-menu">
  <li class="page_item"><a href="#pledge" title="Make Your Pledge">Pledge</a></li>
  <li class="page_item"><a href="#about" title="About VegPledge">About</a></li>
  <li class="page_item"><a href="#recipes" title="Share Recipes">Recipes</a></li>
  <li class="page_item"><a href="#contact" title="Contact VegPledge">Contact</a></li>
</ul>
</div>
<?php
}
add_filter('wp_page_menu', 'vegpledge_menu');

function vegpledge_ticker() {
?>
<div id="vegpledge-ticker">
  My pledge is to...
  <div id="vegpledge-counter"># Pledges</div>
</div>
<?php
}

add_action('thematic_header', 'vegpledge_ticker', 6);

?>
