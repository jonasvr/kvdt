<div class="panel-heading">Showers</div>
<div class="panel-body row">
    @foreach($showers as $key => $shower)
        <div class="col-md-3 text-center margin-25 border-alarm height-200 {{ (!$shower->state)?'free':'taken' }}">
            <p>{{ $shower->device_id }}</p>
            <p>{{ (!$shower->state)?'shower is free':'shower is taken' }}</p>
        </div>
    @endforeach
</div>