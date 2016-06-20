//npm install socket.io ioredis --save
var server = require('http').Server();

var io = require('socket.io')(server);

var Redis = require('ioredis');
var shower = new Redis();

shower.subscribe('shower-channel');

shower.on('message', function(channel,message){
   console.log(channel, message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data);
});

server.listen(3000);
console.log('running on 3000');