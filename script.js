jQuery(document).ready(function($) {
    var $wrapper = $('#wrapper'),
        $header = $('#header'),
        $gallery = $('#vegpledge-gallery'),
        $floatingHeader = $header.clone()
            .attr('id', 'top-header')
            .addClass('header')
            .css({
                position: 'fixed',
                width: $header.width(),
            }),
        pledgeTicker = $('#vegpledge-ticker-pledges li').map(function() {
            return this.textContent;
        }).toArray();
        floatToBottom = function() {
            $floatingHeader.css({
                bottom: 0,
                top: 'auto',
                zIndex: 10
            })
        },
        floatToTop = function() {
            $floatingHeader.css({
                bottom: 'auto',
                top: 0,
                zIndex: 10
            });
        },
        freeFloat = function() {
            $floatingHeader.css({
                zIndex: 1
            })
        },
        repositionHeaders = function() {
            var headerTop = $header.offset().top,
            windowTop = headerTop - $(window).scrollTop(),
            windowBottom = windowTop + $header.outerHeight();

            if (windowBottom < $(window).height()) {
                if (windowTop > 0) {
                    freeFloat();
                } else {
                    floatToTop();
                }
            } else {
                floatToBottom();
            }
        },
        nextPledgeTicker = function() {
            var nextPledge = pledgeTicker.pop();
            $('#random-pledge').fadeOut(function() {
                $(this).text(nextPledge).fadeIn();
            });
            pledgeTicker.unshift(nextPledge);
        };

        $(window).resize(function() {
            repositionHeaders();
        });

        $header.addClass('header');

        $wrapper.wrap('<div class="wrapper" />');
        $wrapper.before($floatingHeader);
        repositionHeaders();

        $(document).scroll(repositionHeaders);
        $(document).localScroll({
            hash: true,
            offset: -$header.outerHeight()
        });
        $.localScroll.hash();

        setInterval(nextPledgeTicker, 10000);
    });

