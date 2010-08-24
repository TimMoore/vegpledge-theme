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

function unhook_thematic_functions() {
  remove_action('thematic_header', 'thematic_blogtitle', 3);
}
add_action('init', 'unhook_thematic_functions');

function vegpledge_blogtitle() {
?>
<div id="blog-title"><span><a href="<?php bloginfo('url') ?>/" title="<?php bloginfo('name') ?>" rel="home"><span class="veg">Veg</span><span class="pledge">Pledge</span>/<span class="veg">Veg</span><span class="out">Out</span></a></span></div>
<?php
}
add_action('thematic_header', 'vegpledge_blogtitle', 3);


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
