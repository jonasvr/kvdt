// var showers = window.showers;
// console.log(koten_id)
// if(typeof koten_id == "undefined") {
// koten_id = 1;
// }
var socket = io('http://192.168.56.101:3000');
//var socket = io('http://37.139.3.121:3000');

new Vue({
    el: '.showerelement',

    data:{
        devices:devices,
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
            console.log(data);
            console.log(data.showers[0]);
            this.showers = data.showers[0];
            console.log(this.koten_id);

            console.log(this.showers.koten_id);
        }.bind(this));

        socket.on('chair-channel:App\\Events\\ChairUpdate', function() {
            var baseUrl = document.location.origin;
            $.get(baseUrl + "/api/chair/check", function(data){
                console.log("Data: " + data);
                if(data == 'True'){
                    alert('Neem een pauze, ge verdient het.');
                }
            });
           // alert('test');
        }.bind(this));
    }
});
