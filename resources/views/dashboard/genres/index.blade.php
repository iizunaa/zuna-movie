@extends('dashboard.layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Genres</h1>
  </div>

  @if(session()->has('success'))
  <div class="alert alert-success" role="alert">
    {{ session('success') }}
  </div>
  @endif

  <div class="table-responsive">
    <a class="btn btn-warning mb-3 rounded-pill" href="/dashboard/genres/create"><span class="me-1" data-feather="upload"></span>New Genre</a>
    <table class="table table-striped table-sm">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Genre</th>
          <th class="text-center" scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($genres as $genre)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $genre->name }}</td>
          <td class="text-center">
          <a class="btn btn-warning rounded-circle" href="/dashboard/genres/{{ $genre->id }}/edit"><span data-feather="edit-2"></span></a>
          <form action="/dashboard/genres/{{ $genre->id }}" method="post" class="d-inline">
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