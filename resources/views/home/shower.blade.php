    @foreach($showers as $key => $shower)
        <div class="clr-white col-md-6 text-center margin-25 border-alarm height-200 {{ (!$shower->state)?'free':'taken' }}">
            <p>{{ $shower->name}}</p>
            <p>{{ (!$shower->state)?'shower is free':'shower is taken' }}</p>
        </div>
    @endforeach