(function ($) {
    $(document).ready(function () {
        window.onscroll = function() {
            var scrollPosition = document.documentElement.scrollTop || document.body.scrollTop,
                threshold = 130;

            if (scrollPosition >= threshold) {
                $('header').addClass('fixed');
            }
            else {
                $('header').removeClass('fixed');
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
