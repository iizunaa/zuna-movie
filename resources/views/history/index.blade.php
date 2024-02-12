@extends('layouts.main')

@section('container')
<div class="container">
    <h1 class="history-title">Watch History</h1>
    <div class="history-container">
        @foreach ($history as $film)
        <div class="history-item">
            <img src="{{ asset('storage/'.$film->poster) }}" alt="{{ $film->title }}" class="history-poster">
            <div class="film-details">
                <h2>{{ $film->title }}</h2>
                <p>Genres: 
                    @foreach ($film->genres as $genre)
                        {{ $genre->name }}
                    @endforeach
                </p>
                <p>Watched on: {{ \Carbon\Carbon::parse($film->pivot->watched_at)->format('d M Y') }}</p>
                <p>Description: {!! $film->description !!}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection