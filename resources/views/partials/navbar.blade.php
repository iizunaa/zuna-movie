<nav class="navbar navbar-expand-lg bg-light fw-bold sticky-top">
    <div class="container">
      <a class="navbar-brand fs-4" href="/home">ZUNA MOVIE</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link btn btn-warning rounded-pill {{ Request::is('home*') ? 'active' : '' }}" aria-current="page" href="/home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-warning rounded-pill {{ Request::is('history*') ? 'active' : '' }}" href="/history">History</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-warning rounded-pill {{ Request::is('liked*') ? 'active' : '' }}" href="/liked">Liked Video</a>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
              @auth
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle btn btn-warning rounded-pill" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                   {{ auth()->user()->username }}
                </a>
                <ul class="dropdown-menu">
                  @can('admin')
                  <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                  @endcan
                  <li>
                    <form action="/logout" method="post">
                      @csrf
                      <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                  </li>
                </ul>
              </li>
              @else
              <li class="nav-item">
                  <a href="/login" class="nav-link">Login</a>
              </li>
              @endauth
          </ul>
      </div>
    </div>
  </nav>