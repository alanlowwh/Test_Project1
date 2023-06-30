@extends('users.layout')

@section('content')
<link href="{!! url('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">
<link href="{!! url('assets/css/userprofile.css') !!}" rel="stylesheet">
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                <span class="font-weight-bold">{{ Auth::user()->username }}</span>
                <span class="text-black-50">{{ Auth::user()->email }}</span>
            </div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">My Profile</h4>
                </div>
                @if(session('success_message'))
                    <div class="alert alert-success">
                        {{ session('success_message') }}
                    </div>
                @endif

                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('users.update', Auth::user()->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to update your account?')">
                    @csrf
                    @method('PUT')
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="labels">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ Auth::user()->name }}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels"><br>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" value="{{ Auth::user()->username }}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels"><br>Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels"><br>Phone Number</label>
                            <input type="text" name="phoneNumber" class="form-control" placeholder="Phone Number" value="{{ Auth::user()->phoneNumber }}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels"><br>Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Address" value="{{ Auth::user()->address }}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels"><br>Postcode</label>
                            <input type="text" name="postcode" class="form-control" placeholder="Postcode" value="{{ Auth::user()->postcode }}">
                        </div>
                        <div class="col-md-12">
                            <label class="labels"><br>City</label>
                            <input type="text" name="city" class="form-control" placeholder="City" value="{{ Auth::user()->city }}">
                        </div>
                    </div>

            </div>
        </div>            
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="col-md-12">
                    <label class="labels"><h4 class="text-right">Update Password</h4></label>
                </div>
                <div class="col-md-12">
                    <label class="labels">Current Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Current Password" value="{{ old('password') }}">
                </div>
                <div class="col-md-12">
                    <label class="labels"><br>New Password</label>
                    <input type="password" name="new_password" class="form-control" placeholder="New Password" value="">
                </div>
                <div class="col-md-12">
                    <label class="labels"><br>Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm Password" value="">
                    </div>
                    <div class="col-md-12">
                        <br>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
                <!-- Delete account form -->
                <div class="col-md-12">
                    <form action="{{ route('users.destroy', Auth::user()) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your account?')">
                        @csrf
                        @method('DELETE')
                        <br>
                        <button type="submit" class="btn btn-primary" style="background-color: red; border-color: red">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<a href="{{ route('home.home') }}" class="btn btn-info back-button">Back</a>
@endsection
