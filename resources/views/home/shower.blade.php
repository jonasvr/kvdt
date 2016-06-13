   @foreach($showers as $key => $shower)

        <div class="col-lg-3 col-md-6">
            <div class="panel {{ (!$shower->state)?'panel-green':'panel-red' }}">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa {{ (!$shower->state)?'fa-unlock':'fa-lock' }} fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ (!$shower->state)?'Free':'Taken' }}</div>
                            <p>shower</p>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">{{ $shower->name}}</span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        {{--<div class=" padding-10 ">--}}
            {{--<h4>{{ $shower->name}}</h4>--}}
            {{--<p>{{ (!$shower->state)?'shower is free':'shower is taken' }}</p>--}}
        {{--</div>--}}
    @endforeach
