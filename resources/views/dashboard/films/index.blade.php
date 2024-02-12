@extends('dashboard.layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Films</h1>
  </div>

  @if(session()->has('success'))
  <div class="alert alert-success" role="alert">
    {{ session('success') }}
  </div>
  @endif

  <div class="table-responsive">
    <a class="btn btn-warning mb-3 rounded-pill" href="/dashboard/films/create"><span class="me-1" data-feather="upload"></span>Upload Film</a>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Release</th>
          <th scope="col">Genre</th>
          <th class="text-center" scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($films as $film)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $film->title }}</td>
          <td>{{ $film->release }}</td>
          <td>
            @foreach ($film->genres as $genre)
                <span class="badge bg-secondary">{{ $genre->name }}</span>
            @endforeach
          </td>
          <td class="text-center">
          <a class="btn btn-warning rounded-circle" href="/dashboard/films/{{ $film->id }}/edit"><span data-feather="edit-2"></span></a>
          <form action="/dashboard/films/{{ $film->id }}" method="post" class="d-inline">
          @method('delete')
          @csrf
          <button class="btn btn-warning rounded-circle" onclick="return confirm('Delete Data?')"><span data-feather="trash-2"></span></button>
          </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

@endsection