<header class="p-3 bg-dark text-white">
  <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
              <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
          </a>
          {{-- Modified --}}
          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
              
              <li><a href="{{ route('display.variations') }}" class="nav-link px-2 text-white">Customer View</a></li>
              <li><a href="#" class="nav-link px-2 text-white"></a></li>
              <li><a href="#" class="nav-link px-2 text-white"></a></li>
              <li><a href="#" class="nav-link px-2 text-white"></a></li>
          </ul>

          <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
              <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
          </form>

          @auth
              {{-- {{ auth()->user()->name }} --}}
              @if (Auth::user()->userType == 'staff')
                  <div class="dropdown">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-person-circle"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="userProfileDropdown">
                    <li><a class="dropdown-item" href="{{ asset('user') }}">Customer List</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('orders.list') }}">Order Maintenenace</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('users.create') }}">Create Staff Account</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('users.userprofile') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout.perform') }}">Logout</a></li>
                    </ul>
                  </div>
              @else
                <div class="dropdown">
                  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="userProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="userProfileDropdown">
                    <li><a class="dropdown-item" href="{{ route('users.userprofile') }}">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('logout.perform') }}">Logout</a></li>
                  </ul>
                </div>
              @endif
          @endauth

          @guest
              <div class="text-end">
                  <a href="{{ route('login.perform') }}" class="btn btn-outline-light me-2">Login</a>
                  <a href="{{ route('register.perform') }}" class="btn btn-warning">Sign-up</a>
                  <a href="{{ route('users.create') }}" class="btn btn-warning">admin</a>
              </div>
          @endguest
      </div>
  </div>
</header>