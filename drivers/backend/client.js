(function(){
    var script = document.querySelector('script[data-plugin="pbs.logout"]');
    var url = script.getAttribute('data-url');

    window.socket = io.connect(url);
})();
