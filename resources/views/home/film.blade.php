@extends('layouts.main')

@section('container')

<div class="stream-container my-4">
    <div class="row">
        <!-- Bagian Video -->
        <div class="col-lg-9">
            <iframe class="stream-iframe" src="{{ $film->video }}" allow="accelerometer; autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

        <!-- Bagian Info Film -->
        <div class="col-lg-3">
            <div class="stream-film-poster">
                <img src="{{ asset('storage/'.$film->poster) }}" alt="Poster" class="img-fluid">
            </div>
            <div class="stream-film-info">
                <h3>{{ $film->title }}</h3>
                <h6>Rating: {{ number_format($averageRating, 1) }}</h6>
                <h6>Genre: 
                    @foreach ($film->genres as $genre)
                        <span>{{ $genre->name }}</span>
                    @endforeach
                </h6>
                <h6>Release Date: {{ $film->release }}</h6>
                <!-- Bagian Rating -->
                <div class="row">
                    <div class="col text-center">
                        <button id="likeButton" class="rating-button">
                            <i data-feather="thumbs-up" class="icon"></i>
                        </button>
                        <button id="dislikeButton" class="rating-button">
                            <i data-feather="thumbs-down" class="icon"></i>
                        </button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Bagian Deskripsi -->
    <div class="row mt-3">
        <div class="col">
            <div class="stream-film-description">
                <h4>Description</h4>
                <p>{!! $film->description !!}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const likeButton = document.getElementById('likeButton');
        const dislikeButton = document.getElementById('dislikeButton');
        const userRating = @json($userRating);
        const filmId = '{{ $film->id }}';
    
        function updateRating(action) {
            fetch(`/home/${filmId}/rate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ action: action })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                if (action === 'like') {
                    likeButton.classList.add('active');
                    dislikeButton.classList.remove('active');
                } else if (action === 'dislike') {
                    dislikeButton.classList.add('active');
                    likeButton.classList.remove('active');
                } else {
                    likeButton.classList.remove('active');
                    dislikeButton.classList.remove('active');
                }
            })
            .catch((error) => console.error('Error:', error));
        }
    
        likeButton.addEventListener('click', function() {
            const action = this.classList.contains('active') ? 'unlike' : 'like';
            updateRating(action);
        });
    
        dislikeButton.addEventListener('click', function() {
            const action = this.classList.contains('active') ? 'undislike' : 'dislike';
            updateRating(action);
        });
    
        // Menentukan status tombol berdasarkan userRating
        if (userRating === 1) {
            likeButton.classList.add('active');
        } else if (userRating === -1) {
            dislikeButton.classList.add('active');
        }
    });
    </script>
    
@endsection