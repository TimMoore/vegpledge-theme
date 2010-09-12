<?php

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
        'bottle' => 'I’ll refill a reusable drink bottle instead of buying a new one',
        'containers' => 'I’ll use reusable containers not foil, plastic or paper wrap',
        'bags' => 'I’ll use my own shopping bags',
        'local' => 'I’ll reduce my food miles and buy local',
        'veg' => 'I’ll eat more veggo meals and less meat',
        'seafood' => 'I’ll choose sustainable seafood options',
        'garden' => 'I’ll start a veggie garden and reap what I sow',
        'mug' => 'I’ll take a reusable mug when I buy take-away',
        'organic' => 'I will buy organic products',
        'trip' => 'I’ll plan ahead and save a trip',
        'packaging' => 'I’ll purchase products with minimal and sustainable packaging',
        'transport' => 'I’ll use sustainable transport to do my shopping',
        'cooking' => 'I’ll do more cooking at home',
        'herbs' => 'I’ll grow my own herbs',
        'venues' => 'I’ll support venues with sustainable food menus'
    );
}

function vegpledge_add_comment_pledges($comment_id) {
    foreach (vegpledge_pledge_names() as $pledge_id => $pledge) {
        if ($_POST[$pledge_id]) {
            add_comment_meta($comment_id, 'vegpledge', $pledge_id);
        }
    }
}
add_action('comment_post', 'vegpledge_add_comment_pledges', 1);

function vegpledge_get_comment_pledges($comment_id) {
    $pledges = get_comment_meta($comment_id, 'vegpledge');
    return array_intersect_key(vegpledge_pledge_names(), array_flip($pledges));
}

function vegpledge_print_pledge($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    $GLOBALS['comment_depth'] = $depth;
?>
<li id="comment-<?php comment_ID() ?>" class="<?php thematic_comment_class() ?>">
    <div class="comment-author vcard"><?php thematic_commenter_link() ?></div>
    <div class="comment-meta">
<?php
    printf(__('Pledged %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Permalink to this pledge">Permalink</a>', 'thematic'),
        get_comment_date(),
        get_comment_time(),
        '#comment-' . get_comment_ID() );
        edit_comment_link(__('Edit', 'thematic'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>');
?>
    </div>
<?php
    if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'thematic')
?>
    <div id="vegpledge-list-pledges">
        <ul>
<?php foreach (vegpledge_get_comment_pledges(get_comment_ID()) as $pledge_id => $pledge) : ?>
            <li id="pledge-<?php echo $pledge_id ?>">
                <?php echo esc_html($pledge) ?>
            </li>
<?php endforeach ?>
        </ul>
    </div>
    <div class="comment-content">
        <?php comment_text() ?>
    </div>
<?php
}

function vegpledge_list_comments_arg() {
    return 'type=comment&callback=vegpledge_print_pledge';
}
add_filter('list_comments_arg', 'vegpledge_list_comments_arg');
?>
