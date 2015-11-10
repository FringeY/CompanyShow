//页面滚动
var scrollPage = (function (window) {
    var $page = 0,
        $pagenum = 0,
        $scroll = 1,    //开启自动滚动
        $running = 0,   //是否有动画进行中
        $pages = {};

    var init = function () {
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
        $('.content').animate({
            scrollTop: $pages[$p]
        }, 300, 'swing', function () {
            $running = 0;
        });
    };

    var _wheellisten = function () {
        $(window).on('mousewheel', function () {
            var scrollY = $('.content').scrollTop();
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
        window.onhashchange= function () {
            $page = isNaN(location.hash.slice(1)) ? 0 : location.hash.slice(1) * 1;
            $page = $page >= $pagenum ? 0 : $page;
            $('.active').removeClass('active');
            $('.nav-li').eq($page).addClass('active');
            _slide($page, $scroll);
        };
    };

    return {
        init: init
    };
})(window);

//返回顶部
var toTop = (function () {
    var init = function () {

        $('.top-icon').on('click', function () {
           _top();
        });
    };

    var _display = function () {
        $(window).on('mousewheel', function () {
            //$('.content').scrollTop() > $(window).height() ? '' : '' ;
        });
    };

    var _top = function () {
        loaction.hash = 0;
    };

    return {
        init: init
    };
})();

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
        $('.back').append('<img class="shadow" style="display:none;" src="' + $bgs[$bg] + '">');
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
            $timer = clearTimeout();
        }, 5000);
    };

    return {
        init: init
    };
})();

$(window).load(function () {
    scrollPage.init();
    toTop.init();
    fade.init($bgs);
});
