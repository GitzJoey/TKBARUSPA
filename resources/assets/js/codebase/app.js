$(document).ready(function () {
    var sessionTimeout = parseInt(document.getElementById("appSettings").value.split('|')[0]) * 60;
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
});