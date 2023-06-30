@extends('users.layout')

@section('content')
    <link href="{!! url('assets/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! url('assets/css/signin.css') !!}" rel="stylesheet">

    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Create Account</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('home.home') }}">Back</a>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Username:</strong>
                        <input type="text" name="username" class="form-control" placeholder="Username" value="{{ old('username') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Email:</strong>
                        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Password:</strong>
                        <input type="password" name="password" class="form-control" placeholder="Password" value="{{ old('password') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Confirm Password:</strong>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Password Confirmation" value="{{ old('password_confirmation') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Name:</strong>
                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Address:</strong>
                        <input type="text" name="address" class="form-control" placeholder="Address" value="{{ old('address') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Postcode:</strong>
                        <input type="text" name="postcode" class="form-control" placeholder="Postcode" value="{{ old('postcode') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <strong>City:</strong>
                        <input type="text" name="city" class="form-control" placeholder="City" value="{{ old('city') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <strong>Phone Number:</strong>
                        <input type="text" name="phoneNumber" class="form-control" placeholder="Phone Number" value="{{ old('phoneNumber') }}">
                    </div>
                </div>

                <div class="col-md-6 d-none">
                    <div class="form-group">
                        <strong>User Type:</strong>
                        <select class="form-control" name="userType" required>
                            <option value="staff" selected>Staff</option>
                        </select>
                        <input type="hidden" name="role" value="staff">
                    </div>
                </div>

                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </div>
        </div>
    </form>
</div>
</div>
@endsection
