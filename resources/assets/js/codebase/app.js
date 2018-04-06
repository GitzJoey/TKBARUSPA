$(document).ready(function () {
    var sessionTimeout = parseInt(document.getElementById("appSettings").value.split('-')[0]) * 60;
    function timeout() {
        setTimeout(function () {
            sessionTimeout = (sessionTimeout - 1);
            if (sessionTimeout >= 30) {
                $('#timeoutCount').text(moment.duration(sessionTimeout, 'seconds').format('hh:mm:ss'));
            } else {

            }
            timeout();
        }, 1000);
    }
    timeout();

    $('#goTop').goTop({ "src": "fa fa-chevron-up fa-fw" });

    $('#calendar').fullCalendar();

    $('[data-toggle="layout"][data-action="sidebar_toggle"]').click(function() {
        if (typeof(Storage) != 'undefined') {
            if ($('#page-container').attr('class').includes('sidebar-o')) {
                localStorage.setItem('sidebar_mode', '');
            } else {
                localStorage.setItem('sidebar_mode', 'sidebar-o');
            }
        }
    });

    if (typeof(Storage) != 'undefined') {
        if (localStorage.getItem('sidebar_mode').length == 0) {
            $('#page-container').removeClass('sidebar-o');
        }
    }
});