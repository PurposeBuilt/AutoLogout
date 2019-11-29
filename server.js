require('dotenv').config()

var port = process.argv[process.argv.length-1] != undefined ? process.argv[process.argv.length-1] : 3000;

if (process.env.SOCKET_SSL == true || process.env.SOCKET_SSL == 'true'){
    var fs = require ('fs');
    var server = require('https').createServer({
        key: fs.readFileSync(process.env.SSL_KEY || '/etc/nginx/ssl/server.key'),
        cert: fs.readFileSync(process.env.SSL_CRT || '/etc/nginx/ssl/server.crt')
    });
    var http = require('https');
} else {
    var server = require('http').Server();
    var http = require('http');
}

var io = require('socket.io')(server);
io.attach(server);

// Middleware to be initialized in case we need
// to process the driver or user ID in any
// future features.
io.use((socket, next) => {
    return next();
});

var openConnections = {};

io.on('connection', (socket) => {
    var id = socket.handshake.query.user_id;
    var driver = socket.handshake.query.driver;

    if (driver in openConnections) {
        openConnections[driver].push(id);
    } else {
        openConnections[driver] = []
        openConnections[driver].push(id);
    }

    socket.on('disconnect', () => {
        var index = openConnections[driver].indexOf(id);

        if (index > -1) {
            openConnections[driver].splice(index, 1);
        }

        var x = 0;
        var intervalID = setInterval(() => {
            if (openConnections[driver].includes(id)) {
                clearInterval(intervalID);
                // The user is back! In this scenario, The user was either refreshing
                // the page, or going to another page on the same site.
                // So we're ok to keep him logged in.
            } else {
                // The user didn't connect for more than 5 seconds, So it means
                // he left the site, closed all site tabs, closed browser,
                // the browser crashed, Or the system crashed.
                if (++x === 5) {
                    clearInterval(intervalID);
                    http.get(`${process.env.APP_URL}/pbs/logout/${driver}/${id}`, (resp) => {
                        // The User has been logged out supposedly.
                    }).on("error", (err) => {
                        console.log("Error: " + err.message);
                    });
                }
            }
        }, 1000)
    });
});

server.listen(port);

console.log(`Server running on port: ${port}`);
