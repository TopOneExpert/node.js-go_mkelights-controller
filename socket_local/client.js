const
    io = require("socket.io-client"),
    ioClient = io.connect("http://3.20.78.29/:8000");

ioClient.on("seq-num", (msg) => console.info(msg));