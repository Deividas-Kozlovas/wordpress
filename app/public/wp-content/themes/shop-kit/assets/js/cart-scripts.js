
document.addEventListener('DOMContentLoaded', function() {
    var cartIcon = document.querySelector('.cart-icon');
    var cartPanel = document.querySelector('.cart-panel');
    var closeIcon = document.querySelector('.panel-close-btn');
    var body = document.body;
    var delay;

    cartIcon.addEventListener('click', function(e) {
        e.preventDefault();
        cartPanel.classList.add('cart-panel-open');
        e.stopPropagation();
    });

    closeIcon.addEventListener('click', function(e) {
        e.preventDefault();
        cartPanel.classList.remove('cart-panel-open');
        e.stopPropagation();
    });

    body.addEventListener('click', function(e) {
        clearTimeout(delay);

        delay = setTimeout(function() {
            if (!cartPanel.contains(e.target) && e.target !== cartIcon) {
                cartPanel.classList.remove('cart-panel-open');
            }
        }, 10);
    });

    // Prevent clicks inside the panel from closing it immediately
    cartPanel.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});
