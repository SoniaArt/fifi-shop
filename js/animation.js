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

    $('#filterOpen').on('click', function(e) {
        $('#filterForm').addClass('active');
        $('.overlay').fadeIn(300);
        $('html, body').addClass('form-open');
    });

    $('#filterClose').on('click', function(e) {
        $('#filterForm').removeClass('active');
        $('.overlay').fadeOut(300);
        $('html, body').removeClass('form-open');
    });

    $('#favouritesOpen').on('click', function(e) {
        e.preventDefault();
        $('#favouritesForm').addClass('active');
        $('.overlay').fadeIn(300);
        $('html, body').addClass('form-open');
    });


    $('#basketOpen').on('click', function(e) {
        e.preventDefault();
        $('#basketForm').addClass('active');
        $('.overlay').fadeIn(300);
        $('html, body').addClass('form-open');
    });

    $('#favouritesClose').on('click', function(){
        $('#favouritesForm').removeClass('active');
        $('.overlay').fadeOut(300);
        $('html, body').removeClass('form-open');
    });


    $('#basketClose').on('click', function(){
        $('#basketForm').removeClass('active');
        $('.overlay').fadeOut(300);
        $('html, body').removeClass('form-open');
    });

    $('.overlay').on('click', function() {
        $('#menu').fadeOut(300);
        $('#returnForm').removeClass('active'); 
        $('#authForm').removeClass('active');
        $('#filterForm').removeClass('active');
        $('#favouritesForm').removeClass('active');
        $('#basketForm').removeClass('active');
        $('#checkoutForm').removeClass('active');
        $('.overlay').fadeOut(300);
        $('html, body').removeClass('menu-open form-open');
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#menu').fadeOut(300);
            $('#returnForm').removeClass('active'); 
            $('#authForm').removeClass('active');
            $('#filterForm').removeClass('active');
            $('#favouritesForm').removeClass('active');
            $('#basketForm').removeClass('active');
            $('#checkoutForm').removeClass('active');
            $('.overlay').fadeOut(300);
            $('html, body').removeClass('menu-open form-open');
        }
    });
});

window.closeAuthForm = function() {
    $('#authForm').removeClass('active');
    $('.overlay').fadeOut(300);
    $('html, body').removeClass('form-open');
};

window.reloadPage = function() {
    closeAuthForm();
    setTimeout(() => {
        location.reload();
    }, 300);
};

window.showError = function(message) {
    alert(message);
};

$('#basketOpen').on('click', function(e) {
    e.preventDefault();
    $('#basketForm').addClass('active');
    $('.overlay').fadeIn(300);
    $('html, body').addClass('form-open');

    import('./user/basket.js').then(module => {
        module.initBasket();
    });
});

$('#checkoutBtn').on('click', function(e) {
    e.preventDefault();
    if ($(this).prop('disabled')) return;
    
    $('#checkoutForm').addClass('active');
    $('.overlay').fadeIn(300);
    $('html, body').addClass('form-open');
    
    import('./user/checkout.js').then(m => m.initCheckout());
});

$('#checkoutClose').on('click', function() {
    $('#checkoutForm').removeClass('active');
    $('.overlay').fadeOut(300);
    $('html, body').removeClass('form-open');
});

const searchBtn = document.getElementById('searchBtn');
const searchInput = document.getElementById('searchInput');

if (searchBtn) {
    function performSearch(query) {
        query = query.trim();
        if (query) {
            window.location.href = `/FIFI/pages/catalog.php?search=${encodeURIComponent(query)}`;
            searchInput.classList.remove('active');
            searchInput.value = '';
        } else {
            searchInput.focus();
        }
    }

    searchBtn.addEventListener('click', () => {
        if (!searchInput.classList.contains('active')) {
            searchInput.classList.add('active');
            searchInput.focus();
            return;
        }

        const query = searchInput.value.trim();
        performSearch(query);
    });

    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            searchInput.classList.remove('active');
            searchInput.value = '';
            return;
        }

        if (e.key === 'Enter') {
            const query = searchInput.value.trim();
            performSearch(query);
        }        
    });
}