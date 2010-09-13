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
?>
            </div><!-- #comments -->
<?php thematic_belowcomments() ?>