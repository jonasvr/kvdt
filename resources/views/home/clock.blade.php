<div class="col-lg-6">

    <div class="panel panel-yellow">
        <div class="panel-heading">
            <i class="fa fa-clock-o fa-fw"></i>Clock
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div id="clock" class="text-center fnt-size-80 margin-tb-10">

            </div>
            <div class="text-center clr-orange fnt-size-30">
                {{$today}}
            </div>
        </div>
        <!-- /.panel-body -->
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