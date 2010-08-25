jQuery(document).ready(function($) {
    var $wrapper = $('#wrapper'),
        $header = $('#header'),
        $gallery = $('#vegpledge-gallery'),
        $topHeader = $header.clone().attr('id', 'top-header').addClass('header'),
        resizeGallery = function() {
            $gallery.height($(window).height() - $header.outerHeight());
        },
        repositionHeaders = function() {
            var top = $header.offset().top,
                windowTop = top - $(window).scrollTop(),
                windowBottom = windowTop + $header.height();

            if (windowBottom < $(window).height()) {
                if (top < $topHeader.offset().top) {
                    $topHeader.css('zIndex', 10);
                } else {
                    $topHeader.css('zIndex', 1);
                }
            } else {
                $topHeader.css('zIndex', 1);
            }
        };

    resizeGallery();
    $(window).resize(function() {
        resizeGallery();
        repositionHeaders();
    });

    $header.addClass('header');

    $wrapper.wrap('<div class="wrapper" />');
    $topHeader.css({
        top: 0,
        position: 'fixed',
        width: $header.width(),
        zIndex: 1
    });
    $wrapper.before($topHeader);

    $(document).scroll(repositionHeaders);
    $('.header').localScroll({
        hash: true,
        offset: -$header.outerHeight()
    });
    $.localScroll.hash();
});

