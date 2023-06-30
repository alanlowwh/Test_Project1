<html>
    <head>
        <title>App Name - @yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/form.css') }}"" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    </head>
    <body>

        
        @section('sidebar')
        <header class="p-3 bg-dark text-white">
            <div class="container">
                <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                    <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                        <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
                    </a>
                    @auth
                    {{-- {{ auth()->user()->name }} --}}
                    @if (Auth::user()->userType == 'staff')
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">

                        <li><a href="{{ route('home.home') }}" class="nav-link px-2 text-white">Back to Admin View</a></li>
                        
                    </ul>
          
                    @else
                        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="{{ route('display.variations') }}" class="nav-link px-2 text-white">Products</a></li>
                        <li><a href="{{ route('export.cart.products') }}" class="nav-link px-2 text-white">Cart</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">Order</a></li>
                        <li><a href="{{ route('users.userprofile') }}" class="nav-link px-2 text-white">Profile</a></li>
                        <li><a href="{{ route('logout.perform') }}" class="nav-link px-2 text-white">Logout</a></li>
                    </ul>
        
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
 
        <div class="container">
            <br>
            @yield('content')
        </div>
    </body>
</html>