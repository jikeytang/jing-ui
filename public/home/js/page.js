;(function(win, $){

    App.namespace('App.model.home')

    $.extend(App.model.home, {
        init: function () {
            this.setBanner()
            this.navAnim()
        },
        setBanner: function () {
            var homeBanner = $('#homeBanner'),
              homeBannerNum = $('#homeBannerNum'),
              len = $('.swipe-wrap').find('.swipe-items').length,
              str = '<a></a>',
              end = '';

            for(var i = 0; i < len; i++){
                if(i == 0){
                    end = '<a class="active"></a>';
                } else {
                    end += str;
                }
            }

            homeBannerNum.append(end);

            this.setSwipe(homeBanner, {
                leftBtn : $('#swipeLeft'),
                rightBtn : $('#swipeRight'),
                callback: function(index, elem) {
                    var link = $('#homeBannerNum a');
                    link.removeClass('active');
                    link.eq(index).addClass('active');
                }
            });
        },
        navAnim: function () {
            var w = 0;
            $('.category-list li').hover(function(){
                $(this).find('a').stop().animate({ left : 0});
            }, function(){
                w = $(this).width() - 15;
                $(this).find('a').stop().animate({ left : '-' + w});
            });

            $('.contact-list li').hover(function(){
                $(this).find('span').stop().animate({top : 0});
            }, function(){
                $(this).find('span').stop().animate({top : '+58'});
            }).on('click', function(){
                location.href = $(this).find('a')[0].href;
            });
        },
        setSwipe: function(obj, options){
            var opts = $.extend({}, {
                  auto: 3000,
                  disableScroll: true,
                  leftBtn : '',
                  rightBtn : ''
              }, options),
              leftBtn = opts.leftBtn,
              rightBtn = opts.rightBtn,
              slide = null;

            if(obj.length > 0){
                slide = obj.Swipe(opts).data('Swipe');
            }

            if(opts.leftBtn.length > 0 && opts.rightBtn.length > 0){
                opts.leftBtn.on('click', slide.prev);
                opts.rightBtn.on('click', slide.next);
            }
        }
    })


}(window, jQuery));


