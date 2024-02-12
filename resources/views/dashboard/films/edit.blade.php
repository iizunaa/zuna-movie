@extends('dashboard.layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Edit Film</h1>
</div>

<div class="col-lg-8">
<form method="post" action="/dashboard/films/{{ $film->id }}" enctype="multipart/form-data">
  @csrf
  @method('put')

  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ $film->title }}">
  </div>

  <div class="mb-3">
    <label for="poster" class="form-label">Film Poster</label>
    @if ($film->poster)
      <img src="{{ asset('storage/' . $film->poster) }}" alt="Poster" style="max-width: 200px;">
    @endif
    <input class="form-control" type="file" id="poster" name="poster">
  </div>

  <div class="mb-3">
    <label for="video" class="form-label">Video</label>
    <input type="text" class="form-control" id="video" name="video" value="{{ $film->video }}">
  </div>

  <div class="mb-3">
    <label for="release" class="form-label">Release</label>
    <input type="text" class="form-control" id="release" name="release" value="{{ $film->release }}">
  </div>

  <div class="mb-3">
    <label for="genre" class="form-label">Genre</label>
    <div class="genre-grid">
        @foreach ($genres as $genre)
            <div class="genre-item">
                <label>
                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                           @if($film->genres->contains($genre->id)) checked @endif>
                    {{ $genre->name }}
                </label>
            </div>
        @endforeach
    </div>
  </div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <input id="description" type="hidden" name="description" value="{{ $film->description }}">
    <trix-editor input="description"></trix-editor>
  </div>

  <button type="submit" class="btn btn-warning mb-3">Update Film</button>
</form>
</div>
@endsection