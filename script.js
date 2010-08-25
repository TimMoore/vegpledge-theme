jQuery(document).ready(function($) {
    var $wrapper = $('#wrapper'),
        $header = $('#header'),
        $gallery = $('#vegpledge-gallery'),
        $bottomHeader = $header.clone().attr('id', 'bottom-header').addClass('header'),
        $topHeader = $header.clone().attr('id', 'top-header').addClass('header'),
        resizeGallery = function() {
            $gallery.height($(window).height());
        },
        repositionHeaders = function() {
            var top = $header.offset().top;
            if (top < $bottomHeader.offset().top) {
                $bottomHeader.css('zIndex', 1);
                if (top < $topHeader.offset().top) {
                    $topHeader.css('zIndex', 10);
                } else {
                    $topHeader.css('zIndex', 1);
                }
            } else {
                $bottomHeader.css('zIndex', 10);
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
        zIndex: 1
    });
    $bottomHeader.css({
        bottom: 0,
        zIndex: 10
    });
    $.each([$topHeader, $bottomHeader], function(i, val) {
        val.css({
            position: 'fixed',
            width: $header.width()
        })
        .insertBefore($wrapper);
    });

    $(document).scroll(repositionHeaders);
    $('.header').localScroll({
        hash: true,
        offset: -$header.outerHeight()
    });
    $.localScroll.hash();
});

