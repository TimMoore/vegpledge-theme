<?php
require( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
$pledge_names = array(
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
$pledge_comments = get_approved_comments(12);
foreach ($pledge_comments as $pledge_comment) {
    if (!get_comment_meta($pledge_comment->comment_ID, 'vegpledge_notified', true)
        && get_comment_meta($pledge_comment->comment_ID, 'vegpledge_subscribe', true)) {
        $pledges = array();
        foreach (get_comment_meta($pledge_comment->comment_ID, 'vegpledge') as $pledge) {
            $pledges[] = $pledge_names[$pledge];
        }
        if (vegpledge_notify_orig_pledger($pledge_comment->comment_author_email, $pledges)) {
            add_comment_meta($pledge_comment->comment_ID, 'vegpledge_notified', 1);
        }
    }
}


function vegpledge_notify_orig_pledger($email, $pledges) {
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
    error_log("To: $email\n$from\nSubject: $subject\n\n$message=====\n", 3, "/home/vegpledge/mail.log");
    return wp_mail($email, $subject, $message, $from);
}

?>