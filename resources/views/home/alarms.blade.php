<div class="col-lg-3">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <i class="fa fa-bell fa-fw"></i>Alarms
            <a href="{{ URL::route('alarms') }}">

                <span class="fa fa-pencil"></span>
            </a>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body">
            <div class="list-group">

                @foreach($alarms as $key => $alarm)
                    <a href="#" class="list-group-item">
                        <i class="fa fa-clock-o fa-fw"></i> {{$alarm->summary}}
                        <span class="pull-right text-muted small">
                            <br><em>{{ $alarm->alarmDate }} {{ $alarm->alarmTime }}</em>
                                    </span>
                    </a>
                @endforeach
            </div>
            <!-- /.list-group -->
            <a href="{{ URL::route('alarms') }}" class="btn btn-default btn-block">View All Alarms</a>
        </div>
        <!-- /.panel-body -->
    </div>

</div>
<!-- /.col-lg-4 -->