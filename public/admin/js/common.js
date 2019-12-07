/**
 * @author: zyh
 * @see: <a href="mailto:jikeytang@gmail.com">zyh</a>
 * @time: 2013-6-17 下午8:06
 * @info:
 */
;(function(win){ // 主开发框架
    win.App = win.App || {};

    App.namespace = function (name, sep) {
        var s = name.split(sep || '.'),
            d = {},
            o = function (a, b, c) {
                if (c < b.length) {
                    if (!a[b[c]]) {
                        a[b[c]] = {};
                    }
                    d = a[b[c]];
                    o(a[b[c]], b, c + 1);
                }
            };

        o(window, s, 0);
        return d;
    };

    App.namespace('App.cookie');

    $.extend(App.cookie, {
        setCookie : function (name, value, days) {
            var exp = new Date();
            exp.setTime(exp.getTime() + days * 24 * 60 * 60 * 1000);
            var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
        },
        getCookie : function (name) {
            var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
            if (arr != null) {
                return unescape(arr[2]);
            }
            return null;
        },
        delCookie : function (name) {
            var exp = new Date();
            exp.setTime(exp.getTime() - 1);
            var cval = App.cookie.getCookie(name);
            if (cval != null) {
                document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
            }
        }
    });

}(window));

(function(win, $){
    App.namespace('App.ui');

    $.extend(App.ui, {

        /**
         * 元素是否在视口内
         * @param {Object} obj
         * @returns {boolean}
         */
        isView : function(obj){
            return (obj.offset().top - $(window).scrollTop()) < $(window).height() / 2;
        }

    });

}(window, jQuery));

