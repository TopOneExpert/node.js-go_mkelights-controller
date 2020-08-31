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
        socket.emit('response message', msg + ' On from server');
    });

    socket.on('light_off', function(msg){
        console.log('light_off: ' + msg);
        socket.emit('response message', msg + ' Off from server');
    });

    // socket.on('chat message', function(msg){
    //     console.log('message: ' + msg);
    //     socket.emit('response message', msg + '  from server');
    //     //socket.broadcast.emit('response message', msg + '  from server');
    // });
});

http.listen(8080, function(){
    console.log('listening on *:8080');
});