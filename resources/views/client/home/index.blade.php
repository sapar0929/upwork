@extends('client.layouts.app')
@section('title')
    Upwork
@endsection
@section('content')
    <div class="container-xl text-center py-3">
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
            @foreach($works as $work)
                <div class="col">
                    <div class="border">
                        <div>{{ $work->uuid }}</div>
                        <div>{!! QrCode::size(200)->generate($work->uuid) !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
