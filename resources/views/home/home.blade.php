@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-5 rounded">
        @auth
            @if (Auth::user()->userType == 'admin' || Auth::user()->userType == 'staff')
                <h1>Admin Dashboard</h1>
                <p>Welcome Admin!</p>
            @else
                <h1>Customer Dashboard</h1>
                <p>Welcome Customer!</p>
            @endif
        @endauth

        @guest
        <h1>Homepage</h1>
        <p class="lead">Your viewing the home page. Please login to view the restricted data.</p>
        @endguest
    </div>
@endsection