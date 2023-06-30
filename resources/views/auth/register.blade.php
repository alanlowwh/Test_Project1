@extends('layouts.auth-master')

@section('content')

@if (auth()->check())
    <?php
        header("Location: ".URL::to('/login'));
        exit();
    ?>
@endif

    <form method="post" action="{{ route('register.perform') }}">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        
        <h1 class="h3 mb-3 fw-normal">Register Account</h1>

        <div class="form-group form-floating mb-3">
            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="name@example.com" required="required" autofocus>
            <label for="floatingEmail">Email address</label>
            @if ($errors->has('email'))
                <span class="text-danger text-left">{{ $errors->first('email') }}</span>
            @endif
        </div>

        <div class="form-group form-floating mb-3">
            <input type="text" class="form-control" name="username" value="{{ old('username') }}" placeholder="Username" required="required" autofocus>
            <label for="floatingName">Username</label>
            @if ($errors->has('username'))
                <span class="text-danger text-left">{{ $errors->first('username') }}</span>
            @endif
        </div>
        
        <div class="form-group form-floating mb-3">
            <input type="password" class="form-control" name="password" value="{{ old('password') }}" placeholder="Password" required="required">
            <label for="floatingPassword">Password</label>
            @if ($errors->has('password'))
                <span class="text-danger text-left">{{ $errors->first('password') }}</span>
            @endif
        </div>

        <div class="form-group form-floating mb-3">
            <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm Password" required="required">
            <label for="floatingConfirmPassword">Confirm Password</label>
            @if ($errors->has('password_confirmation'))
                <span class="text-danger text-left">{{ $errors->first('password_confirmation') }}</span>
            @endif
        </div>

        <div class="form-group form-floating mb-3 d-none">
            <select class="form-control" name="userType" required>
                <option value="customer">customer</option>
            </select>
        <label for="floatingUserType">userType</label>
        @if ($errors->has('userType'))
            <span class="text-danger text-left">{{ $errors->first('userType') }}</span>
        @endif
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>

        <p class="mt-3 mb-3 text-muted text-center">
        Already have an account? <a href="{{ route('login.perform') }}">Login</a></p>
        @include('auth.partials.copy')

    </form>
@endsection