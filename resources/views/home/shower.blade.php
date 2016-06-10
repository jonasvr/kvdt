
<div class="clr-white col-md-6 text-center ">
    @foreach($showers as $key => $shower)
        <div class="{{ (!$shower->state)?'free':'taken' }} padding-10 ">
            <h4>{{ $shower->name}}</h4>
            <p>{{ (!$shower->state)?'shower is free':'shower is taken' }}</p>
        </div>
    @endforeach
</div>
