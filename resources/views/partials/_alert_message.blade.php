


@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong style="border-bottom: 1px solid darkred">Your Mistakes!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            @foreach ($errors->all() as $key => $error)
                <li style="list-style: none; padding: 5px">{{ $key + 1 }}. {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{--@if(Session::get('message'))--}}
{{--    <div class="alert alert-success p-3">--}}
{{--        {{ Session::get('message') }}--}}
{{--    </div>--}}
{{--@endif--}}

@if(session()->has('message'))

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong style="border-bottom: 1px solid darkred">Successful!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <ul>
            <li style="list-style: none; padding: 5px">{{ session()->get('message') }}</li>
        </ul>
    </div>
@endif
