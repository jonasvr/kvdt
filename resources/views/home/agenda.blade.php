
    {{--<h3>Agenda--}}
        {{--<a href="{{ URL::route('calendars') }}">--}}
            {{--<span class="glyphicon glyphicon-pencil "></span>--}}
        {{--</a>--}}
    {{--</h3><br>--}}
{{--@foreach($agenda as $key => $event)--}}
    {{--<h5 class="underline capitalize">{{$event->summary}}</h5>--}}
    {{--<p>start: {{$event->start}}</p>--}}
    {{--<p>end: {{$event->end}}</p>--}}
    {{--<br>--}}
{{--@endforeach--}}


    <div class="col-lg-3">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-bell fa-fw"></i>Calendars
                <a href="{{ URL::route('calendars') }}">

                    <span class="fa fa-pencil"></span>
                </a>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="list-group">

                    @foreach($agenda as $key => $event)
                        <a href="#" class="list-group-item">
                            <i class="fa fa-comment fa-fw"></i> {{$event->summary}}
                            <span class="pull-right text-muted small">
                            <br><em>{{ $event->start }}</em>
                                    </span>
                        </a>
                    @endforeach
                </div>
                <!-- /.list-group -->
                <a href="#" class="btn btn-default btn-block">View All Events</a>
            </div>
            <!-- /.panel-body -->
        </div>

    </div>
    <!-- /.col-lg-4 -->