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
  if (is_front_page()) {
?>
<p id="vegpledge-gallery-intro">
    Take a closer look at the food choices you make everyday &mdash what
    impact on the environment are you having? Join the VegPledge to make it a
    positive one.
</p>
<ul id="vegpledge-gallery">
    <li>I’ll use re-usable shopping bags</li>
    <li>I’ll buy organic products</li>
    <li>I’ll save a trip and plan ahead</li>
    <li>I’ll use reusable containers not plastic/foil/paper wraps</li>
    <li>I’ll have at least one veggo day a week</li>
    <li>I’ll keep my food miles down and buy local products</li>
    <li>I’ll purchase products with minimal, sustainable packaging</li>
    <li>I’ll start a veggie garden and reap what I sow</li>
    <li>I’ll grow my own herbs</li>
    <li>I’ll eat at food venues with sustainable food menus and practices</li>
    <li>I’ll take a reusable mug when I buy take-away drinks</li>
    <li>I’ll choose more sustainable seafood options</li>
    <li>I’ll eat less packaged food</li>
    <li>I’ll refill a water bottle instead of buying a new one</li>
    <li>I’ll use sustainable transport (walk, cycle, public transport) to get to the shops</li>
</ul>
<?php
  }
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
<li class="page_item"><a href="#about" title="About VegPledge">About</a></li>
  <li class="page_item"><a href="#pledge" title="Make Your Pledge">Pledge</a></li>
  <li class="page_item"><a href="#picnic" title="VegOut at the VegPledge Picnic">Picnic</a></li>
  <li class="page_item"><a href="#vegpacks" title="Buy a Veg Pack Lunch Box for the Picnic">VegPacks</a></li>
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

function vegpledge_pledge_names() {
    return array(
        'I’ll use re-usable shopping bags',
        'I’ll buy organic products',
        'I’ll save a trip and plan ahead',
        'I’ll use reusable containers not plastic/foil/paper wraps',
        'I’ll have at least one veggo day a week',
        'I’ll keep my food miles down and buy local products',
        'I’ll purchase products with minimal, sustainable packaging',
        'I’ll start a veggie garden and reap what I sow',
        'I’ll grow my own herbs',
        'I’ll eat at food venues with sustainable food menus and practices',
        'I’ll take a reusable mug when I buy take-away drinks',
        'I’ll choose more sustainable seafood options',
        'I’ll eat less packaged food',
        'I’ll refill a water bottle instead of buying a new one',
        'I’ll use sustainable transport (walk, cycle, public transport) to get to the shops'
    );
}

?>
