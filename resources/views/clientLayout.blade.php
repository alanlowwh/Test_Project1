<!DOCTYPE html>
<html>
<head>
    <title>Mobile Website</title>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <header>
        {{-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Mobile Website</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('display.variations')}}">Product</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('export.cart.products')}}">Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Sign In</a>
                    </li>
                </ul>
            </div>
        </nav> --}}

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
        
    

    <!-- Content here -->
    <main class="container mt-4">
        @yield('content')
    </main>


    <!-- Add Bootstrap JS and jQuery scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
