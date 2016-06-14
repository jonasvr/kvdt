<!DOCTYPE html>

<html>
<head>
    <title>redis test</title>
</head>
    <body>
    <ul>
        <li v-repeat="user: users">
            @{{user}}
        </li>
    </ul>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.6/socket.io.min.js"></script>

    <script>
        var socket = io('http://192.168.56.101:3000');

        new Vue({
            el: 'body',

            data:{
                users:[]
            },

            ready: function() {
                console.log('in');
                socket.on('test-channel:App\\Events\\NewUserSignedUp', function(data) {
                    this.users.push(data.username);
                    console.log('in');
                    console.log(data.username);
                }.bind(this));
            }
        });
    </script>
    </body>
</html>