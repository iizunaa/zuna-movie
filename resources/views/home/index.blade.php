@extends('layouts.main')

@section('container')
    @php
        $searchTerm = request('search'); // Inisialisasi $searchTerm dari request
    @endphp

    <!-- Search Box -->
    <div class="search-box-container text-center my-4">
        <form action="{{ route('home.index') }}" method="GET">
            <input type="text" name="search" placeholder="Search films or genres..." required>
            <button type="submit">Search</button>
        </form>
    </div>
    <!-- End Search Box -->

    @if($searchTerm)
        <!-- Search Results -->
        <div class="card-container mx-auto">
            <div class="card-title">
                <h1 class="fs-4 fw-normal">Search results for '{{ $searchTerm }}'</h1>
            </div>
            <div class="films-card">
                @foreach ($films as $film)
                    <a class="film" href="/home/{{ $film->id }}">
                        <div class="film-poster">
                            <img src="{{ asset('storage/'.$film->poster) }}" alt="Poster">
                            <div class="film-info">
                                <div class="title">{{ $film->title }}</div>
                                <div class="rating">{{ number_format($film->averageRating, 1) }}</div>
                                <div class="year">{{ $film->release }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <!-- End Search Results -->
    @else
        <!-- Rating Card -->
        <div class="card-container mx-auto">
            <div class="card-title">
                <h1 class="fs-4 fw-normal">Top Rating</h1>
            </div>
            <div class="films-card">
                @foreach ($topRatedFilms as $film)
                    <a class="film" href="/home/{{ $film->id }}">
                        <div class="film-poster">
                            <img src="{{ asset('storage/'.$film->poster) }}" alt="Poster">
                            <div class="film-info">
                                <div class="title">{{ $film->title }}</div>
                                <div class="rating">{{ number_format($film->averageRating, 1) }}</div>
                                <div class="year">{{ $film->release }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <!-- End Rating Card -->

        <!-- Recommendation Card -->
@if($recommendations->isNotEmpty())
<div class="card-container mx-auto">
    <div class="card-title">
        <h1 class="fs-4 fw-normal">Recommended For You</h1>
    </div>
    <div class="films-card">
        @foreach ($recommendations as $film)
            <a class="film" href="/home/{{ $film->id }}">
                <div class="film-poster">
                    <img src="{{ asset('storage/'.$film->poster) }}" alt="Poster">
                    <div class="film-info">
                        <div class="title">{{ $film->title }}</div>
                        <div class="year">{{ $film->release }}</div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif
<!-- End Recommendation Card -->

        <!-- Genre -->
        @foreach ($genres as $genre)
            <div class="card-container mx-auto">
                <div class="card-title">
                    <h1 class="fs-4 fw-normal">{{ $genre->name }}</h1>
                </div>
                <div class="films-card">
                    @foreach ($genre->films as $film)
                        <a class="film" href="/home/{{ $film->id }}">
                            <div class="film-poster">
                                <img src="{{ asset('storage/'.$film->poster) }}" alt="Poster">
                                <div class="film-info">
                                    <div class="title">{{ $film->title }}</div>
                                    <div class="year">{{ $film->release }}</div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
        <!-- End Genre Card -->
    @endif
@endsection