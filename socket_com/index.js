var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

// app.get('/', function(req, res){
//     res.sendFile(__dirname + '/test.html');
// });

io.on('connection', function(socket){

    socket.on('joined', function(data) {

        socket.emit('acknowledge', 'Connected');
    });

    socket.on('light_on', function(msg){
        socket.emit('response on', msg);
        socket.broadcast.emit('response on', msg);
        console.log('light_on', msg);
        
        var seconds = 0;
        function incrementSeconds() {
            seconds += 1;
            if (seconds === 5)
            socket.emit('response ready', 'Ready');
        }
        var cancel = setInterval(incrementSeconds, 1000);
    });

    socket.on('light_off', function(msg){
        socket.emit('response off', msg);
        socket.broadcast.emit('response off', msg);
        console.log('light_off', msg);

        var seconds = 0;
        function incrementSeconds() {
            seconds += 1;
            if (seconds === 5)
            socket.emit('response ready', 'Ready');
        }
        var cancel = setInterval(incrementSeconds, 1000);
    });
});

http.listen(8080, function(){
    console.log('listening on *:8080');
});