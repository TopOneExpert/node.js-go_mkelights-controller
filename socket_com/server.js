var express = require("express");
var app = express();
var fs = require('fs');

app.get('/', function(req, res){
    res.send("test");
});

var server = require("https").createServer({
    key: fs.readFileSync('privkey.pem'),
    cert: fs.readFileSync('cert.pem')
}, app);

var io = require("socket.io").listen(server);

server.listen(8080);


io.on('connection', function (socket) {

    socket.on('joined', function (data) {
        console.log('connected: ', msg);
        socket.emit('acknowledge', 'Connected');
    });

    socket.on('light_on', function (msg) {
        socket.emit('response wait', 'Wait...');
        console.log('light_on', msg);

        var seconds = 0;
        function incrementSeconds() {
            seconds += 1;
            if (seconds === 5) {
                console.log('light_on', msg, 'Ready');
                socket.emit('response ready', 'Ready');
                socket.broadcast.emit('response on', msg);
            }
        }
        var cancel = setInterval(incrementSeconds, 1000);
    });

    socket.on('light_off', function (msg) {
        socket.emit('response wait', 'Wait...');
        console.log('light_off', msg);

        var seconds = 0;
        function incrementSeconds() {
            seconds += 1;
            if (seconds === 5) {
                console.log('light_off', msg, 'Ready');
                socket.emit('response ready', 'Ready');
                socket.broadcast.emit('response off', msg);
            }
        }
        var cancel = setInterval(incrementSeconds, 1000);
    });
});
