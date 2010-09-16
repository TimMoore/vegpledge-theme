<?php

function init_vegpledge() {
  remove_action('thematic_header', 'thematic_blogtitle', 3);
  remove_action('thematic_header', 'thematic_blogdescription', 5);

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
<div id="top"></div>
<p id="vegpledge-gallery-intro">
    Take a closer look at the food choices you make everyday &mdash; what
    impact on the environment are you having? Join the VegPledge to make it a
    positive one.
</p>
<ul id="vegpledge-gallery">
<?php foreach (vegpledge_pledge_names() as $pledge_id => $pledge) { ?>
    <li><a class="pledge-<?php echo $pledge_id ?>" href="#pledge-<?php echo $pledge_id ?>"><?php echo esc_html($pledge) ?></a></li>
<?php } ?>
</ul>
<?php
  }
}
add_action('thematic_aboveheader', 'vegpledge_gallery');

function vegpledge_blogtitle() {
?>
<div class="header-top-wrapper">
    <div class="blog-title">
        <a href="<?php bloginfo('url') ?>/#top" title="<?php bloginfo('name') ?>" rel="home">VegPledge/VegOut</a>
    </div>
    <?php if (is_front_page()) { ?>
    <div class='vegpledge-my-pledges'>
        Choose your pledges below.
    </div>
    <?php } ?>
</div>
<?php
}
add_action('thematic_header', 'vegpledge_blogtitle', 3);

function vegpledge_menu() {
?>
<div class="menu">
<ul class="sf-menu">
<li class="page_item"><a href="/#about" title="About VegPledge">About</a></li>
  <li class="page_item"><a href="/#pledge" title="Make Your Pledge">Pledge</a></li>
  <li class="page_item"><a href="/#picnic" title="VegOut at the VegPledge Picnic">Picnic</a></li>
  <li class="page_item"><a href="/#vegpacks" title="Buy a Veg Pack Lunch Box for the Picnic">VegPacks</a></li>
  <li class="page_item"><a href="/#contact" title="Contact VegPledge">Contact</a></li>
</ul>
</div>
<?php
}
add_filter('wp_page_menu', 'vegpledge_menu');

function vegpledge_get_all_pledges() {
    $pledges = array();
    $pledge_labels = vegpledge_pledge_ticker_labels();
    $pledge_comments = get_approved_comments(12);
    foreach ($pledge_comments as $pledge_comment) {
        foreach (get_comment_meta($pledge_comment->comment_ID, 'vegpledge') as $pledge) {
            $pledges[] = $pledge_comment->comment_author . ' pledged to ' . $pledge_labels[$pledge];
        }
    }
    shuffle($pledges);
    return $pledges;
}

function vegpledge_ticker() {
    global $pledges;
    $pledges = vegpledge_get_all_pledges();
?>
<div class="vegpledge-ticker">
  <span class="random-pledge"><?php echo $pledges[0] ?></span>
  <div class="vegpledge-counter"><a href="/pledges/"><?php echo count($pledges) ?> Pledges</a></div>
</div>
<?php
}

add_action('thematic_header', 'vegpledge_ticker', 6);

function vegpledge_ticker_pledges() {
    global $pledges;
?>
    <ul id="vegpledge-ticker-pledges">
      <?php foreach ($pledges as $pledge) { ?>
          <li><?php echo esc_html($pledge) ?></li>
      <?php } ?>
    </ul>
<?php
}

add_action('thematic_belowheader', 'vegpledge_ticker_pledges');

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

function vegpledge_pledge_ticker_labels() {
    return array(
        'bottle' => 'use a reusable drink bottle',
        'containers' => 'use reusable containers',
        'bags' => 'bring reusable shopping bags',
        'local' => 'buy locally',
        'veg' => 'eat more veggo meals',
        'seafood' => 'choose sustainable seafood options',
        'garden' => 'start a veggie garden',
        'mug' => 'take a reusable mug',
        'organic' => 'buy organic products',
        'trip' => 'plan ahead and save a trip',
        'packaging' => 'purchase products with sustainable packaging',
        'transport' => 'use sustainable transport',
        'cooking' => 'do more cooking at home',
        'herbs' => 'grow herbs at home',
        'venues' => 'support venues with sustainable menus'
    );
}

function vegpledge_add_comment_pledges($comment_id) {
    $pledges = array();
    foreach (vegpledge_pledge_names() as $pledge_id => $pledge) {
        if ($_POST[$pledge_id]) {
            add_comment_meta($comment_id, 'vegpledge', $pledge_id);
            $pledges[] = $pledge;
        }
    }
    if ($_POST['subscribe']) {
        add_comment_meta($comment_id, 'vegpledge_subscribe', 1);
        $comment = get_comment($comment_id);
        $email = $comment->comment_author_email;
        if (vegpledge_notify_pledger($email, $pledges)) {
            add_comment_meta($comment_id, 'vegpledge_notified', 1);
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
    <span class="comment-author vcard"><?php thematic_commenter_link() ?></span>
    <span class="comment-meta">
<?php
    printf(__('Pledged %1$s at %2$s <span class="meta-sep">|</span> <a href="%3$s" title="Link to This VegPledge">Link</a>', 'thematic'),
        get_comment_date(),
        get_comment_time(),
        '#comment-' . get_comment_ID() );
        edit_comment_link(__('Edit', 'thematic'), ' <span class="meta-sep">|</span> <span class="edit-link">', '</span>');
?>
    </span>
<?php
    if ($comment->comment_approved == '0') _e("\t\t\t\t\t<span class='unapproved'>Your comment is awaiting moderation.</span>\n", 'thematic')
?>
    <div id="vegpledge-list-pledges">
        <ul>
<?php foreach (vegpledge_get_comment_pledges(get_comment_ID()) as $pledge_id => $pledge) : ?>
            <li class="mini-pledge mini-pledge-<?php echo $pledge_id ?>">
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

function vegpledge_print_share_form() {
    if (!is_front_page()) return;
?>
                    <div id="vegpledge-share-form">
                        <h3>Make Your VegPledge</h3>

                        <div class="formcontainer">
                            <?php thematic_abovecommentsform() ?>

                            <form id="commentform" action="<?php bloginfo('stylesheet_directory'); ?>/vegpledge-post.php" method="post">

    <?php if ( $user_ID ) : ?>
                                <p id="login"><?php printf(__('<span class="loggedin">Logged in as <a href="%1$s" title="Logged in as %2$s">%2$s</a>.</span> <span class="logout"><a href="%3$s" title="Log out of this account">Log out?</a></span>', 'thematic'),
                                    get_option('siteurl') . '/wp-admin/profile.php',
                                    wp_specialchars($user_identity, true),
                                    wp_logout_url(get_permalink()) ) ?></p>
    <?php else : ?>
                                <div id="form-section-author" class="form-section">
                                    <div class="form-label"><label for="author"><?php _e('Name', 'thematic') ?></label> <?php if ($req) _e('<span class="required">*</span>', 'thematic') ?></div>
                                    <div class="form-input"><input id="author" name="author" type="text" value="<?php echo $comment_author ?>" size="30" maxlength="20" /></div>
                                </div><!-- #form-section-author .form-section -->

                                <div id="form-section-email" class="form-section">
                                    <div class="form-label"><label for="email"><?php _e('Email', 'thematic') ?></label> <?php if ($req) _e('<span class="required">*</span>', 'thematic') ?></div>
                                    <div class="form-input"><input id="email" name="email" type="text" value="<?php echo $comment_author_email ?>" size="30" maxlength="50" /></div>
                                </div><!-- #form-section-email .form-section -->

                                <div id="form-section-url" class="form-section">
                                    <div class="form-label"><label for="url"><?php _e('Website', 'thematic') ?></label></div>
                                    <div class="form-input"><input id="url" name="url" type="text" value="<?php echo $comment_author_url ?>" size="30" maxlength="50" /></div>
                                </div><!-- #form-section-url .form-section -->
    <?php endif /* if ( $user_ID ) */ ?>

                                <div id="vegpledge-choose-pledges" class="form-section">
                                    <ul>
                                        <li></li>
                                    </ul>
                                </div>

                                <div id="form-section-comment" class="form-section">
                                    <div class="form-label"><label for="comment">Invent Your Own VegPledge or Add a Comment</label></div>
                                    <div class="form-textarea"><textarea id="comment" name="comment" cols="45" rows="8"></textarea></div>
                                </div><!-- #form-section-comment .form-section -->

                                <?php do_action('comment_form', 12); ?>

                                <input type="hidden" name="comment_post_ID" value="12" />

                                <div class="form-submit">
                                    <input id="subscribe" class="checkbox" name="subscribe" type="checkbox" checked="checked" /> <label for="subscribe">Subscribe to the Announcement List</label>
                                    <button type="submit">Share My VegPledge</button>
                                </div>

                            </form><!-- #commentform -->

                            <?php thematic_belowcommentsform() ?>
                        </div><!-- .formcontainer -->
                    </div><!-- #vegpledge-share-form -->
<?php
}
add_action('thematic_belowfooter', 'vegpledge_print_share_form');

function vegpledge_notify_pledger($email, $pledges) {
    $subject = 'Thanks for signing up to take the VegPledge Challenge!';
    $from = "From: \"VegPledge\" <info@vegpledge.org>\n";
    $pledge_text = join("\n", $pledges);
    $message = <<<EOM
What a legend! Thanks for signing up to take the VegPledge
Challenge with us.

Sustainable food choices are some of the most significant
you can make to reduce your personal carbon footprint so
we hope that the positive changes you make during the
challenge are ones you choose to adopt permanently.
Good luck!

You've signed up to take on the following VegPledges:

$pledge_text

----------------------------------------------------------
The VegPledge Challenge kicks off on Monday 27th September
and finishes Sunday the 10th October.

VEGOUT

Part of banding together as a community and getting to
work on practical solutions to the climate crisis is
celebrating our achievements. The VegOut picnic event on
Sunday 10/10/10 at Centennial Park will be an opportunity
for pledge participants and friends to relax in a
beautiful environment, sharing our VegPledge experiences
and eating a yummy climate friendly picnic. We hope you
will join us!

With an emphasis on sustainable food we welcome BYO
organic, vegetarian, vegan, local and home made picnics.

Veg Pack lunch boxes will also be available for
pre-purchase (http://www.vegpledge.org/#vegpacks). There
are four savoury varieties and one sweet pack to choose
from, prepared by four of Sydney’s wonderful sustainable
food venues.

Please bring your own beverages in reusable or recyclable
containers.

THE DETAILS:

When: Sunday 10th October 2010

Time: 12 noon ’til 4

Photo op: To further celebrate our involvement in
350.org’s Global Work/Party campaign and as a reminder of
the cause, we will be configuring our picnic blankets in a
350 format and attempting to take a photo for the 350.org
website.

Where: Sydney’s Centennial Parklands - Lachlans Reserve,
south of Dickens Dr.

See you there!
----------------------------------------------------------
Live out of the area or can't make it to VegOut?

Why not organise your own VegOut picnic? Encourage your
friends and family to sign up to the VegPledge Challenge
and then get together in a park on the 10/10/10 for your
own environmentally friendly pot-luck picnic. Take some
pics with 350 incorporated (make some posters or get
creative) and send them to us!

EOM;
    error_log("To: $email\n$from\nSubject: $subject\n\n$message", 3, "/home/vegpledge/mail.log");
    return wp_mail($email, $subject, $message, $from);
}
?>