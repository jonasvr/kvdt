<div class="row bgclr-blackgray ">

    <div id="clock" class="fnt-size-80">

    </div>
    <div class="clr-orange fnt-size-30">
        {{$today}}
    </div>

</div>

@section('js')
    <script>
        $(function startTime() {
            console.log('in')
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('clock').innerHTML =
                    h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        });
        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }
    </script>
@endsection