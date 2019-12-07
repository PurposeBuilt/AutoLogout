window.onbeforeunload = function(){
    if (!window.socket.connected) {
        return "Moving too fast between the pages may disconnect you!";
    }

    return undefined;
};

(function(){
    var script = document.querySelector('script[data-client][data-plugin="pbs.logout"]');
    var url = script.getAttribute('data-url');

    window.socket = io.connect(url);
})();
