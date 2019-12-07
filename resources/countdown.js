(function(){
    var script = document.querySelector('script[data-countdown][data-plugin="pbs.logout"]');
    var minutes = script.getAttribute('data-minutes');
    var method = script.getAttribute('data-method');

    if (!minutes || minutes == 0) {
        return;
    }

    clearTimeout(endTimer);

    function startTimer() {
        return setTimeout(function() {
            var timerInterval;
            Swal.fire({
                title: '<strong>Warning!</strong>',
                icon: 'warning',
                html:
                  'You have been inactive for more than <b>' + minutes + ' minutes.</b>, ' +
                  "You'll be logged out in a <i></i> seconds unless you click continue to keep you logged in." +
                  "<br>",
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                timer: 5000,
                timerProgressBar: true,
                confirmButtonText:
                  'Continue!',
                cancelButtonText:
                  'Log Me Out ..',
                onBeforeOpen: function () {
                    timerInterval = setInterval(function() {
                        Swal.getContent().querySelector('i')
                        .textContent =  parseInt(Swal.getTimerLeft() / 1000)
                    }, 100)
                },
                onClose: function () {
                    clearInterval(timerInterval)
                }
            }).then((result) => {
                if (result.value) {
                    // The user wants to continue ...
                    var endTimer = startTimer()
                } else {
                    $.request(method, {
                        success: function (data) {
                            if (data.logged_out) {
                                window.location.reload();
                            } else {
                                startTimer();
                            }
                        }
                    });
                }
            });
        }, minutes * 60 * 1200);
    }
    var endTimer = startTimer();

})();
