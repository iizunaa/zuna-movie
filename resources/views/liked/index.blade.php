@extends('layouts.main')

@section('container')
<div class="container">
    <h1 class="history-title">Liked Videos</h1>
    <div class="history-container">
        @foreach ($likedFilms as $film)
        <div class="history-item">
            <img src="{{ asset('storage/'.$film->poster) }}" alt="{{ $film->title }}" class="history-poster">
            <div class="film-details">
                <h2>{{ $film->title }}</h2>
                <p>Genres: 
                    @foreach ($film->genres as $genre)
                        {{ $genre->name }},
                    @endforeach
                </p>
                <p>Description: {!! $film->description !!}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection