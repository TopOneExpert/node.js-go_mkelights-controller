
const io = require('socket.io-client')
const process = require('process');

var socket = io.connect('https://mkelights.com:8080/');
// var socket = io.connect('http://localhost:8080');

socket.on('connect', function() {
  console.log("connected");
  socket.emit('all_light_off', "All light OFF by Cron Job");
});

socket.on('exit', function(msg) {
  process.exit(1);
})