;(function(win, $){
    
    App.namespace('App.model.design')

    $.extend(App.model.design, {
        init: function () {
            this.bind()
            this.sendComment()
            this.saySupport()
            // this.setImgWidth()
        },
        bind: function () {
            var self = this

            // $('#verifyImg').on('click', function(){
            //     this.src = VERIFY_PATH + '?random' + Math.random();
            // });

            $('#aboutMenu').utilTab({
                tag : '.about-title-list li',
                subName : '.about-items'
            });

            $('.wroks-img-wrap').hover(function(){
                $(this).find('.wroks-admin-info').stop().animate({bottom : '0'}, 'slow');
            }, function(){
                $(this).find('.wroks-admin-info').stop().animate({bottom : '-35'}, 'slow');
            });

            $('#email').on('blur', function(){
                self.loadGravatar(this);
            });
        },
        sendComment: function () {
            var self = this,
              form = $('#form1'),
              msg = null;

            $('#sendMsg').on('click', function(){

                if(self.checkMsg()){
                    msg = $('<div/>').appendTo('.blog-tips-info').addClass('comment-msg');
                    $.ajax({
                        url      : './add',
                        type     : 'post',
                        dataType : 'json',
                        data     : form.serialize(),
                        success  : function(obj){
                            obj.info = '提交成功'

                            if(obj.code){
                                var data = obj.msg;

                                msg.html(obj.info);

                                form[0].reset();
                                $('#author').focus();

                                var str = '<div class="comment-headimg"><a href="' + data.url + '" title="' + data.username + '" target="_blank"><img width="44" height="44" alt="' + data.username + '" src="' + data.headimg + '"></a></div>'
                                  + '<div class="comment-main">'
                                  + '<div class="comment-name clearfix">'
                                  + '<div class="comment-user">'
                                  + '<h1>' + data.username + '</h1>'
                                  + '</div>'
                                  + '<div class="comment-revert"><time class="comment-date">' + self.curentTime() + '</time></div>'
                                  + '</div>'
                                  + '<div class="comment-content">' + data.content + '</div>'
                                  + '</div>';

                                $('<li>' + str + '</li>').prependTo('.comment-list').css({opacity : 0}).fadeTo(800, 1);
                            } else {
                                msg.html(obj.msg);
                            }

                            setTimeout(function(){
                                msg.fadeOut(800).remove();
                            }, 1200);
                        },
                        error : function(obj){
                            msg.html(obj.msg);
                        }
                    });
                }

                return false;
            });
        },
        curentTime: function () {
            var now   = new Date(),
              year  = now.getFullYear(),      //年
              month = now.getMonth() + 1,    //月
              day   = now.getDate(),           //日

              hh    = now.getHours(),            //时
              mm    = now.getMinutes(),          //分
              s     = now.getSeconds(),          //分

              clock = year + "-";

            if(month < 10)
                clock += "0";

            clock += month + "-";

            if(day < 10)
                clock += "0";

            clock += day + " ";

            if(hh < 10)
                clock += "0";

            clock += hh + ":";
            if(mm < 10) clock += '0';
            clock += mm;
            clock += s;

            return clock;
        },
        loadGravatar: function (obj) {
            var src   = obj.value,
              w_url = location.host,
              m_url = '';

            if (src.indexOf('@') < 0) return false;
            m_url = 'http://www.gravatar.com/avatar/' + this.md5(src) + '?d=' + w_url + '/Public/uploads/visitor.png&s=45&r=g';
            $('#gravatarView').attr('src', m_url);
            $('#gravatarInput').val(m_url);
        },
        md5: function (a) {
            function b(a, b) {
                var c = (a & 65535) + (b & 65535);
                return(a >> 16) + (b >> 16) + (c >> 16) << 16 | c & 65535
            }

            function c(a, c, d, f, k, o) {
                a = b(b(c, a), b(f, o));
                return b(a << k | a >>> 32 - k, d)
            }

            function d(a, b, d, f, k, o, g) {
                return c(b & d | ~b & f, a, b, k, o, g)
            }

            function f(a, b, d, f, k, o, g) {
                return c(b & f | d & ~f, a, b, k, o, g)
            }

            function k(a, b, d, f, k, o, g) {
                return c(d ^ (b | ~f), a, b, k, o, g)
            }

            return function (a) {
                var b = "", c, d;
                for (d = 0; d < a.length; d += 1)c = a.charCodeAt(d), b += "0123456789abcdef".charAt(c >>> 4 & 15) + "0123456789abcdef".charAt(c & 15);
                return b
            }(function (a) {
                var e = unescape(encodeURIComponent(a)), m, a = [];
                a[(e.length >> 2) - 1] = void 0;
                for (m = 0; m < a.length; m += 1)a[m] = 0;
                for (m = 0; m < 8 * e.length; m += 8)a[m >> 5] |= (e.charCodeAt(m / 8) & 255) << m % 32;
                e = 8 * e.length;
                a[e >> 5] |= 128 << e % 32;
                a[(e + 64 >>> 9 << 4) + 14] = e;
                for (var p, n, o, g = 1732584193, h = -271733879, i = -1732584194, j = 271733878, e = 0; e < a.length; e += 16)m = g, p = h, n = i, o = j, g = d(g, h, i, j, a[e], 7, -680876936), j = d(j, g, h, i, a[e + 1], 12, -389564586), i = d(i, j, g, h, a[e + 2], 17, 606105819), h = d(h, i, j, g, a[e + 3], 22, -1044525330), g = d(g, h, i, j, a[e + 4], 7, -176418897), j = d(j, g, h, i, a[e + 5], 12, 1200080426), i = d(i, j, g, h, a[e + 6], 17, -1473231341), h = d(h, i, j, g, a[e + 7], 22, -45705983), g = d(g, h, i, j, a[e + 8], 7, 1770035416), j = d(j, g, h, i, a[e + 9], 12, -1958414417), i = d(i, j, g, h, a[e + 10], 17, -42063), h = d(h, i, j, g, a[e + 11], 22, -1990404162), g = d(g, h, i, j, a[e + 12], 7, 1804603682), j = d(j, g, h, i, a[e + 13], 12, -40341101), i = d(i, j, g, h, a[e + 14], 17, -1502002290), h = d(h, i, j, g, a[e + 15], 22, 1236535329), g = f(g, h, i, j, a[e + 1], 5, -165796510), j = f(j, g, h, i, a[e + 6], 9, -1069501632), i = f(i, j, g, h, a[e + 11], 14, 643717713), h = f(h, i, j, g, a[e], 20, -373897302), g = f(g, h, i, j, a[e + 5], 5, -701558691), j = f(j, g, h, i, a[e + 10], 9, 38016083), i = f(i, j, g, h, a[e + 15], 14, -660478335), h = f(h, i, j, g, a[e + 4], 20, -405537848), g = f(g, h, i, j, a[e + 9], 5, 568446438), j = f(j, g, h, i, a[e + 14], 9, -1019803690), i = f(i, j, g, h, a[e + 3], 14, -187363961), h = f(h, i, j, g, a[e + 8], 20, 1163531501), g = f(g, h, i, j, a[e + 13], 5, -1444681467), j = f(j, g, h, i, a[e + 2], 9, -51403784), i = f(i, j, g, h, a[e + 7], 14, 1735328473), h = f(h, i, j, g, a[e + 12], 20, -1926607734), g = c(h ^ i ^ j, g, h, a[e + 5], 4, -378558), j = c(g ^ h ^ i, j, g, a[e + 8], 11, -2022574463), i = c(j ^ g ^ h, i, j, a[e + 11], 16, 1839030562), h = c(i ^ j ^ g, h, i, a[e + 14], 23, -35309556), g = c(h ^ i ^ j, g, h, a[e + 1], 4, -1530992060), j = c(g ^ h ^ i, j, g, a[e + 4], 11, 1272893353), i = c(j ^ g ^ h, i, j, a[e + 7], 16, -155497632), h = c(i ^ j ^ g, h, i, a[e + 10], 23, -1094730640), g = c(h ^ i ^ j, g, h, a[e + 13], 4, 681279174), j = c(g ^ h ^ i, j, g, a[e], 11, -358537222), i = c(j ^ g ^ h, i, j, a[e + 3], 16, -722521979), h = c(i ^ j ^ g, h, i, a[e + 6], 23, 76029189), g = c(h ^ i ^ j, g, h, a[e + 9], 4, -640364487), j = c(g ^ h ^ i, j, g, a[e + 12], 11, -421815835), i = c(j ^ g ^ h, i, j, a[e + 15], 16, 530742520), h = c(i ^ j ^ g, h, i, a[e + 2], 23, -995338651), g = k(g, h, i, j, a[e], 6, -198630844), j = k(j, g, h, i, a[e + 7], 10, 1126891415), i = k(i, j, g, h, a[e + 14], 15, -1416354905), h = k(h, i, j, g, a[e + 5], 21, -57434055), g = k(g, h, i, j, a[e + 12], 6, 1700485571), j = k(j, g, h, i, a[e + 3], 10, -1894986606), i = k(i, j, g, h, a[e + 10], 15, -1051523), h = k(h, i, j, g, a[e + 1], 21, -2054922799), g = k(g, h, i, j, a[e + 8], 6, 1873313359), j = k(j, g, h, i, a[e + 15], 10, -30611744), i = k(i, j, g, h, a[e + 6], 15, -1560198380), h = k(h, i, j, g, a[e + 13], 21, 1309151649), g = k(g, h, i, j, a[e + 4], 6, -145523070), j = k(j, g, h, i, a[e + 11], 10, -1120210379), i = k(i, j, g, h, a[e + 2], 15, 718787259), h = k(h, i, j, g, a[e + 9], 21, -343485551), g = b(g, m), h = b(h, p), i = b(i, n), j = b(j, o);
                a = [g, h, i, j];
                m = "";
                for (e = 0; e < 32 * a.length; e += 8)m += String.fromCharCode(a[e >> 5] >>> e % 32 & 255);
                return m
            }(a));
        },
        setImgWidth: function () {
            var w_width = $(window).width(),
              list = $('.wroks-list li');

            if(w_width > 1360){
                list.css({marginRight : parseInt((w_width - 130 - 4 * list.outerWidth(true)) / 3) });
                list.each(function(i, n){
                    if((i + 1) % 4 == 0){
                        $(this).css({marginRight : 0});
                    }
                });
            }

        },
        checkMsg: function () {
            return this.checkHandler($('#author'), '昵称') && this.checkHandler($('#email'), '邮箱') && this.checkHandler($('#content'), '评论内容');
        },
        checkHandler: function (obj, msg){
            if(obj.length > 0 && obj.val().length < 1){
                alert('请输入您的' + msg + '！');
                obj.focus();
                return false;
            } else {
                return true;
            }
        },
        saySupport: function () {
            var userId = 0;

            $('.blog-ok,.info-sayok').on('click', function(){
                var that = this;
                userId = $(this).data('userid');

                var yetId = App.cookie.getCookie('userid'),
                  yetArr = '',
                  isYet = false;

                if(yetId){ // 已评论过其它
                    yetArr = yetId.split('|');

                    for(var i = 0, len = yetArr.length; i < len; i++){
                        if(userId == yetArr[i]){
                            isYet = true;
                        }
                    }

                    if(isYet){
                        alert('谢谢支持,您已赞过~！');
                    } else {
                        App.cookie.setCookie('userid', yetId + '|' + userId, 1);
                    }
                } else {
                    App.cookie.setCookie('userid', userId, 1);
                }

                if($(this).hasClass('blog-yetok') || $(this).hasClass('blog-yetok')){
                    return ;
                }

                $.ajax({
                    url      : './support',
                    type     : 'get',
                    dataType : 'json',
                    data     : 'id=' + userId,
                    success  : function(msg){
                        var ok = null;
                        if(msg.data == 1){
                            $(that).addClass('blog-yetok');

                            if(that.className.indexOf('info-sayok') >= 0){
                                $(that).html($(that).html() - 0 + 1);
                            } else {
                                ok = $(that).find('.blog-oknum');
                                ok.html(ok.html() - 0 + 1);
                            }
                        } else {
                            alert(msg.info);
                        }
                    },
                    error : function(msg){
                        alert(msg.info);
                    }
                });


            });
        }
    })

}(window, jQuery));

$(function () {
    App.model.home.init()
    App.model.design.init()
});