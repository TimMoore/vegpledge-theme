<?php thematic_abovecomments() ?>
            <div id="comments">
<?php
$req = get_option('require_name_email'); // Checks if fields are required.
if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
    die ( 'Please do not load this page directly. Thanks!' );
?>

<?php
if ( have_comments() ) {
    if ( ! empty($comments_by_type['comment']) ) {
        thematic_abovecommentslist();
?>
                <div id="pledge-list" class="comments">
                    <ol>
                        <?php wp_list_comments(list_comments_arg()); ?>
                    </ol>

                    <div id="comments-nav-below" class="comment-navigation">
                         <div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>
                    </div>

                </div><!-- #comments-list .comments -->
<?php
        thematic_belowcommentslist();
    } /* if ( ! empty($comments_by_type['comment']) ) */
} /* if ( have_comments ) */

if ( 'open' == $post->comment_status ) :
?>
                <div id="respond">
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
<?php foreach (vegpledge_pledge_names() as $pledge_id => $pledge) : ?>
                                    <li id="pledge-<?php echo $pledge_id ?>">
                                        <input
                                            id="pledge-<?php echo $pledge_id ?>-checkbox"
                                            class="checkbox"
                                            type="checkbox"
                                            name="<?php echo $pledge_id ?>">
                                        <label for="pledge-<?php echo $pledge_id ?>-checkbox"><?php echo esc_html($pledge) ?></label>
                                    </li>
<?php endforeach ?>
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
                                <input id="submit" name="submit" type="submit" value="Share My VegPledge" />
                            </div>

                        </form><!-- #commentform -->

                        <?php thematic_belowcommentsform() ?>
                    </div><!-- .formcontainer -->
                </div><!-- #respond -->
<?php endif /* if ( 'open' == $post->comment_status ) */ ?>

            </div><!-- #comments -->
<?php thematic_belowcomments() ?>