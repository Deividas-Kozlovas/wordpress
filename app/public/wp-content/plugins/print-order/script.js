jQuery(document).ready(function($) {
    // Function to print all tables
    $('#spauzdinti-button').click(function() {
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><head></head><body>veikia</body></html>');
        newWin.document.close();
    });
});
