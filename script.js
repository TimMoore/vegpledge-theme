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
        }).toArray(),
        myPledges = [],
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
            $('.random-pledge').fadeOut(function() {
                $(this).text(nextPledge).fadeIn();
            });
            pledgeTicker.unshift(nextPledge);
        },
        updateMyPledges = function() {
            var label = myPledges.length > 0 ?
                ('You have made ' + myPledges.length + ' ' +
                    (myPledges.length > 1 ? 'pledges' : 'pledge') + '.') :
                'Choose your pledges below.';
            $('.vegpledge-my-pledges').html(label)
                .each(function() {
                    // $('<a href="#">Share</a>').click(function() {
                    //     alert('TBD');
                    //     return false;
                    // }).appendTo(this);
                });
        },
        addToMyPledges = function(pledge) {
            if ($.inArray(pledge, myPledges) > -1) return;
            myPledges.push(pledge);
            updateMyPledges();
        },
        removeFromMyPledges = function(pledge) {
            var index = $.inArray(pledge, myPledges);
            if (index > -1) {
                myPledges.splice(index, 1);
            }
            updateMyPledges();
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
        $.localScroll.hash({offset: -$header.outerHeight()});

        setInterval(nextPledgeTicker, 10000);

        $('table#pledge-descriptions tr:first-child').append('<td/>');
        $('table#pledge-descriptions tr:not(:first-child)').each(function() {
            var $row = $(this),
                pledgeId = $row.attr('id').replace('pledge-', '');
            $row.append($('<td class="toggle-vegpledge"/>').append(
                $('<a href="#">Make This Pledge</a>').toggle(function() {
                    addToMyPledges(pledgeId);
                    $(this).text('Undo');
                    return false;
                }, function() {
                    removeFromMyPledges(pledgeId)
                    $(this).text('Make This Pledge');
                    return false;
                })
            ));
        });
    });

