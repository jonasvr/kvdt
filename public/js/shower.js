var showers = window.showers;
console.log(koten_id)
var socket = io('http://192.168.56.101:3000');
new Vue({
    el: '.showerelement',

    data:{
        showers:showers,
        panel:'panel',
        green: 'panel-green',
        red: 'panel-red',
        unlock: 'fa fa-unlock fa-5x',
        lock: 'fa fa-lock fa-5x',
        koten_id: koten_id,
    },

    ready: function() {
        console.log('in ready');
        socket.on('shower-channel:App\\Events\\ShowerUpdate', function(data) {
            this.showers = data.showers;
        }.bind(this));
    }
});