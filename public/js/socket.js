//npm install socket.io ioredis --save

var server = require('http').Server();

var io = require('socket.io')(server);

var Redis =  require('ioredis');
var redis = new Redis();

redis.subscribe('test-channel');

redis.on('message', function(channel,message){
    console.log('Message Received');
    console.log(message);
});

server.listen(3000);
console.log('running on 3000');