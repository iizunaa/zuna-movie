@extends('dashboard.layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Upload New Genre</h1>
</div>

<div class="col-lg-8">
<form method="post" action="/dashboard/genres">
  @csrf
  <div class="mb-3">
    <label for="name" class="form-label">Genre</label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <button type="submit" class="btn btn-warning mb-3">Upload Genre</button>
</form>
</div>
@endsection