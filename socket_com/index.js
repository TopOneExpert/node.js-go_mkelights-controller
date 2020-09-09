var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get('/', function(req, res){
    res.sendFile(__dirname + '/test.html');
});

io.on('connection', function(socket){
    console.log('a user connected');
    socket.on('joined', function(data) {
        console.log(data);
        socket.emit('acknowledge', 'Acknowledged');
    });

    socket.on('light_on', function(msg){
        console.log('light_on: ' + msg);
        socket.emit('response message', msg);
        socket.broadcast.emit('response on', msg);
    });

    socket.on('light_off', function(msg){
        console.log('light_off: ' + msg);
        socket.emit('response message', msg);
        socket.broadcast.emit('response off', msg);
    });
});

http.listen(8080, function(){
    console.log('listening on *:8080');
});