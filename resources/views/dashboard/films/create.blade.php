@extends('dashboard.layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Upload New Film</h1>
</div>

<div class="col-lg-8">
<form method="post" action="/dashboard/films" enctype="multipart/form-data">
  @csrf
  <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control" id="title" name="title">
  </div>
  <div class="mb-3">
    <label for="poster" class="form-label">Film Poster</label>
    <input class="form-control" type="file" id="poster" name="poster">
  </div>
  <div class="mb-3">
    <label for="video" class="form-label">Video</label>
    <input type="text" class="form-control" id="video" name="video">
  </div>
  <div class="mb-3">
    <label for="release" class="form-label">Release</label>
    <input type="text" class="form-control" id="release" name="release">
  </div>
  <div class="mb-3">
    <label for="genre" class="form-label">Genre</label>
    <div class="genre-grid">
        @foreach ($genres as $genre)
            <div class="genre-item">
                <label>
                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}">
                    {{ $genre->name }}
                </label>
            </div>
        @endforeach
    </div>
</div>

  <div class="mb-3">
    <label for="description" class="form-label">Description</label>
    <input id="description" type="hidden" name="description">
    <trix-editor input="description"></trix-editor>
  </div>
  <button type="submit" class="btn btn-warning mb-3">Upload Film</button>
</form>
</div>
@endsection