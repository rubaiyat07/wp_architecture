jQuery(document).ready(function($) {
    $('a[href*="#"]').on('click', function(e) {
        var target = $(this.hash);
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 600);
        }
    });
});
