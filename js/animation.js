$(document).ready(function() {
    $('#menuOpen').on('click', function() {
        $('#menu').fadeIn(300);
        $('.overlay').fadeIn(300);
        $('html, body').addClass('menu-open');
    })

    $('#menuClose').on('click', function() {
        $('#menu').fadeOut(300);        
        $('.overlay').fadeOut(300);      
        $('html, body').removeClass('menu-open');
    });

    $('#returnFormOpen').on('click', function(e) {
        e.preventDefault();
        $('#returnForm').addClass('active');
        $('.overlay').fadeIn(300);
        $('html, body').addClass('form-open');
    })

    $('#returnFormClose').on('click', function() {
        $('#returnForm').removeClass('active');       
        $('.overlay').fadeOut(300);      
        $('html, body').removeClass('form-open');
    });

    $('#authFormOpen').on('click', function(e) {
        e.preventDefault();
        $('#authForm').addClass('active');
        $('.overlay').fadeIn(300);
        $('html, body').addClass('form-open');
    });

    $('#authFormClose').on('click', function() {
        $('#authForm').removeClass('active');
        $('.overlay').fadeOut(300);
        $('html, body').removeClass('form-open');
    });

    $('.auth-tab').on('click', function() {
        $('.auth-tab').removeClass('active');
        $(this).addClass('active');
        const tab = $(this).data('tab');
        $('.auth-panel').removeClass('active');

        if (tab == 'login') {
            $('#loginPanel').addClass('active');
        } else if (tab == 'register') {
            $('#registerPanel').addClass('active');
        }
    });

    $('#showResetLink').on('click', function(e) {
        e.preventDefault();
         $('.auth-panel').removeClass('active');
         $('#resetPanel').addClass('active');
         $('.auth-tab').removeClass('active')
    });

    $('.overlay').on('click', function() {
        $('#menu').fadeOut(300);
        $('#returnForm').removeClass('active'); 
        $('#authForm').removeClass('active');
        $('.overlay').fadeOut(300);
        $('html, body').removeClass('menu-open form-open');
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $('#menu').is(':visible')) {
            $('#menu').fadeOut(300);
            $('#returnForm').removeClass('active'); 
            $('#authForm').removeClass('active');
            $('.overlay').fadeOut(300);
            $('html, body').removeClass('menu-open form-open');
        }
    });


});
