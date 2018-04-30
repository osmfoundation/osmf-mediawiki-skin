(function ($) {
    $(document).ready(function () {
        var $header = $('header'),
            $body = $('body');

        if (window.pageYOffset >= $header.height()) {
            $header.addClass('fixed');
            $body.addClass('header-fixed');
        }
        
        window.onscroll = function() {
            if (window.pageYOffset >= $header.height()) {
                $header.addClass('fixed');
                $body.addClass('header-fixed');
            }
            else {
                $header.removeClass('fixed');
                $body.removeClass('header-fixed');
            }
        };

        $('.nav-toggle').on('click', function () {
            if ($(this).attr('aria-expanded') === 'false') {
                $(this).attr('aria-expanded', 'true');
                $('header').addClass('nav-toggled');
                $('.mainnav').slideDown();
            }
            else {
                $(this).attr('aria-expanded', 'false');
                $('header').removeClass('nav-toggled');
                $('.mainnav').slideUp();
            }
        });
    });
})(jQuery);