(function($){ // jQuery 插件集合
    $.fn.imgSlider = function(options){
        var defaults = {
                auto : true,
                num  : 3,
                speed: 4 // speed
            },
            that     = $(this),
            size     = that.find('li').length,
            timer    = 0,
            opts     = $.extend({}, defaults, options),
            n        = opts.num;

        if(size > n){
            var page = size + 1, // pages
                li_w = that.find('li').outerWidth(true),
                ul_w = li_w * size,
                ul   = that.find('ul');
            ul.append(ul.html()).css({'width': 2 * ul_w, 'marginLeft': - ul_w}); // in order to move forward with pictures

            that.find('.btnRight').bind('click', function(){
                if(!ul.is(':animated')){
                    if(page < 2 * size - n){ //
                        ul.animate({'marginLeft': '-=' + li_w}, 'slow', 'linear');
                        page++;
                    } else {
                        ul.animate({'marginLeft': '-=' + li_w}, 'slow', 'linear', function(){
                            ul.css('marginLeft', (n - size) * li_w);
                            page = (size - n) + 1;
                        });
                    }
                }
            });
            that.find('.btnLeft').bind('click', function(){
                if(!ul.is(':animated')){
                    if(page > 2){
                        ul.animate({'marginLeft': '+=' + li_w}, 'slow', 'linear');
                        page--;
                    } else {
                        ul.animate({'marginLeft': '+=' + li_w}, 'slow', 'linear', function(){
                            ul.css('marginLeft', - ul_w);
                            page = size + 1;
                        });
                    }
                }
            });
            that.hover(function(){
                clearInterval(timer);
            }, function(){
                if(opts.auto){
                    timer = setInterval(function(){
                        that.find('.btnRight').click();
                    }, opts.speed * 1000);
                }
            }).trigger('mouseleave');
        }
    };
    $.fn.utilTab = function(options){
        var defaults = {
                tag       : 'li', // tab 标签名
                subName   : '.utilTabSub', // sub class name
                current   : 'on',    // 当前的className
                eventType : 'click', // 触发的事件类型
                showType  : 'fadeIn' // 触发的效果类型
            },
            opts = $.extend({}, defaults, options),
            that = $(this);

        that.find(opts.tag)[opts.eventType](function() {
            var idx = $(this).index();
            $(this).addClass(opts.current).siblings().removeClass(opts.current);
            that.find(opts.subName).eq(idx)[opts.showType]().siblings(opts.subName).hide();
        });
    };

    $.fn.pop = function(options){
        var defaults = {
                event       : 'click', // event type
                idContent   : '',      // content id
                closeBtn    : '',      // close id
                isMask      : true,    // if the mask
                isMaskClose : true
            },
            that        = $(this),
            opts        = $.extend({}, defaults, options),
            content     = $(opts.idContent),
            IE6         = $.browser.msie && !window.XMLHttpRequest,
            closeBtn    = $(opts.closeBtn),
            viewWidth   = $(window).width(),
            viewHeight  = $(window).height(),
            left        = (viewWidth - content.outerWidth()) / 2,
            top         = (viewHeight - content.outerHeight()) / 2,
            pos         = IE6 && 'absolute',
            sTop        = 0,
            oMask       = null;

        that[opts.event](function(){
            IE6 && (sTop = $(document).scrollTop());
            content.css({position : pos, left : left + 'px', top : top + sTop + 'px', zIndex: 888}).fadeIn();
            if($('.ui-mask').length == 0){
                oMask = $('<div class="ui-mask"></div>').appendTo(document.body);
            } else {
                oMask = $('.ui-mask');
            }
            if(opts.isMask){
                oMask.css({width : $(window).width(), height : $(document).height()}).fadeTo('normal', 0.5);
                IE6 && oMask.html('<iframe src="about:blank" style="width:100%;height:100%;position:absolute;top:0;left:0;z-index:-1;filter:alpha(opacity=0)"></iframe>'); // 添加全屏iframe以防止select穿透
            }
            close();
            oMask.click(function(){
                closeFn();
            });
            return false;
        });

        !IE6 && content.find('.pop-title').setDrag(); // set the drag

        function close(){
            content.on('click', opts.closeBtn, function(){
                closeFn();
            });
        }

        function closeFn(){
            content.fadeOut();
            if(opts.isMask){
                oMask.fadeOut('normal');
                IE6 && oMask.find('iframe').remove();
            }
        }
    }
    $.fn.setDrag = function(options){
        var defaults = {
            },
            that    = $(this),
            opts    = $.extend({}, defaults, options),
            doc     = $(document),
            width   = $(window).width(),
            height  = $(window).height(),
            startX  = 0,
            startY  = 0,
            lastX   = 0,
            lastY   = 0,
            box     = that.parent(), // handler.parentNode
            handler = that[0],
            drag    = {
                down: function(e){
                    that.css('cursor', 'move');
                    startX            = e.clientX - parseInt(box.css('left'));
                    startY            = e.clientY - parseInt(box.css('top'));
                    this.setCapture && this.setCapture(); // IE to prevent fast drag losing of object
                    doc.on('mousemove', drag.move);
                    doc.on('mouseup', drag.up);
                    return false; // chrome to prevent rolling screen, and the loss of the mouse move style
                },
                move: function(e){
                    lastX             = e.clientX - startX;
                    lastY             = e.clientY - startY;
                    lastX             = Math.max(0, Math.min(width - box.outerWidth(), lastX));
                    lastY             = Math.max(0, Math.min(height - box.outerHeight() - 1, lastY));
                    box.css({'top': lastY + 'px', 'left': lastX + 'px'});
                    window.getSelection ? window.getSelection().removeAllRanges() : document.selection.empty(); // cancel the selected text
                    e.stopPropagation();
                },
                up: function(){
                    // that.css('cursor', 'auto');
                    doc.off('mousemove', drag.move);
                    doc.off('mouseup', drag.up);
                    handler.releaseCapture && handler.releaseCapture(); // IE to prevent fast drag losing of object
                }
            };
        that.on('mousedown', drag.down);
    }

    /**
     * 固定定位
     * @param {String} actCls
     */
    $.fn.fixedDiv = function(actCls){
        var pos = 0,
            that = $(this),
            topVal;

        if(that.length > 0){
            topVal = that.offset().top;
        }

        function fix(){
            pos = $(document).scrollTop();

            if (pos > topVal) {
                that.addClass(actCls);
                if (!window.XMLHttpRequest) {
                    that.css({
                        position: 'absolute',
                        top     : pos
                    });
                }
            } else {
                that.removeClass(actCls);
                if (!window.XMLHttpRequest) {
                    that.css({
                        position: 'static',
                        top     : 'auto'
                    });
                }
            }
        }
        fix();

        $(window).scroll(fix);
    }

    $.fn.currenMenu = function(options){
        var that = this,
            opts = $.extend({}, {
                ele : $('.menu'),
                on : 'on',
                out : $('.crumb-name') // crumb-name
            }, options),
            parentEle = null,
            ele = opts.ele,
            on = opts.on,
            link = location.href.toLocaleLowerCase(),
            menuName = '';

        $(that).find('a').each(function(){
            parentEle = $(this).parent();

            if(link.indexOf(this.href) != -1){
                menuName = $(this).html().replace(/<i.*?>(.*)<\/i>/, '');
                opts.out.html(menuName);
                parentEle.siblings().removeClass(on);
                parentEle.addClass(on);
            }
        });
    }

    $.fn.allChoose = function(opt){
        var T = $(this),
            set = $.extend({
                name : ''
            }, opt || {}),
            items = $('input[type="checkbox"][name="' + set.name + '"]');

        T.click(function(){
            items.attr('checked', this.checked);
        });

        items.click(function(){
            T.attr('checked', items.not(':checked').length === 0);
        });
    }

    $.goTop = function(){
        var backToTopTxt = '',
            backToTopEle = $('<div class="backToTop"></div>').appendTo($("body")).text(backToTopTxt).attr("title", backToTopTxt).click(function () {
                $("html, body").animate({ scrollTop: 0 }, 120);
            }),
            backToTopFun = function () {
                var st = $(document).scrollTop(),
                    winh = $(window).height();

                (st > 0) ? backToTopEle.show() : backToTopEle.hide();
                //IE6下的定位
                if (!window.XMLHttpRequest) {
                    backToTopEle.css("top", st + winh - 166);
                }
            };
        $(window).bind("scroll", backToTopFun);
        $(function () {
            backToTopFun();
        });
    }
}(jQuery));

$(function(){
    $.goTop();
    $('.sub-menu').currenMenu();
});
