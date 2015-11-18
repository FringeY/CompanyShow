// 页面滚动
var scrollPage = (function (window) {
    var $page = 0,          // 当前页数
        $pagenum = 0,       // 总页数
        $scroll = 1,        // 开启自动滚动
        $running = 0,       // 是否有动画进行中
        $scrollTop = 0,     // 页面相对高度
        $pages = {};

    // 初始化
    var init = function () {
        // 获取hash值作为锚点
        $page = isNaN(location.hash.slice(1)) ? 0 : location.hash.slice(1) * 1 ;
        $pagenum = $('.nav-li').length;
        $page = $page >= $pagenum ? 0 : $page;
        $('.block').each(function (i, ele) {
            $pages[i] = $(ele).offset().top;
        });
        _active($page);
        _slide($page, $scroll);
        _hashlisten();
        _wheellisten();
    };

    var _active = function ($p) {
        $('.active').removeClass('active');
        $('.nav-li').eq($p).addClass('active');
    };

    var _slide = function ($p, $s) {
        if (!$s) {
            $scroll = 1;
            return false;
        }
        if ($running) {
            $('.content').stop();
        }
        $running = 1;
        $scrollTop = $pages[$p];
        $('.content').animate({
            scrollTop: $pages[$p]
        }, 300, 'swing', function () {
            $running = 0;
        });
    };

    var _wheellisten = function () {
        $(window).on('mousewheel', function () {
            var scrollY = $('.content').scrollTop();
            $scrollTop = scrollY;
            if (scrollY > $pages[$page]) {
                if (scrollY >= $pages[$page + 1]) {
                    ++$page;
                    _active($page);
                    $scroll = 0;
                    location.hash = $page;
                }
            } else if (scrollY < $pages[$page]) {
                --$page;
                _active($page);
                $scroll = 0;
                location.hash = $page;
            }
        });
    };

    var _hashlisten = function () {
        $(window).on('hashchange', function () {
            $page = isNaN(location.hash.slice(1)) ? 0 : location.hash.slice(1) * 1;
            $page = $page >= $pagenum ? 0 : $page;
            $('.active').removeClass('active');
            $('.nav-li').eq($page).addClass('active');
            _slide($page, $scroll);
        });
    };

    return {
        init: init,
        scrollTop: function () {
            return $scrollTop;
        }
    };
})(window);

//返回顶部
var toTop = (function () {
    var init = function () {
        $('.toTop').on('click', function () {
           _top();
        });
        _display();
        if (scrollPage.scrollTop() > $(window).height() && $('.toTop').css('display') == 'none') {
            $('.toTop').fadeIn(500, 'swing');
        }
    };

    var _display = function () {
        $(window).on('mousewheel', function () {
            if (scrollPage.scrollTop() > $(window).height() && $('.toTop').css('display') == 'none') {
                $('.toTop').fadeIn(500, 'swing');
            } else if (scrollPage.scrollTop() <= $(window).height() && $('.toTop').css('display') == 'block') {
                $('.toTop').fadeOut(500, 'swing');
            }
        });
        $(window).on('hashchange', function () {
            if (scrollPage.scrollTop() > $(window).height() && $('.toTop').css('display') == 'none') {
                $('.toTop').fadeIn(500, 'swing');
            } else if (scrollPage.scrollTop() <= $(window).height() && $('.toTop').css('display') == 'block') {
                $('.toTop').fadeOut(500, 'swing');
            }
        });
    };

    var _top = function () {
        location.hash = 0;
    };

    return {
        init: init
    };
})(window);

//背景转换
var fade = (function () {
    var $bg = 0,
        $bgnum = 0,
        $timer = 0;
    var init = function (bgs) {
        $bgnum = bgs.length;
        _timer();
    };

    var _fadeIn = function () {
        $('.back').append('<img class="shadow" style="display:none;width:100%;height:100%;" src="' + $bgs[$bg] + '">');
        $('.back .bg').fadeOut(1500, 'swing');
        $('.back .shadow').fadeIn(3000, 'swing', function () {
            $('.back .bg').attr('src', $bgs[$bg]);
            $('.back .bg').css('display', 'block');
            $('.back .shadow').remove();
            _timer();
        });
    };

    var _timer = function () {
        $timer = setTimeout(function () {
            $bg = $bg++ >= $bgnum - 1 ? 0 : $bg;
            _fadeIn();
            clearTimeout($timer);
        }, 5000);
    };

    return {
        init: init
    };
})(window);

//弹窗
var modal = (function () {
    var init = function () {
        $('.modal-btn').on('click', function () {
            try {
                //$($(this).attr('modal-target')).fadeIn();
                modalIn($($(this).attr('modal-target')).find('.layer'));
            } catch (e) {
                console.log(e.name + ':' + e.message);
            }
        });
        $('.layer-close').on('click', function () {
            modalOut($(this).parents('.layer'));
        });
    };

    var modalIn = function (obj) {
        obj.parent().css('display', 'block');
        obj.animate({
            top: 0,
            opacity: 0.9
        }, 1000, 'swing');
    };

    var modalOut = function (obj) {
        obj.animate({
            top: '-100%',
            opacity: 0
        }, 1000, 'swing', function () {
            obj.parent().css('display', 'none');
        });

    };

    return {
        init: init
    };
})(window);

$(window).load(function () {
    scrollPage.init();
    toTop.init();
    fade.init($bgs);
    modal.init();
});
