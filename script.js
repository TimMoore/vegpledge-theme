jQuery(document).ready(function($) {
    var $wrapper = $('#wrapper'),
        $header = $('#header'),
        $gallery = $('#vegpledge-gallery'),
        $floatingHeader = $header.clone()
            .attr('id', 'top-header')
            .addClass('header')
            .css({
                position: 'fixed',
                width: $header.width()
            }),
        pledgeTicker = $('#vegpledge-ticker-pledges li').map(function() {
            return $(this).text();
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
            $header.show();
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
                $(this).text(nextPledge);
                $(this).fadeIn(); // IE bug prevents chaining from text
            });
            pledgeTicker.unshift(nextPledge);
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
        },
        updateMyPledges = function() {
            var label = myPledges.length > 0 ?
                ('You have made ' + myPledges.length + ' ' +
                    (myPledges.length > 1 ? 'pledges' : 'pledge') + '.') :
                'Choose your pledges below.';
            $('.vegpledge-my-pledges').html(label)
                .each(function() {
                    $('<a href="#vegpledge-share-form">Share</a>')
                        .click(showPledgeForm)
                        .appendTo(this);
                });
        },
        showPledgeForm = function() {
            var $pledgeChoices = $('#vegpledge-choose-pledges ul');
            $.facebox.loading();
            $pledgeChoices.empty();
            $.each(myPledges, function() {
                $pledgeChoices.append(
                    $('<li class="mini-pledge mini-pledge-' + this + '"/>')
                        .append('<input type="hidden" name="' + this +
                            '" value="' + this + '"/>')
                );
            });
            $.facebox(this.href);
            return false;
        };

    $(window).resize(function() {
        repositionHeaders();
    });

    $header.addClass('header');

    $wrapper.wrap('<div class="wrapper" />');
    $wrapper.before($floatingHeader);
    repositionHeaders();

    $(window).scroll(repositionHeaders);
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
    $('#vegpledge-share-form .form-submit')
        .append('<button class="close">Cancel</button>');
});

/*
 * Heavily modified by Tim Moore for VegPledge
 *
 * Facebox (for jQuery)
 * version: 1.2 (05/05/2008)
 * @requires jQuery v1.2 or later
 *
 * Examples at http://famspam.com/facebox/
 *
 * Licensed under the MIT:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2007, 2008 Chris Wanstrath [ chris@ozmm.org ]
 *
 */
(function($) {
    $.facebox = function(data) {
        fillFaceboxFromHref(data)
    }

  /*
   * Public, $.facebox methods
   */

   $.extend($.facebox, {
       settings: {
           opacity      : .5,
           faceboxHtml  : '\
                <div id="facebox" style="display:none;"> \
                  <div class="popup"> \
                    <table> \
                      <tbody id="facebox-tbody"> \
                        <tr> \
                          <td class="tl"/><td class="b"/><td class="tr"/> \
                        </tr> \
                        <tr> \
                          <td class="b"/> \
                          <td class="body"> \
                            <div class="content"> \
                            </div> \
                          </td> \
                          <td class="b"/> \
                        </tr> \
                        <tr> \
                          <td class="bl"/><td class="b"/><td class="br"/> \
                        </tr> \
                      </tbody> \
                    </table> \
                  </div> \
                </div>'
        },

        loading: function() {
            init()
            showOverlay()

            $('#facebox .content').empty()
            $('#facebox .body').children().hide().end()

            $('#facebox').css({
                top:	getPageScroll()[1] + (getPageHeight() / 10),
                left:	$(window).width() / 2 - 205
            }).show()

            $(document).bind('keydown.facebox', function(e) {
                if (e.keyCode == 27) $.facebox.close()
                return true
            })
            $(document).trigger('loading.facebox')
        },

        reveal: function(data) {
            $(document).trigger('beforeReveal.facebox')
            $('#facebox .content').append(data)
            $('#facebox .body').children().fadeIn('normal')
            $('#facebox').css('left', $(window).width() / 2 - ($('#facebox table').width() / 2))
            $(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
        },

        close: function() {
            $(document).trigger('close.facebox')
            return false
        }
    })

    /*
     * Public, $.fn methods
     */

    $.fn.facebox = function(settings) {
        if ($(this).length == 0) return

        init(settings)

        function clickHandler() {
            $.facebox.loading()

            fillFaceboxFromHref(this.href)
            return false
        }

        return this.bind('click.facebox', clickHandler)
    }

    /*
     * Private methods
     */

    // called one time to setup facebox on this page
    function init(settings) {
        if ($.facebox.settings.inited) return true
        else $.facebox.settings.inited = true

        $(document).trigger('init.facebox')

        if (settings) $.extend($.facebox.settings, settings)
        $('body').append($.facebox.settings.faceboxHtml)

        var preload = []

        $('#facebox').find('.b:first, .bl').each(function() {
            preload.push(new Image())
            preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
        })
        $('#facebox .close').live('click', $.facebox.close)
    }

    // getPageScroll() by quirksmode.com
    function getPageScroll() {
        var xScroll, yScroll;
        if (self.pageYOffset) {
            yScroll = self.pageYOffset;
            xScroll = self.pageXOffset;
        } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
            yScroll = document.documentElement.scrollTop;
            xScroll = document.documentElement.scrollLeft;
        } else if (document.body) {// all other Explorers
            yScroll = document.body.scrollTop;
            xScroll = document.body.scrollLeft;
        }
        return new Array(xScroll,yScroll)
    }

    // Adapted from getPageSize() by quirksmode.com
    function getPageHeight() {
        var windowHeight
        if (self.innerHeight) {	// all except Explorer
            windowHeight = self.innerHeight;
        } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
            windowHeight = document.documentElement.clientHeight;
        } else if (document.body) { // other Explorers
            windowHeight = document.body.clientHeight;
        }
        return windowHeight
    }

    function fillFaceboxFromHref(href) {
        var url    = window.location.href.split('#')[0]
        var target = href.replace(url,'')
        if (target == '#') return
        $.facebox.reveal($(target).html())
    }

    function showOverlay() {
        if ($('#facebox_overlay').length == 0)
            $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

        $('#facebox_overlay').addClass("facebox_overlayBG")
            .css('opacity', $.facebox.settings.opacity)
            .fadeIn(200)
        return false
    }

    function hideOverlay() {
        $('#facebox_overlay').fadeOut(200, function(){
            $("#facebox_overlay").removeClass("facebox_overlayBG")
            $("#facebox_overlay").addClass("facebox_hide")
            $("#facebox_overlay").remove()
        })

        return false
    }

    /*
     * Bindings
     */

     $(document).bind('close.facebox', function() {
         $(document).unbind('keydown.facebox')
         $('#facebox').fadeOut(function() {
             $('#facebox .content').removeClass().addClass('content')
             hideOverlay()
             $(document).trigger('afterClose.facebox')
         })
     })
})(jQuery);
