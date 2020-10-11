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

    socket.on('joined', function (msg) {
        console.log('connected: ', msg);
        socket.emit('acknowledge', 'Connected');
    });

    socket.on('light_on', function (msg) {
        socket.emit('response wait', 'Wait...');
        console.log('light_on', msg);

        var intervalId = null;
        var seconds = 0;

        function incrementSeconds() {            
            if (seconds === 5) {
                console.log('light_on', msg, 'Ready');
                socket.emit('response ready', 'Ready');
                socket.broadcast.emit('response on', msg);
                clearInterval(intervalId);
            } else {
                console.log("light on", msg, "Wait");
                socket.emit('response wait', 'Wait... ' + (5 - seconds) + "s");
            }
            seconds += 1;
        }
        intervalId = setInterval(incrementSeconds, 1000);
    });

    socket.on('light_off', function (msg) {
        socket.emit('response wait', 'Wait...');
        console.log('light_off', msg);

        var intervalId = null;
        var seconds = 0;

        function incrementSeconds() {            
            if (seconds === 5) {
                console.log('light_off', msg, 'Ready');
                socket.emit('response ready', 'Ready');
                socket.broadcast.emit('response off', msg);
                clearInterval(intervalId);
            } else {
                console.log("light off", msg, "Wait");
                socket.emit('response wait', 'Wait... ' + (5 - seconds) + "s");
            }
            seconds += 1;
        }
        intervalId = setInterval(incrementSeconds, 1000);
    });
});
